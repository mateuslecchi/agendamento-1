<div>
    <form wire:submit.prevent="save">
        <x-modal.dialog wire:model.defer="show_modal">
            <x-slot name="title">{{ Str::title(__('label.custom.edit', ['name' => __('label.block')])) }}</x-slot>
            <!-- content -->
            <x-slot name="content">
                <x-auth-validation-errors class="mb-4" :errors="$errors"/>
                <div>
                    <x-label for="name" :value="Str::ucfirst(__('label.name'))"/>
                    <x-input id="name" type="text" autofocus class="block mt-1 w-full"
                             wire:model.defer="block.name"/>
                </div>
            </x-slot>
            <!-- footer -->
            <x-slot name="footer">
                <x-button.danger type="button" wire:click="modalToggle">{{ __('label.btn.cancel') }}</x-button.danger>
                <x-button>{{ __('label.btn.save') }}</x-button>
            </x-slot>
        </x-modal.dialog>
    </form>
</div>
