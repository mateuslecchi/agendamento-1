<div>
    <form wire:submit.prevent="updateUser">
        <x-modal.dialog wire:model.defer="show_modal">
            <x-slot name="title">{{ App\Traits\Fmt::title('label.custom.edit', ['name' => 'label.user']) }}</x-slot>
            <!-- content -->
            <x-slot name="content">
                <x-auth-validation-errors class="mb-4" :errors="$errors"></x-auth-validation-errors>
                <div>
                    <x-label for="name_edit" :value="App\Traits\Fmt::text('label.name')"></x-label>
                    <x-input id="name_edit" type="text" autofocus class="block mt-1 w-full"
                             wire:model.defer="user.name"></x-input>
                </div>
                <div class="mt-4">
                    <x-label for="email_edit" :value="App\Traits\Fmt::text('label.email')"></x-label>
                    <x-input id="email_edit" type="email" required class="block mt-1 w-full"
                             wire:model.defer="user.email"></x-input>
                </div>
                <div class="mt-4">
                    <x-label for="password_edit" :value="App\Traits\Fmt::text('label.password')"></x-label>
                    <x-input id="password_edit" type="password" class="block mt-1 w-full"
                             wire:model.defer="user.password"></x-input>
                </div>
                @if($allowGroupEditing)
                <div class="mt-4">
                    <x-label for="group_edit" :value="App\Traits\Fmt::text('label.group')"></x-label>
                    <select id="group_edit" class="block mt-1 w-full"
                            wire:model.defer="group.id">
                        <option value="0" selected>{{ App\Traits\Fmt::text('label.select') }}</option>
                        @forelse($groups as $group)
                            <option value="{{ __($group->id) }}">{{ App\Traits\Fmt::text($group->name) }}</option>
                        @empty
                            <option value="0">{{ App\Traits\Fmt::text('text.no-record-found') }}</option>
                        @endforelse
                    </select>
                </div>
                @endif
            </x-slot>
            <!-- footer -->
            <x-slot name="footer">
                <x-button.danger type="button" wire:click="modalToggle">{{ App\Traits\Fmt::text('label.btn.cancel') }}</x-button.danger>
                <x-button>{{ App\Traits\Fmt::text('label.btn.save') }}</x-button>
            </x-slot>
        </x-modal.dialog>
    </form>
</div>
