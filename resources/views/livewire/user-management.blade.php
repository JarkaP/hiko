<div>
    <div x-data="{formVisible: false}">
        <x-buttons.simple class="mb-4" type="button">
            <span x-on:click="">
                {{ __('Nový uživatel') }}
            </span>
        </x-buttons.simple>
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
                            Odstranit
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
