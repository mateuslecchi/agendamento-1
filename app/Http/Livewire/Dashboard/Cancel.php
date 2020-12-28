<?php

namespace App\Http\Livewire\Dashboard;

use App\Http\Livewire\Schedules\Details;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;


class Cancel extends Details
{
    public const ID = 'f0f1bffd-68b4-4ee7-b59e-9b08c68c4c10';
    public const CANCEL_SCHEDULE = 'cad74468-b5cd-4f1a-9b07-c423d3fa2264';

    protected $listeners = [
        self::ID => 'construct',
        self::CANCEL_SCHEDULE => 'cancelSchedule'
    ];

    public function render(): Factory|View|Application
    {
        return view('livewire.dashboard.cancel');
    }
}
