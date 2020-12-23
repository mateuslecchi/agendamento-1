<?php

namespace App\Http\Livewire\Schedules;

use App\Domain\Enum\Situation;
use App\Jobs\SendCanceledScheduleEmails;
use App\Models\Schedule;
use App\Traits\AuthenticatedUser;
use App\Traits\AuthorizesRoleOrPermission;
use App\Traits\ModalCtrl;
use App\Traits\NotifyBrowser;
use Livewire\Component;

class Cancel extends Component
{
    use ModalCtrl;
    use NotifyBrowser;
    use AuthenticatedUser;
    use AuthorizesRoleOrPermission;

    public Schedule $schedule;

    protected $listeners = [
        'show_schedule_cancel_modal' => 'load',
        'cancel_schedule_confirmation' => 'cancel'
    ];

    public function render()
    {
        return view('livewire.schedules.cancel', [
            'schedule' => $this->schedule
        ]);
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

    public function cancel(Schedule $schedule)
    {
        if (!$this->modalIsOpen()) {
            return;
        }

        if (is_null($schedule)) {
            $this->notifyError('text.record-found-failed');
            $this->finally();
            return;
        }

        if ($schedule->environment->group->id !== $this->authGroup()->id || $schedule->by !== $this->authGroup()->id || $this->schedule->id !== $schedule->id) {
            $this->notifyError('text.violation.integrity');
            $this->finally();
            return;
        }

        $this->schedule->situations_id = Situation::CANCELED()->getValue();

        $status = $this->schedule->save();

        $this->notifySuccessOrError(
            status: $status,
            success: 'text.schedule.cancel.success',
            error: 'text.schedule.cancel.error'
        );

        if ($status) {
            SendCanceledScheduleEmails::dispatch($this->authUser(), $this->schedule);
        }

        $this->finally();
    }

    protected function finally()
    {
        $this->updateView();
        $this->modalToggle();
        $this->setEmptySchedule();
    }

    protected function updateView()
    {
        $this->emit('update_schedule_display_content');
    }
}
