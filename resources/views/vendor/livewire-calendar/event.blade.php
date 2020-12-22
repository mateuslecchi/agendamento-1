<div
    @if($eventClickEnabled)
        wire:click.stop="onEventClick('{{ $event['id']  }}')"
    @endif
    class="bg-white rounded-lg border py-2 px-3 shadow-md cursor-pointer">

    <p class="text-sm font-medium">
        {{ $event['title'] }}
    </p>
    <p class="mt-2 text-xs text-gray-500">
        {{ $event['for']}}
    </p>
    <p class="mt-2 text-sm font-bold">
        {{ $event['description'] ?? 'No description' }}
    </p>
</div>
