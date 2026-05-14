@props(['transparent' => false])

<nav
    @if($transparent)
        x-data="{ solid: false }"
        @scroll.window="solid = window.scrollY > 60"
        :class="solid ? 'bg-white border-b border-[#E8E2D8]' : 'bg-transparent border-transparent'"
        class="fixed top-0 left-0 right-0 z-[600] h-20 flex items-center justify-between px-[6%] transition-all duration-500"
    @else
        class="sticky top-0 z-[600] h-20 flex items-center justify-between px-[6%] bg-white border-b border-[#E8E2D8]"
        x-data="{ solid: true }"
    @endif
>

    {{-- Logo --}}
    <a href="{{ route('home') }}" wire:navigate class="flex-shrink-0">
        @if($transparent)
            <span class="flex items-center gap-3 font-sans font-light tracking-[.35em] uppercase text-[0.95rem] transition-colors duration-500"
                  :class="solid ? 'text-ink' : 'text-white'">
                <svg width="26" height="30" viewBox="0 0 80 90" fill="none" class="shrink-0">
                    <path d="M40 5C25 5 13 17.5 13 33C13 53 40 85 40 85C40 85 67 53 67 33C67 17.5 55 5 40 5Z"
                          fill="none" stroke="#B8962E" stroke-width="5" stroke-linejoin="round"/>
                    <path d="M40 16L24 29V47H33V37H47V47H56V29L40 16Z"
                          fill="none" stroke="#B8962E" stroke-width="4.5" stroke-linejoin="round"/>
                    <rect x="36.5" y="37" width="7" height="10" rx="1" fill="#B8962E"/>
                </svg>
                Sahoome
            </span>
        @else
            <x-public.logo />
        @endif
    </a>

    {{-- Desktop nav links --}}
    <ul class="hidden lg:flex items-center gap-0.5 list-none m-0 p-0">
        <li>
            <a href="{{ route('properties.index') }}" wire:navigate
               @if($transparent)
                   :class="solid ? 'text-muted' : 'text-white/75'"
               @endif
               class="text-[0.68rem] font-medium tracking-[.12em] uppercase no-underline px-3.5 py-2 transition-colors duration-200 hover:text-gold
                      {{ !$transparent ? 'text-muted' : '' }}">
                {{ __('Rental') }}
            </a>
        </li>
        <li>
            <a href="{{ route('properties.index') }}?isShortTerm=1" wire:navigate
               @if($transparent)
                   :class="solid ? 'text-muted' : 'text-white/75'"
               @endif
               class="text-[0.68rem] font-medium tracking-[.12em] uppercase no-underline px-3.5 py-2 transition-colors duration-200 hover:text-gold
                      {{ !$transparent ? 'text-muted' : '' }}">
                {{ __('Short-term') }}
            </a>
        </li>
        <li>
            <a href="{{ route('home') }}#about"
               @if($transparent)
                   :class="solid ? 'text-muted' : 'text-white/75'"
               @endif
               class="text-[0.68rem] font-medium tracking-[.12em] uppercase no-underline px-3.5 py-2 transition-colors duration-200 hover:text-gold
                      {{ !$transparent ? 'text-muted' : '' }}">
                {{ __('About') }}
            </a>
        </li>

        {{-- Divider --}}
        @if($transparent)
            <div class="h-4 w-px mx-1.5 transition-colors duration-500"
                 :class="solid ? 'bg-[#E8E2D8]' : 'bg-white/20'"></div>
        @else
            <div class="h-4 w-px mx-1.5 bg-[#E8E2D8]"></div>
        @endif

        <li>
            <a href="{{ route('home') }}#contact"
               class="text-[0.68rem] font-semibold tracking-[.14em] uppercase no-underline border border-gold text-gold px-5 py-2 rounded-sm transition-all duration-200 hover:bg-gold hover:text-white">
                {{ __('Contact') }}
            </a>
        </li>

        @auth
            <li class="ml-2">
                <a href="{{ route('landlord.dashboard') }}" wire:navigate
                   class="text-[0.68rem] font-semibold tracking-[.14em] uppercase no-underline bg-gold text-white px-4 py-2 transition-colors duration-200 hover:bg-gold-light">
                    {{ auth()->user()->landlord ? __('Dashboard') : __('List My Property') }}
                </a>
            </li>
        @else
            <li class="ml-2">
                <a href="{{ route('register') }}" wire:navigate
                   class="text-[0.68rem] font-semibold tracking-[.14em] uppercase no-underline bg-gold text-white px-4 py-2 transition-colors duration-200 hover:bg-gold-light">
                    {{ __('List My Property') }}
                </a>
            </li>
        @endauth
    </ul>

    {{-- Right: locale + user auth (compact) --}}
    <div class="flex items-center gap-2">
        <x-public.locale-switcher :transparent="$transparent" />

        @auth
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open"
                    @if($transparent)
                        :class="solid
                            ? 'border-[#E8E2D8] text-ink'
                            : 'border-white/30 text-white'"
                    @endif
                    class="flex items-center gap-2 border rounded-sm py-1.5 pl-1.5 pr-3 text-[0.68rem] font-medium tracking-[.08em] transition-all duration-300 hover:border-gold
                           {{ !$transparent ? 'border-[#E8E2D8] text-ink' : '' }}">
                    <div class="flex size-7 items-center justify-center rounded-full bg-gold text-[10px] font-bold text-white">
                        {{ auth()->user()->initials() }}
                    </div>
                    <span class="hidden sm:inline max-w-[80px] truncate">{{ auth()->user()->name }}</span>
                    <svg class="size-3 opacity-60" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m19 9-7 7-7-7"/></svg>
                </button>

                <div x-show="open" @click.outside="open = false" x-transition
                    class="absolute right-0 top-full z-50 mt-2 w-52 overflow-hidden border border-[#E8E2D8] bg-white shadow-xl">
                    <div class="border-b border-[#E8E2D8] px-4 py-3">
                        <p class="text-xs font-semibold text-ink truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-muted truncate">{{ auth()->user()->email }}</p>
                    </div>
                    <div class="py-1">
                        <a href="{{ route('account') }}" wire:navigate
                            class="flex items-center gap-2.5 px-4 py-2 text-xs text-ink transition hover:bg-[#F4EFE8] hover:text-gold">
                            {{ __('My Profile') }}
                        </a>
                        <a href="{{ route('account.section', 'rents') }}" wire:navigate
                            class="flex items-center gap-2.5 px-4 py-2 text-xs text-ink transition hover:bg-[#F4EFE8] hover:text-gold">
                            {{ __('My Rents') }}
                        </a>
                        <a href="{{ route('account.section', 'favorites') }}" wire:navigate
                            class="flex items-center gap-2.5 px-4 py-2 text-xs text-ink transition hover:bg-[#F4EFE8] hover:text-gold">
                            {{ __('Favourites') }}
                        </a>
                        @if(auth()->user()->landlord)
                            <a href="{{ route('landlord.dashboard') }}" wire:navigate
                                class="flex items-center gap-2.5 px-4 py-2 text-xs text-ink transition hover:bg-[#F4EFE8] hover:text-gold">
                                {{ __('Landlord Dashboard') }}
                            </a>
                        @endif
                    </div>
                    <div class="border-t border-[#E8E2D8] py-1">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="flex w-full items-center gap-2.5 px-4 py-2 text-xs text-muted transition hover:bg-red-50 hover:text-red-600">
                                {{ __('Log out') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @else
            <a href="{{ route('login') }}" wire:navigate
               @if($transparent)
                   :class="solid ? 'text-muted border-[#E8E2D8] hover:border-gold hover:text-gold' : 'text-white/80 border-white/30 hover:border-white hover:text-white'"
               @endif
               class="hidden lg:inline-flex text-[0.68rem] font-medium tracking-[.12em] uppercase no-underline border px-4 py-2 rounded-sm transition-all duration-200
                      {{ !$transparent ? 'text-muted border-[#E8E2D8] hover:border-gold hover:text-gold' : '' }}">
                {{ __('Log in') }}
            </a>
        @endauth
    </div>

</nav>
