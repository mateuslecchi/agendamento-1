<?php

namespace App\Http\Livewire\Users;

use App\Domain\Enum\GroupRoles;
use App\Domain\Policy;
use App\Models\Group;
use App\Models\User;
use App\Traits\AuthorizesRoleOrPermission;
use App\Traits\Fmt;
use App\Traits\Make;
use App\Traits\ModalCtrl;
use App\Traits\NotifyBrowser;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
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

    public function render(): Factory|View|Application
    {
        return view('livewire.users.create', ['groups' => $this->validGroupsForSelection()]);
    }

    public function validGroupsForSelection(): Collection
    {
        return new Collection([Make::personalGroup(),...Group::all()]);
    }

    public function mount(): void
    {
        Policy::users_create_mount();
        $this->initializeProperties();
    }

    protected function initializeProperties(): void
    {
        $this->user = Make::user();
        $this->group = Make::group();
    }

    public function save(): void
    {
        $this->validate();
        $this->sendNotification(
            savedUser: $this->createUser(),
            hasGroup: $this->configureGroup(),
            hasRole: $this->configurePermissions()
        );
        $this->finally();
    }

    protected function sendNotification(bool $savedUser, bool $hasGroup, bool $hasRole): void
    {
        $this->notifySuccessOrError(
            status: $savedUser && $hasGroup && $hasRole,
            success: Fmt::title('text.save.success'),
            error: Fmt::title('text.save.error')
        );
    }

    protected function createUser(): bool
    {
        return $this->user->hashPassword()->save();
    }

    protected function configureGroup(): bool
    {
        if ($this->group->id === Make::personalGroup()->id) {
            $this->group = $this->createPersonalGroup();
        }
        return $this->associateUserWithGroup();
    }

    protected function createPersonalGroup(): Group
    {
        $group = Make::group([
            'name' => $this->user->name,
            'group_roles_id' => GroupRoles::USER()->getValue()
        ]);
        $group->save();
        return $group;
    }

    protected function associateUserWithGroup(): bool
    {
        return Make::groupMember([
            'groups_id' => $this->group->id,
            'users_id' => $this->user->id,
        ])->save();
    }

    protected function configurePermissions(): bool
    {
        $this->user->syncRoles($this->groupRole());
        return $this->user->hasRole($this->groupRole());
    }

    protected function groupRole(): string
    {
        return GroupRoles::getByValue($this->group->group_roles_id)?->getName();
    }

    protected function finally(): void
    {
        $this->modalToggle();
        $this->initializeProperties();
    }

    protected function rules(): array
    {
        return [
            'user.name' => ['required', 'string', 'min:2', 'max:255'],
            'user.email' => ['required', 'string', 'email:rfc,dns', 'min:5', 'max:255', Rule::unique('users', 'email')],
            'user.password' => ['required', 'string', 'min:8', 'max:2048'],
            'group.id' => ['required', 'numeric', Rule::in($this->validGroupsForSelection()->pluck('id'))]
        ];
    }

    protected function messages(): array
    {
        return [
            'group.id.required' => Fmt::text('validation.required', [
                'attribute' => Fmt::lower('label.group')
            ]),
            'group.id.in' => Fmt::text('validation.in', [
                'attribute' => Fmt::lower('label.group')
            ]),
            'group.id.numeric' => Fmt::text('validation.numeric', [
                'attribute' => Fmt::lower('label.group')
            ])
        ];
    }
}
