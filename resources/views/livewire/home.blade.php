<div>

    {{-- ══ NAVBAR (fixed, transparent → solid on scroll) ═══════════════════ --}}
    {{-- ══ CUSTOM GOLD CURSOR ══════════════════════════════════════════════════ --}}
    <div id="alicia-cursor"      class="hidden lg:block" style="position:fixed;width:8px;height:8px;background:#B8962E;border-radius:50%;pointer-events:none;z-index:9999;transform:translate(-50%,-50%);transition:width .15s,height .15s;"></div>
    <div id="alicia-cursor-ring" class="hidden lg:block" style="position:fixed;width:36px;height:36px;border:1px solid #B8962E;border-radius:50%;pointer-events:none;z-index:9998;transform:translate(-50%,-50%);opacity:.35;transition:width .3s,height .3s,opacity .3s;"></div>

    <x-public.navbar :transparent="true" />

    {{-- ══ HERO ══════════════════════════════════════════════════════════════ --}}
    <section class="relative h-screen min-h-[700px] overflow-hidden"
             x-data="{
                 current: 0,
                 total: 3,
                 init() {
                     setInterval(() => { this.current = (this.current + 1) % this.total }, 6000)
                 },
                 goSlide(n) { this.current = n }
             }">

        {{-- Slide 1 --}}
        <div class="absolute inset-0 bg-cover bg-center transition-opacity duration-[1500ms]"
             :class="current === 0 ? 'opacity-100' : 'opacity-0'"
             style="background-image: url('https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=1800&q=90')"></div>

        {{-- Slide 2 --}}
        <div class="absolute inset-0 bg-cover bg-center transition-opacity duration-[1500ms]"
             :class="current === 1 ? 'opacity-100' : 'opacity-0'"
             style="background-image: url('https://images.unsplash.com/photo-1613490493576-7fde63acd811?w=1800&q=90')"></div>

        {{-- Slide 3 --}}
        <div class="absolute inset-0 bg-cover bg-center transition-opacity duration-[1500ms]"
             :class="current === 2 ? 'opacity-100' : 'opacity-0'"
             style="background-image: url('https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=1800&q=90')"></div>

        {{-- Dark directional overlay --}}
        <div class="absolute inset-0" style="background: linear-gradient(105deg, rgba(17,19,24,.78) 0%, rgba(17,19,24,.48) 55%, rgba(17,19,24,.22) 100%)"></div>

        {{-- ── Hero content ───────────────────────────────────────────────── --}}
        <div class="relative z-10 flex h-full flex-col justify-center px-[6%]" style="max-width: 780px">

            {{-- Pre-title --}}
            <div class="mb-8 flex items-center gap-4 opacity-0 animate-fade-right" style="animation-delay: .4s">
                <div class="h-px w-11 shrink-0" style="background: #B8962E"></div>
                <span class="text-[0.68rem] font-medium uppercase tracking-[.2em]" style="color: #B8962E">
                    {{ __('Abidjan · Ivory Coast') }}
                </span>
            </div>

            {{-- H1 --}}
            <h1 class="mb-6 font-serif font-light leading-[1.06] text-white opacity-0 animate-fade-right"
                style="font-size: clamp(3rem, 6vw, 5.6rem); animation-delay: .6s">
                {{ __('Real estate') }}<br>
                {{ __('excellence') }}<br>
                {{ __('at your') }} <em class="italic" style="color: #D4AE52">{{ __('service') }}</em>
            </h1>

            {{-- Paragraph --}}
            <p class="mb-12 text-sm font-light leading-[1.9] text-white/65 opacity-0 animate-fade-right"
               style="max-width: 460px; animation-delay: .8s">
                {{ __('Residential and commercial rentals, long-term and short-term — Sahoome guides you through every step of your real estate project.') }}
            </p>

            {{-- CTAs --}}
            <div class="flex items-center gap-4 opacity-0 animate-fade-right" style="animation-delay: 1s">
                <a href="{{ route('properties.index') }}" wire:navigate
                   class="inline-block text-[0.72rem] font-semibold uppercase tracking-[.16em] text-white transition-all duration-200 hover:-translate-y-0.5 hover:bg-white hover:text-ink"
                   style="background: #B8962E; padding: 16px 36px; border-radius: 2px">
                    {{ __('Discover our properties') }}
                </a>
                <a href="#contact"
                   class="flex items-center gap-2.5 text-[0.72rem] font-normal uppercase tracking-[.12em] text-white/80 transition-all duration-200 hover:text-white hover:gap-4">
                    {{ __('Contact us') }} <span>→</span>
                </a>
                @guest
                <a href="{{ route('register') }}" wire:navigate
                   class="hidden lg:inline text-[0.68rem] font-medium uppercase tracking-[.12em] text-white/55 transition hover:text-white/85">
                    {{ __('List my property →') }}
                </a>
                @endguest
            </div>
        </div>

        {{-- ── Slide indicators ───────────────────────────────────────────── --}}
        <div class="absolute bottom-10 left-[6%] z-20 flex items-center gap-2.5">
            <template x-for="i in [0, 1, 2]" :key="i">
                <button @click="goSlide(i)"
                    class="h-[1.5px] cursor-pointer transition-all duration-300"
                    :style="current === i
                        ? 'width: 48px; background: #B8962E'
                        : 'width: 28px; background: rgba(255,255,255,0.3)'">
                </button>
            </template>
        </div>

        {{-- ── Scroll hint ─────────────────────────────────────────────────── --}}
        <div class="absolute bottom-10 right-[6%] z-20 flex flex-col items-center gap-2 opacity-60">
            <span class="text-[0.6rem] uppercase tracking-[.2em] text-white" style="writing-mode: vertical-rl">
                {{ __('Scroll') }}
            </span>
            <div class="w-px animate-scroll-down" style="height: 50px; background: linear-gradient(to bottom, white, transparent)"></div>
        </div>

    </section>

    {{-- ══════════════════════════════════════════════════════════════════════
         CATEGORY BAND — dark ink2 bg, 4 service category tiles
    ══════════════════════════════════════════════════════════════════════════ --}}
    <div style="background: #1E2330; padding: 60px 6%">
        <div class="mx-auto" style="max-width: 1200px">
            <span class="reveal block text-[0.62rem] font-semibold tracking-[.22em] uppercase text-gold mb-8">
                {{ __('What are you looking for?') }}
            </span>
            <div class="grid grid-cols-2 lg:grid-cols-4" style="gap: 2px; background: rgba(255,255,255,.08)">

                {{-- 01 · Rental --}}
                <a href="{{ route('properties.index') }}" wire:navigate
                   class="group flex flex-col no-underline transition-all duration-300 border-b-[3px] border-transparent hover:border-gold"
                   style="background: #1E2330; padding: 36px 28px; gap: 0; transition: background .3s, border-color .3s">
                    <div class="font-serif text-[2.2rem] font-light leading-none mb-4 transition-colors duration-300 group-hover:text-gold-light"
                         style="color: rgba(184,150,46,.25)">01</div>
                    <div class="font-serif text-[1.3rem] font-light text-white mb-2.5 leading-[1.2]">{{ __('Rental') }}</div>
                    <div class="text-[0.78rem] font-light leading-[1.7] mb-[22px] flex-1"
                         style="color: rgba(255,255,255,.38)">{{ __('Apartments, villas, studios and offices for long-term rent') }}</div>
                    <div class="text-[0.62rem] font-semibold tracking-[.14em] uppercase text-gold group-hover:tracking-[.2em] transition-all duration-200">{{ __('Explore') }} →</div>
                </a>

                {{-- 02 · Commercial Rental --}}
                <a href="{{ route('properties.index') }}?propertyType=commercial" wire:navigate
                   class="group flex flex-col no-underline transition-all duration-300 border-b-[3px] border-transparent hover:border-gold"
                   style="background: #1E2330; padding: 36px 28px; gap: 0; transition: background .3s, border-color .3s">
                    <div class="font-serif text-[2.2rem] font-light leading-none mb-4 transition-colors duration-300 group-hover:text-gold-light"
                         style="color: rgba(184,150,46,.25)">02</div>
                    <div class="font-serif text-[1.3rem] font-light text-white mb-2.5 leading-[1.2]">{{ __('Commercial') }}</div>
                    <div class="text-[0.78rem] font-light leading-[1.7] mb-[22px] flex-1"
                         style="color: rgba(255,255,255,.38)">{{ __('Offices, showrooms, retail spaces and commercial premises for rent') }}</div>
                    <div class="text-[0.62rem] font-semibold tracking-[.14em] uppercase text-gold group-hover:tracking-[.2em] transition-all duration-200">{{ __('Explore') }} →</div>
                </a>

                {{-- 03 · Short-term commercial --}}
                <a href="{{ route('properties.index') }}?isShortTerm=1&propertyType=commercial" wire:navigate
                   class="group flex flex-col no-underline transition-all duration-300 border-b-[3px] border-transparent hover:border-gold"
                   style="background: #1E2330; padding: 36px 28px; gap: 0; transition: background .3s, border-color .3s">
                    <div class="font-serif text-[2.2rem] font-light leading-none mb-4 transition-colors duration-300 group-hover:text-gold-light"
                         style="color: rgba(184,150,46,.25)">03</div>
                    <div class="font-serif text-[1.3rem] font-light text-white mb-2.5 leading-[1.2]">{{ __('Short-term Spaces') }}</div>
                    <div class="text-[0.78rem] font-light leading-[1.7] mb-[22px] flex-1"
                         style="color: rgba(255,255,255,.38)">{{ __('Pop-up stores, showrooms and flexible event spaces') }}</div>
                    <div class="text-[0.62rem] font-semibold tracking-[.14em] uppercase text-gold group-hover:tracking-[.2em] transition-all duration-200">{{ __('Explore') }} →</div>
                </a>

                {{-- 04 · Short-term residential --}}
                <a href="{{ route('properties.index') }}?isShortTerm=1" wire:navigate
                   class="group flex flex-col no-underline transition-all duration-300 border-b-[3px] border-transparent hover:border-gold"
                   style="background: #1E2330; padding: 36px 28px; gap: 0; transition: background .3s, border-color .3s">
                    <div class="font-serif text-[2.2rem] font-light leading-none mb-4 transition-colors duration-300 group-hover:text-gold-light"
                         style="color: rgba(184,150,46,.25)">04</div>
                    <div class="font-serif text-[1.3rem] font-light text-white mb-2.5 leading-[1.2]">{{ __('Short-term Rental') }}</div>
                    <div class="text-[0.78rem] font-light leading-[1.7] mb-[22px] flex-1"
                         style="color: rgba(255,255,255,.38)">{{ __('Furnished accommodation for short to medium-term stays') }}</div>
                    <div class="text-[0.62rem] font-semibold tracking-[.14em] uppercase text-gold group-hover:tracking-[.2em] transition-all duration-200">{{ __('Explore') }} →</div>
                </a>

            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════════════
         FEATURED PROPERTIES — cream bg, 3-col Alicia card grid, dynamic
    ══════════════════════════════════════════════════════════════════════════ --}}
    <section style="background: #FAFAF7; border-top: 1px solid #E8E2D8; padding: 90px 6%" id="properties">
        <div class="mx-auto" style="max-width: 1200px">

            {{-- Header --}}
            <div class="reveal flex items-end justify-between mb-14 flex-wrap gap-5">
                <div>
                    <div class="flex items-center gap-4 mb-4">
                        <div class="h-px w-8 bg-gold shrink-0"></div>
                        <span class="text-[0.62rem] font-semibold tracking-[.18em] uppercase text-gold">{{ __('Our selection') }}</span>
                    </div>
                    <h2 class="font-serif text-[clamp(2rem,3.5vw,3.2rem)] font-light text-ink leading-[1.15]">
                        {{ __('Featured') }} <em class="italic">{{ __('properties') }}</em>
                    </h2>
                </div>
                <a href="{{ route('properties.index') }}" wire:navigate
                   class="group flex items-center gap-2 text-[0.65rem] font-semibold tracking-[.16em] uppercase text-gold no-underline transition-all duration-200 hover:gap-3.5 hover:text-ink">
                    {{ __('All properties') }} →
                </a>
            </div>

            @if($popularProperties->isEmpty())
                <p class="text-sm font-light text-muted text-center py-12">{{ __('No featured properties yet. Check back soon!') }}</p>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3" style="gap: 2px; background: #E8E2D8">
                    @foreach($popularProperties->take(3) as $property)
                        @php
                            $cardReveal = ['rd1', 'rd2', 'rd3'][$loop->index] ?? 'rd3';
                            $img        = $property->images->first();
                            $rating     = $property->reviews_avg_rating ? round((float) $property->reviews_avg_rating, 1) : null;
                            $price      = $property->monthly_rent ?? $property->weekly_rent ?? $property->daily_rent;
                            $priceLabel = $property->monthly_rent ? __('/mo') : ($property->weekly_rent ? __('/wk') : __('/day'));
                            $isOccupied = $property->contracts->isNotEmpty();
                            $currencySymbol = match($property->currency ?? 'USD') {
                                'EUR' => '€', 'GBP' => '£', 'SAR' => 'SAR', default => '$',
                            };
                        @endphp
                        <a href="{{ route('properties.show', $property->slug) }}" wire:navigate
                           class="reveal {{ $cardReveal }} group block bg-white overflow-hidden no-underline relative transition-transform duration-[400ms] hover:scale-[.985] hover:shadow-[0_20px_60px_rgba(0,0,0,.12)] hover:z-10">

                            {{-- Image --}}
                            <div class="relative overflow-hidden" style="height: 260px">
                                @if($img)
                                    <img src="{{ $img->url() }}" alt="{{ $property->title }}"
                                         class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-[1.06]" />
                                @else
                                    <div class="w-full h-full flex items-center justify-center" style="background: #F4EFE8">
                                        <svg class="size-14 text-[#E8E2D8]" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/>
                                        </svg>
                                    </div>
                                @endif
                                {{-- Status label --}}
                                <div class="absolute top-0 left-0 px-3.5 py-2.5 text-[0.58rem] font-semibold tracking-[.14em] uppercase"
                                     style="{{ $isOccupied ? 'background: #B8962E; color: white' : 'background: #1E2330; color: white' }}">
                                    {{ $isOccupied ? __('Occupied') : __('Available') }}
                                </div>
                                {{-- Fav --}}
                                <button onclick="event.preventDefault()"
                                        class="absolute bottom-3.5 right-3.5 flex items-center justify-center size-[34px] rounded-full bg-white/90 shadow-md text-zinc-400 transition-transform duration-200 hover:scale-110">
                                    <svg class="size-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/>
                                    </svg>
                                </button>
                            </div>

                            {{-- Body --}}
                            <div class="p-6">
                                <div class="text-[0.58rem] font-semibold tracking-[.14em] uppercase text-gold mb-1.5">
                                    {{ __('Rental') }}
                                </div>
                                <h3 class="font-serif text-[1.15rem] font-normal text-ink leading-[1.3] mb-1.5 line-clamp-2">
                                    {{ $property->title }}
                                </h3>
                                <p class="text-[0.74rem] font-light text-muted mb-3.5 tracking-[.02em] truncate">
                                    {{ implode(', ', array_filter([$property->address_line_1, $property->city])) ?: __('Location TBA') }}
                                </p>
                                @if($property->size_sqm)
                                    <div class="flex items-center gap-4 mb-4">
                                        <span class="text-[0.65rem] font-medium tracking-[.06em] text-muted flex items-center gap-1.5">
                                            <svg class="size-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3.75v4.5m0-4.5h4.5m-4.5 0L9 9M3.75 20.25v-4.5m0 4.5h4.5m-4.5 0L9 15M20.25 3.75h-4.5m4.5 0v4.5m0-4.5L15 9m5.25 11.25h-4.5m4.5 0v-4.5m0 4.5L15 15"/></svg>
                                            {{ number_format($property->size_sqm) }} m²
                                        </span>
                                        @if($rating)
                                            <span class="text-[0.65rem] font-medium tracking-[.06em] text-muted flex items-center gap-1">
                                                <svg class="size-3 text-gold" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd"/></svg>
                                                {{ $rating }}
                                            </span>
                                        @endif
                                    </div>
                                @endif
                                <div class="flex items-center justify-between pt-3.5 border-t border-[#E8E2D8]">
                                    @if($price)
                                        <div class="font-serif text-[1.3rem] font-normal text-ink leading-none">
                                            {{ $currencySymbol }}{{ number_format($price) }}
                                            <sub class="font-sans text-[0.62rem] font-light text-muted not-italic">{{ $priceLabel }}</sub>
                                        </div>
                                    @endif
                                    <span class="text-[0.6rem] tracking-[.1em] text-muted uppercase">
                                        #{{ str_pad($property->id, 4, '0', STR_PAD_LEFT) }}
                                    </span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- ══════════════════════════════════════════════════════════════════════
         SERVICES DUO — nude bg, 2 text cards for Location + Vente
    ══════════════════════════════════════════════════════════════════════════ --}}
    <section style="background: #F4EFE8; border-top: 1px solid #E8E2D8; padding: 90px 6%" id="about">
        <div class="mx-auto" style="max-width: 1200px">

            {{-- Header --}}
            <div class="reveal mb-14">
                <div class="flex items-center gap-4 mb-4">
                    <div class="h-px w-8 bg-gold shrink-0"></div>
                    <span class="text-[0.62rem] font-semibold tracking-[.18em] uppercase text-gold">{{ __('Our other services') }}</span>
                </div>
                <h2 class="font-serif text-[clamp(2rem,3.5vw,3.2rem)] font-light text-ink leading-[1.15] mb-4">
                    {{ __('Rental & Property') }}<br>{{ __('services in') }} <em class="italic">{{ __('Abidjan') }}</em>
                </h2>
                <p class="text-[0.88rem] font-light text-muted leading-[1.9]" style="max-width: 480px">
                    {{ __('Apartments, villas, studios and commercial spaces — Sahoome guides you to your ideal rental.') }}
                </p>
            </div>

            {{-- 2-col cards --}}
            <div class="grid grid-cols-1 lg:grid-cols-2" style="gap: 2px; background: #E8E2D8">

                {{-- Rental Card --}}
                <a href="{{ route('properties.index') }}" wire:navigate
                   class="reveal rd1 group block bg-white no-underline border-t-[3px] border-[#E8E2D8] transition-all duration-300 hover:border-gold hover:opacity-100"
                   style="padding: 28px 28px 32px; opacity: 1">
                    <div class="font-serif text-[2rem] font-light text-[#F5EDD6] leading-none mb-2.5 transition-colors duration-300 group-hover:text-gold-light">01</div>
                    <div class="font-serif text-[1.4rem] font-light text-ink mb-2.5">{{ __('Residential') }} <em class="italic">{{ __('Rental') }}</em></div>
                    <p class="text-[0.82rem] font-light text-muted leading-[1.8] mb-4">
                        {{ __('Furnished apartments, villas, studios and commercial premises for long-term rent. Verified properties across all areas.') }}
                    </p>
                    <div class="flex flex-wrap gap-1.5 mb-5">
                        <span class="text-[0.62rem] font-medium tracking-[.08em] text-muted px-2.5 py-1 border border-[#E8E2D8]" style="background: #F4EFE8">{{ __('Apartments') }}</span>
                        <span class="text-[0.62rem] font-medium tracking-[.08em] text-muted px-2.5 py-1 border border-[#E8E2D8]" style="background: #F4EFE8">{{ __('Villas') }}</span>
                        <span class="text-[0.62rem] font-medium tracking-[.08em] text-muted px-2.5 py-1 border border-[#E8E2D8]" style="background: #F4EFE8">{{ __('Studios') }}</span>
                        <span class="text-[0.62rem] font-medium tracking-[.08em] text-muted px-2.5 py-1 border border-[#E8E2D8]" style="background: #F4EFE8">{{ __('Offices') }}</span>
                    </div>
                    <div class="text-[0.65rem] font-semibold tracking-[.14em] uppercase text-gold">{{ __('Explore') }} →</div>
                </a>

                {{-- Commercial Rental Card --}}
                <a href="{{ route('properties.index') }}?propertyType=commercial" wire:navigate
                   class="reveal rd2 group block bg-white no-underline border-t-[3px] border-[#E8E2D8] transition-all duration-300 hover:border-gold hover:opacity-100"
                   style="padding: 28px 28px 32px; opacity: 1">
                    <div class="font-serif text-[2rem] font-light text-[#F5EDD6] leading-none mb-2.5 transition-colors duration-300 group-hover:text-gold-light">02</div>
                    <div class="font-serif text-[1.4rem] font-light text-ink mb-2.5"><em class="italic">{{ __('Commercial') }}</em> {{ __('Rental') }}</div>
                    <p class="text-[0.82rem] font-light text-muted leading-[1.8] mb-4">
                        {{ __('Offices, showrooms, retail spaces and commercial premises available for long-term or short-term rent.') }}
                    </p>
                    <div class="flex flex-wrap gap-1.5 mb-5">
                        <span class="text-[0.62rem] font-medium tracking-[.08em] text-muted px-2.5 py-1 border border-[#E8E2D8]" style="background: #F4EFE8">{{ __('Offices') }}</span>
                        <span class="text-[0.62rem] font-medium tracking-[.08em] text-muted px-2.5 py-1 border border-[#E8E2D8]" style="background: #F4EFE8">{{ __('Showrooms') }}</span>
                        <span class="text-[0.62rem] font-medium tracking-[.08em] text-muted px-2.5 py-1 border border-[#E8E2D8]" style="background: #F4EFE8">{{ __('Retail') }}</span>
                        <span class="text-[0.62rem] font-medium tracking-[.08em] text-muted px-2.5 py-1 border border-[#E8E2D8]" style="background: #F4EFE8">{{ __('Warehouses') }}</span>
                    </div>
                    <div class="text-[0.65rem] font-semibold tracking-[.14em] uppercase text-gold">{{ __('Explore') }} →</div>
                </a>

            </div>
        </div>
    </section>

    {{-- ══════════════════════════════════════════════════════════════════════
         ENGAGEMENTS — dark ink bg, 4 commitment items
    ══════════════════════════════════════════════════════════════════════════ --}}
    <section style="background: #111318; padding: 90px 6%">
        <div class="mx-auto" style="max-width: 1100px">

            {{-- Header --}}
            <div class="reveal mb-14">
                <div class="flex items-center gap-4 mb-4">
                    <div class="h-px w-8 shrink-0" style="background: #D4AE52"></div>
                    <span class="text-[0.62rem] font-semibold tracking-[.18em] uppercase" style="color: #D4AE52">{{ __('Our commitments') }}</span>
                </div>
                <h2 class="font-serif text-[clamp(2rem,3.5vw,3.2rem)] font-light text-white leading-[1.15]">
                    {{ __('Uncompromising') }}<br><em class="italic" style="color: #D4AE52">{{ __('standards') }}</em>
                </h2>
            </div>

            {{-- 2×2 grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2" style="gap: 2px; background: rgba(255,255,255,.08)">

                @php $engagements = [
                    ['num' => '01', 'title' => __('Field-verified listings'), 'desc' => __('Every property is physically inspected by our team before publication. Zero fake listings on Sahoome.')],
                    ['num' => '02', 'title' => __('Response guaranteed within 24h'), 'desc' => __('Every viewing request or inquiry receives a response from our team within 24 hours.')],
                    ['num' => '03', 'title' => __('Secure transactions'), 'desc' => __('Protected payments and verified contracts. Every transaction is supervised to protect all parties.')],
                    ['num' => '04', 'title' => __('Trusted intermediary'), 'desc' => __('Sahoome acts exclusively as a marketplace — full transparency at every step of the process.')],
                ]; @endphp

                @foreach($engagements as $item)
                    <div class="reveal {{ 'rd'.($loop->index + 1) }} group flex flex-col border-t-[2px] border-transparent hover:border-gold transition-all duration-300"
                         style="background: #111318; padding: 36px 32px; transition: border-color .3s, background .3s">
                        <div class="font-serif text-[0.7rem] font-light text-gold tracking-[.2em] mb-3.5">{{ $item['num'] }}</div>
                        <div class="text-[0.84rem] font-semibold tracking-[.04em] text-white mb-2.5">{{ $item['title'] }}</div>
                        <p class="text-[0.82rem] font-light leading-[1.75]" style="color: rgba(255,255,255,.42)">{{ $item['desc'] }}</p>
                    </div>
                @endforeach

            </div>
        </div>
    </section>

    {{-- ══════════════════════════════════════════════════════════════════════
         GUIDES & INSIGHTS — cream bg, 3 article tiles (dynamic from DB)
    ══════════════════════════════════════════════════════════════════════════ --}}
    <section style="background: #FAFAF7; border-top: 1px solid #E8E2D8; padding: 90px 6%">
        <div class="mx-auto" style="max-width: 1200px">

            {{-- Header --}}
            <div class="reveal flex items-end justify-between mb-12 flex-wrap gap-4">
                <div>
                    <div class="flex items-center gap-4 mb-4">
                        <div class="h-px w-8 bg-gold shrink-0"></div>
                        <span class="text-[0.62rem] font-semibold tracking-[.18em] uppercase text-gold">{{ __('Guides & Insights') }}</span>
                    </div>
                    <h2 class="font-serif text-[clamp(2rem,3.5vw,3.2rem)] font-light text-ink leading-[1.15]">
                        {{ __('Real estate') }}<br>{{ __('in') }} <em class="italic">{{ __('real time') }}</em>
                    </h2>
                </div>
                <a href="{{ route('articles.index') }}" wire:navigate
                   class="flex items-center gap-2 text-[0.65rem] font-semibold tracking-[.16em] uppercase text-gold no-underline transition-all duration-200 hover:gap-3.5">
                    {{ __('View all articles') }} →
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3" style="gap: 2px; background: #E8E2D8">
                @forelse($latestArticles as $article)
                    <a href="{{ route('articles.show', $article->slug) }}" wire:navigate
                       class="reveal rd{{ $loop->index + 1 }} group block bg-white overflow-hidden no-underline transition-opacity duration-300 hover:opacity-[.88]">
                        <div class="overflow-hidden" style="height: 190px; background: #F4EFE8">
                            @if($article->cover_image_url)
                                <img src="{{ $article->cover_image_url }}" alt="{{ $article->title }}"
                                     class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-[1.05]" />
                            @endif
                        </div>
                        <div style="padding: 20px 22px 24px">
                            <div class="text-[0.58rem] font-semibold tracking-[.16em] uppercase text-gold mb-1">{{ $article->category }}</div>
                            <div class="text-[0.62rem] font-light text-muted mb-2" style="letter-spacing: .04em">
                                {{ $article->published_at?->translatedFormat('F Y') }}
                            </div>
                            <div class="font-serif text-[1.05rem] font-normal text-ink leading-[1.35] mb-2.5">{{ $article->title }}</div>
                            <p class="text-[0.79rem] font-light text-muted leading-[1.75] mb-3 line-clamp-2">{{ $article->excerpt }}</p>
                            <div class="flex items-center gap-2 text-[0.62rem] font-semibold tracking-[.14em] uppercase text-gold transition-all duration-200 group-hover:gap-3">
                                {{ __('Read') }} →
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="col-span-3 py-12 text-center text-muted text-sm">{{ __('No articles yet.') }}</div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- ══════════════════════════════════════════════════════════════════════
         STATS FINAL — dark ink bg, 4 counters (dynamic from DB)
    ══════════════════════════════════════════════════════════════════════════ --}}
    <div style="background: #111318; border-top: 1px solid rgba(255,255,255,.08)"
         x-data="{
             started: false,
             counts: [0, 0, 0, 0],
             targets: [{{ $totalActiveProperties }}, {{ $totalLandlords }}, {{ $totalContracts }}, 98],
             init() {
                 const io = new IntersectionObserver(([e]) => {
                     if (e.isIntersecting) { this.start(); io.disconnect(); }
                 }, { threshold: 0.3 });
                 io.observe(this.$el);
             },
             start() {
                 if (this.started) return;
                 this.started = true;
                 this.targets.forEach((target, i) => {
                     const step = Math.max(1, Math.ceil(target / 50));
                     const iv = setInterval(() => {
                         this.counts[i] = Math.min(this.counts[i] + step, target);
                         if (this.counts[i] >= target) clearInterval(iv);
                     }, 30);
                 });
             }
         }">
        <div class="flex flex-wrap" style="max-width: 1200px; margin: 0 auto">

            @php $statLabels = [__('Properties available'), __('Partner landlords'), __('Completed transactions'), __('% satisfaction')]; @endphp
            @foreach($statLabels as $idx => $label)
                <div class="flex-1 text-center border-r border-[rgba(255,255,255,.06)] last:border-r-0" style="padding: 52px 20px; min-width: 150px">
                    <div class="font-serif text-[3rem] font-light leading-none mb-2" style="color: #D4AE52">
                        <span x-text="counts[{{ $idx }}].toLocaleString() + '{{ $idx === 3 ? '%' : '+' }}'">—</span>
                    </div>
                    <div class="text-[0.58rem] font-semibold tracking-[.16em] uppercase mt-2" style="color: rgba(255,255,255,.35)">
                        {{ $label }}
                    </div>
                </div>
            @endforeach

        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════════════
         LANDLORD BANNER — nude bg, CTA for property owners
    ══════════════════════════════════════════════════════════════════════════ --}}
    <section style="background: #F4EFE8; border-top: 1px solid #E8E2D8; border-bottom: 1px solid #E8E2D8; padding: 52px 6%">
        <div class="mx-auto flex items-center justify-between gap-7 flex-wrap" style="max-width: 1100px">
            <div>
                <div class="flex items-center gap-3 mb-2.5">
                    <div class="h-px w-6 shrink-0" style="background: #D4AE52"></div>
                    <span class="text-[0.62rem] font-semibold tracking-[.18em] uppercase" style="color: #D4AE52">{{ __('Are you a property owner?') }}</span>
                </div>
                <h3 class="font-serif text-[1.6rem] font-light text-ink mb-2">
                    {{ __('List your property on') }} <em class="italic">{{ __('Sahoome') }}</em>
                </h3>
                <p class="text-[0.83rem] font-light text-muted leading-[1.8]" style="max-width: 460px">
                    {{ __('Join our network of verified property owners. Residential, commercial, short-term or long-term — we connect you to the right clients.') }}
                </p>
            </div>
            @auth
                <a href="{{ route('landlord.dashboard') }}" wire:navigate
                   class="shrink-0 text-[0.65rem] font-semibold tracking-[.18em] uppercase text-white no-underline px-7 py-3.5 transition-colors duration-200 hover:bg-gold"
                   style="background: #111318; white-space: nowrap">
                    {{ __('Go to my dashboard') }} →
                </a>
            @else
                <a href="{{ route('register') }}" wire:navigate
                   class="shrink-0 text-[0.65rem] font-semibold tracking-[.18em] uppercase text-white no-underline px-7 py-3.5 transition-colors duration-200 hover:bg-gold"
                   style="background: #111318; white-space: nowrap">
                    {{ __('List my property') }} →
                </a>
            @endauth
        </div>
    </section>

    {{-- ══════════════════════════════════════════════════════════════════════
         CONTACT FORM — white bg, redesigned with Alicia aesthetic
    ══════════════════════════════════════════════════════════════════════════ --}}
    <section style="background: white; padding: 90px 6%" id="contact">
        <div class="mx-auto grid grid-cols-1 lg:grid-cols-2 items-start gap-16" style="max-width: 1100px">

            {{-- Left: form --}}
            <div>
                <div class="flex items-center gap-4 mb-5">
                    <div class="h-px w-8 bg-gold shrink-0"></div>
                    <span class="text-[0.62rem] font-semibold tracking-[.18em] uppercase text-gold">{{ __('Get in touch') }}</span>
                </div>
                <h2 class="font-serif text-[clamp(1.8rem,3vw,2.8rem)] font-light text-ink leading-[1.15] mb-4">
                    {{ __('Send us a') }} <em class="italic">{{ __('message') }}</em>
                </h2>
                <p class="text-[0.88rem] font-light text-muted leading-[1.9] mb-10" style="max-width: 400px">
                    {{ __('Our team responds to every request within 24 hours.') }}
                </p>

                @if($messageSent)
                    <div class="border-l-4 border-gold px-5 py-4 mb-8 text-sm font-light text-ink" style="background: #F5EDD6">
                        {{ __("Thank you! Your message has been sent. We'll be in touch shortly.") }}
                    </div>
                @endif

                <form wire:submit="sendMessage" class="flex flex-col gap-5">

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div class="flex flex-col gap-2">
                            <label class="text-[0.62rem] font-semibold tracking-[.14em] uppercase text-gold">{{ __('Full name') }}</label>
                            <input wire:model="contactName" type="text" placeholder="{{ __('Your name') }}"
                                   class="border-b border-[#E8E2D8] bg-transparent py-3 text-sm font-light text-ink placeholder-[#C4BDB4] outline-none focus:border-gold transition-colors duration-200" />
                            @error('contactName') <span class="text-xs text-red-500 font-light">{{ $message }}</span> @enderror
                        </div>
                        <div class="flex flex-col gap-2">
                            <label class="text-[0.62rem] font-semibold tracking-[.14em] uppercase text-gold">{{ __('Email') }}</label>
                            <input wire:model="contactEmail" type="email" placeholder="{{ __('your@email.com') }}"
                                   class="border-b border-[#E8E2D8] bg-transparent py-3 text-sm font-light text-ink placeholder-[#C4BDB4] outline-none focus:border-gold transition-colors duration-200" />
                            @error('contactEmail') <span class="text-xs text-red-500 font-light">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="flex flex-col gap-2">
                        <label class="text-[0.62rem] font-semibold tracking-[.14em] uppercase text-gold">{{ __('Phone') }} <span class="text-muted normal-case tracking-normal">({{ __('optional') }})</span></label>
                        <input wire:model="contactPhone" type="tel" placeholder="{{ __('Your phone number') }}"
                               class="border-b border-[#E8E2D8] bg-transparent py-3 text-sm font-light text-ink placeholder-[#C4BDB4] outline-none focus:border-gold transition-colors duration-200" />
                    </div>

                    <div class="flex flex-col gap-2">
                        <label class="text-[0.62rem] font-semibold tracking-[.14em] uppercase text-gold">{{ __('Message') }}</label>
                        <textarea wire:model="contactMessage" rows="5" placeholder="{{ __('Tell us about your project...') }}"
                                  class="border-b border-[#E8E2D8] bg-transparent py-3 text-sm font-light text-ink placeholder-[#C4BDB4] outline-none focus:border-gold transition-colors duration-200 resize-none"></textarea>
                        @error('contactMessage') <span class="text-xs text-red-500 font-light">{{ $message }}</span> @enderror
                    </div>

                    <div class="pt-3">
                        <button type="submit"
                                class="text-[0.65rem] font-semibold tracking-[.18em] uppercase text-white px-9 py-4 transition-colors duration-200 hover:bg-gold-light disabled:opacity-60"
                                style="background: #B8962E; border-radius: 1px"
                                wire:loading.attr="disabled">
                            <span wire:loading.remove>{{ __('Send message') }}</span>
                            <span wire:loading>{{ __('Sending…') }}</span>
                        </button>
                    </div>
                </form>
            </div>

            {{-- Right: contact details from SiteSettings --}}
            <div class="hidden lg:flex flex-col gap-10 pt-24">
                <div>
                    <div class="text-[0.62rem] font-semibold tracking-[.18em] uppercase text-gold mb-5">{{ __('Contact information') }}</div>
                    <ul class="flex flex-col gap-5">
                        <li class="flex items-start gap-4">
                            <div class="mt-0.5 h-px w-5 shrink-0 bg-gold"></div>
                            <div>
                                <div class="text-[0.68rem] font-semibold tracking-[.1em] uppercase text-ink mb-1">{{ __('Email') }}</div>
                                <a href="mailto:{{ \App\Models\SiteSetting::get('contact_email', 'sahoome@gmail.com') }}"
                                   class="text-sm font-light text-muted no-underline hover:text-gold transition-colors duration-200">
                                    {{ \App\Models\SiteSetting::get('contact_email', 'sahoome@gmail.com') }}
                                </a>
                            </div>
                        </li>
                        <li class="flex items-start gap-4">
                            <div class="mt-0.5 h-px w-5 shrink-0 bg-gold"></div>
                            <div>
                                <div class="text-[0.68rem] font-semibold tracking-[.1em] uppercase text-ink mb-1">{{ __('Phone') }}</div>
                                <a href="tel:{{ \App\Models\SiteSetting::get('contact_phone', '+996-564-648') }}"
                                   class="text-sm font-light text-muted no-underline hover:text-gold transition-colors duration-200">
                                    {{ \App\Models\SiteSetting::get('contact_phone', '+996-564-648') }}
                                </a>
                            </div>
                        </li>
                        <li class="flex items-start gap-4">
                            <div class="mt-0.5 h-px w-5 shrink-0 bg-gold"></div>
                            <div>
                                <div class="text-[0.68rem] font-semibold tracking-[.1em] uppercase text-ink mb-1">{{ __('Address') }}</div>
                                <span class="text-sm font-light text-muted">
                                    {{ \App\Models\SiteSetting::get('contact_address', '321 Market St, Los Angeles, CA') }}
                                </span>
                            </div>
                        </li>
                    </ul>
                </div>
                {{-- Social links --}}
                <div>
                    <div class="text-[0.62rem] font-semibold tracking-[.18em] uppercase text-gold mb-5">{{ __('Follow us') }}</div>
                    <div class="flex gap-2.5">
                        <a href="{{ \App\Models\SiteSetting::get('facebook_url', '#') }}"
                           class="flex size-[34px] items-center justify-center border border-[#E8E2D8] text-muted text-xs no-underline transition-all duration-200 hover:border-gold hover:text-gold">f</a>
                        <a href="{{ \App\Models\SiteSetting::get('instagram_url', '#') }}"
                           class="flex size-[34px] items-center justify-center border border-[#E8E2D8] text-muted text-xs no-underline transition-all duration-200 hover:border-gold hover:text-gold">ig</a>
                    </div>
                </div>
            </div>

        </div>
    </section>

    {{-- ══ WHATSAPP FLOATING BUTTON ══════════════════════════════════════════ --}}
    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', \App\Models\SiteSetting::get('contact_phone', '996564648')) }}"
       target="_blank" rel="noopener"
       class="fixed z-[400] flex items-center justify-center transition-transform duration-200 hover:scale-[1.08] group"
       style="bottom:28px;right:28px;width:54px;height:54px;background:#25D366;border-radius:50%;box-shadow:0 4px 20px rgba(37,211,102,.4);text-decoration:none">
        <span class="absolute whitespace-nowrap px-3 py-1.5 text-[0.7rem] font-medium tracking-[.08em] text-white opacity-0 group-hover:opacity-100 transition-all duration-200"
              style="right:64px;background:#111318;border-radius:2px">{{ __('Contact on WhatsApp') }}</span>
        <svg viewBox="0 0 24 24" style="width:26px;height:26px;fill:#fff" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
    </a>

    @include('partials.public-footer')

    {{-- ══ SCROLL REVEAL + CURSOR SCRIPTS ════════════════════════════════════ --}}
    @script
    <script>
    (function () {
        /* ── Custom gold cursor ── */
        var cur  = document.getElementById('alicia-cursor');
        var ring = document.getElementById('alicia-cursor-ring');
        if (cur && window.matchMedia('(pointer:fine)').matches) {
            document.body.classList.add('has-custom-cursor');
            document.addEventListener('livewire:navigating', function () { document.body.classList.remove('has-custom-cursor'); });
            var mx = 0, my = 0, rx = 0, ry = 0;
            document.addEventListener('mousemove', function (e) {
                mx = e.clientX; my = e.clientY;
                cur.style.left = mx + 'px'; cur.style.top = my + 'px';
            });
            (function loop() {
                rx += (mx - rx) * 0.12; ry += (my - ry) * 0.12;
                ring.style.left = rx + 'px'; ring.style.top = ry + 'px';
                requestAnimationFrame(loop);
            })();
            document.querySelectorAll('a, button, [role="button"]').forEach(function (el) {
                el.addEventListener('mouseenter', function () { cur.style.width = '14px'; cur.style.height = '14px'; ring.style.width = '52px'; ring.style.height = '52px'; ring.style.opacity = '.18'; });
                el.addEventListener('mouseleave', function () { cur.style.width = '8px';  cur.style.height = '8px';  ring.style.width = '36px'; ring.style.height = '36px'; ring.style.opacity = '.35'; });
            });
        }

        /* ── Scroll reveal ── */
        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (e) {
                if (e.isIntersecting) { e.target.classList.add('visible'); io.unobserve(e.target); }
            });
        }, { threshold: 0.12 });
        document.querySelectorAll('.reveal').forEach(function (el) { io.observe(el); });
    })();
    </script>
    @endscript

</div>
