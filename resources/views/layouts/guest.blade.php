<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name') }}</title>
        <link rel="stylesheet" href="{{ asset('dist/app.css') }}">
        <script src="{{ asset('dist/app.js') }}" defer></script>
    </head>
    <body class="px-6 font-sans antialiased text-gray-900 bg-gray-100">
        {{ $slot }}
    </body>
</html>
