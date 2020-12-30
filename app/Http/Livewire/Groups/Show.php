<?php

namespace App\Http\Livewire\Groups;

use App\Domain\Policy;
use App\Models\Group;
use App\Traits\AuthenticatedUser;
use App\Traits\AuthorizesRoleOrPermission;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Show extends Component
{
    use AuthenticatedUser;
    use AuthorizesRoleOrPermission;

    public function mount(): void
    {
        Policy::groups_show_mount();
    }

    public function render(): Factory|View|Application
    {
        return view('livewire.groups.show', [
            'groups' => Group::all()
        ]);
    }
}
