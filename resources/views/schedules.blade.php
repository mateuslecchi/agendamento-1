<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ \App\Traits\Fmt::title('route.schedules.label') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <livewire:schedules.calendar
            week-starts-at="1"
            :day-click-enabled="true"
            :event-click-enabled="true"
            :drag-and-drop-enabled="true"
            calendar-view="livewire/schedules/calendar/calendar"
            day-of-week-view="livewire/schedules/calendar/day-of-week"
            before-calendar-view="livewire/schedules/calendar/before-calendar"
            event-view="livewire/schedules/calendar/event"
            day-view="livewire/schedules/calendar/day"
            pollMillis="2000"
        />
        <livewire:schedules.create/>
    </div>
    <x-notification/>
</x-app-layout>
