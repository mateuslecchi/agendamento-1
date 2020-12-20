<?php /** @noinspection PhpMissingFieldTypeInspection */

namespace App\Http\Livewire\Users;

use App\Domain\Enum\AdminGroup;
use App\Domain\Enum\GroupRoles;
use App\Models\Group;
use App\Models\User;
use App\Traits\AuthenticatedUser;
use App\Traits\AuthorizesRoleOrPermission;
use App\Traits\ModalCtrl;
use App\Traits\NotifyBrowser;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use JetBrains\PhpStorm\ArrayShape;
use Livewire\Component;

class Delete extends Component
{
    use ModalCtrl;
    use NotifyBrowser;
    use AuthorizesRoleOrPermission;
    use AuthenticatedUser;

    public User $user;

    protected $listeners = [
        'show_user_exclusion_modal' => 'load',
        'delete_user_confirmation' => 'delete'
    ];

    #[ArrayShape(['user.id' => "string[]", 'user.name' => "string[]"])]
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

    public function render(): Factory|View|Application
    {
        return view('livewire.users.delete');
    }

    public function mount(): void
    {
        $this->setEmptyUser();
    }

    protected function setEmptyUser(): void
    {
        $this->user = User::make([]);
    }

    protected function updateView(): void
    {
        $this->emit('update_user_display_content');
    }

    /** @noinspection TypeUnsafeComparisonInspection
     * @noinspection PhpNonStrictObjectEqualityInspection
     * @param User $user
     */
    public function load(User $user): void
    {
        if (is_null($user)) {
            $this->notifyError('text.record-found-failed');
            return;
        }

        if ($user->id === $this->authUser()->id) {
            $this->notifyAlert('text.violation.integrity');
            return;
        }
        if (!is_null($user?->group?->role->id) &&
            (GroupRoles::getByValue($user?->group?->role->id) == GroupRoles::ADMIN()) &&
            (Group::find(AdminGroup::ID()->getValue())?->groupMembers()->count() < 2)
        ) {
            $this->notifyAlert('text.violation.integrity');
            return;
        }
        $this->user = $user;
        $this->modalToggle();
    }

    protected function finally(): void
    {
        $this->updateView();
        $this->modalToggle();
        $this->setEmptyUser();
    }

    public function delete(User $user): void
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
