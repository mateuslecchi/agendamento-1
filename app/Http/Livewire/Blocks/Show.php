<?php

namespace App\Http\Livewire\Blocks;

use App\Domain\Policy;
use App\Models\Block;
use App\Traits\AuthenticatedUser;
use Livewire\Component;

class Show extends Component
{
    use AuthenticatedUser;

    protected $listeners = [
        'update_block_display_content' => '$refresh'
    ];

    public function mount(): void
    {
        Policy::blocks_show_mount();
    }

    public function render()
    {
        return view('livewire.blocks.show', [
            'blocks' => Block::all()
        ]);
    }
}
