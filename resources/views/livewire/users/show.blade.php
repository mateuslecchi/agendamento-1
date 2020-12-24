<div>
    <div class="py-4 px-4">
        <div class="flex justify-end">
            <x-button type="button" wire:click="$emit('show_modal_user')">{{ App\Traits\Fmt::text('label.btn.new') }}</x-button>
        </div>
    </div>
    <div wire:poll>
        <x-table>
            <x-slot name="head">
                <x-table.heading>{{ App\Traits\Fmt::text('label.name') }}</x-table.heading>
                <x-table.heading>{{ App\Traits\Fmt::text('label.group') }}</x-table.heading>
                <x-table.heading>{{ App\Traits\Fmt::text('label.role') }}</x-table.heading>
                <x-table.heading>{{-- empty --}}</x-table.heading>
                <x-table.heading>{{-- empty --}}</x-table.heading>
            </x-slot>
            <!-- rows -->
            <x-slot name="body">
                @forelse($users as $user)
                    <x-table.row>
                        <x-table.cell>{{ $user->formattedName }}</x-table.cell>
                        <x-table.cell>{{ $user->formattedGroup }}</x-table.cell>
                        <x-table.cell>{{ $user->formattedRole }}</x-table.cell>
                        <x-table.cell>
                            <x-button type="button"
                                      wire:click="$emit('show_user_editing_modal', {{ $user->id }})">{{ App\Traits\Fmt::text('label.btn.edit') }}</x-button>
                        </x-table.cell>
                        <x-table.cell>
                            <x-button type="button"
                                      wire:click="$emit('show_user_exclusion_modal', {{ $user->id }})">{{ App\Traits\Fmt::text('label.btn.delete') }}</x-button>
                        </x-table.cell>
                    </x-table.row>
                @empty
                    <x-table.row>
                        <x-table.cell colspan="5">
                            <div class="flex justify-center items-center">
                                <div class="font-medium py-8 text-cool-gray-400 text-xl">
                                    {{ App\Traits\Fmt::text('text.no-record-found') }}
                                </div>
                            </div>
                        </x-table.cell>
                    </x-table.row>
                @endforelse
            </x-slot>
        </x-table>
    </div>
</div>
