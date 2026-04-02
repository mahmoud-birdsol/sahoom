<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>{{ $title ?? config('app.name') }}</title>
        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-white antialiased">

        <div class="flex min-h-screen">

            {{-- ── Left: Form panel ──────────────────────────────────────── --}}
            <div class="relative flex w-full flex-col lg:w-1/2">

                {{-- Top nav bar --}}
                <div class="flex items-center justify-between px-8 py-5">
                    <a href="{{ route('home') }}" class="flex items-center gap-2" wire:navigate>
                        <span class="font-bold tracking-widest text-amber-600 text-sm uppercase">SAHOOME</span>
                    </a>
                    <nav class="hidden items-center gap-6 text-sm text-zinc-500 sm:flex">
                        <a href="#" class="transition hover:text-zinc-800">{{ __('Support') }}</a>
                        <a href="#" class="transition hover:text-zinc-800">{{ __('Docs') }}</a>
                        <a href="#" class="transition hover:text-zinc-800">{{ __('Contact Us') }}</a>
                    </nav>
                </div>

                {{-- Form area --}}
                <div class="flex flex-1 items-center justify-center px-8 py-10">
                    <div class="w-full max-w-sm">
                        {{ $slot }}
                    </div>
                </div>

                {{-- Footer --}}
                <div class="flex items-center justify-between px-8 py-5 text-xs text-zinc-400">
                    <span>&copy; {{ date('Y') }} {{ config('app.name') }}</span>
                    <div class="flex gap-4">
                        <a href="#" class="transition hover:text-zinc-600">{{ __('Privacy') }}</a>
                        <a href="#" class="transition hover:text-zinc-600">{{ __('Terms') }}</a>
                    </div>
                </div>
            </div>

            {{-- ── Right: Amber geometric panel ──────────────────────────── --}}
            <div class="relative hidden overflow-hidden lg:flex lg:w-1/2">
                {{-- Amber background with diagonal left edge --}}
                <div
                    class="absolute inset-0 bg-amber-600"
                    style="clip-path: polygon(8% 0%, 100% 0%, 100% 100%, 0% 100%)"
                ></div>

                {{-- Subtle decorative circles --}}
                <div class="absolute -top-24 -right-24 size-96 rounded-full bg-amber-500/40"></div>
                <div class="absolute bottom-0 -left-10 size-72 rounded-full bg-amber-700/30"></div>
                <div class="absolute top-1/2 right-1/4 size-48 rounded-full bg-amber-400/20"></div>

                {{-- Branding content --}}
                <div class="relative z-10 flex w-full flex-col items-center justify-center px-16 text-center">
                    <span class="mb-4 text-4xl font-extrabold tracking-widest text-white">SAHOOME</span>
                    <p class="max-w-xs text-sm leading-relaxed text-amber-100">
                        {{ __('Your smart property management platform — find, manage, and grow your real estate portfolio.') }}
                    </p>

                    {{-- Decorative dots --}}
                    <div class="mt-12 flex gap-2">
                        <span class="size-2 rounded-full bg-white/60"></span>
                        <span class="size-2 rounded-full bg-white/40"></span>
                        <span class="size-2 rounded-full bg-white/20"></span>
                    </div>
                </div>
            </div>

        </div>

        @fluxScripts
    </body>
</html>
