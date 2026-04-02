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
                        <button class="flex size-9 shrink-0 items-center justify-center rounded-full border border-zinc-200 text-zinc-400 transition hover:border-rose-300 hover:text-rose-500">
                            <svg class="size-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/></svg>
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
                    <div class="mb-5 flex flex-wrap gap-3">
                        @if($property->floor)
                            <div class="flex flex-col items-center gap-1.5 rounded-xl border border-zinc-100 bg-zinc-50 px-5 py-3 text-center">
                                <svg class="size-5 text-zinc-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" /></svg>
                                <span class="text-xs font-semibold text-zinc-700">{{ $property->floor }} {{ __('Floor') }}</span>
                            </div>
                        @endif
                        @if($property->size_sqm)
                            <div class="flex flex-col items-center gap-1.5 rounded-xl border border-zinc-100 bg-zinc-50 px-5 py-3 text-center">
                                <svg class="size-5 text-zinc-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3.75v4.5m0-4.5h4.5m-4.5 0L9 9M3.75 20.25v-4.5m0 4.5h4.5m-4.5 0L9 15M20.25 3.75h-4.5m4.5 0v4.5m0-4.5L15 9m5.25 11.25h-4.5m4.5 0v-4.5m0 4.5L15 15" /></svg>
                                <span class="text-xs font-semibold text-zinc-700">{{ number_format($property->size_sqm) }} {{ __('sq ft') }}</span>
                            </div>
                        @endif
                        @if($property->space_number)
                            <div class="flex flex-col items-center gap-1.5 rounded-xl border border-zinc-100 bg-zinc-50 px-5 py-3 text-center">
                                <svg class="size-5 text-zinc-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 0 0 3.75.614" /></svg>
                                <span class="text-xs font-semibold text-zinc-700">{{ __('Main street view') }}</span>
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
                        <div class="mt-4 flex flex-wrap gap-2">
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

                    {{-- POI tags --}}
                    <div class="mt-3 flex flex-wrap gap-3 text-xs text-zinc-500">
                        <span class="flex items-center gap-1.5">
                            <svg class="size-3.5 text-amber-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                            {{ __('Metro Station') }} · 2 {{ __('min walk') }}
                        </span>
                        <span class="flex items-center gap-1.5">
                            <svg class="size-3.5 text-amber-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8.25v-1.5m0 1.5c-1.355 0-2.697.056-4.024.166C6.845 8.51 6 9.473 6 10.608v2.513m6-4.871c1.355 0 2.697.056 4.024.166C17.155 8.51 18 9.473 18 10.608v2.513M15 8.25v-1.5m-6 1.5v-1.5m12 9.75-1.5.75a3.354 3.354 0 0 1-3 0 3.354 3.354 0 0 0-3 0 3.354 3.354 0 0 1-3 0 3.354 3.354 0 0 0-3 0 3.354 3.354 0 0 1-3 0L3 16.5m15-3.379a48.474 48.474 0 0 0-6-.371c-2.032 0-4.034.126-6 .371m12 0c.39.049.777.102 1.163.16 1.07.16 1.837 1.094 1.837 2.175v5.169c0 .621-.504 1.125-1.125 1.125H4.125A1.125 1.125 0 0 1 3 20.625v-5.17c0-1.08.768-2.014 1.837-2.174A47.78 47.78 0 0 1 6 13.12M12.265 3.11a.375.375 0 1 1-.53 0L12 2.845l.265.265Zm-3 0a.375.375 0 1 1-.53 0L9 2.845l.265.265Zm6 0a.375.375 0 1 1-.53 0L15 2.845l.265.265Z" /></svg>
                            {{ __('Restaurants') }} · 1 {{ __('min walk') }}
                        </span>
                        <span class="flex items-center gap-1.5">
                            <svg class="size-3.5 text-amber-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" /></svg>
                            {{ __('Shopping') }} · 5 {{ __('min walk') }}
                        </span>
                    </div>
                </div>

            </div>

            {{-- ══ RIGHT / BOOKING SIDEBAR ════════════════════════════════════ --}}
            <div class="w-full shrink-0 lg:w-80">
                <div class="sticky top-20 rounded-2xl border border-zinc-200 bg-white p-6 shadow-md">

                    {{-- Price + rating --}}
                    <div class="flex items-start justify-between">
                        <div>
                            @if($price)
                                <p class="text-3xl font-extrabold text-zinc-900">${{ number_format($price) }}</p>
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
                    <div class="mb-3">
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
                            <option value="1">1 {{ __('month') }}</option>
                            <option value="3">3 {{ __('months') }}</option>
                            <option value="6">6 {{ __('months') }}</option>
                            <option value="12">12 {{ __('months') }}</option>
                            <option value="24">24 {{ __('months') }}</option>
                        </select>
                    </div>

                    <div class="my-4 border-t border-zinc-100"></div>

                    {{-- Cost breakdown --}}
                    @if($this->monthlyRent > 0)
                        <dl class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <dt class="text-zinc-500">{{ __('Monthly rent') }}</dt>
                                <dd class="font-medium text-zinc-900">${{ number_format($this->monthlyRent) }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-zinc-500">{{ __('Security deposit') }}</dt>
                                <dd class="font-medium text-zinc-900">${{ number_format($this->securityDeposit) }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-zinc-500">{{ __('Application fee') }}</dt>
                                <dd class="font-medium text-zinc-900">${{ number_format($this->applicationFee) }}</dd>
                            </div>
                        </dl>

                        <div class="my-3 border-t border-zinc-100"></div>

                        <div class="flex justify-between">
                            <dt class="text-sm font-semibold text-zinc-700">{{ __('Total due at signing') }}</dt>
                            <dd class="text-base font-extrabold text-zinc-900">${{ number_format($this->totalDueAtSigning) }}</dd>
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

    {{-- ══ FOOTER ════════════════════════════════════════════════════════════ --}}
    <footer class="mt-12 border-t border-zinc-100 bg-white">
        <div class="mx-auto max-w-7xl px-6 py-12 lg:px-10">
            <div class="grid grid-cols-1 gap-10 lg:grid-cols-4">
                <div class="lg:col-span-1">
                    <span class="text-base font-extrabold tracking-widest text-amber-600 uppercase">SAHOOME</span>
                    <p class="mt-3 text-xs leading-relaxed text-zinc-500">It Refers To The Practice Of Sharing<br>Access To Real Estate (Such As<br>Homes, Offices, Or Tourist Rentals).</p>
                    <div class="mt-5 flex gap-3">
                        <a href="#" class="flex size-8 items-center justify-center rounded-full border border-zinc-200 text-zinc-500 transition hover:border-amber-400 hover:text-amber-600">
                            <svg class="size-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        <a href="#" class="flex size-8 items-center justify-center rounded-full border border-zinc-200 text-zinc-500 transition hover:border-amber-400 hover:text-amber-600">
                            <svg class="size-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </a>
                    </div>
                    <p class="mt-5 text-xs text-zinc-400">{{ __('Al Copywrite Reserved For Sahoome@2025') }}</p>
                </div>
                <div>
                    <h5 class="mb-4 text-xs font-bold uppercase tracking-wider text-zinc-900">{{ __('Supports') }}</h5>
                    <ul class="space-y-2.5 text-sm text-zinc-500">
                        <li><a href="#" class="transition hover:text-amber-600">{{ __('Privacy & Policy') }}</a></li>
                        <li><a href="#" class="transition hover:text-amber-600">{{ __('Terms & Candilitian') }}</a></li>
                        <li><a href="#" class="transition hover:text-amber-600">{{ __('FQA') }}</a></li>
                    </ul>
                </div>
                <div>
                    <h5 class="mb-4 text-xs font-bold uppercase tracking-wider text-zinc-900">{{ __('Quick links') }}</h5>
                    <ul class="space-y-2.5 text-sm text-zinc-500">
                        <li><a href="{{ route('home') }}" wire:navigate class="transition hover:text-amber-600">{{ __('Home') }}</a></li>
                        <li><a href="#" class="transition hover:text-amber-600">{{ __('About us') }}</a></li>
                        <li><a href="{{ route('home') }}#contact" class="transition hover:text-amber-600">{{ __('Contact us') }}</a></li>
                    </ul>
                </div>
                <div>
                    <h5 class="mb-4 text-xs font-bold uppercase tracking-wider text-zinc-900">{{ __('Contact info') }}</h5>
                    <ul class="space-y-3 text-sm text-zinc-500">
                        <li class="flex items-start gap-2">
                            <svg class="mt-0.5 size-4 shrink-0 text-amber-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" /></svg>
                            <span>Sahoome@gmail.com</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="mt-0.5 size-4 shrink-0 text-amber-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                            <span>321 Market St, Los Angeles, CA.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="mt-0.5 size-4 shrink-0 text-amber-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" /></svg>
                            <span>+966-554-648</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

</div>
