<div  class="max-w-7xl mx-auto px-0 py-0">
    <div class="bg-white mx-auto flex justify-center items-center p-3 border rounded-md">
        <div class="sm:w-1/2">
            <select class="w-full bg-white border border-gray-300 rounded-md shadow-sm pl-3 pr-10 py-2 text-left cursor-default focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    wire:model="block.id" wire:change="resetEnvironment()">
                <option value="0">{{ \App\Traits\Fmt::text('label.schedule.all.in') }}</option>
                @foreach($blocks as $block)
                    <option value="{{ $block->id }}">{{ $block->formattedName }}</option>
                @endforeach
            </select>
        </div>

        <div class="sm:w-1/2 ml-3">
            <select
                class="w-full bg-white border border-gray-300 rounded-md shadow-sm pl-3 pr-10 py-2 text-left cursor-default focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                wire:model="environment.id" {{ $environments->count() ? '' : 'disabled' }}>
                <option value="0">{{ __('label.environments.all') }}</option>
                @foreach($environments as $environment)
                    <option value="{{ $environment->id }}">{{ $environment->formattedName }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="bg-white flex justify-between items-center mb-5 mt-5 p-3 border rounded-md">
        <x-button type="button" wire:click="goToPreviousMonth">
            <x-icon.arrow-left class="w-4 h-4 flex justify-center"></x-icon.arrow-left>
        </x-button>

        <span class="font-semibold md:text-2xl">
            {{ \App\Traits\Fmt::title($startsAt->translatedFormat('F')) }} - {{ $startsAt->year }}
        </span>

        <x-button type="button" wire:click="goToNextMonth">
            <x-icon.arrow-right class="w-4 h-4 flex justify-center"></x-icon.arrow-right>
        </x-button>
    </div>
</div>
