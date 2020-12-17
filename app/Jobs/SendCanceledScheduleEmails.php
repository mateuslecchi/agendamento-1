<?php

namespace App\Jobs;

use App\Mail\ScheduleCancel;
use App\Models\GroupMember;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendCanceledScheduleEmails implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(private User $current, private Schedule $schedule)
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
            SendMail::dispatch(new ScheduleCancel(
                current: $this->current,
                toUser: $groupMember->user,
                schedule: $this->schedule
            ))->delay(
                now()->addSeconds(rand(1, 5))
            );
        });
    }
}
