<?php

namespace App\Http\Livewire\Users;

use App\Domain\Enum\GroupRoles;
use App\Models\Group;
use App\Models\GroupMember;
use App\Models\User;
use App\Traits\AuthorizesRoleOrPermission;
use Illuminate\Validation\Rule;

class Edit extends Create
{
    use AuthorizesRoleOrPermission;

    protected $listeners = [
        'show_user_editing_modal' => 'load'
    ];

    public function mount(): void
    {
        $this->authorizeRoleOrPermission([
            GroupRoles::ADMIN()->getName()
        ]);
    }

    public function render()
    {
        return view('livewire.users.edit', [
            'groups' => Group::all()
        ]);
    }

    protected function editRules(): array
    {
        return [
            'user.name' => ['required', 'string', 'min:2', 'max:255'],
            'user.email' => [
                'required',
                'string',
                'email:rfc,dns',
                'min:5',
                'max:255',
                Rule::unique('users', 'email')
                    ->ignore($this->user->id)
            ],
            'user.password' => ['sometimes', 'string', 'min:8', 'max:2048'],
            'group.id' => ['required', 'numeric', Rule::in(Group::all()->pluck('id')->all())]
        ];
    }

    public function load(User $user)
    {
        if (is_null($user)) {
            $this->notifyError('text.record-found-failed');
            return;
        }

        $this->user = $user;

        if (is_null($user->group)) {
            $this->notifyAlert('text.violation.integrity');
            $this->setEmptyGroup();
        } else {
            $this->group = $user->group;
        }

        $this->modalToggle();
    }

    public function save(): void
    {
        if (!$this->modalIsOpen()) {
            return;
        }

        $this->validate($this->editRules());

        $status = $this->saveEntity();

        $this->notifySuccessOrError(
            status: $status,
            success: __('text.save.success'),
            error: __('text.save.error')
        );

        $this->finally();
    }

    protected function saveEntity(): bool
    {
        if (!is_null($this->user->password)) {
            $this->user->hashPassword();
        } else {
            $this->user->password = $this->user->getOriginal('password');
        }

        if (!$this->user->save()) {
            return false;
        }

        if (is_null($this->user?->group)) {
            return $this->insertInGroup();
        }

        if ($this->user->group->id !== $this->group->id) {
            $groupMember = GroupMember::findByUser($this->user->id);
            $groupMember->groups_id = $this->group->id;

            $this->user->syncRoles(GroupRoles::getByValue(
                Group::find($this->group->id)?->group_roles_id
            )->getName());

            return $groupMember->save();
        }

        return true;
    }
}
