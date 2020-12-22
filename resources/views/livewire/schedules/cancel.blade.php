<div>
    <x-modal.confirmation wire:model.defer="show_modal">
        <x-slot name="title">{{ Str::title(__('label.custom.cancel', ['name' => __('label.schedule')])) }}</x-slot>
        <x-slot name="content">
            {!!
                Str::ucfirst(__('text.custom.schedule.description',[
                    'environment' => Str::ucfirst(__($schedule?->environment?->name)),
                    'block' => Str::ucfirst(__($schedule?->environment?->block?->name)),
                    'date' => Carbon\Carbon::parse($schedule?->date)->format('d/m/Y'),
                    'start_time' => Str::replaceLast(':00', '', $schedule?->start_time),
                    'end_time' => Str::replaceLast(':00', '', $schedule?->end_time),
                    'for' => Str::ucfirst(__($schedule->forGroup()?->name)),
                    'by' => Str::ucfirst(__($schedule->byGroup()?->name)),
                ]))
            !!}
            <br>
            {{ Str::ucfirst(__('text.custom.cancel.confirmation')) }}
        </x-slot>
        <x-slot name="footer">
            <x-button type="button" wire:click="modalToggle">{{ __('label.btn.cancel')}} </x-button>
            <x-button.danger
                wire:click="$emit('cancel_schedule_confirmation', {{ $schedule?->id }})">{{ __('label.btn.yes-confirmation')}} </x-button.danger>
        </x-slot>
    </x-modal.confirmation>
</div>
