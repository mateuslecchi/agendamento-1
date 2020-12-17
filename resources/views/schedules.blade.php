<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ Str::title(__('route.schedules.label')) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <livewire:schedules.create/>
                <livewire:schedules.show/>
                <livewire:schedules.cancel>
            </div>
        </div>
    </div>
    <x-notification/>
</x-app-layout>
