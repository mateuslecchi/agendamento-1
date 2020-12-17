<?php

namespace App\Http\Livewire\Groups;

use App\Domain\Enum\GroupRoles;
use App\Domain\Policy;
use App\Models\Group;
use App\Models\GroupMember;
use App\Traits\AuthorizesRoleOrPermission;
use App\Traits\ModalCtrl;
use App\Traits\NotifyBrowser;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Create extends Component
{
    use ModalCtrl;
    use NotifyBrowser;
    use AuthorizesRoleOrPermission;

    public Group $group;

    protected $listeners = [
        'show_modal_group' => 'modalToggle'
    ];

    public function render()
    {
        return view('livewire.groups.create', [
            'roles' => GroupRoles::values()
        ]);
    }

    public function mount()
    {
        Policy::groups_create_mount();
        $this->setEmptyGroup();
    }

    protected function setEmptyGroup()
    {
        $this->group = Group::make([]);
    }

    public function save()
    {
        Policy::groups_create_save();

        if (!$this->modalIsOpen()) {
            return;
        }

        $this->validate();

        $status = $this->group->save();

        GroupMember::findByGroup($this->group)->map(function (GroupMember $member) {
            $member->user->syncRoles(GroupRoles::getByValue($this->group->group_roles_id)->getName());
        });

        $this->notifySuccessOrError(
            status: $status,
            success: __('text.save.success'),
            error: __('text.save.error')
        );

        $this->finally();
    }

    protected function updateView()
    {
        $this->emit('update_group_display_content');
    }

    protected function rules()
    {
        return [
            'group.name' => ['required', 'min:2', 'max:255'],
            'group.group_roles_id' => ['required', Rule::in($this->groupRoleId())]
        ];
    }

    protected function groupRoleId()
    {
        return array_values(array_map(function (GroupRoles $role) {
            return $role->getValue();
        }, GroupRoles::values()));
    }

    protected function messages()
    {
        return [
            'group.group_roles_id.required' => __('validation.required', ['attribute' => __('label.role')]),
            'group.group_roles_id.in' => __('validation.in', ['attribute' => __('label.role')])
        ];
    }

    protected function finally()
    {
        $this->updateView();
        $this->modalToggle();
        $this->setEmptyGroup();
    }
}
