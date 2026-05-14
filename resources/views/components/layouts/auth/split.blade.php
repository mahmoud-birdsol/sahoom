<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>{{ $title ?? config('app.name') }}</title>
        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=playfair-display:300,400,400i,500,300i&family=montserrat:300,400,500,600&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen antialiased" style="background: #FAFAF7; font-family: 'Montserrat', sans-serif">

        <div class="flex min-h-screen">

            {{-- ── Left: Form panel ─────────────────────────────────────────────────────── --}}
            <div class="relative flex w-full flex-col lg:w-[55%]" style="background: #FAFAF7">

                {{-- Top nav --}}
                <div class="flex items-center justify-between px-10 py-6" style="border-bottom: 1px solid #E8E2D8">
                    <a href="{{ route('home') }}" wire:navigate>
                        <x-public.logo size="sm" />
                    </a>
                    <nav class="hidden items-center gap-7 sm:flex">
                        <a href="{{ route('home') }}" wire:navigate
                           style="font-size: 0.65rem; font-weight: 600; letter-spacing: .12em; text-transform: uppercase; color: rgba(30,35,48,.45)"
                           class="transition hover:opacity-100">{{ __('Home') }}</a>
                        <a href="{{ route('properties.index') }}" wire:navigate
                           style="font-size: 0.65rem; font-weight: 600; letter-spacing: .12em; text-transform: uppercase; color: rgba(30,35,48,.45)"
                           class="transition hover:opacity-100">{{ __('Properties') }}</a>
                        <a href="{{ route('articles.index') }}" wire:navigate
                           style="font-size: 0.65rem; font-weight: 600; letter-spacing: .12em; text-transform: uppercase; color: rgba(30,35,48,.45)"
                           class="transition hover:opacity-100">{{ __('Articles') }}</a>
                    </nav>
                </div>

                {{-- Form area --}}
                <div class="flex flex-1 items-center justify-center px-10 py-12">
                    <div class="w-full" style="max-width: 380px">
                        {{ $slot }}
                    </div>
                </div>

                {{-- Footer --}}
                <div class="flex items-center justify-between px-10 py-5"
                     style="border-top: 1px solid #E8E2D8; font-size: 0.65rem; color: rgba(30,35,48,.35); letter-spacing: .04em">
                    <span>&copy; {{ date('Y') }} Sahoome</span>
                    <div class="flex gap-5">
                        <a href="{{ route('privacy') }}" wire:navigate class="transition hover:opacity-70">{{ __('Privacy') }}</a>
                        <a href="{{ route('terms') }}" wire:navigate class="transition hover:opacity-70">{{ __('Terms') }}</a>
                    </div>
                </div>
            </div>

            {{-- ── Right: Dark ink decorative panel ──────────────────────────────────── --}}
            <div class="relative hidden overflow-hidden lg:flex lg:w-[45%]" style="background: #1E2330">

                {{-- Gold top accent bar --}}
                <div class="absolute top-0 left-0 right-0" style="height: 3px; background: #B8962E"></div>

                {{-- Subtle diagonal grid (decorative) --}}
                <div class="absolute inset-0" style="opacity: .035; background-image: repeating-linear-gradient(45deg, #B8962E 0, #B8962E 1px, transparent 1px, transparent 56px), repeating-linear-gradient(-45deg, #B8962E 0, #B8962E 1px, transparent 1px, transparent 56px)"></div>

                {{-- Large faint circle --}}
                <div class="absolute -bottom-40 -right-40 rounded-full" style="width: 480px; height: 480px; background: rgba(184,150,46,.06)"></div>
                <div class="absolute -top-20 -left-20 rounded-full" style="width: 300px; height: 300px; background: rgba(184,150,46,.04)"></div>

                {{-- Branding content --}}
                <div class="relative z-10 flex w-full flex-col items-center justify-center px-16 text-center">

                    <x-public.logo :white="true" size="2xl" class="mb-10 justify-center" />

                    {{-- Gold rule --}}
                    <div class="mb-8" style="height: 1px; width: 48px; background: #B8962E"></div>

                    {{-- Serif headline --}}
                    <p style="font-family: 'Playfair Display', serif; font-size: clamp(1.6rem, 2.2vw, 2.2rem); font-weight: 300; color: #fff; line-height: 1.35; margin-bottom: 18px">
                        {{ __('Your property,') }}<br>
                        <em class="italic" style="color: #B8962E">{{ __('your future.') }}</em>
                    </p>

                    <p style="font-size: 0.78rem; font-weight: 300; color: rgba(255,255,255,.42); line-height: 1.85; max-width: 260px">
                        {{ __('Find, manage, and grow your real estate portfolio with confidence.') }}
                    </p>

                    {{-- Gold dots --}}
                    <div class="mt-12 flex gap-2.5">
                        <span class="rounded-full" style="width: 6px; height: 6px; background: #B8962E; opacity: 1"></span>
                        <span class="rounded-full" style="width: 6px; height: 6px; background: #B8962E; opacity: .45"></span>
                        <span class="rounded-full" style="width: 6px; height: 6px; background: #B8962E; opacity: .2"></span>
                    </div>
                </div>
            </div>

        </div>

        @fluxScripts
    </body>
</html>
