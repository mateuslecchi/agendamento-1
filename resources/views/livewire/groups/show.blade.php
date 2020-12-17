<div>
    <div class="py-4 px-4">
        <div class="flex justify-end">
            <x-button type="button" wire:click="$emit('show_modal_group')">{{ __('label.btn.new') }}</x-button>
        </div>
    </div>

    <x-table>
        <x-slot name="head">
            <x-table.heading>{{ Str::ucfirst(__('label.name')) }}</x-table.heading>
            <x-table.heading>{{ Str::ucfirst(__('label.role')) }}</x-table.heading>
            <x-table.heading>{{-- empty --}}</x-table.heading>
            <x-table.heading>{{-- empty --}}</x-table.heading>
        </x-slot>
        <!-- rows -->
        <x-slot name="body">
            @forelse($groups as $group)
                <x-table.row>
                    <x-table.cell>{{ Str::ucfirst(__($group->name)) }}</x-table.cell>
                    <x-table.cell>{{ Str::ucfirst(__($group->groupRole->name)) }}</x-table.cell>
                    <x-table.cell>
                        <x-button type="button" wire:click="$emit('show_group_editing_modal', {{ $group->id }})">
                            {{ __('label.btn.edit') }}
                        </x-button>
                    </x-table.cell>
                    <x-table.cell>
                        <x-button type="button" wire:click="$emit('show_group_exclusion_modal', {{ $group->id }})">
                            {{ __('label.btn.delete') }}
                        </x-button>
                    </x-table.cell>
                </x-table.row>
            @empty
                <x-table.row>
                    <x-table.cell colspan="4">
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
