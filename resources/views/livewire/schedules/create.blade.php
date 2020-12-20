<div>
    <form wire:submit.prevent="save">
        <x-modal.dialog wire:model.defer="show_modal">
            <x-slot name="title">{{ Str::title(__('label.custom.new', ['name' => __('label.schedule')])) }}</x-slot>
            <!-- content -->
            <x-slot name="content">
                <x-auth-validation-errors class="mb-4" :errors="$errors"/>
                <div>
                    <x-label for="local" :value="Str::ucfirst(__('label.local'))"/>
                    <x-input id="local" type="text" class="block mt-1 w-full" disabled
                             value="{{ $environment_name }} - {{ $block_name }}"/>
                </div>

                @can(\App\Domain\Enum\Permission::SCHEDULE_SET_GROUP())
                    <div class="mt-1">
                        <x-label for="group" :value="Str::ucfirst(__('label.for'))"/>
                        <select id="group"
                                class="w-full bg-white border border-gray-300 rounded-md shadow-sm pl-3 pr-10 py-2 text-left cursor-default focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                wire:model="group.id">
                            @foreach($groups as $group)
                                <option value="{{ $group->id }}">{{ Str::ucfirst(__($group->name)) }}</option>
                            @endforeach
                        </select>
                    </div>
                @endcan

                <div class="mt-1">
                    <x-label for="date" :value="Str::ucfirst(__('label.date'))"/>
                    <x-input id="date" type="date" class="block mt-1 w-full" wire:model="schedule.date"/>
                </div>

                <div class="mt-1">
                    <x-label for="start_time" :value="Str::ucfirst(__('label.time.start'))"/>
                    <x-input id="start_time" type="time" class="block mt-1 w-full" wire:model="schedule.start_time"/>
                </div>

                <div class="mt-1">
                    <x-label for="end_time" :value="Str::ucfirst(__('label.time.end'))"/>
                    <x-input id="end_time" type="time" class="block mt-1 w-full" wire:model="schedule.end_time"/>
                </div>

                <div class="mt-1">
                    <x-label for="end_time" :value="Str::ucfirst(__('Frequencia'))"/>
                    <div class="flex justify-between">
                        <div class="w-1/2 mt-0.5">
                            <select id="environment"
                                    class="w-full bg-white border border-gray-300 rounded-md shadow-sm pl-3 pr-10 py-2 text-left cursor-default focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                    wire:model="selectedFrequency" wire:change="$set('repetitions', 2)">
                                @foreach($frequencies as $key => $frequency)
                                    <option value="{{ $key }}">{{ Str::ucfirst(__($frequency)) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="w-1/2 ml-1 mt-0.5">
                            <select id="environment"
                                    class="w-full bg-white border border-gray-300 rounded-md shadow-sm pl-3 pr-10 py-2 text-left cursor-default focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                    wire:model="repetitions" {{ $selectedFrequency === 0 ? 'disabled' : '' }}>
                                @foreach(range($optionsFrequency[$selectedFrequency]['min'],$optionsFrequency[$selectedFrequency]['max']) as $index)
                                    @if($selectedFrequency)
                                        <option
                                            value="{{ $index }}">{{ __('label.by') }} {{ $index }} {{ Str::plural(__($optionsFrequency[$selectedFrequency]['text'])) }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                    </div>
                </div>
            </x-slot>
            <!-- footer -->
            <x-slot name="footer">
                <x-button.danger type="button" wire:click="modalToggle">{{ __('label.btn.cancel') }}</x-button.danger>
                <x-button>{{ __('label.btn.save') }}</x-button>
            </x-slot>

        </x-modal.dialog>
    </form>
</div>

