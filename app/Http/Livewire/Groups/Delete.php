<?php

namespace App\Http\Livewire\Groups;

use App\Domain\Enum\AdminGroup;
use App\Domain\Policy;
use App\Models\Group;
use App\Traits\AuthorizesRoleOrPermission;
use App\Traits\ModalCtrl;
use App\Traits\NotifyBrowser;
use Exception;
use Livewire\Component;

class Delete extends Component
{
    use ModalCtrl;
    use NotifyBrowser;
    use AuthorizesRoleOrPermission;

    public Group $group;

    protected $listeners = [
        'show_group_exclusion_modal' => 'load',
        'delete_group_confirmation' => 'delete'
    ];

    protected function rules(): array
    {
        return [
            'group.id' => [
                'sometimes',
            ],
            'group.name' => [
                'sometimes',
            ]
        ];
    }

    public function render()
    {
        return view('livewire.groups.delete', [
            'group' => $this->group
        ]);
    }

    public function mount()
    {
        Policy::groups_delete_mount();
        $this->setEmptyGroup();
    }

    protected function setEmptyGroup()
    {
        $this->group = Group::make([]);
    }

    protected function updateView()
    {
        $this->emit('update_group_display_content');
    }

    public function load(Group $group)
    {
        Policy::groups_delete_load();

        if (is_null($group)) {
            $this->notifyError('text.record-found-failed');
            return;
        }

        if ($group->id === AdminGroup::ID()->getValue()) {
            Policy::groups_delete_admin();
        }

        $this->group = $group;
        $this->modalToggle();
    }

    public function delete(Group $group)
    {
        Policy::groups_delete_save();
        if (!$this->modalIsOpen()) {
            return;
        }

        if (is_null($group)) {
            $this->notifyError('text.record-found-failed');
            $this->finally();
            return;
        }

        if ($group->id === AdminGroup::ID()->getValue()) {
            Policy::groups_delete_admin();
        }

        if ($this->group->id !== $group->id) {
            $this->notifyAlert('text.violation.integrity');
            $this->finally();
            return;
        }

        try {
            $status = $this->group->delete();

            $this->notifySuccessOrError(
                status: $status,
                success: 'text.delete.success',
                error: 'text.delete.error'
            );
        } catch (Exception) {
            $this->notifyError('text.delete.error');
            $this->finally();
        }

        $this->finally();
    }

    protected function finally()
    {
        $this->updateView();
        $this->modalToggle();
        $this->setEmptyGroup();
    }
}
