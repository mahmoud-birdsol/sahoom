<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

        <title>{{ $title ?? config('app.name') }}</title>

        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @if(config('services.google.maps_key'))
            <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_key') }}&libraries=places&loading=async" defer></script>
        @endif
    </head>
    <body class="min-h-screen bg-zinc-100">
        <flux:sidebar sticky stashable class="border-e border-zinc-200 bg-white">
            <flux:sidebar.toggle class="lg:hidden text-zinc-500 hover:text-zinc-900" icon="x-mark" />

            {{-- Logo --}}
            <a href="{{ route('landlord.dashboard') }}" class="px-1 py-2" wire:navigate>
                <x-public.logo />
            </a>

            {{-- Primary Navigation --}}
            <nav class="mt-4 flex flex-col gap-0.5 px-2">
                <a
                    href="{{ route('landlord.dashboard') }}"
                    wire:navigate
                    class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors
                        {{ request()->routeIs('landlord.dashboard')
                            ? 'bg-amber-50 text-amber-700'
                            : 'text-zinc-500 hover:bg-zinc-100 hover:text-zinc-900' }}"
                >
                    <flux:icon.home class="size-4.5 shrink-0" />
                    {{ __('Overview') }}
                </a>

                <a
                    href="{{ route('landlord.properties') }}"
                    wire:navigate
                    class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors
                        {{ request()->routeIs('landlord.properties*')
                            ? 'bg-amber-50 text-amber-700'
                            : 'text-zinc-500 hover:bg-zinc-100 hover:text-zinc-900' }}"
                >
                    <flux:icon.building-office-2 class="size-4.5 shrink-0" />
                    {{ __('Properties') }}
                </a>

                <a
                    href="{{ route('landlord.bookings') }}"
                    wire:navigate
                    class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors
                        {{ request()->routeIs('landlord.bookings*')
                            ? 'bg-amber-50 text-amber-700'
                            : 'text-zinc-500 hover:bg-zinc-100 hover:text-zinc-900' }}"
                >
                    <flux:icon.calendar-days class="size-4.5 shrink-0" />
                    <span class="flex-1">{{ __('Booking & calendar') }}</span>
                    @php($bookingCount = auth()->user()->landlord?->contracts()->where('contract_status', 'active')->where('start_date', '>', now())->count() ?? 0)
                    @if($bookingCount > 0)
                        <span class="flex size-5 items-center justify-center rounded-full bg-amber-500 text-[10px] font-semibold text-white">
                            {{ $bookingCount > 99 ? '99+' : $bookingCount }}
                        </span>
                    @endif
                </a>

                <a
                    href="{{ route('landlord.viewing-requests') }}"
                    wire:navigate
                    class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors
                        {{ request()->routeIs('landlord.viewing-requests*')
                            ? 'bg-amber-50 text-amber-700'
                            : 'text-zinc-500 hover:bg-zinc-100 hover:text-zinc-900' }}"
                >
                    <flux:icon.eye class="size-4.5 shrink-0" />
                    <span class="flex-1">{{ __('Viewing Requests') }}</span>
                    @php($vrCount = auth()->user()->landlord?->properties()->withCount(['viewingRequests' => fn($q) => $q->where('status', 'new')])->get()->sum('viewing_requests_count') ?? 0)
                    @if($vrCount > 0)
                        <span class="flex size-5 items-center justify-center rounded-full bg-amber-500 text-[10px] font-semibold text-white">
                            {{ $vrCount > 99 ? '99+' : $vrCount }}
                        </span>
                    @endif
                </a>

                <a
                    href="{{ route('landlord.traffic') }}"
                    wire:navigate
                    class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors
                        {{ request()->routeIs('landlord.traffic*')
                            ? 'bg-amber-50 text-amber-700'
                            : 'text-zinc-500 hover:bg-zinc-100 hover:text-zinc-900' }}"
                >
                    <flux:icon.chart-bar class="size-4.5 shrink-0" />
                    {{ __('Traffic') }}
                </a>

                <a
                    href="{{ route('landlord.notifications') }}"
                    wire:navigate
                    class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors
                        {{ request()->routeIs('landlord.notifications*')
                            ? 'bg-amber-50 text-amber-700'
                            : 'text-zinc-500 hover:bg-zinc-100 hover:text-zinc-900' }}"
                >
                    <flux:icon.bell class="size-4.5 shrink-0" />
                    <span class="flex-1">{{ __('Notification') }}</span>
                    @php($notifCount = auth()->user()->unreadNotifications()->count())
                    @if($notifCount > 0)
                        <span class="flex size-5 items-center justify-center rounded-full bg-amber-500 text-[10px] font-semibold text-white">
                            {{ $notifCount > 99 ? '99+' : $notifCount }}
                        </span>
                    @endif
                </a>
            </nav>

            <flux:spacer />

            {{-- Bottom Navigation --}}
            <nav class="flex flex-col gap-0.5 px-2 pb-2">
                <a
                    href="#"
                    class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-zinc-500 transition-colors hover:bg-zinc-100 hover:text-zinc-900"
                >
                    <flux:icon.question-mark-circle class="size-4.5 shrink-0" />
                    {{ __('Help') }}
                </a>
            </nav>

            {{-- User Profile --}}
            <flux:dropdown class="w-full" position="top" align="start">
                <button class="flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-zinc-600 transition-colors hover:bg-zinc-100 hover:text-zinc-900">
                    <span class="flex size-8 shrink-0 items-center justify-center rounded-full bg-amber-500 text-sm font-semibold text-white">
                        {{ auth()->user()->initials() }}
                    </span>
                    <span class="flex-1 truncate text-start text-zinc-700">{{ auth()->user()->name }}</span>
                    <flux:icon.chevron-up-down class="size-4 text-zinc-400" />
                </button>

                <flux:menu class="w-56">
                    <flux:menu.radio.group>
                        <div class="px-2 py-1.5">
                            <p class="text-sm font-semibold">{{ auth()->user()->name }}</p>
                            <p class="truncate text-xs text-zinc-500">{{ auth()->user()->email }}</p>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                        <flux:menu.item :href="route('dashboard')" icon="arrow-left" wire:navigate>{{ __('Back to App') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:sidebar>

        {{-- Mobile Header --}}
        <flux:header class="lg:hidden border-b border-zinc-200 bg-white">
            <flux:sidebar.toggle class="text-zinc-500" icon="bars-2" inset="left" />
            <flux:spacer />
            <span class="text-sm font-bold tracking-widest text-amber-600 uppercase">Sahoome</span>
            <flux:spacer />
            <span class="flex size-8 items-center justify-center rounded-full bg-amber-500 text-sm font-semibold text-white">
                {{ auth()->user()->initials() }}
            </span>
        </flux:header>

        {{ $slot }}

        @fluxScripts
    </body>
</html>
