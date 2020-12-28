<?php

namespace App\Http\Livewire\Schedules;

use App\Domain\Enum\Situation;
use App\Jobs\SendCanceledScheduleEmails;
use App\Models\Schedule;
use App\Traits\AuthenticatedUser;
use App\Traits\Fmt;
use App\Traits\Make;
use App\Traits\ModalCtrl;
use App\Traits\NotifyBrowser;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Details extends Component
{
    public const ID = 'd07ec6b7-6d3e-4d77-8221-b472c03b1c87';
    public const CANCEL_SCHEDULE = 'd2102cf6-ea6c-4d16-90cb-0250fcc9b585';

    use ModalCtrl;
    use NotifyBrowser;
    use AuthenticatedUser;

    public Schedule $schedule;

    public string $environment;
    public string $block;
    public string $for;
    public string $date;
    public string $startTime;
    public string $endTime;
    public string $situation;

    public bool $allowCancellation;

    protected $listeners = [
        self::ID => 'construct',
        self::CANCEL_SCHEDULE => 'cancelSchedule'
    ];

    public function render(): Factory|View|Application
    {
        return view('livewire.schedules.details');
    }

    public function construct(Schedule $schedule): void
    {
        $this->initializeProperties();
        $this->schedule = $schedule;
        $this->environment = Fmt::text($schedule->environment->name);
        $this->block = Fmt::text($schedule->environment->block->name);
        $this->for = Fmt::text($schedule->forGroup()->name);
        $this->situation = Fmt::text($schedule->situation->name);
        $this->date = $schedule->date;
        $this->startTime = $schedule->start_time;
        $this->endTime = $schedule->end_time;
        $this->allowCancellation = $this->allowCancellation($schedule);
        $this->modalToggle();
    }

    protected function initializeProperties(): void
    {
        $this->schedule = Make::schedule();
        $this->environment = '';
        $this->block = '';
        $this->for = '';
        $this->situation = '';
        $this->date = '';
        $this->startTime = '';
        $this->endTime = '';
        $this->allowCancellation = false;
    }

    protected function allowCancellation(Schedule $schedule): bool
    {
        $date = Carbon::parse($schedule->date);
        return ($this->authGroup()->id === $schedule->byGroup()->id) &&
            ($date->isAfter(now()->format('Y-m-d')) || $date->isToday()) &&
            ($schedule->situations_id === Situation::CONFIRMED()->getValue() ||
                $schedule->situations_id === Situation::PENDING()->getValue());
    }

    public function cancelSchedule(): void
    {
        $this->schedule->situations_id = Situation::CANCELED()->getValue();
        $this->sendNotifications(
            $this->schedule->save()
        );
        $this->finally();
    }

    protected function sendNotifications(bool $saved): void
    {
        $this->sendBrowserNotification($saved);
        $this->sendEmailNotification($saved);
    }

    protected function sendBrowserNotification(bool $saved): void
    {
        $this->notifySuccessOrError(
            status: $saved,
            success: 'text.schedule.cancel.success',
            error: 'text.schedule.cancel.error'
        );
    }

    protected function sendEmailNotification(bool $saved): void
    {
        if (!$saved) {
            return;
        }
        SendCanceledScheduleEmails::dispatch($this->authUser(), $this->schedule);
    }

    protected function finally(): void
    {
        $this->modalToggle();
        $this->initializeProperties();
    }
}
