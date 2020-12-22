@component('mail::message')
    {!! __('text.custom.schedule.description', [
            'environment' => $environment,
            'block' => $block,
            'date' => $date,
            'start_time' => $start_time,
            'end_time' => $end_time,
            'for' => $for,
            'by' => $by
        ])
    !!}
# <center>{{ Str::ucfirst(__('text.custom.cancel.by', ['name' => $sendBy])) }}</center>
@endcomponent
