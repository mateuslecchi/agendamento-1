<div>
    <form wire:submit.prevent="save">
        <x-modal.dialog wire:model.defer="show_modal">
            <x-slot name="title">{{ Str::title(__('label.custom.edit', ['name' => __('label.environment')])) }}</x-slot>
            <!-- content -->
            <x-slot name="content">
                <x-auth-validation-errors class="mb-4" :errors="$errors"/>

                <div>
                    <x-label for="name" :value="Str::ucfirst(__('label.name'))"/>
                    <x-input id="name" type="text" class="block mt-1 w-full" autofocus
                             wire:model.defer="environment.name"/>
                </div>

                <div class="mt-4">
                    <x-label for="block" :value="Str::ucfirst(__('label.block'))"/>
                    <select id="block" class="block mt-1 w-full rounded"
                            wire:model.defer="environment.blocks_id">
                        <option value="0" selected>{{ __('label.select') }}</option>
                        @foreach($blocks as $block)
                            <option value="{{ $block->id }}">{{ __($block->name) }}</option>
                        @endforeach
                    </select>
                </div>
                @can(\App\Domain\Enum\Permission::ENVIRONMENT_SET_GROUP())
                <div class="mt-4">
                    <x-label for="group" :value="Str::ucfirst(__('label.group'))"/>
                    <select id="group" class="block mt-1 w-full rounded"
                            wire:model.defer="environment.groups_id">
                        <option value="0" selected>{{ __('label.select') }}</option>
                        @foreach($groups as $group)
                            <option value="{{ $group->id }}">{{ __($group->name) }}</option>
                        @endforeach
                    </select>
                </div>
                @endcan
            </x-slot>
            <!-- footer -->
            <x-slot name="footer">
                <x-button.danger type="button" wire:click="modalToggle">{{ __('label.btn.cancel') }}</x-button.danger>
                <x-button>{{ __('label.btn.save') }}</x-button>
            </x-slot>

        </x-modal.dialog>
    </form>
</div>
