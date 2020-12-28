<div>
    <form wire:submit.prevent="save">
        <x-modal.dialog wire:model.defer="show_modal">
            <x-slot name="title">{{ \App\Traits\Fmt::title('label.custom.new', ['name' => 'label.block']) }}</x-slot>
            <!-- content -->
            <x-slot name="content">
                <x-auth-validation-errors class="mb-4" :errors="$errors"></x-auth-validation-errors>
                <div>
                    <x-label for="name" value="{{ \App\Traits\Fmt::text('label.name') }}"></x-label>
                    <x-input id="name" type="text" autofocus class="block mt-1 w-full"
                             wire:model.defer="block.name"></x-input>
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
</div>
