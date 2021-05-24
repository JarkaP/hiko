<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="min-h-screen">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }}</title>
    <link rel="stylesheet" href="{{ asset('dist/app.css') }}">
    <script src="{{ asset('dist/app.js') }}" defer></script>
    @livewireStyles
</head>

<body class="flex flex-col min-h-screen font-sans antialiased bg-gray-100">
    <header class="w-full px-6 bg-white shadow">
        @include('layouts.navigation')
    </header>
    <main class="px-6 pt-12 ">
        <div class="container mx-auto ">
            <h1 class="mb-6 text-xl font-semibold">
                {{ $title }}
            </h1>
            {{ $slot }}
        </div>
    </main>
    <footer class="container px-6 mx-auto mt-auto">
        {{ date('Y') }} HIKO – {{ config('app.name') }}
    </footer>
    @livewireScripts
</body>

</html>
