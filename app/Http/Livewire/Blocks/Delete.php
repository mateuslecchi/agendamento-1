<?php
/** @noinspection PhpMissingFieldTypeInspection */

namespace App\Http\Livewire\Blocks;

use App\Domain\Policy;
use App\Jobs\BlockExclusion;
use App\Models\Block;
use App\Traits\AuthenticatedUser;
use App\Traits\AuthorizesRoleOrPermission;
use App\Traits\ModalCtrl;
use App\Traits\NotifyBrowser;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Delete extends Component
{
    use ModalCtrl;
    use NotifyBrowser;
    use AuthorizesRoleOrPermission;
    use AuthenticatedUser;

    public Block $block;

    protected $listeners = [
        'show_block_exclusion_modal' => 'load',
        'delete_block_confirmation' => 'delete'
    ];

    public function render(): Factory|View|Application
    {
        return view('livewire.blocks.delete', [
            'block' => $this->block
        ]);
    }

    public function mount(): void
    {
        Policy::blocks_delete_mount();
        $this->setEmptyBlock();
    }

    protected function setEmptyBlock(): void
    {
        $this->block = Block::make([]);
    }

    public function load(Block $block): void
    {
        Policy::blocks_delete_load();

        if (is_null($block)) {
            $this->notifyError('text.record-found-failed');
            return;
        }

        $this->block = $block;
        $this->modalToggle();
    }

    public function delete(Block $block): void
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

            BlockExclusion::dispatch($this->authUser()->name, $this->block);

            $this->notifyAlert('text.custom.deletion-of-registration-started');

        } catch (Exception) {
            $this->notifyError('text.delete.error');
            $this->finally();
        }

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
}
