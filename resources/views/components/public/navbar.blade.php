@props(['transparent' => false])

<nav class="{{ $transparent
    ? 'absolute top-0 left-0 right-0 z-50 bg-transparent border-transparent'
    : 'sticky top-0 z-50 bg-white/95 backdrop-blur-sm border-b border-zinc-100 shadow-sm' }}">
    <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-3 lg:px-10">

        {{-- Logo --}}
        <a href="{{ route('home') }}" wire:navigate>
            <x-public.logo :white="$transparent" />
        </a>

        {{-- Nav links --}}
        <div class="hidden items-center gap-7 text-sm font-medium lg:flex {{ $transparent ? 'text-white/80' : 'text-zinc-600' }}">
            <a href="{{ route('home') }}" wire:navigate
                class="{{ $transparent
                    ? (request()->routeIs('home') ? 'text-white font-semibold' : 'transition hover:text-white')
                    : (request()->routeIs('home') ? 'text-teal-600' : 'transition hover:text-zinc-900') }}">
                {{ __('Home') }}
            </a>
            <a href="{{ route('properties.index') }}" wire:navigate
                class="{{ $transparent
                    ? (request()->routeIs('properties.*') ? 'text-white font-semibold' : 'transition hover:text-white')
                    : (request()->routeIs('properties.*') ? 'text-teal-600' : 'transition hover:text-zinc-900') }}">
                {{ __('Properties') }}
            </a>
            <a href="{{ route('home') }}#about"
                class="transition {{ $transparent ? 'hover:text-white' : 'hover:text-zinc-900' }}">
                {{ __('About Us') }}
            </a>
            <a href="{{ route('home') }}#contact"
                class="transition {{ $transparent ? 'hover:text-white' : 'hover:text-zinc-900' }}">
                {{ __('Contact Us') }}
            </a>
        </div>

        {{-- Search --}}
        <form action="{{ route('properties.index') }}" method="GET"
            class="hidden lg:flex items-center gap-2 rounded-full px-3 py-1.5 text-sm
                {{ $transparent
                    ? 'border border-white/30 bg-white/10 text-white/70 backdrop-blur-sm'
                    : 'border border-zinc-200 bg-zinc-50 text-zinc-500' }}">
            <svg class="size-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" /></svg>
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="{{ __('Search...') }}"
                class="w-36 bg-transparent outline-none {{ $transparent ? 'placeholder-white/50 text-white' : 'placeholder-zinc-400 text-zinc-700' }}" />
        </form>

        {{-- Auth --}}
        <div class="flex items-center gap-3">
            {{-- Locale switcher --}}
            <x-public.locale-switcher :transparent="$transparent" />

            @auth
                {{-- Notification bell --}}
                <button class="relative flex size-9 items-center justify-center rounded-full border transition
                    {{ $transparent
                        ? 'border-white/30 text-white/80 hover:border-white hover:text-white'
                        : 'border-zinc-200 text-zinc-500 hover:border-teal-300 hover:text-teal-600' }}">
                    <svg class="size-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0"/></svg>
                    <span class="absolute right-1 top-1 size-2 rounded-full bg-red-500"></span>
                </button>

                {{-- Help icon --}}
                <button class="flex size-9 items-center justify-center rounded-full border transition
                    {{ $transparent
                        ? 'border-white/30 text-white/80 hover:border-white hover:text-white'
                        : 'border-zinc-200 text-zinc-500 hover:border-teal-300 hover:text-teal-600' }}">
                    <svg class="size-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.827v.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 5.25h.008v.008H12v-.008Z"/></svg>
                </button>

                {{-- User dropdown --}}
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open"
                        class="flex items-center gap-2.5 rounded-full border py-1 pl-1 pr-3 transition
                            {{ $transparent
                                ? 'border-white/30 hover:border-white/60'
                                : 'border-zinc-200 hover:border-teal-300' }}">
                        <div class="flex size-7 items-center justify-center rounded-full bg-gradient-to-br from-teal-400 to-teal-600 text-xs font-extrabold text-white">
                            {{ auth()->user()->initials() }}
                        </div>
                        <div class="hidden sm:block text-left">
                            <p class="text-[10px] leading-none {{ $transparent ? 'text-white/60' : 'text-zinc-400' }}">{{ __('Welcome') }}</p>
                            <p class="text-xs font-semibold leading-tight max-w-[80px] truncate {{ $transparent ? 'text-white' : 'text-zinc-800' }}">{{ auth()->user()->name }}</p>
                        </div>
                        <svg class="size-3.5 {{ $transparent ? 'text-white/60' : 'text-zinc-400' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m19 9-7 7-7-7"/></svg>
                    </button>

                    <div x-show="open" @click.outside="open = false" x-transition
                        class="absolute right-0 top-full z-50 mt-2 w-52 overflow-hidden rounded-xl border border-zinc-100 bg-white shadow-lg">
                        <div class="border-b border-zinc-50 px-4 py-3">
                            <p class="text-xs font-semibold text-zinc-900 truncate">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-zinc-400 truncate">{{ auth()->user()->email }}</p>
                        </div>
                        <div class="py-1">
                            <a href="{{ route('account') }}" wire:navigate
                                class="flex items-center gap-2.5 px-4 py-2 text-sm text-zinc-700 transition hover:bg-teal-50 hover:text-teal-700">
                                <svg class="size-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/></svg>
                                {{ __('My Profile') }}
                            </a>
                            <a href="{{ route('account.section', 'rents') }}" wire:navigate
                                class="flex items-center gap-2.5 px-4 py-2 text-sm text-zinc-700 transition hover:bg-teal-50 hover:text-teal-700">
                                <svg class="size-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"/></svg>
                                {{ __('My Rents') }}
                            </a>
                            <a href="{{ route('account.section', 'favorites') }}" wire:navigate
                                class="flex items-center gap-2.5 px-4 py-2 text-sm text-zinc-700 transition hover:bg-teal-50 hover:text-teal-700">
                                <svg class="size-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/></svg>
                                {{ __('Favourites') }}
                            </a>
                            @if(auth()->user()->landlord)
                                <a href="{{ route('landlord.dashboard') }}" wire:navigate
                                    class="flex items-center gap-2.5 px-4 py-2 text-sm text-zinc-700 transition hover:bg-teal-50 hover:text-teal-700">
                                    <svg class="size-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 0 0 6 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0 1 18 16.5h-2.25m-7.5 0h7.5m-7.5 0-1 3m8.5-3 1 3m0 0 .5 1.5m-.5-1.5h-9.5m0 0-.5 1.5"/></svg>
                                    {{ __('Landlord Dashboard') }}
                                </a>
                            @endif
                        </div>
                        <div class="border-t border-zinc-50 py-1">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="flex w-full items-center gap-2.5 px-4 py-2 text-sm text-zinc-500 transition hover:bg-red-50 hover:text-red-600">
                                    <svg class="size-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15M12 9l3 3m0 0-3 3m3-3H2.25"/></svg>
                                    {{ __('Log out') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" wire:navigate
                    class="rounded-full border px-4 py-1.5 text-sm font-semibold transition
                        {{ $transparent
                            ? 'border-white/40 text-white hover:bg-white/10'
                            : 'border-zinc-200 text-zinc-700 hover:bg-zinc-50' }}">
                    {{ __('Log in') }}
                </a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" wire:navigate
                        class="rounded-full px-4 py-1.5 text-sm font-semibold text-white transition
                            {{ $transparent
                                ? 'bg-white/20 hover:bg-white/30 backdrop-blur-sm'
                                : 'bg-teal-600 hover:bg-teal-700' }}">
                        {{ __('Sign up') }}
                    </a>
                @endif
            @endauth
        </div>

    </div>
</nav>
