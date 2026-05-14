<div>

    <x-public.navbar :transparent="true" />

    {{-- ══ HERO ════════════════════════════════════════════════════════════════ --}}
    <section style="background: #1E2330; padding: 100px 6% 70px">
        <div style="max-width: 1200px; margin: 0 auto">
            {{-- Breadcrumb --}}
            <nav class="mb-6 flex items-center gap-2" style="font-size: 0.7rem; color: rgba(255,255,255,.4); letter-spacing: .1em; text-transform: uppercase">
                <a href="{{ route('home') }}" wire:navigate class="transition hover:text-white" style="color: rgba(255,255,255,.4)">{{ __('Home') }}</a>
                <span style="color: #B8962E">/</span>
                <span style="color: rgba(255,255,255,.7)">{{ __('Properties') }}</span>
            </nav>

            <div class="flex flex-wrap items-end justify-between gap-8">
                <div>
                    <div class="flex items-center gap-4 mb-4">
                        <div class="h-px w-8 shrink-0" style="background: #B8962E"></div>
                        <span style="font-size: 0.62rem; font-weight: 600; letter-spacing: .18em; text-transform: uppercase; color: #B8962E">{{ __('Our listings') }}</span>
                    </div>
                    <h1 class="font-serif" style="font-size: clamp(2.2rem,4vw,3.5rem); font-weight: 300; color: #fff; line-height: 1.1">
                        {{ __('Browse') }} <em class="italic">{{ __('properties') }}</em>
                    </h1>
                    <p style="margin-top: 12px; font-size: 0.85rem; color: rgba(255,255,255,.45)">
                        {{ __('Residential & commercial spaces across Côte d\'Ivoire') }}
                    </p>
                </div>

                {{-- Hero search --}}
                <div class="flex items-center gap-3 w-full max-w-md"
                     style="background: rgba(255,255,255,.07); border: 1px solid rgba(255,255,255,.12); padding: 14px 18px">
                    <svg class="size-4 shrink-0" style="color: #B8962E" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                    </svg>
                    <input wire:model.live.debounce.400ms="search" type="text"
                           placeholder="{{ __('Search by title, address or city…') }}"
                           style="flex: 1; background: transparent; font-size: 0.85rem; color: #fff; outline: none"
                           class="placeholder-white/30" />
                    @if($search)
                        <button wire:click="$set('search', '')" style="color: rgba(255,255,255,.4)" class="transition hover:text-white">
                            <svg class="size-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- ══ FILTER STRIP ════════════════════════════════════════════════════════ --}}
    <div style="background: #FAFAF7; border-bottom: 1px solid #E8E2D8; padding: 18px 6%">
        <div style="max-width: 1200px; margin: 0 auto" class="flex flex-wrap items-center gap-3">

            {{-- Property type tabs --}}
            <div class="flex items-center" style="border: 1px solid #E8E2D8; background: white">
                <button wire:click="$set('propertyType', '')"
                    style="padding: 8px 16px; font-size: 0.7rem; font-weight: 600; letter-spacing: .1em; text-transform: uppercase; transition: all .2s;
                        {{ $propertyType === '' ? 'background: #1E2330; color: #fff' : 'color: #1E2330' }}">
                    {{ __('All') }}
                </button>
                <button wire:click="$set('propertyType', 'residential')"
                    style="padding: 8px 16px; font-size: 0.7rem; font-weight: 600; letter-spacing: .1em; text-transform: uppercase; border-left: 1px solid #E8E2D8; transition: all .2s;
                        {{ $propertyType === 'residential' ? 'background: #1E2330; color: #fff' : 'color: #1E2330' }}">
                    {{ __('Residential') }}
                </button>
                <button wire:click="$set('propertyType', 'commercial')"
                    style="padding: 8px 16px; font-size: 0.7rem; font-weight: 600; letter-spacing: .1em; text-transform: uppercase; border-left: 1px solid #E8E2D8; transition: all .2s;
                        {{ $propertyType === 'commercial' ? 'background: #1E2330; color: #fff' : 'color: #1E2330' }}">
                    {{ __('Commercial') }}
                </button>
            </div>

            {{-- City --}}
            <div class="flex items-center gap-2" style="background: white; border: 1px solid #E8E2D8; padding: 8px 14px">
                <svg class="size-3.5 shrink-0" style="color: #B8962E" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/>
                </svg>
                <select wire:model.live="city" style="background: transparent; font-size: 0.78rem; color: #1E2330; outline: none; cursor: pointer; min-width: 100px">
                    <option value="">{{ __('All cities') }}</option>
                    @foreach($cities as $c)
                        <option value="{{ $c }}">{{ $c }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Pricing type --}}
            <div class="flex items-center gap-2" style="background: white; border: 1px solid #E8E2D8; padding: 8px 14px">
                <svg class="size-3.5 shrink-0" style="color: #B8962E" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/>
                </svg>
                <select wire:model.live="pricingType" style="background: transparent; font-size: 0.78rem; color: #1E2330; outline: none; cursor: pointer">
                    <option value="">{{ __('Any duration') }}</option>
                    @foreach($pricingTypes as $type)
                        <option value="{{ $type->value }}">{{ $type->label() }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Price range --}}
            <div class="flex items-center gap-2" style="background: white; border: 1px solid #E8E2D8; padding: 8px 14px">
                <span style="font-size: 0.7rem; color: #B8962E; font-weight: 600; letter-spacing: .08em; text-transform: uppercase">XOF</span>
                <input wire:model.live.debounce.600ms="minPrice" type="number" min="0" placeholder="{{ __('Min') }}"
                       style="width: 72px; background: transparent; font-size: 0.78rem; color: #1E2330; outline: none" />
                <span style="color: #E8E2D8">—</span>
                <input wire:model.live.debounce.600ms="maxPrice" type="number" min="0" placeholder="{{ __('Max') }}"
                       style="width: 72px; background: transparent; font-size: 0.78rem; color: #1E2330; outline: none" />
            </div>

            {{-- Short-term toggle --}}
            <button wire:click="$toggle('isShortTerm')"
                style="padding: 8px 16px; font-size: 0.7rem; font-weight: 600; letter-spacing: .1em; text-transform: uppercase; transition: all .2s;
                    border: 1px solid {{ $isShortTerm ? '#B8962E' : '#E8E2D8' }};
                    background: {{ $isShortTerm ? '#B8962E' : 'white' }};
                    color: {{ $isShortTerm ? '#fff' : '#1E2330' }}">
                <span class="flex items-center gap-1.5">
                    <svg class="size-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/></svg>
                    {{ __('Short-term') }}
                </span>
            </button>

            {{-- Map toggle --}}
            <button wire:click="toggleShowMap"
                style="padding: 8px 16px; font-size: 0.7rem; font-weight: 600; letter-spacing: .1em; text-transform: uppercase; transition: all .2s;
                    border: 1px solid {{ $showMap ? '#1E2330' : '#E8E2D8' }};
                    background: {{ $showMap ? '#1E2330' : 'white' }};
                    color: {{ $showMap ? '#fff' : '#1E2330' }}">
                <span class="flex items-center gap-1.5">
                    <svg class="size-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75V15m6-6v8.25m.503 3.498 4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 0 0-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0Z"/></svg>
                    {{ $showMap ? __('Hide Map') : __('Map') }}
                </span>
            </button>

            {{-- Sort --}}
            <div class="ml-auto flex items-center gap-2">
                <span style="font-size: 0.7rem; color: #B8962E; font-weight: 600; letter-spacing: .1em; text-transform: uppercase">{{ __('Sort') }}</span>
                <select wire:model.live="sort" style="background: white; border: 1px solid #E8E2D8; padding: 8px 12px; font-size: 0.78rem; color: #1E2330; outline: none; cursor: pointer">
                    <option value="newest">{{ __('Newest') }}</option>
                    <option value="popular">{{ __('Most Popular') }}</option>
                    <option value="rating">{{ __('Top Rated') }}</option>
                    <option value="price_asc">{{ __('Price ↑') }}</option>
                    <option value="price_desc">{{ __('Price ↓') }}</option>
                </select>
            </div>
        </div>
    </div>

    {{-- ══ MAIN CONTENT ════════════════════════════════════════════════════════ --}}
    <section style="background: #FAFAF7; padding: 50px 6% 90px">
        <div style="max-width: 1200px; margin: 0 auto">

            {{-- Results + active chips --}}
            <div class="mb-8 flex flex-wrap items-center justify-between gap-3">
                <p style="font-size: 0.8rem; color: #1E2330">
                    <strong>{{ $totalCount }}</strong>
                    <span style="color: rgba(30,35,48,.5)"> {{ __('properties found') }}
                        @if($search) {{ __('for') }} "{{ $search }}"@endif
                    </span>
                </p>
                @if($search || $pricingType || $city || $propertyType || $minPrice || $maxPrice)
                    <div class="flex flex-wrap items-center gap-2">
                        @if($propertyType)
                            <span class="inline-flex items-center gap-1.5" style="border: 1px solid #E8E2D8; background: white; padding: 4px 12px; font-size: 0.68rem; font-weight: 600; letter-spacing: .1em; text-transform: uppercase; color: #1E2330">
                                {{ ucfirst($propertyType) }}
                                <button wire:click="$set('propertyType', '')" style="color: #B8962E">&times;</button>
                            </span>
                        @endif
                        @if($city)
                            <span class="inline-flex items-center gap-1.5" style="border: 1px solid #E8E2D8; background: white; padding: 4px 12px; font-size: 0.68rem; font-weight: 600; letter-spacing: .1em; text-transform: uppercase; color: #1E2330">
                                {{ $city }}
                                <button wire:click="$set('city', '')" style="color: #B8962E">&times;</button>
                            </span>
                        @endif
                        @if($pricingType)
                            <span class="inline-flex items-center gap-1.5" style="border: 1px solid #E8E2D8; background: white; padding: 4px 12px; font-size: 0.68rem; font-weight: 600; letter-spacing: .1em; text-transform: uppercase; color: #1E2330">
                                {{ ucfirst($pricingType) }}
                                <button wire:click="$set('pricingType', '')" style="color: #B8962E">&times;</button>
                            </span>
                        @endif
                        @if($minPrice || $maxPrice)
                            <span class="inline-flex items-center gap-1.5" style="border: 1px solid #E8E2D8; background: white; padding: 4px 12px; font-size: 0.68rem; font-weight: 600; color: #1E2330">
                                @if($minPrice && $maxPrice) {{ number_format($minPrice) }} – {{ number_format($maxPrice) }}
                                @elseif($minPrice) {{ __('From') }} {{ number_format($minPrice) }}
                                @else {{ __('Up to') }} {{ number_format($maxPrice) }}
                                @endif
                                <button wire:click="$set('minPrice', 0); $set('maxPrice', 0)" style="color: #B8962E">&times;</button>
                            </span>
                        @endif
                        <button wire:click="clearFilters" style="font-size: 0.7rem; color: rgba(30,35,48,.4); text-decoration: underline" class="transition hover:opacity-70">{{ __('Clear all') }}</button>
                    </div>
                @endif
            </div>

            {{-- Map panel --}}
            @if($showMap)
                <div class="mb-8 overflow-hidden" style="border: 1px solid #E8E2D8; background: white"
                     x-data="propertyMap()" x-init="init()">
                    <div class="flex items-center justify-between" style="border-bottom: 1px solid #E8E2D8; padding: 14px 20px">
                        <span style="font-size: 0.7rem; font-weight: 600; letter-spacing: .1em; text-transform: uppercase; color: #1E2330">{{ __('Property Map') }}</span>
                        <div class="flex items-center gap-3">
                            <button wire:click="toggleMapFilter"
                                style="padding: 6px 14px; font-size: 0.68rem; font-weight: 600; letter-spacing: .08em; text-transform: uppercase; transition: all .2s;
                                    border: 1px solid {{ $mapFilter ? '#1E2330' : '#E8E2D8' }};
                                    background: {{ $mapFilter ? '#1E2330' : 'transparent' }};
                                    color: {{ $mapFilter ? '#fff' : '#1E2330' }}">
                                {{ $mapFilter ? __('Clear area filter') . ' ×' : __('Filter by area') }}
                            </button>
                            <button wire:click="toggleShowMap" style="font-size: 0.7rem; color: rgba(30,35,48,.4)" class="transition hover:opacity-70">&times; {{ __('Close') }}</button>
                        </div>
                    </div>
                    <div id="property-map" class="h-[420px] w-full"></div>
                    <p style="padding: 10px; text-align: center; font-size: 0.7rem; color: {{ $mapFilter ? '#B8962E' : 'rgba(30,35,48,.35)' }}">
                        {{ $mapFilter ? __('Showing properties in current map view — pan to update') : __('Pan or zoom, then click "Filter by area" to filter results') }}
                    </p>
                </div>
            @endif

            {{-- Loading state --}}
            <div wire:loading.class.remove="hidden" class="hidden mb-6">
                <div class="flex items-center gap-3" style="padding: 14px 20px; background: white; border: 1px solid #E8E2D8">
                    <div class="size-4 rounded-full border-2 animate-spin" style="border-color: #E8E2D8; border-top-color: #B8962E"></div>
                    <span style="font-size: 0.78rem; color: rgba(30,35,48,.5)">{{ __('Updating results…') }}</span>
                </div>
            </div>

            {{-- Empty state --}}
            @if($properties->isEmpty())
                <div class="flex flex-col items-center justify-center py-24 text-center" style="border: 1px solid #E8E2D8; background: white">
                    <svg class="mb-5" style="width: 48px; height: 48px; color: #E8E2D8" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21"/>
                    </svg>
                    <h3 class="font-serif" style="font-size: 1.3rem; font-weight: 300; color: #1E2330; margin-bottom: 8px">{{ __('No properties found') }}</h3>
                    <p style="font-size: 0.82rem; color: rgba(30,35,48,.5); margin-bottom: 28px">{{ __('Try adjusting your filters or search term.') }}</p>
                    <button wire:click="clearFilters"
                        style="padding: 12px 32px; font-size: 0.7rem; font-weight: 600; letter-spacing: .14em; text-transform: uppercase; background: #1E2330; color: white; transition: opacity .2s"
                        class="hover:opacity-80">
                        {{ __('Clear Filters') }}
                    </button>
                </div>

            {{-- ── Card grid ───────────────────────────────────────────────── --}}
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 transition-opacity duration-300"
                     style="gap: 2px; background: #E8E2D8"
                     wire:loading.class="opacity-50">
                    @foreach($properties as $property)
                        @php
                            $img        = $property->images->first();
                            $price      = $property->monthly_rent ?? $property->weekly_rent ?? $property->daily_rent ?? $property->yearly_rent;
                            $priceLabel = $property->monthly_rent ? __('/mois') : ($property->weekly_rent ? __('/sem.') : ($property->daily_rent ? __('/jour') : __('/an')));
                            $currSym    = match(strtoupper($property->currency ?? 'XOF')) {
                                'EUR' => '€ ', 'GBP' => '£ ', 'USD' => '$ ',
                                'SAR' => 'SAR ', 'AED' => 'AED ',
                                default => 'XOF ',
                            };
                            $isOccupied = $property->contracts->isNotEmpty();
                            $isFav      = in_array($property->id, $favoritedIds);
                            $rating     = $property->reviews_avg_rating ? round((float)$property->reviews_avg_rating, 1) : null;
                            $propType    = $property->property_type?->value ?? 'residential';
                            $isShortTerm = (bool) $property->is_short_term;
                        @endphp

                        <a href="{{ route('properties.show', $property->slug) }}" wire:navigate
                           class="group block bg-white overflow-hidden no-underline relative transition-transform duration-[400ms] hover:scale-[.985] hover:shadow-[0_20px_60px_rgba(0,0,0,.1)] hover:z-10">

                            {{-- Image --}}
                            <div class="relative overflow-hidden" style="height: 240px">
                                @if($img)
                                    <img src="{{ $img->url() }}" alt="{{ $property->title }}"
                                         class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-[1.06]" />
                                @else
                                    <div class="w-full h-full flex items-center justify-center" style="background: #F4EFE8">
                                        <svg class="size-12" style="color: #E8E2D8" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/>
                                        </svg>
                                    </div>
                                @endif

                                {{-- Status badge --}}
                                <div class="absolute top-0 left-0 px-3.5 py-2 text-[0.58rem] font-semibold tracking-[.14em] uppercase"
                                     style="{{ $isOccupied ? 'background: #B8962E; color: white' : 'background: #1E2330; color: white' }}">
                                    {{ $isOccupied ? __('Occupied') : __('Available') }}
                                </div>

                                {{-- Property type badge --}}
                                <div class="absolute top-0 right-0 px-3 py-2 text-[0.55rem] font-semibold tracking-[.12em] uppercase"
                                     style="{{ $propType === 'commercial' ? 'background: rgba(30,35,48,.85); color: rgba(255,255,255,.7)' : 'background: rgba(184,150,46,.15); color: #B8962E' }}">
                                    {{ $propType === 'commercial' ? __('Commercial') : __('Résidentiel') }}
                                </div>

                                {{-- Short-term badge --}}
                                @if($isShortTerm)
                                    <div class="absolute bottom-0 left-0 px-3 py-1.5 text-[0.55rem] font-semibold tracking-[.12em] uppercase"
                                         style="background: rgba(184,150,46,.9); color: white">
                                        {{ __('Short-term') }}
                                    </div>
                                @endif

                                {{-- Fav --}}
                                <button onclick="event.preventDefault()"
                                        wire:click.stop="toggleFavorite({{ $property->id }})"
                                        class="absolute bottom-3.5 right-3.5 flex items-center justify-center size-[34px] bg-white/90 shadow-md transition-transform duration-200 hover:scale-110"
                                        style="{{ $isFav ? 'color: #B8962E' : 'color: rgba(30,35,48,.4)' }}">
                                    <svg class="size-4" fill="{{ $isFav ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/>
                                    </svg>
                                </button>
                            </div>

                            {{-- Card body --}}
                            <div style="padding: 22px 24px 24px">
                                {{-- City rule --}}
                                <div class="flex items-center gap-4 mb-3">
                                    <div class="h-px flex-1" style="background: #E8E2D8"></div>
                                    <span style="font-size: 0.58rem; font-weight: 600; letter-spacing: .14em; text-transform: uppercase; color: #B8962E">
                                        {{ $property->city ?? __('Côte d\'Ivoire') }}
                                        @if($rating) · ★ {{ $rating }} @endif
                                    </span>
                                </div>

                                <h3 class="font-serif" style="font-size: 1rem; font-weight: 300; color: #1E2330; line-height: 1.3; margin-bottom: 16px">
                                    {{ $property->title }}
                                </h3>

                                <div class="flex items-end justify-between">
                                    <div>
                                        <span style="font-size: 0.6rem; color: rgba(30,35,48,.4); text-transform: uppercase; letter-spacing: .08em">{{ $priceLabel }}</span>
                                        @if($price)
                                            <p style="font-size: 1.05rem; font-weight: 600; color: #1E2330; margin-top: 2px">
                                                {{ $currSym }}{{ number_format($price) }}
                                            </p>
                                        @else
                                            <p style="font-size: 0.82rem; color: rgba(30,35,48,.4)">{{ __('Sur demande') }}</p>
                                        @endif
                                    </div>
                                    @if($property->size_sqm)
                                        <span style="font-size: 0.72rem; color: rgba(30,35,48,.45)">{{ number_format($property->size_sqm) }} m²</span>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if($properties->hasPages())
                    <div class="mt-12 flex items-center justify-center gap-1">
                        @if($properties->onFirstPage())
                            <span class="flex size-9 items-center justify-center" style="border: 1px solid #E8E2D8; color: #E8E2D8">
                                <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/></svg>
                            </span>
                        @else
                            <button wire:click="previousPage" class="flex size-9 items-center justify-center transition hover:opacity-70" style="border: 1px solid #E8E2D8; color: #1E2330">
                                <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/></svg>
                            </button>
                        @endif
                        @foreach($properties->getUrlRange(max(1, $properties->currentPage() - 2), min($properties->lastPage(), $properties->currentPage() + 2)) as $page => $url)
                            @if($page === $properties->currentPage())
                                <span class="flex size-9 items-center justify-center text-sm font-semibold" style="background: #1E2330; color: white">{{ $page }}</span>
                            @else
                                <button wire:click="gotoPage({{ $page }})" class="flex size-9 items-center justify-center text-sm transition hover:opacity-70" style="border: 1px solid #E8E2D8; color: #1E2330">{{ $page }}</button>
                            @endif
                        @endforeach
                        @if($properties->hasMorePages())
                            <button wire:click="nextPage" class="flex size-9 items-center justify-center transition hover:opacity-70" style="border: 1px solid #E8E2D8; color: #1E2330">
                                <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
                            </button>
                        @else
                            <span class="flex size-9 items-center justify-center" style="border: 1px solid #E8E2D8; color: #E8E2D8">
                                <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
                            </span>
                        @endif
                    </div>
                    <p class="mt-4 text-center" style="font-size: 0.72rem; color: rgba(30,35,48,.4)">
                        {{ __('Showing') }} {{ $properties->firstItem() }}–{{ $properties->lastItem() }} {{ __('of') }} {{ $properties->total() }} {{ __('results') }}
                    </p>
                @endif
            @endif

        </div>
    </section>

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
