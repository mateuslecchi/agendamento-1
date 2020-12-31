<?php /** @noinspection PhpMissingFieldTypeInspection */

namespace App\Http\Livewire\Users;

use App\Domain\Enum\GroupRoles;
use App\Domain\Policy;
use App\Models\Group;
use App\Models\User;
use App\Traits\AuthenticatedUser;
use App\Traits\AuthorizesRoleOrPermission;
use App\Traits\Fmt;
use App\Traits\Make;
use App\Traits\ModalCtrl;
use App\Traits\NotifyBrowser;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Delete extends Component
{
    public const ID = 'f270f232-0225-4260-9dbd-cd3d67709905';
    public const CONFIRM_DELETION = 'c79d9053-627a-48df-a476-0c1408680c47';

    use ModalCtrl;
    use NotifyBrowser;
    use AuthorizesRoleOrPermission;
    use AuthenticatedUser;

    public User $user;

    protected $listeners = [
        self::ID => 'load',
        self::CONFIRM_DELETION => 'delete'
    ];

    public function render(): Factory|View|Application
    {
        return view('livewire.users.delete');
    }

    public function mount(): void
    {
        Policy::users_delete_mount();
        $this->initializeProperties();
    }

    protected function initializeProperties(): void
    {
        $this->user = Make::user();
    }

    public function load(User $user): void
    {
        if ($this->selfDelete($user) || ($this->isAnAdministrator($user) && $this->hasUniqueAdministrator())) {
            $this->notifyError(Fmt::text('label.action.forbidden'));
            return;
        }
        $this->user = $user;
        $this->modalToggle();
    }

    protected function selfDelete(User $user): bool
    {
        return $this->authUser()->id === $user->id;
    }

    protected function isAnAdministrator(User $user): bool
    {
        if (is_null($user->group)) {
            return false;
        }
        return GroupRoles::getByValue($user->group->group_roles_id)
                ->getValue() === GroupRoles::ADMIN()->getValue();
    }

    protected function hasUniqueAdministrator(): bool
    {
        return Group::byRole(GroupRoles::ADMIN())
                ->map(function (Group $group) {
                    return $group->groupMembers()->count();
                })->sum() === 1;
    }

    public function delete(): void
    {
        $this->sendBrowserNotification(
            deletedUser: $this->deleteUser()
        );
        $this->finally();
    }

    protected function sendBrowserNotification(bool $deletedUser): void
    {
        $this->notifySuccessOrError(
            status: $deletedUser,
            success: Fmt::title('text.save.success'),
            error: Fmt::title('text.save.error')
        );
    }

    protected function deleteUser(): bool
    {
        try {
            if ($this->user->group?->personal_group) {
                $this->user->group?->delete();
            }
            return $this->user->delete();
        } catch (Exception) {
            return false;
        }
    }

    protected function finally(): void
    {
        $this->modalToggle();
        $this->initializeProperties();
    }
}
