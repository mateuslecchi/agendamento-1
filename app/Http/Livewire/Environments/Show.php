<?php

namespace App\Http\Livewire\Environments;

use App\Domain\Enum\GroupRoles;
use App\Domain\Policy;
use App\Models\Environment;
use App\Traits\AuthenticatedUser;
use App\Traits\AuthorizesRoleOrPermission;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class Show extends Component
{
    use AuthenticatedUser;
    use AuthorizesRoleOrPermission;

    protected $listeners = [
        'update_environment_display_content' => '$refresh'
    ];

    public function mount()
    {
        Policy::environments_show_mount();
    }

    public function render()
    {
        return view('livewire.environments.show', [
            'environments' => match (GroupRoles::getByValue($this->authGroup()->id)?->getValue()) {
                GroupRoles::ADMIN()->getValue() => Environment::all(),
                GroupRoles::MANAGER()->getValue() => Environment::byGroup($this->authGroup()),
                default => new Collection()
    }
        ]);
}
}
