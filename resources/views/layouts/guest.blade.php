<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>CoRide - Connexion & Inscription</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-100 antialiased bg-gray-900">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-950/40">
            <div>
                <a href="/" class="flex items-center gap-2 mb-6">
                    <span class="text-4xl">🚗</span>
                    <span class="text-3xl font-extrabold text-emerald-400 tracking-wider">CoRide</span>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-2 px-8 py-6 bg-gray-800 border border-gray-700/60 shadow-xl overflow-hidden sm:rounded-2xl">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
