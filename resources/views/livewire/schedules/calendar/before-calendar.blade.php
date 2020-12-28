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
            <div class="w-7 h-5 flex justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M15.707 15.707a1 1 0 01-1.414 0l-5-5a1 1 0 010-1.414l5-5a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 010 1.414zm-6 0a1 1 0 01-1.414 0l-5-5a1 1 0 010-1.414l5-5a1 1 0 011.414 1.414L5.414 10l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
            </div>
        </x-button>

        <span class="font-semibold md:text-2xl">
            {{ \App\Traits\Fmt::title($startsAt->translatedFormat('F')) }} - {{ $startsAt->year }}
        </span>

        <x-button type="button" wire:click="goToNextMonth">
            <div class="w-7 h-5 flex justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10.293 15.707a1 1 0 010-1.414L14.586 10l-4.293-4.293a1 1 0 111.414-1.414l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    <path fill-rule="evenodd" d="M4.293 15.707a1 1 0 010-1.414L8.586 10 4.293 5.707a1 1 0 011.414-1.414l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
            </div>
        </x-button>
    </div>
</div>
