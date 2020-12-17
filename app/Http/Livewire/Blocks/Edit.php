<?php

namespace App\Http\Livewire\Blocks;

use App\Domain\Policy;
use App\Models\Block;
use App\Traits\AuthorizesRoleOrPermission;

class Edit extends Create
{
    use AuthorizesRoleOrPermission;

    protected $listeners = [
        'show_block_editing_modal' => 'load'
    ];

    public function mount(): void
    {
        Policy::blocks_edit_mount();
    }

    public function render()
    {
        return view('livewire.blocks.edit');
    }

    public function load(Block $block)
    {
        Policy::blocks_edit_load();

        if (is_null($block)) {
            $this->notifyError('text.record-found-failed');
            return;
        }

        $this->block = $block;
        $this->modalToggle();
    }
}
