<div>

    <x-public.navbar />

    {{-- ══ PAGE HEADER ══════════════════════════════════════════════════════ --}}
    <section class="bg-zinc-900 py-12">
        <div class="mx-auto max-w-7xl px-6 lg:px-10">
            <nav class="mb-3 flex items-center gap-2 text-xs text-zinc-400">
                <a href="{{ route('home') }}" wire:navigate class="transition hover:text-amber-400">{{ __('Home') }}</a>
                <svg class="size-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                <span class="text-zinc-300">{{ __('Properties') }}</span>
            </nav>
            <h1 class="text-3xl font-extrabold text-white">{{ __('Browse Properties') }}</h1>
            <p class="mt-2 text-sm text-zinc-400">{{ __('Discover available spaces perfect for your business') }}</p>

            {{-- Inline search bar --}}
            <div class="mt-6 flex max-w-xl items-center gap-3 rounded-2xl bg-white/10 px-4 py-3 backdrop-blur-sm border border-white/10">
                <svg class="size-5 shrink-0 text-zinc-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" /></svg>
                <input
                    wire:model.live.debounce.400ms="search"
                    type="text"
                    placeholder="{{ __('Search by title, address or city...') }}"
                    class="flex-1 bg-transparent text-sm text-white placeholder-zinc-400 outline-none"
                />
                @if($search)
                    <button wire:click="$set('search', '')" class="text-zinc-400 transition hover:text-white">
                        <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                    </button>
                @endif
            </div>
        </div>
    </section>

    {{-- ══ MAIN CONTENT ══════════════════════════════════════════════════════ --}}
    <div class="mx-auto max-w-7xl px-6 py-10 lg:px-10">
        <div class="flex flex-col gap-8 lg:flex-row lg:items-start">

            {{-- ── FILTER SIDEBAR ──────────────────────────────────────────── --}}
            <aside class="w-full shrink-0 lg:w-64">
                <div class="rounded-2xl border border-zinc-100 bg-white p-5 shadow-sm">

                    <div class="mb-5 flex items-center justify-between">
                        <h3 class="text-sm font-bold text-zinc-900">{{ __('Filters') }}</h3>
                        <button wire:click="clearFilters" class="text-xs font-medium text-amber-600 transition hover:text-amber-700">
                            {{ __('Clear all') }}
                        </button>
                    </div>

                    {{-- Pricing Type --}}
                    <div class="mb-5">
                        <label class="mb-2 block text-xs font-semibold uppercase tracking-wider text-zinc-500">{{ __('Pricing Type') }}</label>
                        <div class="flex flex-col gap-1.5">
                            <label class="flex cursor-pointer items-center gap-2">
                                <input type="radio" wire:model.live="pricingType" value="" class="accent-amber-600" />
                                <span class="text-sm text-zinc-700">{{ __('Any') }}</span>
                            </label>
                            @foreach($pricingTypes as $type)
                                <label class="flex cursor-pointer items-center gap-2">
                                    <input type="radio" wire:model.live="pricingType" value="{{ $type->value }}" class="accent-amber-600" />
                                    <span class="text-sm text-zinc-700">{{ $type->label() }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- City --}}
                    @if($cities->isNotEmpty())
                        <div class="mb-5">
                            <label class="mb-2 block text-xs font-semibold uppercase tracking-wider text-zinc-500">{{ __('City') }}</label>
                            <select wire:model.live="city" class="w-full rounded-lg border border-zinc-200 bg-zinc-50 px-3 py-2 text-sm text-zinc-700 outline-none focus:border-amber-400 focus:ring-2 focus:ring-amber-100">
                                <option value="">{{ __('All cities') }}</option>
                                @foreach($cities as $c)
                                    <option value="{{ $c }}">{{ $c }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    {{-- Price Range --}}
                    <div class="mb-5">
                        <label class="mb-2 block text-xs font-semibold uppercase tracking-wider text-zinc-500">{{ __('Price Range') }}</label>
                        <div class="flex items-center gap-2">
                            <div class="relative flex-1">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs text-zinc-400">$</span>
                                <input
                                    wire:model.live.debounce.600ms="minPrice"
                                    type="number"
                                    min="0"
                                    placeholder="Min"
                                    class="w-full rounded-lg border border-zinc-200 bg-zinc-50 pl-6 pr-2 py-2 text-sm text-zinc-700 outline-none focus:border-amber-400 focus:ring-2 focus:ring-amber-100"
                                />
                            </div>
                            <span class="text-xs text-zinc-400">—</span>
                            <div class="relative flex-1">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs text-zinc-400">$</span>
                                <input
                                    wire:model.live.debounce.600ms="maxPrice"
                                    type="number"
                                    min="0"
                                    placeholder="Max"
                                    class="w-full rounded-lg border border-zinc-200 bg-zinc-50 pl-6 pr-2 py-2 text-sm text-zinc-700 outline-none focus:border-amber-400 focus:ring-2 focus:ring-amber-100"
                                />
                            </div>
                        </div>
                    </div>

                    {{-- Featured Only --}}
                    <div>
                        <label class="flex cursor-pointer items-center gap-3">
                            <div class="relative">
                                <input type="checkbox" wire:model.live="featuredOnly" class="peer sr-only" />
                                <div class="h-5 w-9 rounded-full border border-zinc-200 bg-zinc-100 transition peer-checked:border-amber-500 peer-checked:bg-amber-500"></div>
                                <div class="absolute left-0.5 top-0.5 size-4 rounded-full bg-white shadow transition peer-checked:translate-x-4"></div>
                            </div>
                            <span class="text-sm font-medium text-zinc-700">{{ __('Featured only') }}</span>
                        </label>
                    </div>

                </div>
            </aside>

            {{-- ── RESULTS AREA ─────────────────────────────────────────────── --}}
            <div class="flex-1 min-w-0">

                {{-- Results bar --}}
                <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
                    <p class="text-sm text-zinc-500">
                        <span class="font-semibold text-zinc-900">{{ $totalCount }}</span> {{ __('properties found') }}
                        @if($search)
                            {{ __('for') }} <span class="font-medium text-amber-600">"{{ $search }}"</span>
                        @endif
                    </p>

                    {{-- Sort --}}
                    <div class="flex items-center gap-2">
                        <label class="text-xs text-zinc-500">{{ __('Sort:') }}</label>
                        <select wire:model.live="sort" class="rounded-lg border border-zinc-200 bg-white px-3 py-1.5 text-sm text-zinc-700 outline-none focus:border-amber-400 focus:ring-2 focus:ring-amber-100">
                            <option value="newest">{{ __('Newest') }}</option>
                            <option value="popular">{{ __('Most Popular') }}</option>
                            <option value="rating">{{ __('Top Rated') }}</option>
                            <option value="price_asc">{{ __('Price: Low to High') }}</option>
                            <option value="price_desc">{{ __('Price: High to Low') }}</option>
                        </select>
                    </div>
                </div>

                {{-- Active filter chips --}}
                @if($search || $pricingType || $city || $featuredOnly || $minPrice || $maxPrice)
                    <div class="mb-4 flex flex-wrap gap-2">
                        @if($search)
                            <span class="inline-flex items-center gap-1 rounded-full bg-amber-50 border border-amber-200 px-3 py-1 text-xs font-medium text-amber-700">
                                "{{ $search }}"
                                <button wire:click="$set('search', '')" class="ml-1 text-amber-500 hover:text-amber-700">&times;</button>
                            </span>
                        @endif
                        @if($pricingType)
                            <span class="inline-flex items-center gap-1 rounded-full bg-blue-50 border border-blue-200 px-3 py-1 text-xs font-medium text-blue-700">
                                {{ ucfirst($pricingType) }}
                                <button wire:click="$set('pricingType', '')" class="ml-1 text-blue-500 hover:text-blue-700">&times;</button>
                            </span>
                        @endif
                        @if($city)
                            <span class="inline-flex items-center gap-1 rounded-full bg-green-50 border border-green-200 px-3 py-1 text-xs font-medium text-green-700">
                                {{ $city }}
                                <button wire:click="$set('city', '')" class="ml-1 text-green-500 hover:text-green-700">&times;</button>
                            </span>
                        @endif
                        @if($featuredOnly)
                            <span class="inline-flex items-center gap-1 rounded-full bg-violet-50 border border-violet-200 px-3 py-1 text-xs font-medium text-violet-700">
                                {{ __('Featured') }}
                                <button wire:click="$set('featuredOnly', false)" class="ml-1 text-violet-500 hover:text-violet-700">&times;</button>
                            </span>
                        @endif
                        @if($minPrice || $maxPrice)
                            <span class="inline-flex items-center gap-1 rounded-full bg-zinc-100 border border-zinc-200 px-3 py-1 text-xs font-medium text-zinc-700">
                                @if($minPrice && $maxPrice) ${{ number_format($minPrice) }} – ${{ number_format($maxPrice) }}
                                @elseif($minPrice) {{ __('From') }} ${{ number_format($minPrice) }}
                                @else {{ __('Up to') }} ${{ number_format($maxPrice) }}
                                @endif
                                <button wire:click="$set('minPrice', 0); $set('maxPrice', 0)" class="ml-1 text-zinc-400 hover:text-zinc-700">&times;</button>
                            </span>
                        @endif
                    </div>
                @endif

                {{-- Loading overlay --}}
                <div wire:loading.class.remove="hidden" class="hidden">
                    <div class="mb-4 text-center text-sm text-zinc-400">{{ __('Updating results...') }}</div>
                </div>

                {{-- Empty state --}}
                @if($properties->isEmpty())
                    <div class="flex flex-col items-center justify-center rounded-2xl border border-dashed border-zinc-200 bg-zinc-50 py-20 text-center">
                        <svg class="mb-4 size-12 text-zinc-300" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Z" /></svg>
                        <p class="text-base font-semibold text-zinc-500">{{ __('No properties found') }}</p>
                        <p class="mt-1 text-sm text-zinc-400">{{ __('Try adjusting your filters or search term.') }}</p>
                        <button wire:click="clearFilters" class="mt-5 rounded-xl bg-amber-600 px-5 py-2 text-sm font-semibold text-white transition hover:bg-amber-700">
                            {{ __('Clear Filters') }}
                        </button>
                    </div>

                {{-- Property grid --}}
                @else
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-3" wire:loading.class="opacity-60 transition-opacity">
                        @foreach($properties as $property)
                            @php
                                $gradients = ['from-amber-300 to-orange-500','from-blue-300 to-blue-500','from-green-300 to-emerald-500','from-violet-300 to-purple-500','from-rose-300 to-pink-500','from-cyan-300 to-sky-500'];
                                $g = $gradients[$property->id % count($gradients)];
                                $img = $property->images->first();
                                $rating = $property->reviews_avg_rating ? round((float)$property->reviews_avg_rating, 1) : null;
                                $price = $property->monthly_rent ?? $property->weekly_rent ?? $property->daily_rent ?? $property->yearly_rent;
                                $priceLabel = $property->monthly_rent ? __('/mo') : ($property->weekly_rent ? __('/wk') : ($property->daily_rent ? __('/day') : __('/yr')));
                                $isOccupied = $property->contracts->isNotEmpty();
                            @endphp
                            <div class="group overflow-hidden rounded-2xl border border-zinc-100 bg-white shadow-sm transition hover:shadow-lg hover:-translate-y-0.5">

                                {{-- Image --}}
                                <div class="relative h-48 overflow-hidden bg-gradient-to-br {{ $g }}">
                                    @if($img)
                                        <img src="{{ $img->url() }}" alt="{{ $property->title }}" class="h-full w-full object-cover transition group-hover:scale-105" />
                                    @else
                                        <div class="flex h-full items-center justify-center">
                                            <svg class="size-12 text-white/40" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21" /></svg>
                                        </div>
                                    @endif

                                    {{-- Status badge --}}
                                    <span class="absolute left-3 top-3 rounded-full px-2.5 py-0.5 text-xs font-bold {{ $isOccupied ? 'bg-blue-500 text-white' : 'bg-green-500 text-white' }}">
                                        {{ $isOccupied ? __('Occupied') : __('Available') }}
                                    </span>

                                    @if($property->is_featured)
                                        <span class="absolute left-3 bottom-3 rounded-full bg-amber-500 px-2.5 py-0.5 text-xs font-bold text-white">
                                            {{ __('Featured') }}
                                        </span>
                                    @endif

                                    {{-- Favorites --}}
                                    <div class="absolute right-3 top-3 flex items-center gap-1 rounded-full bg-white/90 px-2 py-0.5 shadow-sm">
                                        <svg class="size-3.5 text-rose-500" fill="currentColor" viewBox="0 0 24 24"><path d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 01-.383-.218 25.18 25.18 0 01-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0112 5.052 5.5 5.5 0 0116.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 01-4.244 3.17 15.247 15.247 0 01-.383.219l-.022.012-.007.004-.003.001a.752.752 0 01-.704 0l-.003-.001z"/></svg>
                                        <span class="text-xs font-semibold text-zinc-700">{{ $property->favorites_count }}</span>
                                    </div>
                                </div>

                                {{-- Card body --}}
                                <div class="p-4">
                                    {{-- Rating --}}
                                    <div class="mb-2 flex items-center gap-1.5">
                                        @if($rating)
                                            <div class="flex items-center gap-0.5">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <svg class="size-3 {{ $i <= $rating ? 'text-amber-400' : 'text-zinc-200' }}" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd"/></svg>
                                                @endfor
                                            </div>
                                            <span class="text-xs font-semibold text-zinc-700">{{ $rating }}</span>
                                            <span class="text-xs text-zinc-400">({{ $property->reviews_count }})</span>
                                        @else
                                            <span class="text-xs text-zinc-400">{{ __('No reviews yet') }}</span>
                                        @endif
                                    </div>

                                    <h3 class="mb-1 text-sm font-bold text-zinc-900 leading-snug line-clamp-1">{{ $property->title }}</h3>

                                    @if($price)
                                        <p class="mb-2 text-base font-extrabold text-amber-600">
                                            ${{ number_format($price) }}<span class="text-xs font-normal text-zinc-400">{{ $priceLabel }}</span>
                                        </p>
                                    @endif

                                    <p class="flex items-center gap-1 text-xs text-zinc-500 truncate">
                                        <svg class="size-3.5 shrink-0 text-zinc-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                                        {{ implode(', ', array_filter([$property->address_line_1, $property->city])) ?: __('Location TBA') }}
                                    </p>

                                    @if($property->size_sqm)
                                        <p class="mt-1 flex items-center gap-1 text-xs text-zinc-400">
                                            <svg class="size-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3.75v4.5m0-4.5h4.5m-4.5 0L9 9M3.75 20.25v-4.5m0 4.5h4.5m-4.5 0L9 15M20.25 3.75h-4.5m4.5 0v4.5m0-4.5L15 9m5.25 11.25h-4.5m4.5 0v-4.5m0 4.5L15 15" /></svg>
                                            {{ number_format($property->size_sqm) }} m²
                                        </p>
                                    @endif

                                    <div class="mt-3 border-t border-zinc-50 pt-3">
                                        <a
                                            href="{{ route('properties.show', $property->slug) }}"
                                            wire:navigate
                                            class="block w-full rounded-lg bg-amber-50 py-2 text-center text-xs font-semibold text-amber-700 transition hover:bg-amber-600 hover:text-white"
                                        >
                                            {{ __('View Details') }}
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
        </div>
    </div>

    {{-- ══ FOOTER ════════════════════════════════════════════════════════════ --}}
    <footer class="border-t border-zinc-100 bg-white mt-10">
        <div class="mx-auto max-w-7xl px-6 py-10 lg:px-10">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <span class="text-base font-extrabold tracking-widest text-amber-600 uppercase">SAHOOME</span>
                <div class="flex gap-6 text-sm text-zinc-500">
                    <a href="{{ route('home') }}" wire:navigate class="transition hover:text-amber-600">{{ __('Home') }}</a>
                    <a href="{{ route('properties.index') }}" wire:navigate class="transition hover:text-amber-600">{{ __('Properties') }}</a>
                    <a href="{{ route('home') }}#contact" class="transition hover:text-amber-600">{{ __('Contact') }}</a>
                </div>
                <p class="text-xs text-zinc-400">&copy; {{ date('Y') }} {{ config('app.name') }}. {{ __('All Rights Reserved') }}.</p>
            </div>
        </div>
    </footer>

</div>
