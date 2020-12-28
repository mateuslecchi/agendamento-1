<?php

namespace App\Http\Livewire\Schedules;

use App\Domain\Contracts\Frequency;
use App\Domain\Enum\Situation;
use App\Domain\Schedule\Frequency\Diary;
use App\Domain\Schedule\Frequency\Monthly;
use App\Domain\Schedule\Frequency\NoRepeat;
use App\Domain\Schedule\Frequency\Weekly;
use App\Jobs\SendApprovedScheduleEmails;
use App\Jobs\SendPendingScheduleEmails;
use App\Models\Environment;
use App\Models\Schedule;
use App\Rules\EndTimeBeforeStartTime;
use App\Rules\WithoutSchedule;
use App\Traits\AuthenticatedUser;
use App\Traits\Fmt;
use App\Traits\Make;
use App\Traits\ModalCtrl;
use App\Traits\NotifyBrowser;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use JetBrains\PhpStorm\Pure;
use Livewire\Component;

class Create extends Component
{
    public const ID = '837801c6-0ac4-4e6f-be31-63f59091228e';

    use ModalCtrl;
    use AuthenticatedUser;
    use NotifyBrowser;

    public int $env_id;
    public Environment $env;

    public string $date;
    public string $environment;
    public string $block;
    public string $startTime;
    public string $endTime;

    public int $frequency;
    public int $repetitions;

    public array $selectedFrequency;

    protected $listeners = [
        self::ID => 'construct'
    ];

    protected $rules = [
        'date' => 'required',
        'startTime' => 'required',
        'endTime' => 'required',
        'frequency' => 'required',
        'repetitions' => 'required',
    ];

    public function render(): Factory|View|Application
    {
        return view('livewire.schedules.create')
            ->with('frequencies', $this->frequencies());
    }

    public function mount(): void
    {
        $this->initializeProperties();
    }

    public function initializeProperties(): void
    {
        $this->env_id = 0;
        $this->env = Make::environment();
        $this->date = '';
        $this->environment = '';
        $this->block = '';
        $this->startTime = '';
        $this->endTime = '';
        $this->frequency = 0;
        $this->repetitions = 0;
        $this->selectedFrequency = [
            'min' => 0,
            'max' => 0,
            'placeholder' => '',
        ];
    }

    public function construct(int $id, int $year, int $month, int $day): void
    {
        $this->initializeProperties();
        $date = Carbon::parse("$year-$month-$day");
        $this->env_id = $id;
        $this->env = ($env = Environment::find($id));
        $this->date = $date->format('Y-m-d');
        $this->environment = Fmt::text($env->name);
        $this->block = Fmt::text($env->block->name);
        $this->modalToggle();
    }

    #[Pure]
    public function frequencies(): array
    {
        return [
            new NoRepeat(),
            new Diary(),
            new Weekly(),
            new Monthly()
        ];
    }

    public function selectedFrequency(): Frequency
    {
        if (!isset($this->frequency)) {
            return new NoRepeat();
        }
        foreach ($this->frequencies() as $frequency) {
            if ($frequency->id() === $this->frequency) {
                return $frequency;
            }
        }
        return new NoRepeat();
    }

    public function updatedFrequency(): void
    {
        $frequency = $this->selectedFrequency();
        $this->repetitions = $frequency->min();
        $this->selectedFrequency = [
            'min' => $frequency->min(),
            'max' => $frequency->max(),
            'placeholder' => $frequency->placeholder(),
        ];
    }

    public function createNewSchedule(): void
    {
        $this->basicValidate();
        $this->dryRun();
        $this->sendBrowserNotification(
            $this->saveAll()
        );
        $this->finally();
    }

    protected function saveAll(): bool
    {
        $repetitions = $this->repetitions;
        $current = $this->defaultSchedule();
        $succeeded = true;
        do {
            $saved = $current->save();
            $succeeded &= $saved;

            $this->sendNotifications($saved, $current);

            $current = $this->nextSchedule($current);
            $repetitions--;
        } while ($repetitions > 0);
        return $succeeded;
    }

