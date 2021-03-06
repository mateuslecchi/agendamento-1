<?php
/** @noinspection PhpUnusedAliasInspection */

namespace App\Jobs;

use App\Domain\Enum\Situation;
use App\Mail\ScheduleCancel;
use App\Models\Block;
use App\Models\Environment;
use App\Models\GroupMember;
use App\Models\Schedule;
use Carbon\Carbon;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class BlockExclusion implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(protected string $by, protected Block $block)
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     * @noinspection PhpUndefinedFieldInspection
     * @throws Exception
     */
    public function handle(): void
    {
        $data = $this->getSimpleData();

        $this->block->deleted = true;
        $this->block->save();

        foreach ($data as $environment) {
            foreach ($environment as $schedule) {
                foreach ($schedule['users'] as $user) {
                    SendMail::dispatch(new ScheduleCancel(
                        sendBy:$this->by,
                        toEmail: $user['email'],
                        schedule: [
                            'environment' => $schedule['environment']['name'],
                            'block' =>$schedule['environment']['block']['name'],
                            'date' => $schedule['date'],
                            'start_time' => $schedule['start_time'],
                            'end_time' => $schedule['end_time'],
                            'for' => $schedule['for'],
                            'by' => $schedule['by']
                        ],
                        toName: $user['name']
                    ))->delay(
                        now()->addSeconds(random_int(1, 5))
                    );
                }
            }
        }

       $this->block->delete();
    }

    /** @noinspection PhpUndefinedFieldInspection */
    public function getSimpleData(): array
    {
        return $this->block->environments()->get()->map(function (Environment $environment) {
            return $environment->schedules()
                ->where('date', '>=', now()->format('Y-m-d'))
                ->whereIn('situations_id', [
                    Situation::CONFIRMED()->getValue(),
                    Situation::PENDING()->getValue()
                ])
                ->get()
                ->map(function (Schedule $schedule) {
                    $users = GroupMember::findByGroup($schedule->forGroup())->map(function (GroupMember $member) {
                        return $member->user->toArray();
                    });
                    $schedule->for = $schedule->forGroup()?->name;
                    $schedule->by = $schedule->byGroup()?->name;
                    $schedule->users = $users->toArray();
                    return $schedule->toArray();

            });
        })->toArray();
    }
}
