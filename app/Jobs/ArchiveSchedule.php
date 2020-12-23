<?php

namespace App\Jobs;

use App\Domain\Enum\Situation;
use App\Models\Schedule;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ArchiveSchedule implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
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
        Schedule::query()
            ->whereDate('date', '<', now()->format('Y-m-d'))
            ->whereDate('date', '>=', now()->subDays(30))
            ->whereIn('situations_id', [Situation::CONFIRMED()->getValue(), Situation::PENDING()->getValue()])
            ->get()
            ->map(static function(Schedule $schedule) {
                $schedule->situations_id = Situation::ARCHIVED()->getValue();
                $schedule->save();
            });
    }
}
