<div>
    <div x-data="{ newUserDialog: false }" @keydown.escape="newUserDialog = false"
        x-init="$watch('newUserDialog', toggleBodyOverflow)">
        <x-buttons.simple class="mb-4" type="button" x-on:click="newUserDialog = true">
            {{ __('Nový uživatel') }}
        </x-buttons.simple>
        <x-dialog title="{{ __('Nový uživatel') }}" slug="newUserDialog">
            <form wire:submit.prevent="create">
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
                    {{ __('Vytvořit nového uživatele') }}
                </x-buttons.simple>
            </form>
            <span wire:loading wire:target="create">
                Odesílám
            </span>
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
                        E-mail
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
                            <a href="#{{ $user->id }}" class="font-bold hover:underline">
                                {{ $user->name }}
                            </a>
                        </td>
                        <td class="px-4 py-2 text-sm border-b">
                            {{ $user->email }}
                        </td>
                        <td class="px-4 py-2 text-sm border-b">
                            {{ $user->roleName() }}
                        </td>
                        <td class="px-4 py-2 text-sm border-b">
                            {{ $user->isDeactivated() }}
                        </td>
                        <td class="px-4 py-2 text-sm border-b">
                            <div x-data="{ deleteUserDialog: false }" @keydown.escape="deleteUserDialog = false"
                                x-init="$watch('deleteUserDialog', toggleBodyOverflow)">
                                <button type="button" class="text-red-600" type="button" x-on:click="deleteUserDialog = true">
                                    {{ __('Odstranit') }}
                                </button>
                                <x-dialog title="{{ $user->name }}" slug="deleteUserDialog">
                                    <p class="text-red-600">
                                        Odstraní všechna data o uživateli! Chcete pokračovat?
                                    </p>
                                    <form wire:submit.prevent="delete({{ $user->id }})">
                                        @csrf
                                        <x-buttons.simple class="w-full mt-6 mb-2 bg-red-700" wire:loading.attr="disabled">
                                            {{ __('Odstranit uživatele') }}
                                        </x-buttons.simple>
                                    </form>
                                    <span wire:loading wire:target="delete">
                                        Odesílám
                                    </span>
                                </x-dialog>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="w-full max-w-4xl pl-1 mt-3">
        {{ $users->links() }}
    </div>
</div>
