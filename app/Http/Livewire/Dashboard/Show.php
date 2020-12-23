<?php

namespace App\Http\Livewire\Dashboard;

use App\Domain\Contracts\Schedule\Retrieve as ScheduleRetrieve;
use App\Domain\Enum\Situation;
use App\Domain\Schedule\Retrieve\Common;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class Show extends \App\Http\Livewire\Schedules\Show
{
    public int $situation = 0;
    public string $date = '0000-00-00';

    public function render()
    {
        return view('livewire.dashboard.show', [
            'blocks' => $this->blocks(),
            'environments' => $this->environments(),
            'schedules' => $this->schedules(new Common())
        ]);
    }

    protected function schedules(ScheduleRetrieve $retrieve): Collection
    {
        $schedule = Schedule::byGroupEnvironmentBuilder($this->authGroup())->get();

        if ($this->block->id) {
            $schedule = $schedule->filter(function (Schedule $schedule) {
                return $schedule->environment->block->id === $this->block->id;
            });
        }

        if ($this->environment->id) {
            $schedule = $schedule->filter(function (Schedule $schedule) {
                return $schedule->environments_id === $this->environment->id;
            });
        }

        if ($this->situation) {
            $schedule = $schedule->filter(function (Schedule $schedule) {
                return $schedule->situations_id === $this->situation;
            });
        } else {
            if ($this->date === '0000-00-00' || $this->date === '') {
                $schedule = $schedule->filter(function (Schedule $schedule) {
                    return $schedule->situations_id !== Situation::CANCELED()->getValue();
                });
            }
        }

        if ($this->date !== '0000-00-00' && $this->date !== '') {
            $schedule = $schedule->filter(function (Schedule $schedule) {
                return Carbon::parse($schedule->date)->eq(Carbon::parse($this->date));
            });
        } else {
            $schedule = $schedule->filter(function (Schedule $schedule) {
                return Carbon::parse($schedule->date)->isAfter(now()->format('Y-m-d'));
            });
        }

        return $schedule;
    }

}
