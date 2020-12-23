<?php

namespace App\Http\Livewire\Users;

use App\Domain\Enum\GroupRoles;
use App\Models\Group;
use App\Models\GroupMember;
use App\Models\User;
use App\Traits\AuthorizesRoleOrPermission;
use App\Traits\ModalCtrl;
use App\Traits\NotifyBrowser;
use Exception;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Create extends Component
{
    use ModalCtrl;
    use NotifyBrowser;
    use AuthorizesRoleOrPermission;

    public User $user;
    public Group $group;

    protected $listeners = [
        'show_modal_user' => 'modalToggle'
    ];

    public function render()
    {
        return view('livewire.users.create', [
            'groups' => Group::all()
        ]);
    }

    public function mount(): void
    {
        $this->authorizeRoleOrPermission([
            GroupRoles::ADMIN()->getName()
        ]);

        $this->setEmptyUser();
        $this->setEmptyGroup();
    }

    protected function setEmptyUser(): void
    {
        $this->user = User::make([]);
    }

    public function save(): void
    {
        if (!$this->modalIsOpen()) {
            return;
        }

        $this->validate();

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

        if (!$this->user->hashPassword()->save()) {
            return false;
        }

        if ($this->group->id === -1) {
            $group = new Group();
            $group->name = $this->user->name;
            $group->group_roles_id = GroupRoles::USER()->getValue();

            if ($group->save()) {
                $this->group = $group;
            }
        }

        if (!$this->insertInGroup()) {
            try {
                $this->user->delete();
            } catch (Exception) {
                // ...
            } finally {
                return false;
            }
        }

        $this->user->syncRoles(GroupRoles::getByValue(
            Group::find($this->group->id)?->group_roles_id
        )->getName());

        return true;
    }

    protected function insertInGroup(): bool
    {
        return (bool)GroupMember::create([
            'groups_id' => $this->group->id,
            'users_id' => $this->user->id,
        ]);
    }

    protected function rules(): array
    {
        return [
            'user.name' => ['required', 'string', 'min:2', 'max:255'],
            'user.email' => ['required', 'string', 'email:rfc,dns', 'min:5', 'max:255', Rule::unique('users', 'email')],
            'user.password' => ['required', 'string', 'min:8', 'max:2048'],
            'group.id' => ['required', 'numeric', Rule::in([-1, ...Group::all()->pluck('id')->all()])]
        ];
    }

    protected function messages(): array
    {
        return [
            'group.id.required' => __('validation.required', ['attribute' => __('label.group')]),
            'group.id.in' => __('validation.in', ['attribute' => __('label.group')]),
            'group.id.numeric' => __('validation.numeric', ['attribute' => __('label.group')]),
        ];
    }

    protected function finally(): void
    {
        $this->updateView();
        $this->modalToggle();
        $this->setEmptyUser();
        $this->setEmptyGroup();
    }

    protected function updateView(): void
    {
        $this->emit('update_user_display_content');
    }

    public function setEmptyGroup(): void
    {
        $this->group = Group::make([]);
    }
}
