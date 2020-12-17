<form wire:submit.prevent="save">
    <x-modal.dialog wire:model.defer="show_modal">
        <x-slot name="title">{{ Str::title(__('label.custom.new', ['name' => __('label.group')])) }}</x-slot>
        <!-- content -->
        <x-slot name="content">
            <x-auth-validation-errors class="mb-4" :errors="$errors"/>

            <div>
                <x-label for="name" :value="Str::ucfirst(__('label.name'))"/>
                <x-input id="name" type="text" class="block mt-1 w-full" autofocus
                         wire:model.defer="group.name"/>
            </div>

            <div class="mt-4">
                <x-label for="role" :value="Str::ucfirst(__('label.role'))"/>
                <select id="role" class="block mt-1 w-full rounded"
                        wire:model.defer="group.group_roles_id">
                    <option value="0" selected>{{ __('label.select') }}</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->getValue() }}">{{ __($role->getName()) }}</option>
                    @endforeach
                </select>
            </div>
        </x-slot>
        <!-- footer -->
        <x-slot name="footer">
            <x-button.danger type="button" wire:click="modalToggle">{{ __('label.btn.cancel') }}</x-button.danger>
            <x-button>{{ __('label.btn.save') }}</x-button>
        </x-slot>

    </x-modal.dialog>
</form>
