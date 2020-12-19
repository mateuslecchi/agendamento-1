<?php /** @noinspection PhpMissingFieldTypeInspection */

namespace App\Http\Livewire\Environments;

use App\Domain\Policy;
use App\Jobs\BlockExclusion;
use App\Jobs\EnvironmentExclusion;
use App\Models\Environment;
use App\Traits\AuthenticatedUser;
use App\Traits\AuthorizesRoleOrPermission;
use App\Traits\ModalCtrl;
use App\Traits\NotifyBrowser;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Delete extends Component
{
    use ModalCtrl;
    use NotifyBrowser;
    use AuthorizesRoleOrPermission;
    use AuthenticatedUser;

    public Environment $environment;

    protected $listeners = [
        'show_environment_exclusion_modal' => 'load',
        'delete_environment_confirmation' => 'delete'
    ];

    public function render(): Factory|View|Application
    {
        return view('livewire.environments.delete', [
            'environment' => $this->environment
        ]);
    }

    public function mount(): void
    {
        Policy::environments_delete_mount();
        $this->setEmptyEnvironment();
    }

    protected function setEmptyEnvironment(): void
    {
        $this->environment = Environment::make([]);
    }

    public function load(Environment $environment): void
    {
        Policy::environments_delete_load();

        if (is_null($environment)) {
            $this->notifyError('text.record-found-failed');
            return;
        }

        $this->environment = $environment;
        $this->modalToggle();
    }

    public function delete(Environment $environment): void
    {
        Policy::environments_delete_delete();

        if (!$this->modalIsOpen()) {
            return;
        }

        if (is_null($environment)) {
            $this->notifyError('text.record-found-failed');
            $this->finally();
            return;
        }

        if ($this->environment->id !== $environment->id) {
            $this->notifyAlert('text.violation.integrity');
            $this->finally();
            return;
        }

        try {

            EnvironmentExclusion::dispatch($this->authUser()->name, $this->environment);

            $this->notifyAlert('text.custom.deletion-of-registration-started');

        } catch (Exception) {
            $this->notifyError('text.delete.error');
            $this->finally();
        }

        $this->finally();
    }

    protected function finally(): void
    {
        $this->updateView();
        $this->modalToggle();
        $this->setEmptyEnvironment();
    }

    protected function updateView(): void
    {
        $this->emit('update_environment_display_content');
    }
}
