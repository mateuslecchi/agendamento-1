<?php

namespace App\Http\Livewire\Environments;

use App\Domain\Policy;
use App\Models\Environment;
use App\Traits\AuthorizesRoleOrPermission;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class Edit extends Create
{
    public const ID = '9a6c5acd-1ff3-4e91-a3e5-b1ea1a2aef42';

    use AuthorizesRoleOrPermission;

    protected $listeners = [
        self::ID => 'load'
    ];

    public function mount(): void
    {
        Policy::environments_edit_mount();
    }

    public function render(): Factory|View|Application
    {
        return view('livewire.environments.edit', [
            'blocks' => $this->validBlocksForSelection(),
            'groups' => $this->validGroupsForSelection()
        ]);
    }

    public function load(Environment $environment): void
    {
        $this->environment = $environment;
        $this->modalToggle();
    }

    public function updateEnvironment(): void
    {
        $this->validate();
        $this->sendBrowserNotification(
            savedEnvironment: $this->saveEnvironment()
        );
        $this->finally();
    }
}
