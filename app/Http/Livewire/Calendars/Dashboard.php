<?php

namespace App\Http\Livewire\Calendars;

use App\Domain\Enum\Situation;
use App\Models\Schedule;
use App\Traits\AuthenticatedUser;
use Asantibanez\LivewireCalendar\LivewireCalendar;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;


class Dashboard extends LivewireCalendar
{
    use AuthenticatedUser;

    public function events(): Collection
    {
        return Schedule::byGroupEnvironmentBuilder($this->authGroup())
            ->whereDate('date', '>=', $this->gridStartsAt)
            ->whereDate('date', '<=', $this->gridEndsAt)
            ->where('situations_id', '=', Situation::CONFIRMED()->getValue())
            ->get()
            ->map(function (Schedule $schedule) {
                return [
                    'id' => $schedule->id,
                    'title' => Str::ucfirst(__($schedule->environment->name)) . " - " . Str::ucfirst(__($schedule->environment->block->name)),
                    'for' => Str::ucfirst(__($schedule->forGroup()->name)),
                    'description' => Carbon::parse($schedule->start_time)->format('H:i') . ' às ' . Carbon::parse($schedule->end_time)->format('H:i'),
                    'date' => Carbon::parse($schedule->date),
                ];
            });
    }
}
