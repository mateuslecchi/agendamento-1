<div>
    <form wire:submit.prevent="createNewEnvironment">
        <x-modal.dialog wire:model.defer="show_modal">
            <x-slot
                name="title">{{ \App\Traits\Fmt::title('label.custom.new', ['name' => 'label.environment']) }}</x-slot>
            <!-- content -->
            <x-slot name="content">
                <x-auth-validation-errors class="mb-4" :errors="$errors"></x-auth-validation-errors>

                <div>
                    <x-label for="name" :value="\App\Traits\Fmt::text('label.name')"></x-label>
                    <x-input id="name" type="text" class="block mt-1 w-full" autofocus
                             wire:model.defer="environment.name"></x-input>
                </div>

                <div class="mt-4">
                    <x-label for="block" :value="\App\Traits\Fmt::text('label.block')"></x-label>
                    <select id="block" class="block mt-1 w-full rounded"
                            wire:model.defer="environment.blocks_id">
                        <option value="0" selected>{{ \App\Traits\Fmt::text('label.select') }}</option>
                        @foreach($blocks as $block)
                            <option value="{{ $block->id }}">{{ $block->formattedName }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mt-4">
                    <x-label for="approval" :value="\App\Traits\Fmt::text('label.approval')"></x-label>
                    <select id="approval" class="block mt-1 w-full rounded"
                            wire:model="environment.automatic_approval">
                        <option value="0">{{ \App\Traits\Fmt::text('label.approval.manual') }}</option>
                        <option value="1">{{ \App\Traits\Fmt::text('label.approval.automatic') }}</option>
                    </select>
                </div>

                @can(\App\Domain\Enum\Permission::ENVIRONMENT_SET_GROUP())
                    <div class="mt-4">
                        <x-label for="group" :value="\App\Traits\Fmt::text('label.group')"></x-label>
                        <select id="group" class="block mt-1 w-full rounded"
                                wire:model.defer="environment.groups_id">
                            <option value="0" selected>{{ \App\Traits\Fmt::text('label.select') }}</option>
                            @foreach($groups as $group)
                                <option value="{{ $group->id }}">{{ $group->formattedName }}</option>
                            @endforeach
                        </select>
                    </div>
                @endcan
            </x-slot>
            <!-- footer -->
            <x-slot name="footer">
                <x-button.danger type="button" wire:click="modalToggle">
                    <x-icon.cancel class="w-4 h-4 mr-1">
                        {{ \App\Traits\Fmt::text('label.btn.cancel') }}
                    </x-icon.cancel>
                </x-button.danger>
                <x-button>
                    <x-icon.save class="w-4 h-4 mr-1">
                        {{ \App\Traits\Fmt::text('label.btn.save') }}
                    </x-icon.save>
                </x-button>
            </x-slot>

        </x-modal.dialog>
    </form>
</div>
