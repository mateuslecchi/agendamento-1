<?php

namespace App\Jobs;

use App\Mail\ScheduleApproved;
use App\Models\GroupMember;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendApprovedScheduleEmails implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @param User $current
     * @param Schedule $schedule
     */
    public function __construct(
        private User $current,
        private Schedule $schedule
    )
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $userGroup = GroupMember::findByGroup($this->schedule->forGroup());

        $userGroup->map(function (GroupMember $groupMember) {
            SendMail::dispatch(new ScheduleApproved(
                current: $this->current,
                toUser: $groupMember->user,
                schedule: $this->schedule
            ))->delay(
                now()->addSeconds(rand(1, 5))
            );
        });
    }
}
