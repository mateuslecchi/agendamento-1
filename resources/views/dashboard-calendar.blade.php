<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ Str::title(__('route.dashboard.calendar.label', [
                    'month' => now()->getTranslatedMonthName()
                ]))
            }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="bg-white max-w-7xl mx-auto sm:px-0 lg:px-0">
            <livewire:calendars.dashboard
                week-starts-at="1"
                :day-click-enabled="false"
                :event-click-enabled="false"
                :drag-and-drop-enabled="false"
            />
        </div>
    </div>
    <x-notification/>
</x-app-layout>
