<?php

namespace App\Http\Livewire\Environments;

use App\Domain\Enum\AdminGroup;
use App\Domain\Enum\GroupRoles;
use App\Domain\Policy;
use App\Models\Block;
use App\Models\Environment;
use App\Models\Group;
use App\Traits\AuthenticatedUser;
use App\Traits\AuthorizesRoleOrPermission;
use App\Traits\ModalCtrl;
use App\Traits\NotifyBrowser;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Create extends Component
{
    use ModalCtrl;
    use NotifyBrowser;
    use AuthenticatedUser;
    use AuthorizesRoleOrPermission;

    public Environment $environment;

    protected $listeners = [
        'show_modal_environment' => 'modalToggle'
    ];

    public function render()
    {
        return view('livewire.environments.create', [
            'blocks' => Block::all(),
            'groups' => Group::all()
        ]);
    }

    public function mount()
    {
        Policy::environments_create_mount();
        $this->setEmptyEnvironment();
    }

    protected function setEmptyEnvironment()
    {
        $this->environment = Environment::make([]);
    }

    public function save()
    {
        Policy::environments_create_save();

        if (!$this->modalIsOpen()) {
            return;
        }

        $this->validate();

        $userGroup = $this->authGroup();
        if (is_null($userGroup)) {
            $this->notifyError('text.save.error');
            return;
        }


        $this->environment->groups_id = match ($userGroup->role?->id) {
            GroupRoles::ADMIN()->getValue() => $this->environment->groups_id ? $this->environment->groups_id : AdminGroup::ID(),
        default => $userGroup->id
        };

            $status = $this->environment->save();

            $this->notifySuccessOrError(
                status: $status,
                success: __('text.save.success'),
                error: __('text.save.error')
            );

        $this->finally();
    }

    protected function finally()
    {
        $this->updateView();
        $this->modalToggle();
        $this->setEmptyEnvironment();
    }

    protected function updateView()
    {
        $this->emit('update_environment_display_content');
    }

    protected function rules()
    {
        return [
            'environment.name' => ['required', 'min:2', 'max:255'],
            'environment.blocks_id' => ['required', Rule::in(Block::all()->pluck('id')->all())],
            'environment.groups_id' => ['sometimes', Rule::in(Group::all()->pluck('id')->all())]
        ];
    }

    protected function messages()
    {
        return [
            'environment.blocks_id.required' => __('validation.required', ['attribute' => __('label.block')]),
            'environment.blocks_id.in' => __('validation.in', ['attribute' => __('label.block')]),
            'environment.groups_id.required' => __('validation.required', ['attribute' => __('label.group')]),
            'environment.groups_id.in' => __('validation.in', ['attribute' => __('label.group')])
        ];
    }
}
