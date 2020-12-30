<?php

namespace App\Http\Livewire\Groups;

use App\Domain\Enum\AdminGroup;
use App\Domain\Enum\GroupRoles;
use App\Domain\Policy;
use App\Models\Group;
use App\Traits\AuthorizesRoleOrPermission;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class Edit extends Create
{
    public const ID = '45e684b7-f1c3-40ba-9413-f712e44ffb54';

    use AuthorizesRoleOrPermission;

    protected $listeners = [
        self::ID => 'construct'
    ];

    public function mount(): void
    {
        Policy::groups_edit_mount();
    }

    public function render(): Factory|View|Application
    {
        return view('livewire.groups.edit', [
            'roles' => GroupRoles::values()
        ]);
    }

    public function construct(Group $group): void
    {
        if ($this->isEditingAdminGroup($group)) {
            $this->sendAccessDeniedNotification();
            return;
        }
        $this->group = $group;
        $this->modalToggle();
    }

    public function updateGroup(): void
    {
        $this->createGroup();
    }

    protected function isEditingAdminGroup(Group $group): bool
    {
        return $group->id === AdminGroup::ID()->getValue();
    }

    protected function sendAccessDeniedNotification(): void
    {
        $this->notifyError('label.action.forbidden');
    }
}
