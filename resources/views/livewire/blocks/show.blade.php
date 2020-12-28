<div>
    <div class="py-4 px-4">
        <div class="flex justify-end">
            <x-button type="button" wire:click="$emit('show_modal_block')">
                <x-icon.plus class="w-4 h-4 mr-1">{{ __('label.btn.new') }}</x-icon.plus>
            </x-button>
        </div>
    </div>
    <div wire:poll.2s>
        <x-table>
            <x-slot name="head">
                <x-table.heading>{{ \App\Traits\Fmt::text('label.name') }}</x-table.heading>
                <x-table.heading>{{ \App\Traits\Fmt::text('label.count-environment') }}</x-table.heading>
                <x-table.heading>{{-- empty --}}</x-table.heading>
                <x-table.heading>{{-- empty --}}</x-table.heading>
            </x-slot>
            <!-- rows -->
            <x-slot name="body">
                @forelse($blocks as $block)
                    <x-table.row>
                        <x-table.cell>{{ $block->formattedName }}</x-table.cell>
                        <x-table.cell>{{ \App\Traits\Fmt::text('label.custom.count-environment', ['count' => $block->environments()->count() , 'plural' =>  \App\Traits\Fmt::text('label.environments')], false) }}</x-table.cell>
                        <x-table.cell>
                            <x-button type="button"
                                      wire:click="$emit('show_block_editing_modal', {{ $block->id }})">
                                <x-icon.edit
                                    class="w-4 h-4 mr-1">{{ \App\Traits\Fmt::text('label.btn.edit') }}</x-icon.edit>
                            </x-button>
                        </x-table.cell>
                        <x-table.cell>
                            <x-button.danger type="button"
                                             wire:click="$emit('show_block_exclusion_modal', {{ $block->id }})">
                                <x-icon.trash
                                    class="w-4 h-4 mr-1">{{ \App\Traits\Fmt::text('label.btn.delete') }}</x-icon.trash>
                            </x-button.danger>
                        </x-table.cell>
                    </x-table.row>
                @empty
                    <x-table.row>
                        <x-table.cell colspan="4">
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
