<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ Str::title(__('route.dashboard.label')) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-5 flex justify-end">
                <a href="{{ url(__('route.dashboard.calendar.uri')) }}">
                    <x-button type="button">{{ Str::ucfirst(__('label.btn.calendar')) }}</x-button>
                </a>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <livewire:dashboard.show/>
                <livewire:schedules.cancel/>
                <livewire:schedules.confirm/>
            </div>
        </div>
    </div>
    <x-notification/>
</x-app-layout>
