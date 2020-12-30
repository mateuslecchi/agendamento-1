<?php

namespace App\Http\Livewire\Dashboard;

use App\Domain\Enum\Situation;
use App\Domain\Policy;
use App\Models\Block;
use App\Models\Environment;
use App\Models\Schedule;
use App\Traits\AuthenticatedUser;
use App\Traits\Make;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Component;

class Show extends Component
{
    use AuthenticatedUser;

    public Block $block;
    public Environment $environment;

    private const EMPTY_DATE = '0000-00-00';

    public int $situation = 0;
    public string $date = self::EMPTY_DATE;

    public function mount(): void
    {
        Policy::dashboard_show_mount();
        $this->initializeProperties();
    }

    protected function initializeProperties(): void
    {
        $this->block = Make::block([Block::ID => 0]);
        $this->environment = Make::environment([Environment::ID => 0]);
    }

    public function render(): Factory|View|Application
    {
        return view('livewire.dashboard.show', [
            'blocks' => $this->blocks(),
            'environments' => $this->environments(),
            'schedules' => $this->schedules()
        ]);
    }

    protected function schedules(): Collection
    {
        $schedule = Schedule::byGroupEnvironmentBuilder($this->authGroup())->get();

        if ($this->block->id) {
            $schedule = $schedule->filter(function (Schedule $schedule) {
                return $schedule->environment->block->id === $this->block->id;
            });
        }

        if ($this->environment->id) {
            $schedule = $schedule->filter(function (Schedule $schedule) {
                return $schedule->environments_id === $this->environment->id;
            });
        }

        if ($this->situation) {
            $schedule = $schedule->filter(function (Schedule $schedule) {
                return $schedule->situations_id === $this->situation;
            });
        } else if ($this->date === self::EMPTY_DATE || $this->date === '') {
            $schedule = $schedule->filter(function (Schedule $schedule) {
                return $schedule->situations_id !== Situation::CANCELED()->getValue();
            });
        }

        if ($this->date !== self::EMPTY_DATE && $this->date !== '') {
            $schedule = $schedule->filter(function (Schedule $schedule) {
                return Carbon::parse($schedule->date)->eq(Carbon::parse($this->date));
            });
        } else {
            $schedule = $schedule->filter(function (Schedule $schedule) {
                return Carbon::parse($schedule->date)->isAfter(now()->format('Y-m-d'));
            });
        }

        return $schedule;
    }

    protected function blocks(): Collection
    {
        return Block::all();
    }

    protected function environments(): Collection
    {
        return Environment::where('blocks_id', '=', $this->block->id)->get() ?? new Collection();
    }

    protected function rules(): array
    {
        return [
            'block.id' => ['required'],
            'environment.id' => ['required']
        ];
    }
}
