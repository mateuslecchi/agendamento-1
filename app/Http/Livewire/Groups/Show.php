<?php

namespace App\Http\Livewire\Groups;

use App\Domain\Policy;
use App\Models\Group;
use App\Traits\AuthenticatedUser;
use App\Traits\AuthorizesRoleOrPermission;
use Livewire\Component;

class Show extends Component
{
    use AuthenticatedUser;
    use AuthorizesRoleOrPermission;

    protected $listeners = [
        'update_group_display_content' => '$refresh'
    ];

    public function mount(): void
    {
        Policy::groups_show_mount();
    }

    public function render()
    {
        return view('livewire.groups.show', [
            'groups' => Group::all()
        ]);
    }
}
