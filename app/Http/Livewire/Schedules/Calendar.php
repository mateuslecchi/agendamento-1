<?php

namespace App\Http\Livewire\Schedules;

use App\Domain\Enum\Situation;
use App\Models\Block;
use App\Models\Environment;
use App\Models\Schedule as ScheduleModel;
use App\Traits\AuthenticatedUser;
use App\Traits\Fmt;
use App\Traits\Make;
use Asantibanez\LivewireCalendar\LivewireCalendar;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\View\View;


class Calendar extends LivewireCalendar
{
    use AuthenticatedUser;

    public Block $block;
    public Environment $environment;

    public function mount($initialYear = null, $initialMonth = null, $weekStartsAt = null, $calendarView = null, $dayView = null, $eventView = null, $dayOfWeekView = null, $dragAndDropClasses = null, $beforeCalendarView = null, $afterCalendarView = null, $pollMillis = null, $pollAction = null, $dragAndDropEnabled = true, $dayClickEnabled = true, $eventClickEnabled = true, $extras = [])
    {
        $this->initializeProperties();
        parent::mount($initialYear, $initialMonth, $weekStartsAt, $calendarView, $dayView, $eventView, $dayOfWeekView, $dragAndDropClasses, $beforeCalendarView, $afterCalendarView, $pollMillis, $pollAction, $dragAndDropEnabled, $dayClickEnabled, $eventClickEnabled, $extras);
    }

    public function render(): Factory|View
    {
        return parent::render()
            ->with('blocks', $this->validBlocksForSelection())
            ->with('environments', $this->validEnvironmentsForSelection());
    }

    protected function validBlocksForSelection(): Collection
    {
        return Block::all();
    }

    protected function validEnvironmentsForSelection(): Collection
    {
        return Environment::byBlock($this->block);
    }

    protected function initializeProperties(): void
    {
        $this->block = Make::block(['id' => 0]);
        $this->environment = Make::environment(['id' => 0]);
    }

    public function resetEnvironment(): void
    {
        $this->environment->id = 0;
    }

    protected function rules(): array
    {
        return [
            'block.id' => ['required'],
            'environment.id' => ['required']
        ];
    }

    public function events(): Collection
    {
        return $this->formattedSchedules();
    }

    protected function formattedSchedules(): Collection
    {
        return $this->schedules()->map(function (ScheduleModel $schedule) {
            return [
                'id' => $schedule->id,
                'environment' => $schedule->environment->name,
                'block' => $schedule->environment->block->name,
                'for' => $schedule->forGroup()->name,
                'start' => Carbon::parse($schedule->start_time)->format('H:i'),
                'end' => Carbon::parse($schedule->end_time)->format('H:i'),
                'approved' => $this->isApproved($schedule),
                'date' => Carbon::parse($schedule->date),
            ];
        });
    }

    protected function isApproved(ScheduleModel $schedule): bool
    {
        return Situation::getByValue($schedule->situations_id)->getValue() === Situation::CONFIRMED()->getValue();
    }

    protected function schedules(): Collection
    {
        if ($this->block->id && $this->environment->id) {
            return $this->schedulesByEnvironment();
        }

        if ($this->block->id) {
            return $this->schedulesByBlock();
        }

        return $this->schedulesByGroup();
    }

    protected function schedulesByGroup(): Collection
    {
        return ScheduleModel::byGroupForCalendar($this->authGroup(), $this->gridStartsAt, $this->gridEndsAt);
    }

    private function schedulesByEnvironment(): Collection
    {
        return ScheduleModel::byEnvironmentForCalendar($this->environment, $this->gridStartsAt, $this->gridEndsAt);
    }

    private function schedulesByBlock(): Collection
    {
        return ScheduleModel::byGroupAndBlockForCalendar($this->authGroup(), $this->block, $this->gridStartsAt, $this->gridEndsAt);
    }
}
