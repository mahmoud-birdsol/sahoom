<div>

    {{-- ══ HERO (full-screen with transparent navbar overlaid) ═══════════════ --}}
    <section class="relative min-h-screen overflow-hidden">

        {{-- Background image --}}
        <div class="absolute inset-0 bg-cover bg-center bg-no-repeat"
             style="background-image: url('https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=1920&q=80')"></div>

        {{-- Teal overlay --}}
        <div class="absolute inset-0 bg-teal-900/70"></div>

        {{-- Soft bottom gradient so content reads cleanly --}}
        <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent"></div>

        {{-- Transparent navbar floating over the hero --}}
        <x-public.navbar :transparent="true" />

        {{-- Hero content — anchored to the bottom-left --}}
        <div class="relative z-10 flex min-h-screen flex-col justify-end pb-24 pt-20">
            <div class="mx-auto w-full max-w-7xl px-6 lg:px-10">
                <div class="max-w-2xl">
                    <span class="inline-block rounded-full bg-white/15 px-4 py-1.5 text-xs font-semibold uppercase tracking-widest text-white/90 backdrop-blur-sm">
                        {{ __('Commercial Space Rental') }}
                    </span>
                    <h1 class="mt-4 text-4xl font-extrabold leading-tight text-white lg:text-6xl">
                        {{ __('The Ideal Space') }}<br>
                        <em class="not-italic text-teal-300">{{ __('for Your Projects') }}</em>
                    </h1>
                    <p class="mt-5 max-w-lg text-sm leading-relaxed text-white/75">
                        {{ __('Sahoome connects merchants and property owners for flexible commercial space rental — pop-up stores, showrooms, events.') }}
                    </p>
                    <div class="mt-8 flex flex-wrap items-center gap-4">
                        <a href="{{ route('properties.index') }}" wire:navigate
                           class="inline-flex items-center gap-2 rounded-xl bg-teal-500 px-7 py-3 text-sm font-bold text-white transition hover:bg-teal-400">
                            {{ __('Explore Spaces') }}
                            <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                        </a>
                        <a href="{{ route('home') }}#how-it-works"
                           class="inline-flex items-center gap-2 rounded-xl border border-white/40 px-7 py-3 text-sm font-semibold text-white backdrop-blur-sm transition hover:bg-white/10">
                            {{ __('How it works') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </section>

    {{-- ══ STATS BAR ════════════════════════════════════════════════════════ --}}
    <section class="bg-teal-600">
        <div class="mx-auto max-w-7xl px-6 lg:px-10">
            <div class="grid grid-cols-1 divide-y divide-teal-500 sm:grid-cols-3 sm:divide-x sm:divide-y-0">
                <div class="flex flex-col items-center justify-center py-8 text-center">
                    <span class="text-4xl font-extrabold text-white">120+</span>
                    <span class="mt-1 text-sm font-medium text-teal-100">{{ __('Available Spaces') }}</span>
                </div>
                <div class="flex flex-col items-center justify-center py-8 text-center">
                    <span class="text-4xl font-extrabold text-white">8</span>
                    <span class="mt-1 text-sm font-medium text-teal-100">{{ __('Cities Covered') }}</span>
                </div>
                <div class="flex flex-col items-center justify-center py-8 text-center">
                    <span class="text-4xl font-extrabold text-white">500+</span>
                    <span class="mt-1 text-sm font-medium text-teal-100">{{ __('Completed Rentals') }}</span>
                </div>
            </div>
        </div>
    </section>

    {{-- ══ MOST POPULAR PROPERTIES ══════════════════════════════════════════ --}}
    <section class="bg-zinc-50 py-16 lg:py-24" id="properties">
        <div class="mx-auto max-w-7xl px-6 lg:px-10">

            <div class="mb-10 text-center">
                <span class="text-xs font-semibold uppercase tracking-widest text-teal-600">{{ __('Discover our properties') }}</span>
                <h2 class="mt-2 text-3xl font-extrabold text-zinc-900">{{ __('Most Popular Properties') }}</h2>
            </div>

            @if($popularProperties->isEmpty())
                <p class="text-center text-sm text-zinc-400">{{ __('No featured properties yet. Check back soon!') }}</p>
            @else
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($popularProperties as $property)
                        @php
                            $img       = $property->images->first();
                            $rating    = $property->reviews_avg_rating ? round((float)$property->reviews_avg_rating, 1) : null;
                            $reviewCnt = $property->reviews_count ?? 0;
                            $price     = $property->monthly_rent ?? $property->weekly_rent ?? $property->daily_rent;
                            $priceLabel = $property->monthly_rent ? __('/month') : ($property->weekly_rent ? __('/week') : __('/day'));
                            $isOccupied = $property->contracts->isNotEmpty();
                        @endphp
                        <a href="{{ route('properties.show', $property->slug) }}" wire:navigate
                            class="group block overflow-hidden rounded-2xl bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">

                            {{-- Image --}}
                            <div class="relative h-52 overflow-hidden bg-zinc-100">
                                @if($img)
                                    <img src="{{ $img->url() }}" alt="{{ $property->title }}"
                                         class="h-full w-full object-cover transition duration-300 group-hover:scale-105" />
                                @else
                                    <div class="flex h-full items-center justify-center bg-zinc-100">
                                        <svg class="size-14 text-zinc-300" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75"/></svg>
                                    </div>
                                @endif

                                {{-- Heart button --}}
                                <button onclick="event.preventDefault()"
                                    class="absolute right-3 top-3 flex size-9 items-center justify-center rounded-full bg-white shadow-sm transition hover:scale-110">
                                    <svg class="size-4 text-rose-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/>
                                    </svg>
                                </button>
                            </div>

                            {{-- Card body --}}
                            <div class="p-4">

                                {{-- Status + rating row --}}
                                <div class="mb-3 flex items-center justify-between">
                                    <span class="rounded-full px-2.5 py-0.5 text-xs font-semibold
                                        {{ $isOccupied ? 'bg-zinc-100 text-zinc-500' : 'bg-green-100 text-green-700' }}">
                                        {{ $isOccupied ? __('Occupied') : __('Available') }}
                                    </span>
                                    <div class="flex items-center gap-1">
                                        <svg class="size-3.5 text-amber-400" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd"/></svg>
                                        <span class="text-xs font-semibold text-zinc-700">{{ $rating ?? '—' }}</span>
                                        <span class="text-xs text-zinc-400">({{ $reviewCnt }} {{ __('Review') }})</span>
                                    </div>
                                </div>

                                {{-- Title + price row --}}
                                <div class="mb-2 flex items-start justify-between gap-2">
                                    <h3 class="line-clamp-1 text-sm font-bold leading-snug text-zinc-900">{{ $property->title }}</h3>
                                    @if($price)
                                        <span class="shrink-0 text-sm font-extrabold text-teal-600">
                                            ${{ number_format($price) }}<span class="text-xs font-normal text-zinc-400">{{ $priceLabel }}</span>
                                        </span>
                                    @endif
                                </div>

                                {{-- Address --}}
                                <p class="mb-1.5 flex items-center gap-1 truncate text-xs text-zinc-500">
                                    <svg class="size-3.5 shrink-0 text-zinc-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                                    {{ implode(', ', array_filter([$property->address_line_1, $property->city])) ?: __('Location TBA') }}
                                </p>

                                {{-- Size --}}
                                @if($property->size_sqm)
                                    <p class="flex items-center gap-1 text-xs text-zinc-500">
                                        <svg class="size-3.5 shrink-0 text-zinc-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3.75v4.5m0-4.5h4.5m-4.5 0L9 9M3.75 20.25v-4.5m0 4.5h4.5m-4.5 0L9 15M20.25 3.75h-4.5m4.5 0v4.5m0-4.5L15 9m5.25 11.25h-4.5m4.5 0v-4.5m0 4.5L15 15"/></svg>
                                        {{ number_format($property->size_sqm) }} m²
                                    </p>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="mt-10 text-center">
                    <a href="{{ route('properties.index') }}" wire:navigate
                       class="inline-flex items-center gap-2 rounded-xl border border-teal-200 px-6 py-2.5 text-sm font-semibold text-teal-700 transition hover:bg-teal-50">
                        {{ __('View All Properties') }}
                        <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                    </a>
                </div>
            @endif
        </div>
    </section>

    {{-- ══ HOW IT WORKS ═════════════════════════════════════════════════════ --}}
    <section class="bg-white py-16 lg:py-24" id="how-it-works">
        <div class="mx-auto max-w-7xl px-6 lg:px-10">

            <div class="mb-12 text-center">
                <span class="text-xs font-semibold uppercase tracking-widest text-teal-600">{{ __('Simple Process') }}</span>
                <h2 class="mt-2 text-3xl font-extrabold text-zinc-900">{{ __('How It Works') }}</h2>
                <p class="mx-auto mt-3 max-w-lg text-sm text-zinc-500">{{ __('Find and book your ideal commercial space in just a few steps.') }}</p>
            </div>

            <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-4">

                <div class="relative flex flex-col items-center text-center">
                    <div class="flex size-16 items-center justify-center rounded-2xl bg-teal-50 ring-2 ring-teal-100">
                        <svg class="size-8 text-teal-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/></svg>
                    </div>
                    <div class="absolute -right-3 -top-3 hidden size-7 items-center justify-center rounded-full bg-teal-600 text-xs font-extrabold text-white lg:flex">1</div>
                    <h3 class="mt-5 text-base font-bold text-zinc-900">{{ __('Search') }}</h3>
                    <p class="mt-2 text-sm leading-relaxed text-zinc-500">{{ __('Filter by location, size, and space type to find what fits your project.') }}</p>
                </div>

                <div class="relative flex flex-col items-center text-center">
                    <div class="flex size-16 items-center justify-center rounded-2xl bg-teal-50 ring-2 ring-teal-100">
                        <svg class="size-8 text-teal-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/></svg>
                    </div>
                    <div class="absolute -right-3 -top-3 hidden size-7 items-center justify-center rounded-full bg-teal-600 text-xs font-extrabold text-white lg:flex">2</div>
                    <h3 class="mt-5 text-base font-bold text-zinc-900">{{ __('Book Online') }}</h3>
                    <p class="mt-2 text-sm leading-relaxed text-zinc-500">{{ __('Choose your dates and sign a digital contract — fully online.') }}</p>
                </div>

                <div class="relative flex flex-col items-center text-center">
                    <div class="flex size-16 items-center justify-center rounded-2xl bg-teal-50 ring-2 ring-teal-100">
                        <svg class="size-8 text-teal-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"/></svg>
                    </div>
                    <div class="absolute -right-3 -top-3 hidden size-7 items-center justify-center rounded-full bg-teal-600 text-xs font-extrabold text-white lg:flex">3</div>
                    <h3 class="mt-5 text-base font-bold text-zinc-900">{{ __('Secure Payment') }}</h3>
                    <p class="mt-2 text-sm leading-relaxed text-zinc-500">{{ __('Pay securely via Mobile Money, bank transfer, or credit card.') }}</p>
                </div>

                <div class="relative flex flex-col items-center text-center">
                    <div class="flex size-16 items-center justify-center rounded-2xl bg-teal-50 ring-2 ring-teal-100">
                        <svg class="size-8 text-teal-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 0 0 3.75.614"/></svg>
                    </div>
                    <div class="absolute -right-3 -top-3 hidden size-7 items-center justify-center rounded-full bg-teal-600 text-xs font-extrabold text-white lg:flex">4</div>
                    <h3 class="mt-5 text-base font-bold text-zinc-900">{{ __('Launch Your Activity') }}</h3>
                    <p class="mt-2 text-sm leading-relaxed text-zinc-500">{{ __('Access your space and launch your business with full support.') }}</p>
                </div>

            </div>
        </div>
    </section>

    {{-- ══ WHY CHOOSE US ════════════════════════════════════════════════════ --}}
    <section class="bg-zinc-50 py-16 lg:py-24">
        <div class="mx-auto max-w-7xl px-6 lg:px-10">

            <div class="mb-12 text-center">
                <span class="text-xs font-semibold uppercase tracking-widest text-teal-600">{{ __('Our Advantages') }}</span>
                <h2 class="mt-2 text-3xl font-extrabold text-zinc-900">{{ __('Why Choose Sahoome') }}</h2>
            </div>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">

                <div class="rounded-2xl bg-white p-6 shadow-sm">
                    <div class="mb-4 flex size-12 items-center justify-center rounded-xl bg-teal-50">
                        <svg class="size-6 text-teal-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75Z"/></svg>
                    </div>
                    <h4 class="text-sm font-bold text-zinc-900">{{ __('Flexible Duration') }}</h4>
                    <p class="mt-2 text-xs leading-relaxed text-zinc-500">{{ __('Rent by the day, week, or month — no long-term commitment required.') }}</p>
                </div>

                <div class="rounded-2xl bg-white p-6 shadow-sm">
                    <div class="mb-4 flex size-12 items-center justify-center rounded-xl bg-teal-50">
                        <svg class="size-6 text-teal-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                    </div>
                    <h4 class="text-sm font-bold text-zinc-900">{{ __('Prime Locations') }}</h4>
                    <p class="mt-2 text-xs leading-relaxed text-zinc-500">{{ __('All spaces are in high-traffic commercial areas with strong visibility.') }}</p>
                </div>

                <div class="rounded-2xl bg-white p-6 shadow-sm">
                    <div class="mb-4 flex size-12 items-center justify-center rounded-xl bg-teal-50">
                        <svg class="size-6 text-teal-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"/></svg>
                    </div>
                    <h4 class="text-sm font-bold text-zinc-900">{{ __('Verified Spaces') }}</h4>
                    <p class="mt-2 text-xs leading-relaxed text-zinc-500">{{ __('Every listed space is verified and reviewed by our team for quality.') }}</p>
                </div>

                <div class="rounded-2xl bg-white p-6 shadow-sm">
                    <div class="mb-4 flex size-12 items-center justify-center rounded-xl bg-teal-50">
                        <svg class="size-6 text-teal-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z"/></svg>
                    </div>
                    <h4 class="text-sm font-bold text-zinc-900">{{ __('Secure Payments') }}</h4>
                    <p class="mt-2 text-xs leading-relaxed text-zinc-500">{{ __('All transactions are protected and processed through secure channels.') }}</p>
                </div>

                <div class="rounded-2xl bg-white p-6 shadow-sm">
                    <div class="mb-4 flex size-12 items-center justify-center rounded-xl bg-teal-50">
                        <svg class="size-6 text-teal-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z"/></svg>
                    </div>
                    <h4 class="text-sm font-bold text-zinc-900">{{ __('Dedicated Support') }}</h4>
                    <p class="mt-2 text-xs leading-relaxed text-zinc-500">{{ __('Our team is available 7 days a week to assist landlords and tenants.') }}</p>
                </div>

                <div class="rounded-2xl bg-white p-6 shadow-sm">
                    <div class="mb-4 flex size-12 items-center justify-center rounded-xl bg-teal-50">
                        <svg class="size-6 text-teal-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 0 0 6 3.75v16.5a2.25 2.25 0 0 0 2.25 2.25h7.5A2.25 2.25 0 0 0 18 20.25V3.75a2.25 2.25 0 0 0-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3"/></svg>
                    </div>
                    <h4 class="text-sm font-bold text-zinc-900">{{ __('100% Digital') }}</h4>
                    <p class="mt-2 text-xs leading-relaxed text-zinc-500">{{ __('Everything from browsing to signing contracts is done entirely online.') }}</p>
                </div>

            </div>
        </div>
    </section>

    {{-- ══ ABOUT / MISSION / VISION ══════════════════════════════════════════ --}}
    <section class="bg-white py-16 lg:py-24" id="about">
        <div class="mx-auto max-w-7xl px-6 lg:px-10">
            <div class="grid grid-cols-1 items-center gap-12 lg:grid-cols-2">

                {{-- Left: Two-photo overlapping collage --}}
                <div class="relative h-[420px]">
                    <div class="absolute left-0 top-0 h-full w-[72%] overflow-hidden rounded-2xl shadow-lg">
                        <img src="https://images.unsplash.com/photo-1555396273-367ea4eb4db5?w=800&q=80"
                             alt="{{ __('About Sahoome') }}"
                             class="h-full w-full object-cover" />
                    </div>
                    <div class="absolute bottom-6 right-0 h-[55%] w-[52%] overflow-hidden rounded-2xl shadow-xl ring-4 ring-white">
                        <img src="https://images.unsplash.com/photo-1441984904996-e0b6ba687e04?w=600&q=80"
                             alt="{{ __('Our stores') }}"
                             class="h-full w-full object-cover" />
                    </div>
                </div>

                {{-- Right: Text content --}}
                <div>
                    <span class="text-xs font-semibold uppercase tracking-widest text-teal-600">{{ __('Who We Are') }}</span>
                    <h2 class="mt-2 text-3xl font-extrabold leading-snug text-zinc-900">
                        {{ __('Unveiling our identity, vision and values') }}
                    </h2>
                    <p class="mt-3 text-sm leading-relaxed text-zinc-500">
                        {{ __("We're passionate about real estate innovation, with years of experience in the industry.") }}
                    </p>

                    <div class="mt-7 space-y-4">

                        {{-- Mission --}}
                        <div class="flex gap-4 rounded-xl bg-zinc-50 p-4">
                            <div class="flex size-11 shrink-0 items-center justify-center rounded-full bg-teal-50 ring-1 ring-teal-200">
                                <svg class="size-5 text-teal-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 14.25v2.25m3-4.5v4.5m3-6.75v6.75m3-9v9M6 20.25h12A2.25 2.25 0 0 0 20.25 18V6A2.25 2.25 0 0 0 18 3.75H6A2.25 2.25 0 0 0 3.75 6v12A2.25 2.25 0 0 0 6 20.25Z"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-zinc-900">{{ __('Our Mission') }}</h4>
                                <p class="mt-1 text-xs leading-relaxed text-zinc-500">{{ __('Our mission is to help individuals and businesses find their perfect property by providing trusted, transparent, and professional real estate solutions.') }}</p>
                            </div>
                        </div>

                        {{-- Vision --}}
                        <div class="flex gap-4 rounded-xl bg-zinc-50 p-4">
                            <div class="flex size-11 shrink-0 items-center justify-center rounded-full bg-teal-50 ring-1 ring-teal-200">
                                <svg class="size-5 text-teal-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-zinc-900">{{ __('Our Vision') }}</h4>
                                <p class="mt-1 text-xs leading-relaxed text-zinc-500">{{ __('To be the leading platform connecting merchants and property owners through innovative, flexible, and transparent commercial space rental solutions.') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <a href="#contact"
                           class="inline-flex items-center rounded-lg border border-teal-600 px-5 py-2 text-sm font-semibold text-teal-700 transition hover:bg-teal-50">
                            {{ __('More about us') }}
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ══ OUR PROPERTIES ════════════════════════════════════════════════════ --}}
    <section class="bg-zinc-50 py-16 lg:py-24">
        <div class="mx-auto max-w-7xl px-6 lg:px-10">

            <div class="mb-8 flex items-center justify-between">
                <h2 class="text-2xl font-extrabold text-zinc-900">{{ __('Our Properties') }}</h2>
                <a href="{{ route('properties.index') }}" wire:navigate
                   class="rounded-lg border border-teal-300 px-4 py-2 text-sm font-semibold text-teal-700 transition hover:bg-teal-50">
                    {{ __('View All') }}
                </a>
            </div>

            @if($properties->isEmpty())
                <p class="text-sm text-zinc-400">{{ __('No properties available right now.') }}</p>
            @else
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    @foreach($properties as $property)
                        @php
                            $img        = $property->images->first();
                            $rating     = $property->reviews_avg_rating ? round((float)$property->reviews_avg_rating, 1) : null;
                            $reviewCnt  = $property->reviews_count ?? 0;
                            $price      = $property->monthly_rent ?? $property->weekly_rent ?? $property->daily_rent;
                            $priceLabel = $property->monthly_rent ? __('/month') : ($property->weekly_rent ? __('/week') : __('/day'));
                            $isOccupied = $property->contracts->isNotEmpty();
                        @endphp
                        <a href="{{ route('properties.show', $property->slug) }}" wire:navigate
                            class="group flex gap-4 overflow-hidden rounded-2xl bg-white p-3 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">

                            {{-- Thumbnail --}}
                            <div class="relative size-32 shrink-0 overflow-hidden rounded-xl bg-zinc-100">
                                @if($img)
                                    <img src="{{ $img->url() }}" alt="{{ $property->title }}"
                                         class="h-full w-full object-cover transition duration-300 group-hover:scale-105" />
                                @else
                                    <div class="flex h-full items-center justify-center">
                                        <svg class="size-8 text-zinc-300" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75"/></svg>
                                    </div>
                                @endif
                                <button onclick="event.preventDefault()"
                                    class="absolute left-2 top-2 flex size-7 items-center justify-center rounded-full bg-white shadow-sm transition hover:scale-110">
                                    <svg class="size-3.5 text-rose-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/>
                                    </svg>
                                </button>
                            </div>

                            {{-- Info --}}
                            <div class="flex flex-1 flex-col justify-center gap-1.5 py-1">

                                <div class="flex items-center justify-between">
                                    <span class="rounded-full px-2.5 py-0.5 text-xs font-semibold
                                        {{ $isOccupied ? 'bg-zinc-100 text-zinc-500' : 'bg-green-100 text-green-700' }}">
                                        {{ $isOccupied ? __('Occupied') : __('Available') }}
                                    </span>
                                    <div class="flex items-center gap-1">
                                        <svg class="size-3 text-amber-400" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd"/></svg>
                                        <span class="text-xs font-semibold text-zinc-700">{{ $rating ?? '—' }}</span>
                                        <span class="text-xs text-zinc-400">({{ $reviewCnt }} {{ __('Review') }})</span>
                                    </div>
                                </div>

                                <div class="flex items-start justify-between gap-2">
                                    <h3 class="line-clamp-1 text-sm font-bold leading-snug text-zinc-900">{{ $property->title }}</h3>
                                    @if($price)
                                        <span class="shrink-0 text-sm font-extrabold text-teal-600">
                                            ${{ number_format($price) }}<span class="text-xs font-normal text-zinc-400">{{ $priceLabel }}</span>
                                        </span>
                                    @endif
                                </div>

                                <p class="flex items-center gap-1 truncate text-xs text-zinc-500">
                                    <svg class="size-3.5 shrink-0 text-zinc-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                                    {{ implode(', ', array_filter([$property->address_line_1, $property->city])) ?: __('Location TBA') }}
                                </p>

                                @if($property->size_sqm)
                                    <p class="flex items-center gap-1 text-xs text-zinc-500">
                                        <svg class="size-3.5 shrink-0 text-zinc-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3.75v4.5m0-4.5h4.5m-4.5 0L9 9M3.75 20.25v-4.5m0 4.5h4.5m-4.5 0L9 15M20.25 3.75h-4.5m4.5 0v4.5m0-4.5L15 9m5.25 11.25h-4.5m4.5 0v-4.5m0 4.5L15 15"/></svg>
                                        {{ number_format($property->size_sqm) }} m²
                                    </p>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- ══ TESTIMONIALS ══════════════════════════════════════════════════════ --}}
    <section class="bg-white py-16 lg:py-24">
        <div class="mx-auto max-w-7xl px-6 lg:px-10">

            <div class="mb-12 text-center">
                <span class="text-xs font-semibold uppercase tracking-widest text-teal-600">{{ __('Testimonials') }}</span>
                <h2 class="mt-2 text-3xl font-extrabold text-zinc-900">{{ __('What Our Customers Say') }}</h2>
            </div>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">

                <div class="rounded-2xl border border-zinc-100 bg-zinc-50 p-6">
                    <div class="mb-3 flex items-center gap-1">
                        @for($i = 0; $i < 5; $i++)
                            <svg class="size-4 text-amber-400" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd"/></svg>
                        @endfor
                    </div>
                    <p class="text-sm leading-relaxed text-zinc-600">{{ __('"Sahoome made it incredibly easy to find a pop-up space for our launch event. The booking process was seamless and the location was perfect."') }}</p>
                    <div class="mt-4 flex items-center gap-3">
                        <div class="flex size-9 items-center justify-center rounded-full bg-teal-100 text-xs font-bold text-teal-700">AK</div>
                        <div>
                            <p class="text-xs font-semibold text-zinc-900">{{ __('Amara Koné') }}</p>
                            <p class="text-xs text-zinc-400">{{ __('Brand Owner') }}</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-zinc-100 bg-zinc-50 p-6">
                    <div class="mb-3 flex items-center gap-1">
                        @for($i = 0; $i < 5; $i++)
                            <svg class="size-4 text-amber-400" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd"/></svg>
                        @endfor
                    </div>
                    <p class="text-sm leading-relaxed text-zinc-600">{{ __('"We used Sahoome for our seasonal showroom and the experience was fantastic. The platform is intuitive and the support team was very responsive."') }}</p>
                    <div class="mt-4 flex items-center gap-3">
                        <div class="flex size-9 items-center justify-center rounded-full bg-teal-100 text-xs font-bold text-teal-700">FD</div>
                        <div>
                            <p class="text-xs font-semibold text-zinc-900">{{ __('Fatou Diallo') }}</p>
                            <p class="text-xs text-zinc-400">{{ __('Fashion Designer') }}</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-zinc-100 bg-zinc-50 p-6">
                    <div class="mb-3 flex items-center gap-1">
                        @for($i = 0; $i < 5; $i++)
                            <svg class="size-4 text-amber-400" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd"/></svg>
                        @endfor
                    </div>
                    <p class="text-sm leading-relaxed text-zinc-600">{{ __('"As a property owner, Sahoome has helped me monetize my commercial spaces efficiently. The tenant quality has been excellent and payments are always on time."') }}</p>
                    <div class="mt-4 flex items-center gap-3">
                        <div class="flex size-9 items-center justify-center rounded-full bg-teal-100 text-xs font-bold text-teal-700">MB</div>
                        <div>
                            <p class="text-xs font-semibold text-zinc-900">{{ __('Mamadou Bah') }}</p>
                            <p class="text-xs text-zinc-400">{{ __('Property Owner') }}</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ══ BECOME A LANDLORD ═════════════════════════════════════════════════ --}}
    <section class="relative overflow-hidden py-24">

        <div class="absolute inset-0 bg-cover bg-center"
             style="background-image: url('https://images.unsplash.com/photo-1486325212027-8081e485255e?w=1920&q=80')"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-teal-900/85 via-teal-900/50 to-transparent"></div>

        <div class="relative z-10 mx-auto max-w-7xl px-6 lg:px-10">
            <div class="max-w-md rounded-2xl bg-white p-8 shadow-2xl">
                <h2 class="text-2xl font-extrabold text-zinc-900">
                    {{ __('Become a') }} <span class="text-teal-600">{{ __('landlord') }}</span>
                </h2>
                <p class="mt-3 text-sm leading-relaxed text-zinc-500">
                    {{ __('Join thousands of happy landlords and tenants who have found success with Sahoome.') }}
                </p>
                @auth
                    <a href="{{ route('landlord.dashboard') }}" wire:navigate
                       class="mt-6 inline-flex items-center gap-2 rounded-xl bg-teal-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-teal-700">
                        {{ __('Join us now') }}
                        <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                    </a>
                @else
                    <a href="{{ route('register') }}" wire:navigate
                       class="mt-6 inline-flex items-center gap-2 rounded-xl bg-teal-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-teal-700">
                        {{ __('Join us now') }}
                        <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                    </a>
                @endauth
            </div>
        </div>

    </section>

    {{-- ══ CONTACT / SEND MESSAGE ════════════════════════════════════════════ --}}
    <section class="bg-zinc-50 py-16 lg:py-24" id="contact">
        <div class="mx-auto grid max-w-7xl grid-cols-1 items-start gap-12 px-6 lg:grid-cols-2 lg:px-10">

            {{-- Left: Form --}}
            <div>
                <span class="text-xs font-semibold uppercase tracking-widest text-teal-600">{{ __('Get In Touch') }}</span>
                <h2 class="mt-2 text-2xl font-extrabold text-zinc-900">{{ __('Send us a Message') }}</h2>

                @if($messageSent)
                    <div class="mt-6 rounded-xl border border-green-200 bg-green-50 px-5 py-4 text-sm font-medium text-green-700">
                        {{ __('Thank you! Your message has been sent. We\'ll be in touch shortly.') }}
                    </div>
                @endif

                <form wire:submit="sendMessage" class="mt-6 flex flex-col gap-4">

                    <div class="flex flex-col gap-1.5">
                        <label class="text-sm font-medium text-zinc-700">{{ __('Your Name') }}</label>
                        <input wire:model="contactName" type="text" placeholder="{{ __('Enter your full name') }}"
                            class="rounded-lg border border-zinc-200 bg-white px-4 py-2.5 text-sm outline-none placeholder-zinc-400 transition focus:border-teal-400 focus:ring-2 focus:ring-teal-100" />
                        @error('contactName') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <label class="text-sm font-medium text-zinc-700">{{ __('Email') }}</label>
                        <input wire:model="contactEmail" type="email" placeholder="{{ __('Enter your email') }}"
                            class="rounded-lg border border-zinc-200 bg-white px-4 py-2.5 text-sm outline-none placeholder-zinc-400 transition focus:border-teal-400 focus:ring-2 focus:ring-teal-100" />
                        @error('contactEmail') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <label class="text-sm font-medium text-zinc-700">{{ __('Phone number') }}</label>
                        <input wire:model="contactPhone" type="tel" placeholder="{{ __('Enter Phone number') }}"
                            class="rounded-lg border border-zinc-200 bg-white px-4 py-2.5 text-sm outline-none placeholder-zinc-400 transition focus:border-teal-400 focus:ring-2 focus:ring-teal-100" />
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <label class="text-sm font-medium text-zinc-700">{{ __('Your Message') }}</label>
                        <textarea wire:model="contactMessage" rows="5" placeholder="{{ __('Enter your Message') }}"
                            class="resize-none rounded-lg border border-zinc-200 bg-white px-4 py-2.5 text-sm outline-none placeholder-zinc-400 transition focus:border-teal-400 focus:ring-2 focus:ring-teal-100"></textarea>
                        @error('contactMessage') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-center pt-1">
                        <button type="submit"
                            class="rounded-xl bg-teal-600 px-8 py-2.5 text-sm font-semibold text-white transition hover:bg-teal-700 focus:outline-none"
                            wire:loading.attr="disabled" wire:loading.class="opacity-75">
                            <span wire:loading.remove>{{ __('Send Message') }}</span>
                            <span wire:loading>{{ __('Sending...') }}</span>
                        </button>
                    </div>
                </form>
            </div>

            {{-- Right: Contact illustration --}}
            <div class="hidden items-center justify-center pt-8 lg:flex">
                <svg viewBox="0 0 420 340" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full max-w-md">
                    <rect width="420" height="340" rx="16" fill="#f0fdfa"/>
                    <rect x="20" y="120" width="30" height="160" fill="#99f6e4" opacity="0.5"/>
                    <rect x="55" y="90" width="25" height="190" fill="#5eead4" opacity="0.5"/>
                    <rect x="330" y="100" width="28" height="180" fill="#99f6e4" opacity="0.5"/>
                    <rect x="362" y="130" width="22" height="150" fill="#5eead4" opacity="0.5"/>
                    <rect x="388" y="115" width="18" height="165" fill="#99f6e4" opacity="0.5"/>
                    <rect x="0" y="270" width="420" height="70" fill="#5eead4" opacity="0.3"/>
                    <rect x="110" y="150" width="200" height="140" rx="8" fill="#0d9488"/>
                    <path d="M110 158 L210 220 L310 158" stroke="#f0fdfa" stroke-width="3" fill="none"/>
                    <path d="M110 290 L170 230" stroke="#f0fdfa" stroke-width="2" opacity="0.6"/>
                    <path d="M310 290 L250 230" stroke="#f0fdfa" stroke-width="2" opacity="0.6"/>
                    <circle cx="270" cy="145" r="65" fill="#0f766e"/>
                    <circle cx="270" cy="145" r="55" fill="#0d9488"/>
                    <circle cx="258" cy="133" r="5" fill="#0f766e"/>
                    <circle cx="270" cy="133" r="5" fill="#0f766e"/>
                    <circle cx="282" cy="133" r="5" fill="#0f766e"/>
                    <circle cx="258" cy="145" r="5" fill="#0f766e"/>
                    <circle cx="270" cy="145" r="5" fill="#0f766e"/>
                    <circle cx="282" cy="145" r="5" fill="#0f766e"/>
                    <circle cx="258" cy="157" r="5" fill="#0f766e"/>
                    <circle cx="270" cy="157" r="5" fill="#0f766e"/>
                    <circle cx="282" cy="157" r="5" fill="#0f766e"/>
                    <circle cx="148" cy="148" r="12" fill="#4a3728"/>
                    <rect x="140" y="160" width="16" height="22" rx="4" fill="#0d9488"/>
                    <rect x="135" y="162" width="8" height="14" rx="4" fill="#4a3728"/>
                    <rect x="157" y="162" width="8" height="14" rx="4" fill="#4a3728"/>
                    <rect x="136" y="176" width="22" height="14" rx="3" fill="#2d3748"/>
                    <rect x="137" y="177" width="20" height="10" rx="2" fill="#4299e1"/>
                    <circle cx="308" cy="72" r="11" fill="#6b4c3b"/>
                    <rect x="300" y="83" width="16" height="20" rx="4" fill="#0d9488"/>
                    <rect x="296" y="85" width="8" height="12" rx="4" fill="#6b4c3b"/>
                    <rect x="316" y="83" width="8" height="16" rx="4" fill="#6b4c3b"/>
                    <rect x="315" y="55" width="60" height="30" rx="8" fill="#14b8a6"/>
                    <path d="M320 85 L315 95 L330 85Z" fill="#14b8a6"/>
                    <rect x="322" y="63" width="8" height="8" rx="2" fill="white" opacity="0.8"/>
                    <rect x="334" y="63" width="8" height="8" rx="2" fill="white" opacity="0.8"/>
                    <rect x="346" y="63" width="8" height="8" rx="2" fill="white" opacity="0.8"/>
                    <ellipse cx="85" cy="270" rx="22" ry="30" fill="#7c9a5e" opacity="0.7"/>
                    <ellipse cx="73" cy="258" rx="16" ry="22" fill="#5a7a42" opacity="0.6"/>
                    <ellipse cx="340" cy="265" rx="20" ry="28" fill="#7c9a5e" opacity="0.7"/>
                    <ellipse cx="353" cy="255" rx="15" ry="20" fill="#5a7a42" opacity="0.6"/>
                </svg>
            </div>

        </div>
    </section>

    @include('partials.public-footer')

</div>
