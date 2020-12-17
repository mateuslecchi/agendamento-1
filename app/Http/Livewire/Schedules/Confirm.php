<?php

namespace App\Http\Livewire\Schedules;

use App\Domain\Enum\Situation;
use App\Jobs\SendApprovedScheduleEmails;
use App\Models\Schedule;
use App\Traits\AuthenticatedUser;
use App\Traits\AuthorizesRoleOrPermission;
use App\Traits\ModalCtrl;
use App\Traits\NotifyBrowser;
use Livewire\Component;

class Confirm extends Component
{
    use ModalCtrl;
    use NotifyBrowser;
    use AuthenticatedUser;
    use AuthorizesRoleOrPermission;

    public Schedule $schedule;

    protected $listeners = [
        'show_schedule_confirm_modal' => 'load',
        'confirm_schedule_confirmation' => 'confirm'
    ];

    public function render()
    {
        return view('livewire.schedules.confirm');
    }

    public function mount()
    {
        $this->setEmptySchedule();
    }

    protected function setEmptySchedule()
    {
        $this->schedule = Schedule::make(['id' => 0]);
    }

    public function load(Schedule $schedule)
    {
        if (is_null($schedule)) {
            $this->notifyError('text.record-found-failed');
            return;
        }
        $this->schedule = $schedule;
        $this->modalToggle();
    }

    public function confirm(Schedule $schedule)
    {
        if (!$this->modalIsOpen()) {
            return;
        }

        if (is_null($schedule)) {
            $this->notifyError('text.record-found-failed');
            $this->finally();
            return;
        }

        if ($this->schedule->id !== $schedule->id) {
            $this->notifyAlert('text.violation.integrity');
            $this->finally();
            return;
        }

        $this->schedule->situations_id = Situation::CONFIRMED()->getValue();

        $status = $this->schedule->save();

        $this->notifySuccessOrError(
            status: $status,
            success: 'text.schedule.approve.success',
            error: 'text.schedule.approve.error'
        );

        if ($status) {
            SendApprovedScheduleEmails::dispatch($this->authUser(), $this->schedule);
        }

        $this->finally();
    }

    protected function finally(): void
    {
        $this->updateView();
        $this->modalToggle();
        $this->setEmptySchedule();
    }

    protected function updateView(): void
    {
        $this->emit('update_schedule_display_content');
    }

}
