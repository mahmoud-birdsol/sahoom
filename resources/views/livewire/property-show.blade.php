@php
    $gradients = ['from-amber-300 to-orange-500','from-blue-300 to-blue-500','from-green-300 to-emerald-500','from-violet-300 to-purple-500'];
    $g         = $gradients[$property->id % count($gradients)];
    $images    = $property->images;
    $isOccupied = $property->contracts->isNotEmpty();
    $rating    = $property->reviews_avg_rating ? round((float)$property->reviews_avg_rating, 1) : null;
@endphp

<div>

    <x-public.navbar />

    {{-- ══ BREADCRUMB ════════════════════════════════════════════════════════ --}}
    <div class="border-b border-zinc-100 bg-white">
        <div class="mx-auto max-w-7xl px-6 py-3 lg:px-10">
            <nav class="flex items-center gap-1.5 text-xs text-zinc-400">
                <a href="{{ route('home') }}" wire:navigate class="transition hover:text-amber-600">{{ __('Home') }}</a>
                <svg class="size-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                <a href="{{ route('properties.index') }}" wire:navigate class="transition hover:text-amber-600">{{ __('Properties') }}</a>
                <svg class="size-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                <span class="truncate max-w-xs text-zinc-700">{{ $property->title }}</span>
            </nav>
        </div>
    </div>

    {{-- ══ MAIN WRAPPER ══════════════════════════════════════════════════════ --}}
    <div class="mx-auto max-w-7xl px-6 py-8 lg:px-10">
        <div class="flex flex-col gap-8 lg:flex-row lg:items-start">

            {{-- ══ LEFT / MAIN COLUMN ════════════════════════════════════════ --}}
            <div class="flex-1 min-w-0 space-y-7">

                {{-- ── TITLE + FAVORITE ──────────────────────────────────── --}}
                <div>
                    <div class="flex items-start justify-between gap-4">
                        <h1 class="text-2xl font-extrabold text-zinc-900 lg:text-3xl leading-snug">{{ $property->title }}</h1>
                        <button wire:click="toggleFavorite"
                            class="flex size-9 shrink-0 items-center justify-center rounded-full border transition
                                {{ $this->isFavorited ? 'border-rose-300 bg-rose-50 text-rose-500' : 'border-zinc-200 text-zinc-400 hover:border-rose-300 hover:text-rose-500' }}">
                            <svg class="size-5" fill="{{ $this->isFavorited ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/></svg>
                        </button>
                    </div>
                    <p class="mt-2 flex items-center gap-1.5 text-sm text-zinc-500">
                        <svg class="size-4 shrink-0 text-zinc-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                        {{ implode(', ', array_filter([$property->address_line_1, $property->city, $property->state, $property->postal_code])) ?: __('Location TBA') }}
                    </p>
                </div>

                {{-- ── IMAGE GALLERY ─────────────────────────────────────── --}}
                @php $price = $property->monthly_rent ?? $property->weekly_rent ?? $property->daily_rent ?? $property->yearly_rent; @endphp

                {{-- Large left + 2×2 grid right --}}
                <div class="grid grid-cols-5 gap-2 overflow-hidden rounded-2xl">
                    {{-- Main large image --}}
                    <div class="relative col-span-5 h-64 overflow-hidden rounded-2xl bg-gradient-to-br {{ $g }} md:col-span-3 md:h-80">
                        @if($images->isNotEmpty())
                            <img src="{{ $images->first()->url() }}" alt="{{ $property->title }}" class="h-full w-full object-cover" />
                        @else
                            <div class="flex h-full items-center justify-center">
                                <svg class="size-16 text-white/30" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21" /></svg>
                            </div>
                        @endif
                        <span class="absolute left-3 top-3 rounded-full px-2.5 py-0.5 text-xs font-bold {{ $isOccupied ? 'bg-blue-500 text-white' : 'bg-green-500 text-white' }}">
                            {{ $isOccupied ? __('Occupied') : __('Available') }}
                        </span>
                        @if($property->is_featured)
                            <span class="absolute left-3 bottom-3 rounded-full bg-amber-500 px-2.5 py-0.5 text-xs font-bold text-white">{{ __('Featured') }}</span>
                        @endif
                    </div>

                    {{-- 2×2 thumbnail grid --}}
                    <div class="col-span-5 grid grid-cols-2 gap-2 md:col-span-2">
                        @foreach(collect([1,2,3,4]) as $i)
                            @php $thumb = $images->get($i); @endphp
                            <div class="h-32 overflow-hidden rounded-xl bg-gradient-to-br {{ $g }} md:h-auto">
                                @if($thumb)
                                    <img src="{{ $thumb->url() }}" alt="" class="h-full w-full object-cover" />
                                @else
                                    <div class="flex h-full min-h-[6rem] items-center justify-center opacity-60">
                                        <svg class="size-8 text-white/50" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" /></svg>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- ── PROPERTY DETAILS CARD ──────────────────────────── --}}
                <div class="rounded-2xl border border-zinc-100 bg-white p-6 shadow-sm">
                    <h2 class="mb-4 text-lg font-bold text-zinc-900">{{ __('Property Details') }}</h2>

                    {{-- Feature badges --}}
                    <div class="mb-4 flex flex-wrap gap-3">
                        @if($property->floor)
                            <div class="flex flex-col items-center gap-1.5 rounded-xl border border-zinc-100 bg-zinc-50 px-5 py-3 text-center">
                                <svg class="size-5 text-zinc-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" /></svg>
                                <span class="text-xs font-semibold text-zinc-700">{{ $property->floor }} {{ __('Floor') }}</span>
                            </div>
                        @endif
                        @if($property->size_sqm)
                            <div class="flex flex-col items-center gap-1.5 rounded-xl border border-zinc-100 bg-zinc-50 px-5 py-3 text-center">
                                <svg class="size-5 text-zinc-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3.75v4.5m0-4.5h4.5m-4.5 0L9 9M3.75 20.25v-4.5m0 4.5h4.5m-4.5 0L9 15M20.25 3.75h-4.5m4.5 0v4.5m0-4.5L15 9m5.25 11.25h-4.5m4.5 0v-4.5m0 4.5L15 15" /></svg>
                                <span class="text-xs font-semibold text-zinc-700">{{ number_format($property->size_sqm) }} m²</span>
                            </div>
                        @endif
                        @if($property->space_number)
                            <div class="flex flex-col items-center gap-1.5 rounded-xl border border-zinc-100 bg-zinc-50 px-5 py-3 text-center">
                                <svg class="size-5 text-zinc-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 0 0 3.75.614" /></svg>
                                <span class="text-xs font-semibold text-zinc-700">{{ __('Unit') }}: {{ $property->space_number }}</span>
                            </div>
                        @endif
                        @if($property->traffic_score)
                            <div class="flex flex-col items-center gap-1.5 rounded-xl border border-zinc-100 bg-zinc-50 px-5 py-3 text-center">
                                <svg class="size-5 text-zinc-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" /></svg>
                                <span class="text-xs font-semibold text-zinc-700">{{ __('Traffic') }}: {{ $property->traffic_score }}</span>
                            </div>
                        @endif
                    </div>

                    {{-- Description --}}
                    @if($property->description)
                        <p class="text-sm leading-relaxed text-zinc-600">{!! nl2br(e($property->description)) !!}</p>
                    @endif

                    {{-- Amenities --}}
                    @if($property->amenities->isNotEmpty())
                        <div class="mt-4 pt-4 border-t border-zinc-50 flex flex-wrap gap-2">
                            @foreach($property->amenities as $amenity)
                                <span class="flex items-center gap-1 rounded-full border border-amber-100 bg-amber-50 px-3 py-1 text-xs font-medium text-amber-700">
                                    <svg class="size-3 text-amber-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
                                    {{ $amenity->name }}
                                </span>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- ── WRITE A REVIEW ────────────────────────────────────── --}}
                @if($this->canWriteReview)
                <div class="rounded-2xl border border-zinc-100 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-bold text-zinc-900">{{ __('Write a Review') }}</h2>
                    <p class="mt-0.5 text-sm text-zinc-400">{{ __('Share your thoughts about this property') }}</p>

                    @if($reviewSent)
                        <div class="mt-4 rounded-xl bg-green-50 border border-green-200 px-4 py-3 text-sm font-medium text-green-700">
                            {{ __('Thank you! Your review has been submitted.') }}
                        </div>
                    @else
                        <form wire:submit="submitReview" class="mt-4">
                            <label class="mb-2 block text-xs font-semibold text-zinc-600">{{ __('Your Rating') }}</label>
                            <div class="mb-4 flex items-center gap-1">
                                @for($star = 1; $star <= 5; $star++)
                                    <button type="button" wire:click="$set('reviewRating', {{ $star }})" class="transition hover:scale-110">
                                        <svg class="size-7 {{ $reviewRating >= $star ? 'text-amber-400' : 'text-zinc-200' }} transition" fill="currentColor" viewBox="0 0 24 24">
                                            <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd"/>
                                        </svg>
                                    </button>
                                @endfor
                            </div>
                            @error('reviewRating') <p class="mb-2 text-xs text-red-500">{{ $message }}</p> @enderror
                            <textarea wire:model="reviewText" rows="3" placeholder="{{ __('Optional: Share more details...') }}"
                                class="w-full rounded-xl border border-zinc-200 px-3 py-2 text-sm outline-none placeholder-zinc-400 focus:border-amber-400 focus:ring-2 focus:ring-amber-100 transition resize-none mb-3"></textarea>
                            <button type="submit"
                                class="rounded-xl bg-amber-600 px-6 py-2.5 text-sm font-bold text-white transition hover:bg-amber-700"
                                wire:loading.attr="disabled" wire:loading.class="opacity-75">
                                {{ __('Rate now') }}
                            </button>
                        </form>
                    @endif
                </div>
                @endif

                {{-- ── LOCATION ──────────────────────────────────────────── --}}
                <div class="rounded-2xl border border-zinc-100 bg-white p-6 shadow-sm">
                    <h2 class="mb-4 text-lg font-bold text-zinc-900">{{ __('Location') }}</h2>

                    @if($property->latitude && $property->longitude)
                        <div class="overflow-hidden rounded-xl border border-zinc-100">
                            <iframe
                                src="https://www.openstreetmap.org/export/embed.html?bbox={{ $property->longitude - 0.01 }},{{ $property->latitude - 0.01 }},{{ $property->longitude + 0.01 }},{{ $property->latitude + 0.01 }}&layer=mapnik&marker={{ $property->latitude }},{{ $property->longitude }}"
                                width="100%" height="240" style="border:0" loading="lazy"
                                class="w-full"
                            ></iframe>
                        </div>
                    @else
                        <div class="flex h-48 items-center justify-center overflow-hidden rounded-xl bg-zinc-100">
                            <div class="text-center">
                                <svg class="mx-auto mb-2 size-10 text-zinc-300" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75V15m6-6v8.25m.503 3.498 4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 0 0-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0Z" /></svg>
                                <p class="text-sm text-zinc-400">{{ implode(', ', array_filter([$property->address_line_1, $property->city])) ?: __('Location not specified') }}</p>
                            </div>
                        </div>
                    @endif

                    {{-- POI tags from nearby_places --}}
                    @if($property->nearby_places && count($property->nearby_places) > 0)
                        <div class="mt-4 flex flex-wrap gap-3 text-xs text-zinc-500">
                            @foreach($property->nearby_places as $placeName => $placeDistance)
                                <span class="flex items-center gap-1.5">
                                    <svg class="size-3.5 text-amber-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                                    {{ $placeName }}{{ $placeDistance ? ' · ' . $placeDistance : '' }}
                                </span>
                            @endforeach
                        </div>
                    @endif
                </div>

            </div>

            {{-- ══ RIGHT / BOOKING SIDEBAR ════════════════════════════════════ --}}
            <div class="w-full shrink-0 lg:w-80">
                <div class="sticky top-20 rounded-2xl border border-zinc-200 bg-white p-6 shadow-md">

                    {{-- Price + rating --}}
                    <div class="flex items-start justify-between">
                        <div>
                            @if($price)
                                <p class="text-3xl font-extrabold text-zinc-900">{{ $this->currencySymbol }}{{ number_format($price) }}</p>
                                <p class="text-sm text-zinc-400">{{ $property->monthly_rent ? __('/month') : ($property->weekly_rent ? __('/week') : ($property->daily_rent ? __('/day') : __('/year'))) }}</p>
                            @else
                                <p class="text-lg font-bold text-zinc-400">{{ __('Price on request') }}</p>
                            @endif
                        </div>
                    </div>

                    @if($rating)
                        <div class="mt-2 flex items-center gap-1.5">
                            <svg class="size-4 text-amber-400" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd"/></svg>
                            <span class="text-sm font-bold text-zinc-800">{{ $rating }}</span>
                            <a href="#" class="text-sm text-zinc-400 underline">({{ $property->reviews_count }} {{ __('reviews') }})</a>
                        </div>
                    @endif

                    <div class="my-4 border-t border-zinc-100"></div>

                    {{-- Move-in date --}}
                    <div class="mb-4">
                        <label class="mb-1 block text-xs font-semibold text-zinc-700">{{ __('Move-in Date') }}</label>
                        <input wire:model="moveInDate" type="date" min="{{ now()->toDateString() }}"
                            class="w-full rounded-xl border border-zinc-200 px-3 py-2.5 text-sm outline-none focus:border-amber-400 focus:ring-2 focus:ring-amber-100 transition" />
                        @error('moveInDate') <p class="mt-0.5 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    {{-- Lease duration --}}
                    <div class="mb-4">
                        <label class="mb-1 block text-xs font-semibold text-zinc-700">{{ __('Lease Duration') }}</label>
                        <select wire:model.live="leaseDuration"
                            class="w-full rounded-xl border border-zinc-200 bg-white px-3 py-2.5 text-sm outline-none focus:border-amber-400 focus:ring-2 focus:ring-amber-100 transition">
                            @foreach($this->leaseDurationOptions as $months)
                                <option value="{{ $months }}">{{ $months }} {{ $months === 1 ? __('month') : __('months') }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="my-4 border-t border-zinc-100"></div>

                    {{-- Cost breakdown --}}
                    @if($this->monthlyRent > 0)
                        <dl class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <dt class="text-zinc-500">{{ __('Monthly rent') }}</dt>
                                <dd class="font-medium text-zinc-900">{{ $this->currencySymbol }}{{ number_format($this->monthlyRent) }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-zinc-500">{{ __('Security deposit') }}</dt>
                                <dd class="font-medium text-zinc-900">{{ $this->currencySymbol }}{{ number_format($this->securityDeposit) }}</dd>
                            </div>
                            @if($this->applicationFee > 0)
                                <div class="flex justify-between">
                                    <dt class="text-zinc-500">{{ __('Application fee') }}</dt>
                                    <dd class="font-medium text-zinc-900">{{ $this->currencySymbol }}{{ number_format($this->applicationFee) }}</dd>
                                </div>
                            @endif
                        </dl>

                        <div class="my-4 border-t border-zinc-100"></div>

                        <div class="flex justify-between">
                            <dt class="text-sm font-semibold text-zinc-700">{{ __('Total due at signing') }}</dt>
                            <dd class="text-base font-extrabold text-zinc-900">{{ $this->currencySymbol }}{{ number_format($this->totalDueAtSigning) }}</dd>
                        </div>

                        <div class="my-4 border-t border-zinc-100"></div>
                    @endif

                    {{-- Book Now --}}
                    @if($bookingSent)
                        <div class="rounded-xl bg-green-50 border border-green-200 px-4 py-3 text-center text-sm font-medium text-green-700">
                            {{ __('Booking inquiry sent! The landlord will contact you.') }}
                        </div>
                    @else
                        <button wire:click="openBookingModal"
                            class="w-full rounded-xl bg-amber-600 py-3 text-sm font-bold text-white transition hover:bg-amber-700">
                            {{ __('Book Now') }}
                        </button>
                    @endif

                    {{-- Schedule Tour --}}
                    @if($tourSent)
                        <div class="mt-3 rounded-xl bg-green-50 border border-green-200 px-4 py-3 text-center text-sm font-medium text-green-700">
                            {{ __('Tour request sent! The landlord will contact you.') }}
                        </div>
                    @else
                        @if(!$showTourForm)
                            <button wire:click="$set('showTourForm', true)" class="mt-3 w-full rounded-xl border border-zinc-200 py-3 text-sm font-semibold text-zinc-700 transition hover:bg-zinc-50">
                                {{ __('Schedule Tour') }}
                            </button>
                        @else
                            <div class="mt-4 space-y-2.5">
                                <div class="flex items-center justify-between">
                                    <p class="text-xs font-semibold text-zinc-700">{{ __('Your Details') }}</p>
                                    <button wire:click="$set('showTourForm', false)" class="text-xs text-zinc-400 hover:text-zinc-600">&times; {{ __('Cancel') }}</button>
                                </div>
                                <input wire:model="renterName" type="text" placeholder="{{ __('Full name') }}"
                                    class="w-full rounded-xl border border-zinc-200 px-3 py-2 text-sm outline-none placeholder-zinc-400 focus:border-amber-400 focus:ring-2 focus:ring-amber-100 transition" />
                                @error('renterName') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                                <input wire:model="renterEmail" type="email" placeholder="email@example.com"
                                    class="w-full rounded-xl border border-zinc-200 px-3 py-2 text-sm outline-none placeholder-zinc-400 focus:border-amber-400 focus:ring-2 focus:ring-amber-100 transition" />
                                @error('renterEmail') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                                <input wire:model="renterPhone" type="tel" placeholder="+1 (555) 000-0000"
                                    class="w-full rounded-xl border border-zinc-200 px-3 py-2 text-sm outline-none placeholder-zinc-400 focus:border-amber-400 focus:ring-2 focus:ring-amber-100 transition" />
                                @error('renterPhone') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                                <textarea wire:model="tourMessage" rows="2" placeholder="{{ __('Optional message...') }}"
                                    class="w-full rounded-xl border border-zinc-200 px-3 py-2 text-sm outline-none placeholder-zinc-400 focus:border-amber-400 focus:ring-2 focus:ring-amber-100 transition resize-none"></textarea>
                                <button wire:click="scheduleTour"
                                    class="w-full rounded-xl bg-zinc-900 py-2.5 text-sm font-bold text-white transition hover:bg-zinc-800"
                                    wire:loading.attr="disabled" wire:loading.class="opacity-75">
                                    <span wire:loading.remove wire:target="scheduleTour">{{ __('Confirm Tour Request') }}</span>
                                    <span wire:loading wire:target="scheduleTour">{{ __('Submitting...') }}</span>
                                </button>
                            </div>
                        @endif
                    @endif

                    {{-- Security badge --}}
                    <p class="mt-4 flex items-center justify-center gap-1.5 text-xs text-zinc-400">
                        <svg class="size-3.5 text-zinc-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" /></svg>
                        {{ __('Your information is protected') }}
                    </p>

                </div>
            </div>
        </div>
    </div>

    {{-- ══ BOOK NOW MODAL ═════════════════════════════════════════════════════ --}}
    @if($showBookingModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4"
             wire:click.self="$set('showBookingModal', false)">
            <div class="w-full max-w-md overflow-hidden rounded-2xl bg-white shadow-2xl">
                <div class="flex items-center justify-between border-b border-zinc-100 px-6 py-4">
                    <div>
                        <h3 class="text-base font-bold text-zinc-900">{{ __('Book This Property') }}</h3>
                        <p class="text-xs text-zinc-400">{{ $property->title }}</p>
                    </div>
                    <button wire:click="$set('showBookingModal', false)" class="text-zinc-400 transition hover:text-zinc-700">
                        <svg class="size-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="space-y-4 px-6 py-5">
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-zinc-700">{{ __('Full Name') }} <span class="text-red-500">*</span></label>
                        <input wire:model="bookingName" type="text" placeholder="{{ __('Your name') }}"
                            class="w-full rounded-xl border border-zinc-200 px-3 py-2.5 text-sm outline-none placeholder-zinc-400 focus:border-amber-400 focus:ring-2 focus:ring-amber-100 transition" />
                        @error('bookingName') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-zinc-700">{{ __('Email Address') }} <span class="text-red-500">*</span></label>
                        <input wire:model="bookingEmail" type="email" placeholder="email@example.com"
                            class="w-full rounded-xl border border-zinc-200 px-3 py-2.5 text-sm outline-none placeholder-zinc-400 focus:border-amber-400 focus:ring-2 focus:ring-amber-100 transition" />
                        @error('bookingEmail') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-zinc-700">{{ __('Phone Number') }} <span class="text-red-500">*</span></label>
                        <input wire:model="bookingPhone" type="tel" placeholder="+1 (555) 000-0000"
                            class="w-full rounded-xl border border-zinc-200 px-3 py-2.5 text-sm outline-none placeholder-zinc-400 focus:border-amber-400 focus:ring-2 focus:ring-amber-100 transition" />
                        @error('bookingPhone') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-zinc-700">{{ __('Preferred Move-in Date') }} <span class="text-red-500">*</span></label>
                        <input wire:model="moveInDate" type="date" min="{{ now()->toDateString() }}"
                            class="w-full rounded-xl border border-zinc-200 px-3 py-2.5 text-sm outline-none focus:border-amber-400 focus:ring-2 focus:ring-amber-100 transition" />
                        @error('moveInDate') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-zinc-700">{{ __('Message') }} <span class="text-zinc-400 font-normal">({{ __('optional') }})</span></label>
                        <textarea wire:model="bookingMessage" rows="3" placeholder="{{ __('Any notes or questions...') }}"
                            class="w-full resize-none rounded-xl border border-zinc-200 px-3 py-2 text-sm outline-none placeholder-zinc-400 focus:border-amber-400 focus:ring-2 focus:ring-amber-100 transition"></textarea>
                    </div>
                </div>

                <div class="flex gap-3 border-t border-zinc-100 px-6 py-4">
                    <button wire:click="$set('showBookingModal', false)"
                        class="flex-1 rounded-xl border border-zinc-200 py-2.5 text-sm font-semibold text-zinc-700 transition hover:bg-zinc-50">
                        {{ __('Cancel') }}
                    </button>
                    <button wire:click="submitBooking"
                        class="flex-1 rounded-xl bg-amber-600 py-2.5 text-sm font-bold text-white transition hover:bg-amber-700"
                        wire:loading.attr="disabled" wire:loading.class="opacity-75">
                        <span wire:loading.remove wire:target="submitBooking">{{ __('Confirm Booking') }}</span>
                        <span wire:loading wire:target="submitBooking">{{ __('Submitting...') }}</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    @include('partials.public-footer')

</div>
