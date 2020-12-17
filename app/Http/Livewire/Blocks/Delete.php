<?php

namespace App\Http\Livewire\Blocks;

use App\Domain\Policy;
use App\Models\Block;
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

    public Block $block;

    protected $listeners = [
        'show_block_exclusion_modal' => 'load',
        'delete_block_confirmation' => 'delete'
    ];

    public function render()
    {
        return view('livewire.blocks.delete', [
            'block' => $this->block
        ]);
    }

    public function mount()
    {
        Policy::blocks_delete_mount();
        $this->setEmptyBlock();
    }

    protected function setEmptyBlock(): void
    {
        $this->block = Block::make([]);
    }

    public function load(Block $block)
    {
        Policy::blocks_delete_load();

        if (is_null($block)) {
            $this->notifyError('text.record-found-failed');
            return;
        }

        $this->block = $block;
        $this->modalToggle();
    }

    public function delete(Block $block)
    {
        Policy::blocks_delete_delete();

        if (!$this->modalIsOpen()) {
            return;
        }

        if (is_null($block)) {
            $this->notifyError('text.record-found-failed');
            $this->finally();
            return;
        }

        if ($this->block->id !== $block->id) {
            $this->notifyAlert('text.violation.integrity');
            $this->finally();
            return;
        }

        try {
            $status = $this->block->delete();

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
        $this->setEmptyBlock();
    }

    protected function updateView()
    {
        $this->emit('update_block_display_content');
    }
}
