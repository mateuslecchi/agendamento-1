<div>
    <form wire:submit.prevent="save">
        <x-modal.dialog wire:model.defer="show_modal">
            <x-slot name="title">{{ Str::title(__('label.custom.new', ['name' => __('label.user')])) }}</x-slot>
            <!-- content -->
            <x-slot name="content">
                <x-auth-validation-errors class="mb-4" :errors="$errors"/>
                <div>
                    <x-label for="name" :value="Str::ucfirst(__('label.name'))"/>
                    <x-input id="name" type="text" autofocus class="block mt-1 w-full"
                             wire:model.defer="user.name"/>
                </div>
                <div class="mt-4">
                    <x-label for="email" :value="Str::ucfirst(__('label.email'))"/>
                    <x-input id="email" type="email" required class="block mt-1 w-full"
                             wire:model.defer="user.email"/>
                </div>
                <div class="mt-4">
                    <x-label for="password" :value="Str::ucfirst(__('label.password'))"/>
                    <x-input id="password" type="password" required class="block mt-1 w-full"
                             wire:model.defer="user.password"/>
                </div>
                <div class="mt-4">
                    <x-label for="group" :value="Str::ucfirst(__('label.group'))"/>
                    <select id="group" class="block mt-1 w-full"
                            wire:model.defer="group.id">
                        <option value="0" selected>{{ __('label.select') }}</option>
                        <option value="-1" selected>{{ __('label.personal-group') }}</option>
                        @forelse($groups as $group)
                            <option value="{{ __($group->id) }}">{{ __($group->name) }}</option>
                        @empty
                            <option value="0">{{ __('text.no-record-found') }}</option>
                        @endforelse
                    </select></div>
            </x-slot>
            <!-- footer -->
            <x-slot name="footer">
                <x-button.danger type="button" wire:click="modalToggle">{{ __('label.btn.cancel') }}</x-button.danger>
                <x-button>{{ __('label.btn.save') }}</x-button>
            </x-slot>
        </x-modal.dialog>
    </form>
</div>
