<?php

namespace App\Http\Livewire\Users;

use App\Domain\Enum\GroupRoles;
use App\Models\User;
use App\Traits\AuthorizesRoleOrPermission;
use App\Traits\ModalCtrl;
use App\Traits\NotifyBrowser;
use Livewire\Component;

class Delete extends Component
{
    use ModalCtrl;
    use NotifyBrowser;
    use AuthorizesRoleOrPermission;

    public User $user;

    protected $listeners = [
        'show_user_exclusion_modal' => 'load',
        'delete_user_confirmation' => 'delete'
    ];

    protected function rules(): array
    {
        return [
            'user.id' => [
                'sometimes',
            ],
            'user.name' => [
                'sometimes',
            ]
        ];
    }

    public function render()
    {
        return view('livewire.users.delete');
    }

    public function mount()
    {
        $this->authorizeRoleOrPermission([
            GroupRoles::ADMIN()->getName()
        ]);

        $this->setEmptyUser();
    }

    protected function setEmptyUser()
    {
        $this->user = User::make([]);
    }

    protected function updateView()
    {
        $this->emit('update_user_display_content');
    }

    public function load(User $user)
    {
        if (is_null($user)) {
            $this->notifyError('text.record-found-failed');
            return;
        }

        $this->user = $user;
        $this->modalToggle();
    }

    protected function finally()
    {
        $this->updateView();
        $this->modalToggle();
        $this->setEmptyUser();
    }

    public function delete(User $user)
    {
        if (!$this->modalIsOpen()) {
            return;
        }

        if (is_null($user)) {
            $this->notifyError('text.record-found-failed');
            $this->finally();
            return;
        }

        if ($this->user->id !== $user->id) {
            $this->notifyAlert('text.violation.integrity');
            $this->finally();
            return;
        }

        try {
            $status = $this->user->delete();

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
}
