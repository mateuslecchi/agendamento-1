<div
    @if($eventClickEnabled)
        wire:click.stop="onEventClick('{{ $event['id']  }}')"
    @endif
    class="bg-white rounded-lg border py-2 px-3 shadow-md cursor-pointer">

    <p class="text-sm font-medium overflow-clip">
        {!! \App\Traits\Fmt::text('calendar.event.block', ['label' => 'label.block','name' => $event['block']]) !!}
    </p>

    <p class="text-sm font-medium overflow-clip">
        {!! \App\Traits\Fmt::text('calendar.event.environment', ['label' => 'label.local','name' => $event['environment']]) !!}
    </p>

    <p class="text-sm font-medium overflow-clip">
        {!! \App\Traits\Fmt::text('calendar.event.schedule', ['label' => 'label.time.schedule','start' => $event['start'], 'end' => $event['end']]) !!}
    </p>

    <p class="text-sm font-medium truncate">
        {!! \App\Traits\Fmt::text('calendar.event.for', ['label' => 'label.for','name' => $event['for']]) !!}
    </p>

    <p class="text-sm overflow-clip">
        @if($event['approved'])
            {!! \App\Traits\Fmt::text('calendar.event.situation.approved', ['label' => 'label.situation','name' => \App\Domain\Enum\Situation::CONFIRMED()->getName()]) !!}
        @else
            {!! \App\Traits\Fmt::text('calendar.event.situation.pending', ['label' => 'label.situation','name' => \App\Domain\Enum\Situation::PENDING()->getName()]) !!}
        @endif
    </p>
</div>
