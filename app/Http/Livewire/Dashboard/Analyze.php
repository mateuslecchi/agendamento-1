<?php

namespace App\Http\Livewire\Dashboard;

use App\Domain\Enum\Situation;
use App\Http\Livewire\Schedules\Details;
use App\Jobs\SendApprovedScheduleEmails;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class Analyze extends Details
{
    public const ID = '109e23bc-3889-4d82-97ad-b61b6456d8be';
    public const CANCEL_SCHEDULE = '0b2a7b67-7cd2-49ce-b9db-cdb2ad1d66d6';
    public const APPROVE_SCHEDULE = '0f265bff-1cc0-4376-834f-a70b692d2bdf';

    protected bool $sendCancel = true;

    protected $listeners = [
        self::ID => 'construct',
        self::CANCEL_SCHEDULE => 'cancelSchedule',
        self::APPROVE_SCHEDULE => 'approveSchedule'
    ];

    public function render(): Application|Factory|View
    {
        return view('livewire.dashboard.analyze');
    }

    public function approveSchedule(): void
    {
        $this->schedule->situations_id = Situation::CONFIRMED()->getValue();
        $this->sendCancel = false;
        $this->sendNotifications(
            $this->schedule->save()
        );
        $this->finally();
    }

    protected function sendBrowserNotification(bool $saved): void
    {
        $this->notifySuccessOrError(
            status: $saved,
            success: 'text.schedule.approve.success',
            error: 'text.schedule.approve.error'
        );
    }

    protected function sendEmailNotification(bool $saved): void
    {
        if (!$saved) {
            return;
        }
        if ($this->sendCancel) {
            parent::sendEmailNotification($saved);
        } else {
            SendApprovedScheduleEmails::dispatch($this->authUser(), $this->schedule);
        }
    }
}
