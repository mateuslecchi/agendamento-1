<?php

namespace App\Http\Livewire\Environments;

use App\Domain\Policy;
use App\Models\Environment;
use App\Traits\AuthenticatedUser;
use App\Traits\AuthorizesRoleOrPermission;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Component;

class Show extends Component
{
    use AuthenticatedUser;
    use AuthorizesRoleOrPermission;

    public function mount(): void
    {
        Policy::environments_show_mount();
    }

    public function render(): Factory|View|Application
    {
        return view('livewire.environments.show', [
            'environments' => $this->environments()
        ]);
    }

    protected function environments(): Collection
    {
        if ($this->authIsAdmin()) {
            return Environment::all();
        }
        return Environment::byGroup($this->authGroup());
    }
}
