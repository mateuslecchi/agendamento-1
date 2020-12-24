<?php

namespace App\Http\Livewire\Users;

use App\Domain\Policy;
use App\Models\User as UserModel;
use App\Traits\AuthenticatedUser;
use App\Traits\AuthorizesRoleOrPermission;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Show extends Component
{
    use AuthenticatedUser;
    use AuthorizesRoleOrPermission;

    public function mount(): void
    {
        Policy::users_show_mount();
    }

    public function render(): Factory|View|Application
    {
        return view('livewire.users.show', [
            'users' => UserModel::all()
        ]);
    }
}
