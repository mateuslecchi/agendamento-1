<?php /** @noinspection PhpMissingFieldTypeInspection */

namespace App\Http\Livewire\Users;

use App\Domain\Enum\GroupRoles;
use App\Domain\Policy;
use App\Models\Group;
use App\Models\User;
use App\Traits\AuthenticatedUser;
use App\Traits\AuthorizesRoleOrPermission;
use App\Traits\Make;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use JetBrains\PhpStorm\Pure;

class Edit extends Create
{
    public const ID = '832f7f5b-9e8c-419d-83f2-4ac63226fefc';

    use AuthenticatedUser;
    use AuthorizesRoleOrPermission;

    protected $listeners = [
        self::ID => 'load'
    ];

    public function mount(): void
    {
        Policy::users_edit_mount();
        $this->initializeProperties();
    }

    public function render(): View|Factory|Application
    {
        return view('livewire.users.edit', [
            'groups' => $this->validGroupsForSelection(),
            'allowGroupEditing' => !$this->disableGroupEditing()
        ]);
    }

    protected function validGroupsForSelection(): Collection
    {
        if(!isset($this->user)) {
            return new Collection();
        }

        $groups = Group::query()
            ->where(Group::PERSONAL_GROUP, '=', false)
            ->get();

        if (!$this->user?->group) {
            return $groups;
        }

        if ($this->user->group->personal_group) {
            return new Collection([
                $this->user->group,
                ...$groups
            ]);
        }
        return $groups;
    }

    protected function disableGroupEditing(): bool
    {
        if (!$this->userIsEditing()) {
            return true;
        }
        return $this->editingUserIsAnAdministrator() &&
            $this->isUniqueAdministrator() &&
            $this->administratorSelfEditing();
    }

    #[Pure]
    protected function userIsEditing(): bool
    {
        return isset($this->user) ? !is_null($this->user->id) : false;
    }

    protected function editingUserIsAnAdministrator(): bool
    {
        return GroupRoles::getByValue($this->user->group?->group_roles_id ?? 0, GroupRoles::USER())
                ->getValue() === GroupRoles::ADMIN()->getValue();
    }

    protected function isUniqueAdministrator(): bool
    {
        return Group::byRole(GroupRoles::ADMIN())
                ->map(function (Group $group) {
                    return $group->groupMembers()->count();
                })->sum() === 1;
    }

    protected function administratorSelfEditing(): bool
    {
        return $this->authIsAdmin() &&
            $this->editingUserIsAnAdministrator() &&
            $this->authUser()->id === $this->user->id;
    }

    public function load(User $user): void
    {
        $this->user = $user;
        $this->group = $user->group ?? Make::group();
        $this->modalToggle();
    }

    public function updateUser(): void
    {
        $this->validate();

        $this->sendBrowserNotification(
            savedUser: $this->saveUser(),
            hasGroup: $this->saveGroup(),
            hasRole: $this->configurePermissions()
        );
        $this->finally();
    }

    protected function saveUser(): bool
    {
        if (is_null($this->user->password)) {
            $this->user->password = $this->user->getOriginal(User::PASSWORD);
        } else {
            $this->user->hashPassword();
        }
        return $this->user->save();
    }

    protected function saveGroup(): bool
    {
        if ($this->group->id > 0 && $this->group->id === ($this->user->group?->id ?? 0)) {
            return true;
        }

        $this->group = Group::find($this->group->id);

        if ($this->user->group->personal_group) {
            $this->user->group->delete();
            return $this->associateUserWithGroup();
        }
        $this->user->member->delete();
        return $this->associateUserWithGroup();
    }

    protected function configurePermissions(): bool
    {
        $this->user = User::find($this->user->id);
        $this->user->syncRoles($this->groupRole());
        return $this->user->hasRole($this->groupRole());
    }

    protected function rules(): array
    {
        if (!isset($this->user)) {
            return parent::rules();
        }
        $rules = parent::rules();
        $rules['user.email'] = ['required', 'string', 'email:rfc,dns', 'min:5', 'max:255', Rule::unique('users', 'email')->ignore($this->user->id)];
        $rules['user.password'] = ['sometimes', 'string', 'min:8', 'max:2048'];
        return $rules;
    }
}
