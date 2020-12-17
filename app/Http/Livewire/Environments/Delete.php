<?php

namespace App\Http\Livewire\Environments;

use App\Domain\Policy;
use App\Models\Environment;
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

    public Environment $environment;

    protected $listeners = [
        'show_environment_exclusion_modal' => 'load',
        'delete_environment_confirmation' => 'delete'
    ];

    public function render()
    {
        return view('livewire.environments.delete', [
            'environment' => $this->environment
        ]);
    }

    public function mount()
    {
        Policy::environments_delete_mount();
        $this->setEmptyEnvironment();
    }

    protected function setEmptyEnvironment()
    {
        $this->environment = Environment::make([]);
    }

    public function load(Environment $environment)
    {
        Policy::environments_delete_load();

        if (is_null($environment)) {
            $this->notifyError('text.record-found-failed');
            return;
        }

        $this->environment = $environment;
        $this->modalToggle();
    }

    public function delete(Environment $environment)
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
            $status = $this->environment->delete();

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
        $this->setEmptyEnvironment();
    }

    protected function updateView()
    {
        $this->emit('update_environment_display_content');
    }
}