    protected function sendNotifications(bool $saved, Schedule $schedule): void
    {
        $this->sendBrowserNotificationIfFail(
            saved: $saved,
            schedule: $schedule
        );
        $this->sendEmailNotification(
            succeeded: $saved,
            schedule: $schedule
        );
    }

    protected function sendBrowserNotification(bool $succeeded): void
    {
        $this->notifySuccessOrError(
            status: $succeeded,
            success: 'text.schedule.save.succeeded',
            error: 'text.schedule.save.error'
        );
    }

    protected function sendEmailNotification(bool $succeeded, Schedule $schedule): void
    {
        if (!$succeeded) {
            return;
        }

        match ($this->determineSituation()) {
            Situation::CONFIRMED()->getValue() => SendApprovedScheduleEmails::dispatch($this->authUser(), $schedule),
            default => SendPendingScheduleEmails::dispatch($schedule)
        };
    }

    protected function sendBrowserNotificationIfFail(bool $saved, Schedule $schedule): void
    {
        if ($saved) {
            return;
        }
        $date = Carbon::parse($schedule->date);
        $this->notifyError(Fmt::text('text.schedule.save.fail', [
            'day' => $date->day,
            'month' => $date->monthName,
            'year' => $date->year
        ]));
    }

    protected function basicValidate(): void
    {
        $this->validate();
        $this->isValidTime();
    }

    protected function isValidTime(): void
    {
        $this->validateOnly('endTime', [
            'endTime' => new EndTimeBeforeStartTime($this->startTime, $this->endTime)
        ]);
    }

    protected function dryRun(): void
    {
        $repetitions = $this->repetitions;
        $frequency = $this->selectedFrequency();
        $current = $this->defaultSchedule();

        do {
            $this->validateOnly('env_id', [
                'env_id' => new WithoutSchedule($current->date, $current->start_time, $current->end_time),

            ]);
            $current = $this->nextSchedule($current);
            $repetitions--;
        } while ($repetitions >= $frequency->min());
    }

    protected function defaultSchedule(): Schedule
    {
        return Make::schedule([
            Schedule::ENVIRONMENT_ID => $this->env->id,
            Schedule::FOR => $this->authGroup()->id,
            Schedule::BY => $this->authGroup()->id,
            Schedule::DATE => $this->date,
            Schedule::START_TIME => $this->startTime,
            Schedule::END_TIME => $this->endTime,
            Schedule::SITUATION_ID => $this->determineSituation()
        ]);
    }

    protected function nextSchedule(Schedule $current): Schedule
    {
        $schedule = Make::schedule($this->scheduleToArrayWithoutID($current));
        $schedule->date = match ($this->selectedFrequency()->id()) {
            (new Diary())->id() => Carbon::parse($schedule->date)->addDay()->format('Y-m-d'),
            (new Weekly())->id() => Carbon::parse($schedule->date)->addWeek()->format('Y-m-d'),
            (new Monthly())->id() => Carbon::parse($schedule->date)->addMonth()->format('Y-m-d'),
            default => $schedule->date
        };
        return $schedule;
    }

    protected function scheduleToArrayWithoutID(Schedule $schedule): array
    {
        $attributes = new Collection($schedule->toArray());
        return $attributes->forget(Schedule::ID)->toArray();
    }

    protected function determineSituation(): int
    {
        if ($this->env->automatic_approval) {
            return Situation::CONFIRMED()->getValue();
        }
        if ($this->authGroup()->id === $this->env->groups_id) {
            return Situation::CONFIRMED()->getValue();
        }
        return Situation::PENDING()->getValue();
    }

    protected function rules(): array
    {
        return [
            'date' => 'required',
            'startTime' => 'required',
            'endTime' => 'required',
            'frequency' => 'required',
            'repetitions' => 'required',
        ];
    }

    protected function messages(): array
    {
        return [
            'startTime.required' => Fmt::text('validation.required', [
                'attribute' => 'label.time.start'
            ]),
            'endTime.required' => Fmt::text('validation.required', [
                'attribute' => 'label.time.end'
            ])
        ];
    }

    protected function finally(): void
    {
        $this->modalToggle();
        $this->initializeProperties();
    }
}
