<?php

namespace App\Http\Livewire\Schedules;

use App\Domain\Contracts\Schedule\Retrieve;
use App\Domain\Policy;
use App\Domain\Schedule\Retrieve\Common;
use App\Models\Block;
use App\Models\Environment;
use App\Traits\AuthenticatedUser;
use App\Traits\AuthorizesRoleOrPermission;
use App\Traits\NotifyBrowser;
use Illuminate\Support\Collection;
use Livewire\Component;

class Show extends Component
{
    use AuthenticatedUser;
    use NotifyBrowser;
    use AuthorizesRoleOrPermission;

    public Block $block;
    public Environment $environment;

    protected $listeners = [
        'update_schedule_display_content' => '$refresh',
        'emit_current_environment' => 'emitCurrentEnvironment'
    ];

    public function mount()
    {
        Policy::schedule_show_mount();

        $this->block = Block::make(['id' => 0]);
        $this->environment = Environment::make(['id' => 0]);
    }

    public function emitCurrentEnvironment(): void
    {
        if (!$this->block->id) {
            $this->notifyAlert(__('validation.in', ['attribute' => __('label.block')]));
            return;
        }

        if (!$this->environment->id) {
            $this->notifyAlert(__('validation.in', ['attribute' => __('label.environment')]));
            return;
        }

        $this->emit('current_environment_selected', $this->environment->id);
    }

    public function render()
    {
        return view('livewire.schedules.show', [
            'blocks' => $this->blocks(),
            'environments' => $this->environments(),
            'schedules' => $this->schedules(new Common())
        ]);
    }

    protected function blocks(): Collection
    {
        return Block::all();
    }

    protected function environments(): Collection
    {
        return Environment::where('blocks_id', '=', $this->block->id)->get() ?? new Collection();
    }

    protected function schedules(Retrieve $retrieve): Collection
    {
        if ($this->block->id && $this->environment->id) {
            return $retrieve->byEnvironment($this->environment);
        }

        if ($this->block->id && !$this->environment->id) {
            return $retrieve->byBlock($this->block);
        }
        return $retrieve->all();
    }

    protected function rules(): array
    {
        return [
            'block.id' => ['required'],
            'environment.id' => ['required']
        ];
    }
}
