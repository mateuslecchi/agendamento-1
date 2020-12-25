<?php

namespace App\Http\Livewire\Environments;

use App\Domain\Policy;
use App\Models\Block;
use App\Models\Environment;
use App\Models\Group;
use App\Traits\AuthenticatedUser;
use App\Traits\AuthorizesRoleOrPermission;
use App\Traits\Fmt;
use App\Traits\Make;
use App\Traits\ModalCtrl;
use App\Traits\NotifyBrowser;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Create extends Component
{
    public const ID = 'e10c36bd-b78a-456f-ad1d-0aeeee70134c';

    use ModalCtrl;
    use NotifyBrowser;
    use AuthenticatedUser;
    use AuthorizesRoleOrPermission;

    public Environment $environment;

    protected $listeners = [
        self::ID => 'modalToggle'
    ];

    public function mount(): void
    {
        Policy::environments_create_mount();
        $this->initializeProperties();
    }

    protected function initializeProperties(): void
    {
        $this->environment = Make::environment([
            Environment::AUTOMATIC_APPROVAL => false
        ]);
    }

    public function render(): Factory|View|Application
    {
        return view('livewire.environments.create', [
            'blocks' => $this->validBlocksForSelection(),
            'groups' => $this->validGroupsForSelection()
        ]);
    }

    protected function validBlocksForSelection(): Collection
    {
        return Block::all();
    }

    protected function validGroupsForSelection(): Collection
    {
        return Group::all();
    }

    protected function createNewEnvironment(): void
    {
        $this->validate();
        $this->sendBrowserNotification(
            savedEnvironment: $this->saveEnvironment()
        );
        $this->finally();
    }

    protected function sendBrowserNotification(bool $savedEnvironment): void
    {
        $this->notifySuccessOrError(
            status: $savedEnvironment,
            success: Fmt::title('text.save.success'),
            error: Fmt::title('text.save.error')
        );
    }

    protected function saveEnvironment(): bool
    {
        if (is_null($this->environment->groups_id)) {
            $this->environment->groups_id = $this->authGroup()->id;
        }
        return $this->environment->save();
    }

    protected function finally(): void
    {
        $this->modalToggle();
        $this->initializeProperties();
    }

    protected function rules(): array
    {
        return [
            'environment.name' => ['required', 'min:2', 'max:255'],
            'environment.blocks_id' => [
                'required',
                Rule::in($this->validBlocksForSelection()->pluck('id'))
            ],
            'environment.groups_id' => [
                'sometimes',
                Rule::in($this->validGroupsForSelection()->pluck('id'))
            ],
            'environment.automatic_approval' => ['sometimes', 'boolean']
        ];
    }

    protected function messages(): array
    {
        return [
            'environment.blocks_id.required' => Fmt::text('validation.required', [
                'attribute' => 'label.block'
            ]),
            'environment.blocks_id.in' => Fmt::text('validation.in', [
                'attribute' => 'label.block'
            ]),
            'environment.groups_id.required' => Fmt::text('validation.required', [
                'attribute' => 'label.group'
            ]),
            'environment.groups_id.in' => Fmt::text('validation.in', [
                'attribute' => 'label.group'
            ])
        ];
    }
}
