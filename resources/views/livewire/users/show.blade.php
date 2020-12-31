<div>
    <div class="py-4 px-4">
        <div class="flex justify-end">
            <x-button type="button" wire:click="$emit('{{ App\Http\Livewire\Users\Create::ID }}')">
                <x-icon.plus class="w-4 h-4 mr-1">
                    {{ App\Traits\Fmt::text('label.btn.new') }}
                </x-icon.plus>
            </x-button>
        </div>
    </div>
    <div wire:poll.1s>
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
                        <x-table.cell class="text-center">{{ $user->formattedRole }}</x-table.cell>
                        <x-table.cell>
                            <div class="flex justify-center">
                                <x-button type="button"
                                          wire:click="$emit('{{ \App\Http\Livewire\Users\Edit::ID }}', {{ $user->id }})">
                                    <x-icon.edit class="w-4 h-4 mr-1">
                                        {{ App\Traits\Fmt::text('label.btn.edit') }}
                                    </x-icon.edit>
                                </x-button>
                            </div>
                        </x-table.cell>
                        <x-table.cell>
                            <div class="flex justify-center">
                                <x-button.danger type="button"
                                                 wire:click="$emit('{{ \App\Http\Livewire\Users\Delete::ID }}', {{ $user->id }})">
                                    <x-icon.trash class="w-4 h-4 mr-1">
                                        {{ App\Traits\Fmt::text('label.btn.delete') }}
                                    </x-icon.trash>
                                </x-button.danger>
                            </div>
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
