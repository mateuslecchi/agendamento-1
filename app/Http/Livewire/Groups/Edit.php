<?php

namespace App\Http\Livewire\Groups;

use App\Domain\Enum\AdminGroup;
use App\Domain\Enum\GroupRoles;
use App\Domain\Policy;
use App\Models\Group;
use App\Traits\AuthorizesRoleOrPermission;

class Edit extends Create
{
    use AuthorizesRoleOrPermission;

    protected $listeners = [
        'show_group_editing_modal' => 'load'
    ];

    public function mount(): void
    {
        Policy::groups_edit_mount();
    }

    public function render()
    {
        return view('livewire.groups.edit', [
            'roles' => GroupRoles::values()
        ]);
    }

    public function load(Group $group)
    {
        Policy::groups_edit_load();

        if (is_null($group)) {
            $this->notifyError('text.record-found-failed');
            return;
        }

        if ($group->id === AdminGroup::ID()->getValue()) {
            Policy::groups_edit_admin();
        }

        $this->group = $group;
        $this->modalToggle();
    }
}
