<div>
    <div>
        <div class="mx-auto flex justify-center items-center py-4 px-4 space-y-1 space-x-5">
            <div class="w-1/4">
                <select id="block"
                        class="w-full bg-white border border-gray-300 rounded-md shadow-sm pl-3 pr-10 py-2 text-left cursor-default focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        wire:model="block.id" wire:change="$set('environment.id', 0)">
                    <option value="0" selected>{{ __('todos os agendamentos') }}</option>
                    @foreach($blocks as $block)
                        <option value="{{ $block->id }}">{{ Str::ucfirst(__($block->name)) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="w-1/4" style="margin-top: -1px">
                <select id="environment"
                        class="w-full bg-white border border-gray-300 rounded-md shadow-sm pl-3 pr-10 py-2 text-left cursor-default focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        wire:model="environment.id">
                    <option value="0" selected>{{ __('label.environment.all') }}</option>
                    @foreach($environments as $environment)
                        <option value="{{ $environment->id }}">{{ Str::ucfirst(__($environment->name)) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="w-1/4" style="margin-top: -1px">
                <select id="environment"
                        class="w-full bg-white border border-gray-300 rounded-md shadow-sm pl-3 pr-10 py-2 text-left cursor-default focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        wire:model="situation">
                    <option value="0" selected>{{ __('em qualquer situação') }}</option>
                    @foreach(\App\Domain\Enum\Situation::values() as $situation)
                        <option
                            value="{{ $situation->getValue() }}">{{ Str::ucfirst(__($situation->getName())) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="w-1/4" style="margin-top: -1px">
                <input type="date" wire:model="date"
                       class="w-full bg-white border border-gray-300 rounded-md shadow-sm pl-3 pr-3 py-2 text-left cursor-default focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
        </div>
    </div>
    <!-- visualiza -->
    <div wire:poll.5s>
        <x-table>
            <x-slot name="head">
                <x-table.heading>{{ Str::ucfirst(__('label.name')) }}</x-table.heading>
                <x-table.heading>{{ Str::ucfirst(__('label.environment')) }}</x-table.heading>
                <x-table.heading>{{ Str::ucfirst(__('label.block')) }}</x-table.heading>
                <x-table.heading>{{ Str::ucfirst(__('label.date')) }}</x-table.heading>
                <x-table.heading>{{ Str::ucfirst(__('label.time.schedule')) }}</x-table.heading>
                <x-table.heading>{{ Str::ucfirst(__('label.situation')) }}</x-table.heading>
                <x-table.heading>{{ Str::ucfirst(__('label.action')) }}</x-table.heading>
            </x-slot>
            <!-- rows -->
            <x-slot name="body">
                @forelse($schedules as $schedule)
                    <x-table.row>
                        <x-table.cell>{{ Str::ucfirst(__($schedule->forGroup()->name)) }}</x-table.cell>
                        <x-table.cell>{{ Str::ucfirst(__($schedule->environment?->name)) }}</x-table.cell>
                        <x-table.cell>{{ Str::ucfirst(__($schedule->environment?->block?->name)) }}</x-table.cell>

                        <x-table.cell
                            class="text-center">{{ Carbon\Carbon::parse($schedule->date)->format('d/m/Y') }}
                        </x-table.cell>

                        <x-table.cell class="text-center">
                            {{ Str::replaceLast(':00', '', $schedule->start_time) }}
                            às {{ Str::replaceLast(':00', '', $schedule->end_time) }}
                        </x-table.cell>

                        <x-table.cell class="text-center">
                            {{ Str::ucfirst(__($schedule->situation?->name)) }}
                        </x-table.cell>

                        <x-table.cell class="text-center">
                            @switch($schedule->situation?->id)
                                @case(\App\Domain\Enum\Situation::CONFIRMED()->getValue())
                                <x-button.danger type="button"
                                                 wire:click="$emit('show_schedule_cancel_modal', {{ $schedule?->id }})">{{ __('label.btn.cancel') }}</x-button.danger>
                                @break
                                @case(\App\Domain\Enum\Situation::PENDING()->getValue())
                                <x-button type="button"
                                          wire:click="$emit('show_schedule_confirm_modal', {{ $schedule?->id }})">{{ __('label.btn.approve') }}</x-button>
                                @break
                                @default
                                <x-button.danger type="button" disabled>{{ __('label.btn.cancel') }}</x-button.danger>
                            @endswitch
                        </x-table.cell>
                    </x-table.row>
                @empty
                    <x-table.row>
                        <x-table.cell colspan="7">
                            <div class="flex justify-center items-center">
                                <div class="font-medium py-8 text-cool-gray-400 text-xl">
                                    {{ __('text.no-record-found') }}
                                </div>
                            </div>
                        </x-table.cell>
                    </x-table.row>
                @endforelse
            </x-slot>
        </x-table>
    </div>
</div>
