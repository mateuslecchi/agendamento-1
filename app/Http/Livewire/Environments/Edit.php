<?php

namespace App\Http\Livewire\Environments;

use App\Domain\Policy;
use App\Models\Block;
use App\Models\Environment;
use App\Models\Group;
use App\Traits\AuthorizesRoleOrPermission;

class Edit extends Create
{
    use AuthorizesRoleOrPermission;

    protected $listeners = [
        'show_environment_editing_modal' => 'load'
    ];

    public function mount()
    {
        Policy::environments_edit_mount();
    }

    public function render()
    {
        return view('livewire.environments.edit', [
            'blocks' => Block::all(),
            'groups' => Group::all()
        ]);
    }

    public function load(Environment $environment)
    {
        Policy::environments_edit_load();

        if (is_null($environment)) {
            $this->notifyError('text.record-found-failed');
            return;
        }

        $this->environment = $environment;
        $this->modalToggle();
    }
}
