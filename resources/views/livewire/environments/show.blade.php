<div>
    <div class="py-4 px-4">
        <div class="flex justify-end">
            <x-button type="button"
                      wire:click="$emit('{{ \App\Http\Livewire\Environments\Create::ID }}')">{{ \App\Traits\Fmt::text('label.btn.new') }}</x-button>
        </div>
    </div>
    <div wire:poll.2s>
        <x-table>
            <x-slot name="head">
                <x-table.heading>{{ \App\Traits\Fmt::text('label.name') }}</x-table.heading>
                <x-table.heading>{{ \App\Traits\Fmt::text('label.block') }}</x-table.heading>
                <x-table.heading>{{ \App\Traits\Fmt::text('label.group') }}</x-table.heading>
                <x-table.heading>{{ \App\Traits\Fmt::text('label.approval') }}</x-table.heading>
                <x-table.heading>{{-- empty --}}</x-table.heading>
                <x-table.heading>{{-- empty --}}</x-table.heading>
            </x-slot>
            <!-- rows -->
            <x-slot name="body">
                @forelse($environments as $environment)
                    <x-table.row>
                        <x-table.cell>{{ $environment->formattedName }}</x-table.cell>
                        <x-table.cell>{{ $environment->block?->formattedName }}</x-table.cell>
                        <x-table.cell>{{ $environment->group?->formattedName }}</x-table.cell>
                        @if($environment->automatic_approval)
                            <x-table.cell>{{ \App\Traits\Fmt::text('label.approval.automatic') }}</x-table.cell>
                        @else
                            <x-table.cell>{{ \App\Traits\Fmt::text('label.approval.manual') }}</x-table.cell>
                        @endif
                        <x-table.cell>
                            <x-button type="button"
                                      wire:click="$emit('{{ \App\Http\Livewire\Environments\Edit::ID }}', {{ $environment->id }})">{{ \App\Traits\Fmt::text('label.btn.edit') }}</x-button>
                        </x-table.cell>
                        <x-table.cell>
                            <x-button type="button"
                                      wire:click="$emit('{{ \App\Http\Livewire\Environments\Delete::ID }}', {{ $environment->id }})">{{ \App\Traits\Fmt::text('label.btn.delete') }}</x-button>
                        </x-table.cell>
                    </x-table.row>
                @empty
                    <x-table.row>
                        <x-table.cell colspan="6">
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
