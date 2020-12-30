<?php

namespace App\Http\Livewire\Groups;

use App\Domain\Enum\GroupRoles;
use App\Domain\Policy;
use App\Models\Group;
use App\Models\GroupMember;
use App\Traits\AuthorizesRoleOrPermission;
use App\Traits\Fmt;
use App\Traits\Make;
use App\Traits\ModalCtrl;
use App\Traits\NotifyBrowser;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Create extends Component
{
    public const ID = 'a9d2e479-ec8b-493b-b390-30775823eaf3';

    use ModalCtrl;
    use NotifyBrowser;
    use AuthorizesRoleOrPermission;

    public Group $group;

    protected $listeners = [
        self::ID => 'modalToggle'
    ];

    public function mount(): void
    {
        Policy::groups_create_mount();
        $this->initializeProperties();
    }

    public function render(): Factory|View|Application
    {
        return view('livewire.groups.create', [
            'roles' => GroupRoles::values()
        ]);
    }

    protected function initializeProperties(): void
    {
        $this->group = Make::group();
    }

    public function createGroup(): void
    {
        $this->validate();
        $this->sendBrowserNotification(
            saved: $this->saveGroup()
        );
        $this->finally();
    }

    protected function saveGroup(): bool
    {
        $status = $this->group->save();
        $this->configurePermissions();
        return $status;
    }

    protected function configurePermissions(): void
    {
        $role = GroupRoles::getByValue($this->group->group_roles_id)->getName();
        GroupMember::findByGroup($this->group)->map(function (GroupMember $member) use ($role) {
            $member->user->syncRoles($role);
        });
    }

    protected function sendBrowserNotification(bool $saved): void
    {
        $this->notifySuccessOrError(
            status: $saved,
            success: Fmt::title('text.save.success'),
            error: Fmt::title('text.save.error')
        );
    }

    protected function rules(): array
    {
        return [
            'group.name' => ['required', 'min:2', 'max:255'],
            'group.group_roles_id' => ['required', Rule::in($this->validIDsForGroupRole())]
        ];
    }

    protected function validIDsForGroupRole(): array
    {
        return array_values(array_map(static function (GroupRoles $role) {
            return $role->getValue();
        }, GroupRoles::values()));
    }

    protected function messages(): array
    {
        return [
            'group.group_roles_id.required' => Fmt::text('validation.required', ['attribute' => 'label.role']),
            'group.group_roles_id.in' => Fmt::text('validation.in', ['attribute' => 'label.role'])
        ];
    }

    protected function finally(): void
    {
        $this->modalToggle();
        $this->initializeProperties();
    }
}
