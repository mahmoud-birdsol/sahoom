<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>{{ $title ?? config('app.name') }} — Find Your Perfect Space</title>
        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=playfair-display:300,400,400i,500,300i&family=montserrat:300,400,500,600&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('head')
    </head>
    <body class="bg-white antialiased text-zinc-800">
        {{ $slot }}
        @stack('scripts')
        @fluxScripts
    </body>
</html>
