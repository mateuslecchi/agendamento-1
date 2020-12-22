<?php /** @noinspection PhpMissingFieldTypeInspection */

namespace App\Http\Livewire\Schedules;

use App\Domain\Enum\Situation;
use App\Jobs\SendApprovedScheduleEmails;
use App\Jobs\SendCanceledScheduleEmails;
use App\Models\Schedule;
use App\Traits\AuthenticatedUser;
use App\Traits\AuthorizesRoleOrPermission;
use App\Traits\ModalCtrl;
use App\Traits\NotifyBrowser;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
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
        'confirm_schedule_confirmation' => 'confirm',
        'cancel_schedule_confirmation' => 'cancel'
    ];

    public function render(): Factory|View|Application
    {
        return view('livewire.schedules.confirm');
    }

    public function mount(): void
    {
        $this->setEmptySchedule();
    }

    protected function setEmptySchedule(): void
    {
        $this->schedule = Schedule::make(['id' => 0]);
    }

    public function load(Schedule $schedule): void
    {
        if (is_null($schedule)) {
            $this->notifyError('text.record-found-failed');
            return;
        }
        $this->schedule = $schedule;
        $this->modalToggle();
    }

    public function cancel(Schedule $schedule): void
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

       /* $this->emit('show_schedule_cancel_modal', $schedule->id);
        $this->modalToggle();*/
    }

    public function confirm(Schedule $schedule): void
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
