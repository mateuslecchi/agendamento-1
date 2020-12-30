<div>
    <x-modal.confirmation wire:model.defer="show_modal">
        <x-slot
            name="title">{{ \App\Traits\Fmt::title('label.custom.delete', ['name' => '']) }}</x-slot>
        <x-slot
            name="content">{!! \App\Traits\Fmt::text('text.custom.delete.environment',['name' => $environment->name]) !!}  </x-slot>
        <x-slot name="footer">
            <x-button type="button" wire:click="modalToggle">
                <x-icon.cancel class="w-4 h-4 mr-1">
                    {{ \App\Traits\Fmt::text('label.btn.cancel')}}
                </x-icon.cancel>
            </x-button>
            <x-button.danger wire:click="$emit('{{ \App\Http\Livewire\Environments\Delete::CONFIRM_DELETION }}')">
                <x-icon.check class="w-4 h-4 mr-1">
                    {{ \App\Traits\Fmt::text('label.btn.yes-confirmation')}}
                </x-icon.check>
            </x-button.danger>
        </x-slot>
    </x-modal.confirmation>
</div>
