<div>
    <x-modal.confirmation wire:model.defer="show_modal">
        <x-slot name="title">{{ \App\Traits\Fmt::title('label.custom.delete', ['name' => $user->name]) }}</x-slot>
        <x-slot name="content">{{ \App\Traits\Fmt::text('text.custom.delete',['name' => $user->name]) }} </x-slot>
        <x-slot name="footer">
            <x-button type="button" wire:click="modalToggle">{{ \App\Traits\Fmt::text('label.btn.cancel')}} </x-button>
            <x-button.danger
                wire:click="$emit('{{ \App\Http\Livewire\Users\Delete::CONFIRM_DELETION }}', {{ $user->id }})">{{ \App\Traits\Fmt::text('label.btn.yes-confirmation')}} </x-button.danger>
        </x-slot>
    </x-modal.confirmation>
</div>
