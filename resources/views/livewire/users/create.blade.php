<div>
    <form wire:submit.prevent="createNewUser">
        <x-modal.dialog wire:model.defer="show_modal">
            <x-slot name="title">{{ App\Traits\Fmt::text('label.custom.new', ['name' => 'label.user'])  }}</x-slot>
            <!-- content -->
            <x-slot name="content">
                <x-auth-validation-errors class="mb-4" :errors="$errors"></x-auth-validation-errors>
                <div>
                    <x-label for="name" :value="App\Traits\Fmt::text('label.name')"></x-label>
                    <x-input id="name" type="text" autofocus class="block mt-1 w-full"
                             wire:model.defer="user.name"></x-input>
                </div>
                <div class="mt-4">
                    <x-label for="email" :value="App\Traits\Fmt::text('label.email')"></x-label>
                    <x-input id="email" type="email" required class="block mt-1 w-full"
                             wire:model.defer="user.email"></x-input>
                </div>
                <div class="mt-4">
                    <x-label for="password" :value="App\Traits\Fmt::text('label.password')"></x-label>
                    <x-input id="password" type="password" required class="block mt-1 w-full"
                             wire:model.defer="user.password"></x-input>
                </div>
                <div class="mt-4">
                    <x-label for="group" :value="App\Traits\Fmt::text('label.group')"></x-label>
                    <select id="group" class="block mt-1 w-full" wire:model.defer="group.id">
                        <option value="0" selected>{{ App\Traits\Fmt::text('label.select') }}</option>
                        @forelse($groups as $group)
                            <option value="{{ $group->id }}">{{ $group->formattedName }}</option>
                        @empty
                            <option value="0">{{ App\Traits\Fmt::text('text.no-record-found') }}</option>
                        @endforelse
                    </select>
                </div>
            </x-slot>
            <!-- footer -->
            <x-slot name="footer">
                <x-button.danger type="button" wire:click="modalToggle">{{ App\Traits\Fmt::text('label.btn.cancel') }}</x-button.danger>
                <x-button>{{ App\Traits\Fmt::text('label.btn.save') }}</x-button>
            </x-slot>
        </x-modal.dialog>
    </form>
</div>
