<?php

namespace App\Http\Livewire\Schedules;

use App\Domain\Enum\GroupRoles;
use App\Domain\Enum\Situation;
use App\Domain\Policy;
use App\Jobs\SendApprovedScheduleEmails;
use App\Jobs\SendPendingScheduleEmails;
use App\Models\Environment;
use App\Models\Group;
use App\Models\Schedule;
use App\Rules\WithoutSchedule;
use App\Traits\AuthenticatedUser;
use App\Traits\AuthorizesRoleOrPermission;
use App\Traits\ModalCtrl;
use App\Traits\NotifyBrowser;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Component;

class Create extends Component
{
    use ModalCtrl;
    use NotifyBrowser;
    use AuthenticatedUser;
    use AuthorizesRoleOrPermission;

    public Group $group;
    public Environment $environment;
    public Schedule $schedule;

    public int $selectedFrequency = 0;
    public int $repetitions = 0;

    public array $frequencies = [
        0 => 'label.frequency.no-repeat',
        1 => 'label.frequency.diary',
        2 => 'label.frequency.weekly',
        3 => 'label.frequency.monthly'
    ];

    public array $optionsFrequency = [
        0 => [
            'min' => 0,
            'max' => 0,
            'text' => ''
        ],
        1 => [
            'min' => 2,
            'max' => 30,
            'text' => 'label.day'
        ],
        2 => [
            'min' => 2,
            'max' => 24,
            'text' => 'label.week'
        ],
        3 => [
            'min' => 2,
            'max' => 6,
            'text' => 'label.month'
        ]
    ];

    protected $listeners = [
        'show_modal_schedule' => 'modalToggle',
        'current_environment_selected' => 'load'
    ];

    public function render(): Factory|View|Application
    {
        return view('livewire.schedules.create', [
            'environment_name' => $this->environment?->name,
            'block_name' => $this->environment?->block?->name,
            'groups' => match ($this->authGroupRole()->getValue()) {
                GroupRoles::USER()->getValue() => new Collection([$this->authGroup()]),
                default => Group::all()
            },
            'optionsFrequency' => $this->optionsFrequency
        ]);
    }

    public function mount(): void
    {
        Policy::schedule_create_mount();

        $this->setEmptyEnvironment();
        $this->setEmptySchedule();
        $this->setEmptyGroup();
    }

    private function setEmptyEnvironment(): void
    {
        $this->environment = Environment::make([]);
    }

    private function setEmptySchedule(): void
    {
        $this->schedule = Schedule::make([]);
    }

    private function setEmptyGroup(): void
    {
        $this->group = Group::make(['id' => $this->authGroup()]);
    }

    public function load(Environment $environment): void
    {
        Policy::schedule_create_load();

        $this->environment = $environment;
        $this->modalToggle();
    }

    public function save(): void
    {
        Policy::schedule_create_save();

        if (!$this->modalIsOpen()) {
            return;
        }

        $this->validate();

        if ($this->repetitions) {
            $this->runDry();
        }

        do {
            $this->saveSchedule();
        } while ($this->nextSchedule());

        $this->finally();
    }

    protected function runDry(): void
    {
        $count = $this->repetitions;
        $current = $this->schedule;
        do {
            $schedule = $this->makeNextSchedule($current);
            $this->validate([
                'environment.id' => [
                    new WithoutSchedule(
                        $schedule->date,
                        $schedule->start_time,
                        $schedule->end_time,
                    )
                ]
            ]);
            $count--;
        } while ($count >= 2);
    }

    protected function makeNextSchedule(Schedule $current): Schedule
    {
        $new = new Schedule();
        $new->for = $current->for;
        $new->by = $current->by;
        $new->environments_id = $current->environments_id;
        $new->situations_id = $current->situations_id;
        $new->start_time = $current->start_time;
        $new->end_time = $current->end_time;

        $new->date = match ($this->selectedFrequency) {
            1 => Carbon::parse($current->date)->addDay()->format('Y-m-d'),
            2 => Carbon::parse($current->date)->addWeek()->format('Y-m-d'),
            3 => Carbon::parse($current->date)->addMonth()->format('Y-m-d'),
            default => $current->date
        };

        return $new;
    }

    protected function saveSchedule(): void
    {
        $this->validate([
            'environment.id' => [
                new WithoutSchedule(
                    $this->schedule->date,
                    $this->schedule->start_time,
                    $this->schedule->end_time,
                )
            ]
        ]);

        $this->schedule->for = $this->group->id ?? $this->authGroup()->id;

        $this->schedule->by = $this->authGroup()->id;

        $this->schedule->environments_id = $this->environment->id;

        if ($this->authIsAdmin()) {
            $this->schedule->situations_id = Situation::CONFIRMED()->getValue();
            $isApproved = true;

        } else {
            if ($this->authGroup()->id === $this->environment->group->id) {
                $this->schedule->situations_id = Situation::CONFIRMED()->getValue();
                $isApproved = true;
            } else {
                $this->schedule->situations_id = Situation::PENDING()->getValue();
                $isApproved = false;
            }
        }

        $status = $this->schedule->save();

        $this->notifySuccessOrError(
            status: $status,
            success: __('text.save.success'),
            error: __('text.save.error')
        );

        if ($isApproved) {
            SendApprovedScheduleEmails::dispatch($this->authUser(), $this->schedule);
        } else {
            SendPendingScheduleEmails::dispatch($this->schedule);
        }
    }

    protected function nextSchedule(): bool
    {
        if (!$this->selectedFrequency || $this->repetitions < 2) {
            return false;
        }

        $this->schedule = $this->makeNextSchedule($this->schedule);
        $this->repetitions--;

        return true;
    }

    protected function finally(): void
    {
        $this->modalToggle();
        $this->updateView();
        $this->setEmptyEnvironment();
        $this->setEmptySchedule();
        $this->selectedFrequency = 0;
        $this->repetitions = 0;
    }

    protected function updateView(): void
    {
        $this->emit('update_schedule_display_content');
    }

    protected function rules(): array
    {
        return [
            'schedule.date' => [
                'required',
                'after_or_equal:' . date('Y-m-d'),
                'before_or_equal:' . Carbon::parse(date('Y-m-d'))
                    ->addDays(90)
                    ->format('Y-m-d')
            ],
            'schedule.start_time' => [
                'required'
            ],
            'schedule.end_time' => [
                'required'
            ],
            'group.id' => [
                'required'
            ],
        ];
    }

    protected function saveRules(): array
    {
        return [
            'schedule.date' => [
                'required',
                'after_or_equal:' . date('Y-m-d'),
                'before_or_equal:' . Carbon::parse(date('Y-m-d'))
                    ->addDays(90)
                    ->format('Y-m-d')
            ],
            'schedule.start_time' => [
                'required'
            ],
            'schedule.end_time' => [
                'required'
            ],
        ];
    }
}
