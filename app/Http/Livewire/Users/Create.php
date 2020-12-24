<?php

namespace App\Http\Livewire\Users;

use App\Domain\Enum\GroupRoles;
use App\Domain\Policy;
use App\Models\Group;
use App\Models\GroupMember;
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
    public const ID = '9be59e58-bb80-46c5-954c-4a1d752b84fd';

    use ModalCtrl;
    use NotifyBrowser;
    use AuthorizesRoleOrPermission;

    public User $user;
    public Group $group;

    protected $listeners = [
        self::ID => 'modalToggle'
    ];

    public function render(): Factory|View|Application
    {
        return view('livewire.users.create', ['groups' => $this->validGroupsForSelection()]);
    }

    protected function validGroupsForSelection(): Collection
    {
        return new Collection([Make::personalGroup(), ...Group::all()]);
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

    public function createNewUser(): void
    {
        $this->validate();
        $this->sendBrowserNotification(
            savedUser: $this->createUser(),
            hasGroup: $this->configureGroup(),
            hasRole: $this->configurePermissions()
        );
        $this->finally();
    }

    protected function sendBrowserNotification(bool $savedUser, bool $hasGroup, bool $hasRole): void
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
            Group::NAME => $this->user->name,
            Group::GROUP_ROLE_ID => GroupRoles::USER()->getValue()
        ]);
        $group->save();
        return $group;
    }

    protected function associateUserWithGroup(): bool
    {
        return Make::groupMember([
            GroupMember::GROUP_ID => $this->group->id,
            GroupMember::USER_ID => $this->user->id,
        ])->save();
    }

    protected function configurePermissions(): bool
    {
        $this->user->syncRoles($this->groupRole());
        return $this->user->hasRole($this->groupRole());
    }

    protected function groupRole(): string
    {
        return GroupRoles::getByValue($this->group->group_roles_id, GroupRoles::USER())->getName();
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
            ], false),
            'group.id.in' => Fmt::text('validation.in', [
                'attribute' => Fmt::lower('label.group')
            ], false),
            'group.id.numeric' => Fmt::text('validation.numeric', [
                'attribute' => Fmt::lower('label.group')
            ], false)
        ];
    }

    public function hydrate(): void
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
