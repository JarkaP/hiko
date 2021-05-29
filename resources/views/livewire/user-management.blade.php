<div>
    <x-success-alert :message="$message" />
    <div x-data="{ newUserDialog: false }" @keydown.escape="newUserDialog = false"
        x-init="$watch('newUserDialog', toggleBodyOverflow)" x-on:user-created.window="newUserDialog = false">
        <x-buttons.simple class="mb-4" type="button" x-on:click="newUserDialog = true">
            {{ __('Nový účet') }}
        </x-buttons.simple>
        <x-dialog title="{{ __('Nový účet') }}" slug="newUserDialog">
            <form wire:submit.prevent="create" autocomplete="off">
                @csrf
                <x-label for="name" :value="__('Jméno')" class="mt-4" />
                <x-input wire:model.defer="name" id="name" class="block w-full mt-1" type="text" name="name" required />
                @error('name')
                    <div class="text-red-600">{{ $message }}</div>
                @enderror
                <x-label for="email" :value="__('E-mail')" class="mt-4" />
                <x-input wire:model.defer="email" id="email" class="block w-full mt-1" type="email" name="email"
                    required />
                @error('email')
                    <div class="text-red-600">{{ $message }}</div>
                @enderror
                <x-label for="role" :value="__('Role')" class="mt-4" />
                <x-select wire:model.defer="role" name="role" id="role">
                    <option value="administrator">Administrátor</option>
                    <option value="editor">Editor</option>
                    <option value="guest">Divák</option>
                </x-select>
                @error('role')
                    <div class="text-red-600">{{ $message }}</div>
                @enderror
                <x-buttons.simple class="w-full mt-6 mb-2" wire:loading.attr="disabled">
                    {{ __('Vytvořit nový účet') }}
                </x-buttons.simple>
            </form>
            <div wire:loading wire:target="create">
                <x-loading-indicator>
                    Odesílám
                </x-loading-indicator>
            </div>
        </x-dialog>
    </div>
    <div class="overflow-auto">
        <table class="w-full max-w-4xl text-left">
            <thead>
                <tr class="border-b border-gray-500">
                    <th class="px-4 py-2 text-sm font-bold uppercase">
                        Jméno
                    </th>
                    <th class="px-4 py-2 text-sm font-bold uppercase">
                        Role
                    </th>
                    <th class="px-4 py-2 text-sm font-bold uppercase">
                        Status
                    </th>
                    <th class="px-4 py-2 text-sm font-bold uppercase">

                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr class="hover:bg-gray-200">
                        <td class="px-4 py-2 text-sm border-b">
                            <div x-data="{ editUserDialog: false }" @keydown.escape="editUserDialog = false"
                                x-init="$watch('editUserDialog', toggleBodyOverflow)"
                                x-on:user-edited.window="editUserDialog = false">
                                <button type="button" class="flex flex-col items-start hover:underline" type="button"
                                    x-on:click="editUserDialog = true">
                                    <span class="font-bold">{{ $user->name }}</span>
                                    <span>{{ $user->email }}</span>
                                </button>
                                <x-dialog title="{{ $user->name }}" slug="editUserDialog">
                                    <form
                                        wire:submit.prevent="edit({{ $user->id }}, Object.fromEntries(new FormData($event.target)))"
                                        autocomplete="off">
                                        @csrf
                                        <x-label for="name-edit-{{ $user->id }}" :value="__('Jméno')"
                                            class="mt-4" />
                                        <x-input id="name-edit-{{ $user->id }}" class="block w-full mt-1"
                                            type="text" name="name" required value="{{ $user->name }}" />
                                        @error('name')
                                            <div class="text-red-600">{{ $message }}</div>
                                        @enderror
                                        <x-label for="role-edit-{{ $user->id }}" :value="__('Role')"
                                            class="mt-4" />
                                        <x-select name="role" id="role-edit-{{ $user->id }}">
                                            <option value="administrator" @if ($user->role === 'administrator') selected @endif>Administrátor</option>
                                            <option value="editor" @if ($user->role === 'editor') selected @endif>Editor</option>
                                            <option value="guest" @if ($user->role === 'guest') selected @endif>Divák</option>
                                        </x-select>
                                        @error('role')
                                            <div class="text-red-600">{{ $message }}</div>
                                        @enderror
                                        <div class="block mt-4">
                                            <x-checkbox name="status" label="{{ __('Aktivní uživatel') }}"
                                                :checked="!$user->isDeactivated()" />
                                        </div>
                                        <x-buttons.simple class="w-full mt-6 mb-2 bg-red-700"
                                            wire:loading.attr="disabled">
                                            {{ __('Upravit uživatele') }}
                                        </x-buttons.simple>
                                    </form>
                                    <div wire:loading.flex wire:target="edit">
                                        <x-loading-indicator>
                                            Odesílám
                                        </x-loading-indicator>
                                    </div>
                                </x-dialog>
                            </div>
                        </td>
                        <td class="px-4 py-2 text-sm border-b">
                            {{ $user->roleName() }}
                        </td>
                        <td class="px-4 py-2 text-sm border-b">
                            @if ($user->isDeactivated())
                                Neaktivní
                            @else
                                Aktivní
                            @endif
                        </td>
                        <td class="px-4 py-2 text-sm border-b">
                            <div x-data="{ deleteUserDialog: false }" @keydown.escape="deleteUserDialog = false"
                                x-init="$watch('deleteUserDialog', toggleBodyOverflow)"
                                x-on:user-deleted.window="deleteUserDialog = false">
                                <div class="text-right">
                                    <button type="button" class="text-red-600" type="button"
                                        x-on:click="deleteUserDialog = true">
                                        {{ __('Odstranit') }}
                                    </button>
                                </div>
                                <x-dialog title="{{ $user->name }}" slug="deleteUserDialog">
                                    <p class="text-red-600">
                                        Odstraní všechna data o uživateli! Chcete pokračovat?
                                    </p>
                                    <form wire:submit.prevent="delete({{ $user->id }})">
                                        @csrf
                                        <x-buttons.simple class="w-full mt-6 mb-2 bg-red-700"
                                            wire:loading.attr="disabled">
                                            {{ __('Odstranit uživatele') }}
                                        </x-buttons.simple>
                                    </form>
                                    <div wire:loading wire:target="delete">
                                        <x-loading-indicator>
                                            Odesílám
                                        </x-loading-indicator>
                                    </div>
                                </x-dialog>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="w-full max-w-4xl pl-1 mt-3">
        {{ $users->links('pagination::tailwind') }}
    </div>
</div>
