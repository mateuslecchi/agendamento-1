<div>
    <div>
        <div class="mx-auto flex justify-center items-center py-4 px-4 space-y-1 space-x-5">
            <div class="w-1/4">
                <select id="block"
                        class="w-full bg-white border border-gray-300 rounded-md shadow-sm pl-3 pr-10 py-2 text-left cursor-default focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        wire:model="block.id" wire:change="$set('environment.id', 0)">
                    <option value="0" selected>{{ \App\Traits\Fmt::text('label.schedule.all.in') }}</option>
                    @foreach($blocks as $block)
                        <option value="{{ $block->id }}">{{ \App\Traits\Fmt::text($block->name) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="w-1/4" style="margin-top: -1px">
                <select id="environment"
                        class="w-full bg-white border border-gray-300 rounded-md shadow-sm pl-3 pr-10 py-2 text-left cursor-default focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        wire:model="environment.id" {{ $environments->count() === 0 ? 'disabled' : '' }}>
                    <option value="0" selected>{{ \App\Traits\Fmt::text('label.environments.all') }}</option>
                    @foreach($environments as $environment)
                        <option value="{{ $environment->id }}">{{ \App\Traits\Fmt::text($environment->name) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="w-1/4" style="margin-top: -1px">
                <select id="environment"
                        class="w-full bg-white border border-gray-300 rounded-md shadow-sm pl-3 pr-10 py-2 text-left cursor-default focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        wire:model="situation">
                    <option value="0" selected>{{ \App\Traits\Fmt::text('label.situation.all') }}</option>
                    @foreach(\App\Domain\Enum\Situation::values() as $situation)
                        <option
                            value="{{ $situation->getValue() }}">{{ \App\Traits\Fmt::text($situation->getName()) }}</option>
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
                <x-table.heading>{{ \App\Traits\Fmt::text('label.name') }}</x-table.heading>
                <x-table.heading>{{ \App\Traits\Fmt::text('label.environments') }}</x-table.heading>
                <x-table.heading>{{ \App\Traits\Fmt::text('label.block') }}</x-table.heading>
                <x-table.heading>{{ \App\Traits\Fmt::text('label.date') }}</x-table.heading>
                <x-table.heading>{{ \App\Traits\Fmt::text('label.time.schedule') }}</x-table.heading>
                {{--<x-table.heading>{{ \App\Traits\Fmt::text('label.situation') }}</x-table.heading>--}}
                <x-table.heading>{{ \App\Traits\Fmt::text('label.action') }}</x-table.heading>
            </x-slot>
            <!-- rows -->
            <x-slot name="body">
                @forelse($schedules as $schedule)
                    <x-table.row>
                        <x-table.cell>{{ $schedule->forGroup()->formattedName }}</x-table.cell>
                        <x-table.cell>{{ $schedule->environment->formattedName }}</x-table.cell>
                        <x-table.cell>{{ $schedule->environment->block->formattedName }}</x-table.cell>

                        <x-table.cell
                            class="text-center">{{ Carbon\Carbon::parse($schedule->date)->format('d/m/Y') }}
                        </x-table.cell>

                        <x-table.cell class="text-center">
                            {{
                                \App\Traits\Fmt::text('label.schedule.time', [
                                    'start' => \Illuminate\Support\Str::replaceLast(':00', '', $schedule->start_time),
                                    'end' => \Illuminate\Support\Str::replaceLast(':00', '', $schedule->end_time)
                                ])
                            }}
                        </x-table.cell>

                        {{--<x-table.cell class="text-center">
                            <div class="font-semibold">
                                @if($schedule->situation->id === \App\Domain\Enum\Situation::CONFIRMED()->getValue())
                                    <span class="text-green-500">{{ Str::ucfirst(__($schedule->situation?->name)) }}</span>
                                @elseif($schedule->situation->id === \App\Domain\Enum\Situation::PENDING()->getValue())
                                    <span class="text-yellow-500">{{ Str::ucfirst(__($schedule->situation?->name)) }}</span>
                                @endif
                            </div>
                        </x-table.cell>--}}

                        <x-table.cell class="text-center">
                            @switch($schedule->situation?->id)
                                @case(\App\Domain\Enum\Situation::CONFIRMED()->getValue())
                                <x-button.danger type="button"
                                                 wire:click="$emit('{{ \App\Http\Livewire\Dashboard\Cancel::ID }}', {{ $schedule?->id }})">
                                    <x-icon.cancel class="w-4 h-4 mr-1">
                                        {{ \App\Traits\Fmt::text('label.btn.cancel') }}
                                    </x-icon.cancel>
                                </x-button.danger>
                                @break
                                @case(\App\Domain\Enum\Situation::PENDING()->getValue())
                                <x-button type="button"
                                          wire:click="$emit('{{ \App\Http\Livewire\Dashboard\Analyze::ID }}', {{ $schedule?->id }})">
                                    <x-icon.clipboard class="w-4 h-4 mr-1">
                                        {{ \App\Traits\Fmt::text('label.btn.analyze') }}
                                    </x-icon.clipboard>
                                </x-button>
                                @break
                            @endswitch
                        </x-table.cell>
                    </x-table.row>
                @empty
                    <x-table.row>
                        <x-table.cell colspan="7">
                            <div class="flex justify-center items-center">
                                <div class="font-medium py-8 text-cool-gray-400 text-xl">
                                    {{ \App\Traits\Fmt::text('text.no-record-found') }}
                                </div>
                            </div>
                        </x-table.cell>
                    </x-table.row>
                @endforelse
            </x-slot>
        </x-table>
    </div>
</div>
