<?php /** @noinspection PhpMissingFieldTypeInspection */

namespace App\Http\Livewire\Users;

use App\Domain\Enum\AdminGroup;
use App\Domain\Enum\GroupRoles;
use App\Domain\Policy;
use App\Models\Group;
use App\Models\GroupMember;
use App\Models\User;
use App\Traits\AuthenticatedUser;
use App\Traits\AuthorizesRoleOrPermission;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use JetBrains\PhpStorm\ArrayShape;

class Edit extends Create
{
    use AuthenticatedUser;
    use AuthorizesRoleOrPermission;

    protected $listeners = [
        'show_user_editing_modal' => 'load'
    ];

    public function mount(): void
    {
        Policy::users_edit_mount();
        $this->initializeProperties();
    }

    public function render(): View|Factory|Application
    {
        $admins = Group::find(AdminGroup::ID()->getValue())?->groupMembers();
        return view('livewire.users.edit', [
            'groups' => Group::all(),
            'blockEditGroupIfTheLastAdministrator' => ($admins->count() !== 1 || $admins->first()->id !== $this?->user?->id)
        ]);
    }

    #[ArrayShape(['user.name' => "string[]", 'user.email' => "array", 'user.password' => "string[]", 'group.id' => "array"])]
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

    public function load(User $user): void
    {
        if (is_null($user)) {
            $this->notifyError('text.record-found-failed');
            return;
        }

        $this->user = $user;

        if (is_null($user->group)) {
            $this->notifyAlert('text.violation.integrity');
            $this->initializeProperties();
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

            if(is_null($groupMember)) { return false; }
            $groupMember->groups_id = $this->group->id;

            $groupRole = GroupRoles::getByValue(
                Group::find($this->group->id)?->group_roles_id
            );

            if(is_null($groupRole)) { return false; }
            $this->user->syncRoles($groupRole->getName());

            return $groupMember->save();
        }

        return true;
    }
}
