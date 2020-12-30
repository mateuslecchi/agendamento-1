<form wire:submit.prevent="createGroup">
    <x-modal.dialog wire:model.defer="show_modal">
        <x-slot name="title">{{ \App\Traits\Fmt::title('label.custom.new', ['name' => 'label.group']) }}</x-slot>
        <!-- content -->
        <x-slot name="content">
            <x-auth-validation-errors class="mb-4" :errors="$errors"></x-auth-validation-errors>

            <div>
                <x-label for="name" value="{{ \App\Traits\Fmt::text('label.name') }}"></x-label>
                <x-input id="name" type="text" class="block mt-1 w-full" autofocus
                         wire:model.defer="group.name"></x-input>
            </div>

            <div class="mt-4">
                <x-label for="role" value="{{  \App\Traits\Fmt::text('label.role') }}"></x-label>
                <select id="role" class="block mt-1 w-full rounded"
                        wire:model.defer="group.group_roles_id">
                    <option value="0" selected>{{ \App\Traits\Fmt::text('label.select') }}</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->getValue() }}">{{ \App\Traits\Fmt::text($role->getName()) }}</option>
                    @endforeach
                </select>
            </div>
        </x-slot>
        <!-- footer -->
        <x-slot name="footer">
            <x-button.danger type="button" wire:click="modalToggle">
                <x-icon.cancel class="w-4 h-4 mr-1">
                    {{ \App\Traits\Fmt::text('label.btn.cancel') }}
                </x-icon.cancel>
            </x-button.danger>
            <x-button>
                <x-icon.save class="w-4 h-4 mr-1">
                    {{ \App\Traits\Fmt::text('label.btn.save') }}
                </x-icon.save>
            </x-button>
        </x-slot>

    </x-modal.dialog>
</form>
