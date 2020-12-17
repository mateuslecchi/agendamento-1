<?php

namespace App\Http\Livewire\Blocks;

use App\Domain\Policy;
use App\Models\Block;
use App\Traits\AuthorizesRoleOrPermission;
use App\Traits\ModalCtrl;
use App\Traits\NotifyBrowser;
use Livewire\Component;

class Create extends Component
{
    use ModalCtrl;
    use NotifyBrowser;
    use AuthorizesRoleOrPermission;

    public Block $block;

    protected $listeners = [
        'show_modal_block' => 'modalToggle'
    ];

    public function render()
    {
        return view('livewire.blocks.create');
    }

    public function mount()
    {
        Policy::blocks_create_mount();
        $this->setEmptyBlock();
    }

    protected function setEmptyBlock(): void
    {
        $this->block = Block::make([]);
    }

    public function save()
    {
        Policy::blocks_create_save();

        if (!$this->modalIsOpen()) {
            return;
        }

        $this->validate();

        $status = $this->block->save();

        $this->notifySuccessOrError(
            status: $status,
            success: __('text.save.success'),
            error: __('text.save.error')
        );

        $this->finally();
    }

    protected function finally(): void
    {
        $this->updateView();
        $this->modalToggle();
        $this->setEmptyBlock();
    }

    protected function updateView(): void
    {
        $this->emit('update_block_display_content');
    }

    protected function rules(): array
    {
        return [
            'block.name' => ['required', 'string', 'min:2', 'max:255'],
        ];
    }
}
