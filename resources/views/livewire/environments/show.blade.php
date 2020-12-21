<div>
    <div class="py-4 px-4">
        <div class="flex justify-end">
            <x-button type="button" wire:click="$emit('show_modal_environment')">{{ __('label.btn.new') }}</x-button>
        </div>
    </div>
    <div wire:poll.2s>
        <x-table>
            <x-slot name="head">
                <x-table.heading>{{ Str::ucfirst(__('label.name')) }}</x-table.heading>
                <x-table.heading>{{ Str::ucfirst(__('label.block')) }}</x-table.heading>
                <x-table.heading>{{ Str::ucfirst(__('label.group')) }}</x-table.heading>
                <x-table.heading>{{-- empty --}}</x-table.heading>
                <x-table.heading>{{-- empty --}}</x-table.heading>
            </x-slot>
            <!-- rows -->
            <x-slot name="body">
                @forelse($environments as $environment)
                    <x-table.row>
                        <x-table.cell>{{ Str::ucfirst(__($environment->name)) }}</x-table.cell>
                        <x-table.cell>{{ Str::ucfirst(__($environment->block?->name)) }}</x-table.cell>
                        <x-table.cell>{{ Str::ucfirst(__($environment->group?->name)) }}</x-table.cell>
                        <x-table.cell>
                            <x-button type="button"
                                      wire:click="$emit('show_environment_editing_modal', {{ $environment->id }})">{{ __('label.btn.edit') }}</x-button>
                        </x-table.cell>
                        <x-table.cell>
                            <x-button type="button"
                                      wire:click="$emit('show_environment_exclusion_modal', {{ $environment->id }})">{{ __('label.btn.delete') }}</x-button>
                        </x-table.cell>
                    </x-table.row>
                @empty
                    <x-table.row>
                        <x-table.cell colspan="5">
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
