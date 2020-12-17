<?php

namespace App\Http\Livewire\Users;

use App\Domain\Enum\GroupRoles;
use App\Domain\Policy;
use App\Models\User;
use App\Traits\AuthenticatedUser;
use App\Traits\AuthorizesRoleOrPermission;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class Show extends Component
{
    use AuthenticatedUser;
    use AuthorizesRoleOrPermission;

    protected $listeners = [
        'update_user_display_content' => '$refresh'
    ];

    public function mount(): void
    {
       Policy::users_show_mount();
    }

    /** @noinspection NullPointerExceptionInspection */
    public function render()
    {
        return view('livewire.users.show', [
            'users' => match (GroupRoles::getByValue($this->authGroup()->id)?->getValue()) {
                GroupRoles::ADMIN()->getValue() => User::all(),
                default => new Collection()
    }
        ]);
}
}
