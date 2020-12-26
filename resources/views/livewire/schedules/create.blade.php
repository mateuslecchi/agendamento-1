<div>
    <form wire:submit.prevent="createNewSchedule">
        <x-modal.dialog wire:model.defer="show_modal">
            <x-slot name="title">
                {{ \App\Traits\Fmt::title('label.custom.new', ['name' => 'label.schedule']) }}
            </x-slot>

            <x-slot name="content">
                <div>
                    <div>
                        <x-auth-validation-errors class="mb-4" :errors="$errors"></x-auth-validation-errors>
                    </div>

                    <div class="mt-1 flex justify-between">
                        <div class="w-full">
                            <x-label for="local" value="{{ \App\Traits\Fmt::text('label.local') }}"></x-label>
                            <x-input id="local" type="text"
                                     class="block w-full mt-1 border border-gray-300 rounded-md shadow-sm pl-3 pr-10 py-2 text-left cursor-default focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                     disabled wire:model="environment"></x-input>
                        </div>

                        <div class="w-full ml-1 sm:ml-3">
                            <x-label for="local" value="{{ \App\Traits\Fmt::text('label.block') }}"></x-label>
                            <x-input id="local" type="text"
                                     class="block w-full mt-1 border border-gray-300 rounded-md shadow-sm pl-3 pr-10 py-2 text-left cursor-default focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                     disabled wire:model="block"></x-input>
                        </div>
                    </div>

                    <div class="mt-2.5">
                        <x-label for="date" value="{{ \App\Traits\Fmt::text('label.date') }}"></x-label>
                        <x-input id="date" type="date"
                                 class="block w-full mt-1 border border-gray-300 rounded-md shadow-sm text-left cursor-default focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                 disabled wire:model="date"></x-input>
                    </div>

                    <div class="mt-2.5 flex justify-between">
                        <div class="w-full">
                            <x-label for="start_time" value="{{ \App\Traits\Fmt::text('label.time.start') }}"></x-label>
                            <x-input id="start_time" type="time"
                                     class="block w-full mt-1 border border-gray-300 rounded-md shadow-sm text-left cursor-default focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                     wire:model="startTime"></x-input>
                        </div>

                        <div class="w-full ml-1 sm:ml-3">
                            <x-label for="end_time" value="{{ \App\Traits\Fmt::text('label.time.end') }}"></x-label>
                            <x-input id="end_time" type="time"
                                     class="block w-full mt-1 border border-gray-300 rounded-md shadow-sm text-left cursor-default focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                     wire:model="endTime"></x-input>
                        </div>
                    </div>

                    <div class="mt-2.5 flex justify-between">
                        <div class="w-full">
                            <x-label for="frequency" value="{{ \App\Traits\Fmt::text('label.frequency') }}"></x-label>
                            <select id="frequency"
                                    class="mt-1 w-full bg-white border border-gray-300 rounded-md shadow-sm pl-3 pr-10 py-2 text-left cursor-default focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                    wire:model="frequency">
                                @foreach($frequencies as $frequency)
                                    <option
                                        value="{{ $frequency->id() }}"> {{ \App\Traits\Fmt::text($frequency->label()) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="w-full ml-1 sm:ml-3">
                            <x-label for="frequency" value="{{ \App\Traits\Fmt::text('label.by') }}"></x-label>
                            <select id="repetition"
                                    class="mt-1 w-full bg-white border border-gray-300 rounded-md shadow-sm pl-3 pr-10 py-2 text-left cursor-default focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                    wire:model="repetitions" {{ $selectedFrequency['min'] === 0 ? 'disabled' : '' }}>
                                @foreach(range($selectedFrequency['min'], $selectedFrequency['max']) as $index)
                                    <option
                                        value="{{ $index }}"> {{ \App\Traits\Fmt::text('label.frequency.range',['repetition' => $index, 'placeholder' => \Illuminate\Support\Str::plural(\App\Traits\Fmt::text($selectedFrequency['placeholder']), $index)]) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </x-slot>

            <x-slot name="footer">
                <x-button.danger type="button"
                                 wire:click="modalToggle">{{ \App\Traits\Fmt::text('label.btn.cancel') }}</x-button.danger>
                <x-button>{{ \App\Traits\Fmt::text('label.btn.save') }}</x-button>
            </x-slot>
        </x-modal.dialog>
    </form>
</div>
