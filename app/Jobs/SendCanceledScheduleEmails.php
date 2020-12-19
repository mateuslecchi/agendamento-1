<?php

namespace App\Jobs;

use App\Mail\ScheduleCancel;
use App\Models\GroupMember;
use App\Models\Schedule;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class SendCanceledScheduleEmails implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(private User $current, private Schedule $schedule)
    {
        //
    }

    public function handle(): void
    {
        $userGroup = GroupMember::findByGroup($this->schedule->forGroup());

        $userGroup->map(function (GroupMember $groupMember) {
            SendMail::dispatch(new ScheduleCancel(
                sendBy: $this->current->name,
                toEmail: $groupMember->user->email,
                schedule: [
                    'environment' => Str::ucfirst(__($this->schedule->environment->name)),
                    'block' => Str::ucfirst(__($this->schedule->environment->block->name)),
                    'date' => Carbon::parse($this->schedule->date)->format('d/m/Y'),
                    'start_time' => Str::replaceLast(':00', '', $this->schedule->start_time),
                    'end_time' => Str::replaceLast(':00', '', $this->schedule->end_time),
                    'user' =>  Str::ucfirst(__($this->schedule->forGroup()?->name)),
                ],
                toName: $groupMember->user->name
            ))->delay(
                now()->addSeconds(random_int(1, 5))
            );
        });
    }
}
