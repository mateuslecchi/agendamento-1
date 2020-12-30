<?php

namespace App\Http\Livewire\Groups;

use App\Domain\Enum\AdminGroup;
use App\Domain\Policy;
use App\Models\Group;
use App\Traits\AuthorizesRoleOrPermission;
use App\Traits\Fmt;
use App\Traits\ModalCtrl;
use App\Traits\NotifyBrowser;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use JetBrains\PhpStorm\Pure;
use Livewire\Component;

class Delete extends Component
{
    public const ID = 'bc5cb39d-2f00-40ef-ad95-cd873f0539a6';
    public const CONFIRM_DELETION = '863fd85f-26f8-4682-8c26-4b5241156720';

    use ModalCtrl;
    use NotifyBrowser;
    use AuthorizesRoleOrPermission;

    public Group $group;

    protected $listeners = [
        self::ID => 'construct',
        self::CONFIRM_DELETION => 'delete'
    ];

    public function render(): Factory|View|Application
    {
        return view('livewire.groups.delete', [
            'group' => $this->group
        ]);
    }

    public function mount(): void
    {
        Policy::groups_delete_mount();
        $this->initializeProperties();
    }

    protected function initializeProperties(): void
    {
        $this->group = Group::make([]);
    }

    public function construct(Group $group): void
    {
        if ($this->isExcludingAdminGroup($group)) {
            $this->sendAccessDeniedNotification();
            return;
        }
        $this->group = $group;
        $this->modalToggle();
    }

    #[Pure]
    protected function isExcludingAdminGroup(Group $group): bool
    {
        return $group->id === AdminGroup::ID()->getValue();
    }

    protected function sendAccessDeniedNotification(): void
    {
        $this->notifyError('label.action.forbidden');
    }

    public function delete(): void
    {
        $this->sendBrowserNotification(
            saved: $this->group->delete()
        );
        $this->finally();
    }

    protected function sendBrowserNotification(bool $saved): void
    {
        $this->notifySuccessOrError(
            status: $saved,
            success: Fmt::title('text.delete.success'),
            error: Fmt::title('text.delete.error')
        );
    }

    protected function finally(): void
    {
        $this->modalToggle();
        $this->initializeProperties();
    }
}
