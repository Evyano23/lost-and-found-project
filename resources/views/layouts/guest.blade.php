<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Trove') }} — Lost & Found</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900">

            {{-- Logo --}}
            <div class="mb-2">
                <a href="/" class="transition hover:scale-105 inline-block">
                    <x-application-logo class="w-20 h-20" />
                </a>
            </div>

            {{-- Card --}}
            <div class="w-full sm:max-w-md mt-2 px-6 py-6 bg-slate-800/80 backdrop-blur-sm shadow-xl border border-slate-700/60 overflow-hidden sm:rounded-2xl">
                {{ $slot }}
            </div>

            {{-- Footer tagline --}}
            <p class="mt-6 text-xs text-slate-500">
                &copy; {{ date('Y') }} Trove — Reuniting people with their belongings.
            </p>
        </div>
    </body>
</html>

