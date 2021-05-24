<x-guest-layout>
    <x-auth-card>
        <x-auth-session-status class="mb-4" :status="session('status')" />
        <x-auth-validation-errors class="mb-4" :errors="$errors" />
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <x-label for="email" :value="__('E-mail')" />
            <x-input id="email" class="block w-full mt-1" type="email" name="email" :value="old('email')" required
                autofocus />
            <x-label for="password" :value="__('Heslo')" class="mt-4" />
            <x-input id="password" class="block w-full mt-1" type="password" name="password" required
                autocomplete="current-password" />
            <div class="block mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox"
                        class="text-purple-600 border-gray-300 rounded shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50"
                        name="remember">
                    <span class="ml-2 text-sm text-gray-600">{{ __('Zapamatovat') }}</span>
                </label>
            </div>
            <div class="flex flex-col items-center mt-4 space-y-2">
                <x-buttons.simple class="w-full">
                    {{ __('Přihlásit se') }}
                </x-buttons.simple>
                <a class="text-sm text-gray-600 underline hover:text-gray-900" href="{{ route('password.request') }}">
                    {{ __('Zapomněli jste heslo?') }}
                </a>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
