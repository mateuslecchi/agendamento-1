<div>
    <x-modal.confirmation wire:model.defer="show_modal">
        <x-slot name="title">{{ Str::title(__('label.custom.approve', ['name' => __('label.schedule')])) }}</x-slot>
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
            {{ Str::ucfirst(__('text.custom.approve.confirmation')) }}
        </x-slot>
        <x-slot name="footer">
            <x-button.danger type="button" wire:click="$emit('cancel_schedule_confirmation', {{ $schedule?->id }})">{{ __('label.btn.not-approve')}} </x-button.danger>
            <x-button
                wire:click="$emit('confirm_schedule_confirmation', {{ $schedule?->id }})">{{ __('label.btn.yes-approve')}} </x-button>
        </x-slot>
    </x-modal.confirmation>
</div>
