<?php /** @noinspection PhpMissingFieldTypeInspection */

namespace App\Http\Livewire\Environments;

use App\Domain\Policy;
use App\Jobs\EnvironmentExclusion;
use App\Models\Environment;
use App\Traits\AuthenticatedUser;
use App\Traits\AuthorizesRoleOrPermission;
use App\Traits\Make;
use App\Traits\ModalCtrl;
use App\Traits\NotifyBrowser;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Delete extends Component
{
    public const ID = '1967a8a6-075a-486f-abc3-424e10b21826';
    public const CONFIRM_DELETION = '24c5396e-acff-446e-a5cc-7820c8064a66';

    use ModalCtrl;
    use NotifyBrowser;
    use AuthorizesRoleOrPermission;
    use AuthenticatedUser;

    public Environment $environment;

    protected $listeners = [
        self::ID => 'load',
        self::CONFIRM_DELETION => 'delete'
    ];

    public function render(): Factory|View|Application
    {
        return view('livewire.environments.delete');
    }

    public function mount(): void
    {
        Policy::environments_delete_mount();
        $this->initializeProperties();
    }

    protected function initializeProperties(): void
    {
        $this->environment = Make::environment();
    }

    public function load(Environment $environment): void
    {
        $this->environment = $environment;
        $this->modalToggle();
    }

    public function delete(): void
    {
        $this->registerExclusionJob();
        $this->sendBrowserNotification();
        $this->finally();
    }

    protected function registerExclusionJob(): void
    {
        EnvironmentExclusion::dispatch(
            $this->authUser()->name,
            $this->environment
        );
    }

    protected function sendBrowserNotification(): void
    {
        $this->notifyAlert('text.custom.deletion-of-registration-started');
    }

    protected function finally(): void
    {
        $this->modalToggle();
        $this->initializeProperties();
    }
}
