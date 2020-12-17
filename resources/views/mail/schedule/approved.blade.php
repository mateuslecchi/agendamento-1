@component('mail::message')
    {!! __('text.custom.schedule.description', [
            'environment' => Str::ucfirst(__($schedule?->environment?->name)),
            'block' => Str::ucfirst(__($schedule?->environment?->block?->name)),
            'date' => Carbon\Carbon::parse($schedule?->date)->format('d/m/Y'),
            'start_time' => Str::replaceLast(':00', '', $schedule?->start_time),
            'end_time' => Str::replaceLast(':00', '', $schedule?->end_time),
            'user' => Str::ucfirst(__($schedule?->forGroup()->name)),
        ])
    !!}
# <center>{{ Str::ucfirst(__('text.custom.approved.by', ['name' => __($by)])) }}</center>
@endcomponent
