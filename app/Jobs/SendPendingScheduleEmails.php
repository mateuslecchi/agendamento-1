<?php

namespace App\Jobs;

use App\Mail\SchedulePending;
use App\Models\GroupMember;
use App\Models\Schedule;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendPendingScheduleEmails implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @param Schedule $schedule
     */
    public function __construct(private Schedule $schedule)
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
        $environmentGroup = GroupMember::findByGroup($this->schedule->environment->group);

        $environmentGroup->map(function (GroupMember $groupMember) {
            SendMail::dispatch(new SchedulePending(
                toUser: $groupMember->user,
                schedule: $this->schedule
            ))->delay(
                now()->addSeconds(rand(1, 5))
            );
        });
    }
}
