<div>

    {{-- ══ PAGE HERO ══════════════════════════════════════════════════════════ --}}
    <section class="relative overflow-hidden">
        <div class="absolute inset-0 bg-cover bg-center bg-no-repeat"
             style="background-image: url('https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=1920&q=80')"></div>
        <div class="absolute inset-0 bg-amber-950/70"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent"></div>

        <x-public.navbar :transparent="true" />

        <div class="relative z-10 mx-auto max-w-7xl px-6 pb-14 pt-10 lg:px-10">
            <nav class="mb-4 flex items-center gap-2 text-xs text-white/60">
                <a href="{{ route('home') }}" wire:navigate class="transition hover:text-amber-400">{{ __('Home') }}</a>
                <svg class="size-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                <span class="text-white/80">{{ __('Properties') }}</span>
            </nav>
            <h1 class="text-4xl font-extrabold text-white">{{ __('Browse Properties') }}</h1>
            <p class="mt-2 text-sm text-white/70">{{ __('Discover available spaces perfect for your business') }}</p>

            {{-- Inline search bar --}}
            <div class="mt-6 flex max-w-xl items-center gap-3 rounded-2xl bg-white/10 px-4 py-3 backdrop-blur-sm border border-white/20">
                <svg class="size-5 shrink-0 text-white/60" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" /></svg>
                <input
                    wire:model.live.debounce.400ms="search"
                    type="text"
                    placeholder="{{ __('Search by title, address or city...') }}"
                    class="flex-1 bg-transparent text-sm text-white placeholder-white/50 outline-none"
                />
                @if($search)
                    <button wire:click="$set('search', '')" class="text-white/60 transition hover:text-white">
                        <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                    </button>
                @endif
            </div>
        </div>
    </section>

    {{-- ══ MAIN CONTENT ══════════════════════════════════════════════════════ --}}
    <div class="mx-auto max-w-5xl px-6 py-10 lg:px-10">

        {{-- Section heading --}}
        <div class="mb-6">
            <h2 class="text-2xl font-extrabold text-zinc-900">{{ __('Available Properties') }}</h2>
        </div>

        {{-- ── TOP FILTER BAR ─────────────────────────────────────────────── --}}
        <div class="mb-6 space-y-3">
            {{-- Row 1: Location + City --}}
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                {{-- Location / Search --}}
                <div class="flex items-center gap-3 rounded-xl border border-zinc-200 bg-white px-4 py-3 shadow-sm">
                    <svg class="size-4 shrink-0 text-amber-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                    <input
                        wire:model.live.debounce.400ms="search"
                        type="text"
                        placeholder="{{ __('Location') }}"
                        class="flex-1 bg-transparent text-sm text-zinc-700 placeholder-zinc-400 outline-none"
                    />
                    @if($search)
                        <button wire:click="$set('search', '')" class="shrink-0 text-zinc-300 transition hover:text-zinc-500">
                            <svg class="size-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                        </button>
                    @endif
                </div>
                {{-- City / Size --}}
                <div class="flex items-center gap-3 rounded-xl border border-zinc-200 bg-white px-4 py-3 shadow-sm">
                    <svg class="size-4 shrink-0 text-amber-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3.75v4.5m0-4.5h4.5m-4.5 0L9 9M3.75 20.25v-4.5m0 4.5h4.5m-4.5 0L9 15M20.25 3.75h-4.5m4.5 0v4.5m0-4.5L15 9m5.25 11.25h-4.5m4.5 0v-4.5m0 4.5L15 15"/></svg>
                    <select wire:model.live="city" class="flex-1 bg-transparent text-sm text-zinc-700 outline-none cursor-pointer">
                        <option value="">{{ __('Size') }}</option>
                        @foreach($cities as $c)
                            <option value="{{ $c }}">{{ $c }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Row 2: From / To / Amenities / Search button --}}
            <div class="flex flex-wrap items-stretch gap-3">
                {{-- From $ --}}
                <div class="flex w-36 items-center gap-2 rounded-xl border border-zinc-200 bg-white px-4 py-3 shadow-sm">
                    <input
                        wire:model.live.debounce.600ms="minPrice"
                        type="number"
                        min="0"
                        placeholder="{{ __('From') }}"
                        class="min-w-0 flex-1 bg-transparent text-sm text-zinc-700 placeholder-zinc-400 outline-none"
                    />
                    <span class="shrink-0 text-sm font-medium text-zinc-400">$</span>
                </div>
                {{-- To $ --}}
                <div class="flex w-36 items-center gap-2 rounded-xl border border-zinc-200 bg-white px-4 py-3 shadow-sm">
                    <input
                        wire:model.live.debounce.600ms="maxPrice"
                        type="number"
                        min="0"
                        placeholder="{{ __('to') }}"
                        class="min-w-0 flex-1 bg-transparent text-sm text-zinc-700 placeholder-zinc-400 outline-none"
                    />
                    <span class="shrink-0 text-sm font-medium text-zinc-400">$</span>
                </div>
                {{-- Amenities / Pricing type --}}
                <div class="flex flex-1 items-center gap-2 rounded-xl border border-zinc-200 bg-white px-4 py-3 shadow-sm">
                    <svg class="size-4 shrink-0 text-amber-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z"/></svg>
                    <select wire:model.live="pricingType" class="flex-1 bg-transparent text-sm text-zinc-700 outline-none cursor-pointer">
                        <option value="">{{ __('Amenities') }}</option>
                        @foreach($pricingTypes as $type)
                            <option value="{{ $type->value }}">{{ $type->label() }}</option>
                        @endforeach
                    </select>
                    <svg class="size-4 shrink-0 text-zinc-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>
                </div>
                {{-- Search Properties button --}}
                <button wire:click="applyFilters"
                    class="shrink-0 rounded-xl bg-amber-600 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-amber-700 active:bg-amber-800">
                    {{ __('Search Properties') }}
                </button>
            </div>
        </div>

        {{-- Results bar --}}
        <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
            <p class="text-sm text-zinc-500">
                <span class="font-semibold text-zinc-900">{{ $totalCount }}</span> {{ __('properties found') }}
                @if($search)
                    {{ __('for') }} <span class="font-medium text-amber-600">"{{ $search }}"</span>
                @endif
            </p>
            <div class="flex items-center gap-3">
                {{-- Map toggle --}}
                <button wire:click="toggleShowMap"
                    class="flex items-center gap-1.5 rounded-lg border px-3 py-1.5 text-xs font-medium transition
                        {{ $showMap ? 'border-amber-400 bg-amber-50 text-amber-700' : 'border-zinc-200 bg-white text-zinc-600 hover:border-amber-300 hover:text-amber-600' }}">
                    <svg class="size-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75V15m6-6v8.25m.503 3.498 4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 0 0-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0Z"/></svg>
                    {{ $showMap ? __('Hide Map') : __('Show Map') }}
                </button>
                {{-- Sort --}}
                <label class="text-xs text-zinc-500">{{ __('Sort:') }}</label>
                <select wire:model.live="sort" class="rounded-lg border border-zinc-200 bg-white px-3 py-1.5 text-sm text-zinc-700 outline-none focus:border-amber-400 focus:ring-2 focus:ring-amber-100">
                    <option value="newest">{{ __('Newest') }}</option>
                    <option value="popular">{{ __('Most Popular') }}</option>
                    <option value="rating">{{ __('Top Rated') }}</option>
                    <option value="price_asc">{{ __('Price: Low → High') }}</option>
                    <option value="price_desc">{{ __('Price: High → Low') }}</option>
                </select>
            </div>
        </div>

        {{-- Active filter chips --}}
        @if($search || $pricingType || $city || $featuredOnly || $minPrice || $maxPrice)
            <div class="mb-4 flex flex-wrap items-center gap-2">
                @if($search)
                    <span class="inline-flex items-center gap-1 rounded-full border border-amber-200 bg-amber-50 px-3 py-1 text-xs font-medium text-amber-700">
                        "{{ $search }}"
                        <button wire:click="$set('search', '')" class="ml-1 text-amber-500 hover:text-amber-700">&times;</button>
                    </span>
                @endif
                @if($pricingType)
                    <span class="inline-flex items-center gap-1 rounded-full border border-blue-200 bg-blue-50 px-3 py-1 text-xs font-medium text-blue-700">
                        {{ ucfirst($pricingType) }}
                        <button wire:click="$set('pricingType', '')" class="ml-1 text-blue-500 hover:text-blue-700">&times;</button>
                    </span>
                @endif
                @if($city)
                    <span class="inline-flex items-center gap-1 rounded-full border border-green-200 bg-green-50 px-3 py-1 text-xs font-medium text-green-700">
                        {{ $city }}
                        <button wire:click="$set('city', '')" class="ml-1 text-green-500 hover:text-green-700">&times;</button>
                    </span>
                @endif
                @if($minPrice || $maxPrice)
                    <span class="inline-flex items-center gap-1 rounded-full border border-zinc-200 bg-zinc-100 px-3 py-1 text-xs font-medium text-zinc-700">
                        @if($minPrice && $maxPrice) {{ number_format($minPrice) }} – {{ number_format($maxPrice) }}
                        @elseif($minPrice) {{ __('From') }} {{ number_format($minPrice) }}
                        @else {{ __('Up to') }} {{ number_format($maxPrice) }}
                        @endif
                        <button wire:click="$set('minPrice', 0); $set('maxPrice', 0)" class="ml-1 text-zinc-400 hover:text-zinc-700">&times;</button>
                    </span>
                @endif
                <button wire:click="clearFilters" class="text-xs text-zinc-400 underline transition hover:text-zinc-600">{{ __('Clear all') }}</button>
            </div>
        @endif

        {{-- Map panel --}}
        @if($showMap)
            <div class="mb-6 overflow-hidden rounded-2xl border border-zinc-100 bg-white shadow-sm"
                 x-data="propertyMap()"
                 x-init="init()">
                <div class="flex items-center justify-between border-b border-zinc-100 px-4 py-3">
                    <div class="flex items-center gap-2">
                        <svg class="size-4 text-amber-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75V15m6-6v8.25m.503 3.498 4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 0 0-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0Z"/></svg>
                        <span class="text-sm font-semibold text-zinc-700">{{ __('Property Map') }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <button
                            wire:click="toggleMapFilter"
                            class="rounded-lg border px-3 py-1 text-xs font-semibold transition
                                {{ $mapFilter ? 'bg-amber-600 border-amber-600 text-white' : 'bg-zinc-50 border-zinc-200 text-zinc-600 hover:bg-amber-50 hover:text-amber-700 hover:border-amber-300' }}">
                            {{ $mapFilter ? __('Clear area filter') . ' ×' : __('Filter by area') }}
                        </button>
                        <button wire:click="toggleShowMap" class="text-xs font-medium text-zinc-400 transition hover:text-zinc-600">
                            {{ __('Hide') }} &times;
                        </button>
                    </div>
                </div>
                <div id="property-map" class="h-[420px] w-full"></div>
                <p class="px-4 py-2 text-center text-xs {{ $mapFilter ? 'font-medium text-amber-600' : 'text-zinc-400' }}">
                    {{ $mapFilter ? __('Showing properties in current map view — pan to update') : __('Pan or zoom, then click "Filter by area" to filter results') }}
                </p>
            </div>
        @endif

        {{-- Loading indicator --}}
        <div wire:loading.class.remove="hidden" class="hidden">
            <div class="mb-4 text-center text-sm text-zinc-400">{{ __('Updating results...') }}</div>
        </div>

        {{-- Empty state --}}
        @if($properties->isEmpty())
            <div class="flex flex-col items-center justify-center rounded-2xl border border-dashed border-zinc-200 bg-zinc-50 py-20 text-center">
                <svg class="mb-4 size-12 text-zinc-300" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21" /></svg>
                <p class="text-base font-semibold text-zinc-500">{{ __('No properties found') }}</p>
                <p class="mt-1 text-sm text-zinc-400">{{ __('Try adjusting your filters or search term.') }}</p>
                <button wire:click="clearFilters" class="mt-5 rounded-xl bg-amber-600 px-5 py-2 text-sm font-semibold text-white transition hover:bg-amber-700">
                    {{ __('Clear Filters') }}
                </button>
            </div>

        {{-- ── Property list (horizontal cards) ──────────────────────────── --}}
        @else
            <div class="flex flex-col gap-4" wire:loading.class="opacity-60 transition-opacity">
                @foreach($properties as $property)
                    @php
                        $img        = $property->images->first();
                        $rating     = $property->reviews_avg_rating ? round((float)$property->reviews_avg_rating, 1) : null;
                        $reviewCnt  = $property->reviews_count ?? 0;
                        $price      = $property->monthly_rent ?? $property->weekly_rent ?? $property->daily_rent ?? $property->yearly_rent;
                        $priceLabel = $property->monthly_rent ? __('/month') : ($property->weekly_rent ? __('/week') : ($property->daily_rent ? __('/day') : __('/year')));
                        $currSym    = match(strtoupper($property->currency ?? 'USD')) {
                            'SAR' => 'SAR ',
                            'EUR' => '€',
                            'GBP' => '£',
                            default => '$',
                        };
                        $isOccupied = $property->contracts->isNotEmpty();
                    @endphp
                    <div class="group flex overflow-hidden rounded-2xl border border-zinc-100 bg-white shadow-sm transition hover:shadow-md">

                        {{-- Image --}}
                        <div class="relative w-44 shrink-0 overflow-hidden bg-zinc-100 sm:w-48">
                            @if($img)
                                <img src="{{ $img->url() }}" alt="{{ $property->title }}"
                                     class="h-full w-full object-cover transition duration-300 group-hover:scale-105" />
                            @else
                                <div class="flex h-full w-full items-center justify-center bg-zinc-100">
                                    <svg class="size-10 text-zinc-300" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75"/></svg>
                                </div>
                            @endif
                            {{-- Heart --}}
                            @php $isFav = in_array($property->id, $favoritedIds); @endphp
                            <button
                                wire:click="toggleFavorite({{ $property->id }})"
                                class="absolute left-3 top-3 flex size-8 items-center justify-center rounded-full bg-white shadow-sm transition hover:scale-110
                                    {{ $isFav ? 'text-rose-500' : 'text-rose-300 hover:text-rose-500' }}">
                                <svg class="size-3.5" fill="{{ $isFav ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/>
                                </svg>
                            </button>
                            @if($property->is_featured)
                                <span class="absolute bottom-3 left-3 rounded-full bg-amber-500 px-2 py-0.5 text-xs font-bold text-white">{{ __('Featured') }}</span>
                            @endif
                        </div>

                        {{-- Card content --}}
                        <div class="flex flex-1 flex-col justify-between p-4 sm:p-5">
                            <div>
                                {{-- Status + Rating row --}}
                                <div class="mb-2 flex items-center justify-between gap-2">
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

                                {{-- Title + Price row --}}
                                <div class="mb-1.5 flex items-start justify-between gap-3">
                                    <h3 class="text-base font-bold leading-snug text-zinc-900 line-clamp-1">{{ $property->title }}</h3>
                                    @if($price)
                                        <span class="shrink-0 text-sm font-extrabold text-amber-600">
                                            {{ $currSym }}{{ number_format($price) }}<span class="text-xs font-normal text-zinc-400">{{ $priceLabel }}</span>
                                        </span>
                                    @endif
                                </div>

                                {{-- Address --}}
                                <p class="mb-1 flex items-center gap-1 truncate text-xs text-zinc-500">
                                    <svg class="size-3 shrink-0 text-zinc-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                                    {{ implode(', ', array_filter([$property->address_line_1, $property->city])) ?: __('Location TBA') }}
                                </p>

                                {{-- Size --}}
                                @if($property->size_sqm)
                                    <p class="flex items-center gap-1 text-xs text-zinc-500">
                                        <svg class="size-3 shrink-0 text-zinc-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3.75v4.5m0-4.5h4.5m-4.5 0L9 9M3.75 20.25v-4.5m0 4.5h4.5m-4.5 0L9 15M20.25 3.75h-4.5m4.5 0v4.5m0-4.5L15 9m5.25 11.25h-4.5m4.5 0v-4.5m0 4.5L15 15"/></svg>
                                        {{ number_format($property->size_sqm) }} m²
                                    </p>
                                @endif
                            </div>

                            {{-- View Details --}}
                            <div class="mt-3 flex items-center justify-between">
                                <a href="{{ route('properties.show', $property->slug) }}" wire:navigate
                                    class="inline-flex items-center gap-1.5 rounded-lg bg-amber-600 px-4 py-2 text-xs font-semibold text-white transition hover:bg-amber-700">
                                    {{ __('View Details') }}
                                    <svg class="size-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($properties->hasPages())
                <div class="mt-10 flex items-center justify-center">
                    <div class="flex items-center gap-1">
                        {{-- Prev --}}
                        @if($properties->onFirstPage())
                            <span class="flex size-9 items-center justify-center rounded-lg border border-zinc-200 text-zinc-300">
                                <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" /></svg>
                            </span>
                        @else
                            <button wire:click="previousPage" class="flex size-9 items-center justify-center rounded-lg border border-zinc-200 text-zinc-600 transition hover:border-amber-400 hover:text-amber-600">
                                <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" /></svg>
                            </button>
                        @endif
                        {{-- Page numbers --}}
                        @foreach($properties->getUrlRange(max(1, $properties->currentPage() - 2), min($properties->lastPage(), $properties->currentPage() + 2)) as $page => $url)
                            @if($page === $properties->currentPage())
                                <span class="flex size-9 items-center justify-center rounded-lg bg-amber-600 text-sm font-bold text-white">{{ $page }}</span>
                            @else
                                <button wire:click="gotoPage({{ $page }})" class="flex size-9 items-center justify-center rounded-lg border border-zinc-200 text-sm text-zinc-600 transition hover:border-amber-400 hover:text-amber-600">{{ $page }}</button>
                            @endif
                        @endforeach
                        {{-- Next --}}
                        @if($properties->hasMorePages())
                            <button wire:click="nextPage" class="flex size-9 items-center justify-center rounded-lg border border-zinc-200 text-zinc-600 transition hover:border-amber-400 hover:text-amber-600">
                                <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
                            </button>
                        @else
                            <span class="flex size-9 items-center justify-center rounded-lg border border-zinc-200 text-zinc-300">
                                <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
                            </span>
                        @endif
                    </div>
                </div>
                <p class="mt-3 text-center text-xs text-zinc-400">
                    {{ __('Showing') }} {{ $properties->firstItem() }}–{{ $properties->lastItem() }} {{ __('of') }} {{ $properties->total() }} {{ __('results') }}
                </p>
            @endif
        @endif

    </div>

    @include('partials.public-footer')

</div>

@push('head')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css" />
<style>
    .leaflet-popup-content-wrapper { border-radius: 10px; box-shadow: 0 4px 20px rgba(0,0,0,.15); }
    .leaflet-popup-content { margin: 12px 14px; }
    .leaflet-popup-tip { background: #fff; }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>
<script>
function propertyMap() {
    return {
        map: null,
        cluster: null,
        fitDone: false,

        init() {
            this.$nextTick(() => {
                if (typeof L === 'undefined') { return; }
                this.initLeaflet();
                this.$watch('$wire.mapMarkersJson', (val) => {
                    this.refreshMarkers(JSON.parse(val || '[]'));
                });
            });
        },

        initLeaflet() {
            this.map = L.map('property-map', { zoomControl: true });

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
            }).addTo(this.map);

            this.cluster = L.markerClusterGroup({
                chunkedLoading: true,
                maxClusterRadius: 60,
                showCoverageOnHover: false,
                iconCreateFunction(c) {
                    const n = c.getChildCount();
                    return L.divIcon({
                        className: '',
                        html: `<div style="background:#d97706;color:#fff;min-width:32px;height:32px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:13px;box-shadow:0 2px 8px rgba(0,0,0,.3);border:2px solid #fff">${n}</div>`,
                        iconSize: [32, 32],
                        iconAnchor: [16, 16],
                    });
                },
            });
            this.map.addLayer(this.cluster);

            const initial = JSON.parse(this.$wire.mapMarkersJson || '[]');
            this.refreshMarkers(initial);

            this.map.on('moveend zoomend', () => {
                if (this.$wire.mapFilter) {
                    const b = this.map.getBounds();
                    this.$wire.updateMapBounds(b.getNorth(), b.getSouth(), b.getEast(), b.getWest());
                }
            });
        },

        makeIcon(price) {
            const label = price ? '$' + parseInt(price).toLocaleString() : '●';
            return L.divIcon({
                className: '',
                html: `<div style="background:#d97706;color:#fff;padding:3px 8px;border-radius:5px;font-weight:700;font-size:11px;white-space:nowrap;box-shadow:0 2px 6px rgba(0,0,0,.25);position:relative;line-height:1.5">${label}<div style="position:absolute;bottom:-5px;left:50%;transform:translateX(-50%);width:0;height:0;border-left:5px solid transparent;border-right:5px solid transparent;border-top:5px solid #d97706"></div></div>`,
                iconSize: [null, null],
                iconAnchor: [price ? 28 : 8, 28],
            });
        },

        refreshMarkers(markers) {
            if (!this.cluster || !this.map) { return; }
            this.cluster.clearLayers();

            if (!markers || !markers.length) {
                if (!this.fitDone) { this.map.setView([25, 45], 4); this.fitDone = true; }
                return;
            }

            const layers = markers.map(p => {
                const m = L.marker([p.lat, p.lng], { icon: this.makeIcon(p.price) });
                const priceStr = p.price ? '$' + parseInt(p.price).toLocaleString() + '/mo' : '';
                m.bindPopup(
                    `<div style="min-width:150px;font-family:sans-serif">
                        <p style="font-weight:700;font-size:13px;margin:0 0 4px 0;color:#18181b">${p.title}</p>
                        ${priceStr ? `<p style="color:#d97706;font-weight:700;font-size:12px;margin:0 0 8px 0">${priceStr}</p>` : ''}
                        <a href="/properties/${p.slug}" style="display:block;background:#d97706;color:#fff;text-align:center;padding:5px 10px;border-radius:6px;text-decoration:none;font-size:12px;font-weight:600">View Details →</a>
                    </div>`,
                    { maxWidth: 220 }
                );
                return m;
            });

            this.cluster.addLayers(layers);

            if (!this.fitDone) {
                try {
                    this.map.fitBounds(this.cluster.getBounds(), { padding: [30, 30], maxZoom: 13 });
                } catch (_) {
                    this.map.setView([25, 45], 4);
                }
                this.fitDone = true;
            }
        },
    };
}
</script>
@endpush
