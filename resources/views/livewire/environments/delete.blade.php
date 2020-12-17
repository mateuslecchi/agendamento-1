<div>
    <x-modal.confirmation wire:model.defer="show_modal">
        <x-slot name="title">{{ Str::title(__('label.custom.delete', ['name' => $environment->name])) }}</x-slot>
        <x-slot name="content">{{ Str::ucfirst(__('text.custom.delete',['name' => $environment->name])) }} </x-slot>
        <x-slot name="footer">
            <x-button type="button" wire:click="modalToggle">{{ __('label.btn.cancel')}} </x-button>
            <x-button.danger
                wire:click="$emit('delete_environment_confirmation', {{ $environment->id }})">{{ __('label.btn.yes-confirmation')}} </x-button.danger>
        </x-slot>
    </x-modal.confirmation>
</div>
