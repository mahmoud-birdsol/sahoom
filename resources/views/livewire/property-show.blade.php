@php
    $images     = $property->images;
    $isOccupied = $property->contracts->isNotEmpty();
    $rating     = $property->reviews_avg_rating ? round((float)$property->reviews_avg_rating, 1) : null;
    $propType   = $property->property_type?->value ?? 'residential';
    $currSym    = match(strtoupper($property->currency ?? 'XOF')) {
        'EUR' => '€ ', 'GBP' => '£ ', 'USD' => '$ ',
        'SAR' => 'SAR ', 'AED' => 'AED ',
        default => 'XOF ',
    };
@endphp

<div>

    <x-public.navbar />

    {{-- ══ BREADCRUMB ════════════════════════════════════════════════════════ --}}
    <div style="background: #FAFAF7; border-bottom: 1px solid #E8E2D8; padding: 14px 6%">
        <div style="max-width: 1200px; margin: 0 auto">
            <nav class="flex items-center gap-2" style="font-size: 0.68rem; color: rgba(30,35,48,.4); letter-spacing: .08em; text-transform: uppercase">
                <a href="{{ route('home') }}" wire:navigate class="transition hover:opacity-70" style="color: rgba(30,35,48,.4)">{{ __('Home') }}</a>
                <span style="color: #B8962E">/</span>
                <a href="{{ route('properties.index') }}" wire:navigate class="transition hover:opacity-70" style="color: rgba(30,35,48,.4)">{{ __('Properties') }}</a>
                <span style="color: #B8962E">/</span>
                <span class="truncate max-w-xs" style="color: #1E2330">{{ $property->title }}</span>
            </nav>
        </div>
    </div>

    {{-- ══ MAIN ════════════════════════════════════════════════════════════════ --}}
    <section style="background: #FAFAF7; padding: 50px 6% 90px">
        <div style="max-width: 1200px; margin: 0 auto">
            <div class="flex flex-col gap-10 lg:flex-row lg:items-start">

                {{-- ── LEFT COLUMN ─────────────────────────────────────────── --}}
                <div class="flex-1 min-w-0 space-y-6">

                    {{-- Title block --}}
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-3 mb-3">
                                <div class="h-px w-6 shrink-0" style="background: #B8962E"></div>
                                <span style="font-size: 0.6rem; font-weight: 600; letter-spacing: .18em; text-transform: uppercase; color: #B8962E">
                                    {{ $propType === 'commercial' ? __('Commercial') : __('Résidentiel') }}
                                    @if($property->city) · {{ $property->city }} @endif
                                </span>
                            </div>
                            <h1 class="font-serif" style="font-size: clamp(1.6rem,3vw,2.5rem); font-weight: 300; color: #1E2330; line-height: 1.15">
                                {{ $property->title }}
                            </h1>
                            <p class="flex items-center gap-1.5 mt-3" style="font-size: 0.8rem; color: rgba(30,35,48,.5)">
                                <svg class="size-3.5 shrink-0" style="color: #B8962E" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                                {{ implode(', ', array_filter([$property->address_line_1, $property->city, $property->country])) ?: __('Location TBA') }}
                            </p>
                        </div>
                        {{-- Favourite --}}
                        <button wire:click="toggleFavorite"
                                class="flex shrink-0 size-10 items-center justify-center transition"
                                style="border: 1px solid #E8E2D8; background: white; {{ $this->isFavorited ? 'color: #B8962E' : 'color: rgba(30,35,48,.3)' }}">
                            <svg class="size-5" fill="{{ $this->isFavorited ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/>
                            </svg>
                        </button>
                    </div>

                    {{-- Gallery — 1 large + 2×2 thumbs --}}
                    <div style="background: #E8E2D8; overflow: hidden">
                        {{-- Desktop: side-by-side; Mobile: stacked --}}
                        <div class="flex flex-col sm:flex-row" style="gap: 2px; height: auto; min-height: 0">
                            {{-- Main image --}}
                            <div class="relative overflow-hidden shrink-0" style="height: 300px; flex: 3">
                                @if($images->isNotEmpty())
                                    <img src="{{ $images->first()->url() }}" alt="{{ $property->title }}" class="w-full h-full object-cover" />
                                @else
                                    <div class="w-full h-full flex items-center justify-center" style="background: #F4EFE8">
                                        <svg class="size-16" style="color: #E8E2D8" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
                                    </div>
                                @endif
                                <div class="absolute top-0 left-0 px-3.5 py-2" style="font-size: 0.6rem; font-weight: 600; letter-spacing: .14em; text-transform: uppercase; background: {{ $isOccupied ? '#B8962E' : '#1E2330' }}; color: white">
                                    {{ $isOccupied ? __('Occupied') : __('Available') }}
                                </div>
                                @if($property->is_featured)
                                    <div class="absolute bottom-0 left-0 px-3.5 py-2" style="font-size: 0.6rem; font-weight: 600; letter-spacing: .14em; text-transform: uppercase; background: rgba(184,150,46,.9); color: white">
                                        {{ __('Featured') }}
                                    </div>
                                @endif
                            </div>
                            {{-- 2×2 thumbnail grid --}}
                            <div class="grid grid-cols-2 grid-rows-2 shrink-0" style="gap: 2px; flex: 2; height: 300px">
                                @foreach(collect([1,2,3,4]) as $i)
                                    @php $thumb = $images->get($i); @endphp
                                    <div class="overflow-hidden" style="background: #F4EFE8">
                                        @if($thumb)
                                            <img src="{{ $thumb->url() }}" alt="" class="w-full h-full object-cover" />
                                        @else
                                            <div class="w-full h-full flex items-center justify-center">
                                                <svg class="size-6" style="color: #E8E2D8" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/></svg>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Spec chips --}}
                    @php $specs = array_filter([
                        $property->floor       ? ['icon' => 'floor',   'label' => $property->floor . ' ' . __('Floor')] : null,
                        $property->size_sqm    ? ['icon' => 'area',    'label' => number_format($property->size_sqm) . ' m²'] : null,
                        $property->space_number? ['icon' => 'unit',    'label' => __('Unit') . ' ' . $property->space_number] : null,
                        $property->traffic_score ? ['icon' => 'traffic','label' => __('Traffic') . ': ' . $property->traffic_score . '/10'] : null,
                        $rating                ? ['icon' => 'star',    'label' => $rating . ' (' . ($property->reviews_count ?? 0) . ' ' . __('reviews') . ')'] : null,
                    ]); @endphp
                    @if(count($specs))
                        <div class="flex flex-wrap gap-2">
                            @foreach($specs as $spec)
                                <div class="flex items-center gap-2" style="border: 1px solid #E8E2D8; background: white; padding: 8px 16px">
                                    <span style="font-size: 0.72rem; color: #1E2330; font-weight: 500">{{ $spec['label'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    {{-- Description --}}
                    @if($property->description)
                        <div style="background: white; border: 1px solid #E8E2D8; padding: 28px 32px">
                            <div class="flex items-center gap-4 mb-5">
                                <span style="font-size: 0.62rem; font-weight: 600; letter-spacing: .18em; text-transform: uppercase; color: #B8962E">{{ __('Description') }}</span>
                                <div class="h-px flex-1" style="background: #E8E2D8"></div>
                            </div>
                            <p style="font-size: 0.88rem; line-height: 1.85; color: rgba(30,35,48,.75)">{!! nl2br(e($property->description)) !!}</p>
                        </div>
                    @endif

                    {{-- Amenities --}}
                    @if($property->amenities->isNotEmpty())
                        <div style="background: white; border: 1px solid #E8E2D8; padding: 28px 32px">
                            <div class="flex items-center gap-4 mb-5">
                                <span style="font-size: 0.62rem; font-weight: 600; letter-spacing: .18em; text-transform: uppercase; color: #B8962E">{{ __('Amenities') }}</span>
                                <div class="h-px flex-1" style="background: #E8E2D8"></div>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                @foreach($property->amenities as $amenity)
                                    <span class="flex items-center gap-1.5" style="border: 1px solid #E8E2D8; padding: 6px 14px; font-size: 0.72rem; font-weight: 500; color: #1E2330">
                                        <svg class="size-3 shrink-0" style="color: #B8962E" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
                                        {{ $amenity->name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Write a review --}}
                    @if($this->canWriteReview)
                        <div style="background: white; border: 1px solid #E8E2D8; padding: 28px 32px">
                            <div class="flex items-center gap-4 mb-5">
                                <span style="font-size: 0.62rem; font-weight: 600; letter-spacing: .18em; text-transform: uppercase; color: #B8962E">{{ __('Write a Review') }}</span>
                                <div class="h-px flex-1" style="background: #E8E2D8"></div>
                            </div>
                            @if($reviewSent)
                                <div style="border: 1px solid #E8E2D8; padding: 14px 18px; font-size: 0.82rem; color: #1E2330">
                                    {{ __('Thank you! Your review has been submitted.') }}
                                </div>
                            @else
                                <form wire:submit="submitReview">
                                    <div class="flex items-center gap-2 mb-4">
                                        @for($star = 1; $star <= 5; $star++)
                                            <button type="button" wire:click="$set('reviewRating', {{ $star }})" class="transition hover:scale-110">
                                                <svg class="size-7 transition" fill="currentColor" viewBox="0 0 24 24"
                                                     style="color: {{ $reviewRating >= $star ? '#B8962E' : '#E8E2D8' }}">
                                                    <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd"/>
                                                </svg>
                                            </button>
                                        @endfor
                                    </div>
                                    @error('reviewRating') <p class="mb-2 text-xs text-red-500">{{ $message }}</p> @enderror
                                    <textarea wire:model="reviewText" rows="3" placeholder="{{ __('Optional: Share more details…') }}"
                                        style="width: 100%; border: 1px solid #E8E2D8; padding: 10px 14px; font-size: 0.82rem; color: #1E2330; outline: none; resize: none; margin-bottom: 14px"
                                        class="focus:border-[#B8962E] transition placeholder-opacity-50"></textarea>
                                    <button type="submit" wire:loading.attr="disabled" wire:loading.class="opacity-70"
                                        style="padding: 12px 32px; font-size: 0.7rem; font-weight: 600; letter-spacing: .14em; text-transform: uppercase; background: #1E2330; color: white; transition: opacity .2s"
                                        class="hover:opacity-80">
                                        {{ __('Submit Review') }}
                                    </button>
                                </form>
                            @endif
                        </div>
                    @endif

                    {{-- Location --}}
                    <div style="background: white; border: 1px solid #E8E2D8; padding: 28px 32px">
                        <div class="flex items-center gap-4 mb-5">
                            <span style="font-size: 0.62rem; font-weight: 600; letter-spacing: .18em; text-transform: uppercase; color: #B8962E">{{ __('Location') }}</span>
                            <div class="h-px flex-1" style="background: #E8E2D8"></div>
                        </div>
                        @if($property->latitude && $property->longitude)
                            <div style="overflow: hidden; border: 1px solid #E8E2D8">
                                <iframe
                                    src="https://www.openstreetmap.org/export/embed.html?bbox={{ $property->longitude - 0.01 }},{{ $property->latitude - 0.01 }},{{ $property->longitude + 0.01 }},{{ $property->latitude + 0.01 }}&layer=mapnik&marker={{ $property->latitude }},{{ $property->longitude }}"
                                    width="100%" height="240" style="border: 0; display: block" loading="lazy"></iframe>
                            </div>
                        @else
                            <div class="flex h-40 items-center justify-center" style="background: #F4EFE8; border: 1px solid #E8E2D8">
                                <p style="font-size: 0.82rem; color: rgba(30,35,48,.45)">
                                    {{ implode(', ', array_filter([$property->address_line_1, $property->city])) ?: __('Location not specified') }}
                                </p>
                            </div>
                        @endif
                        @if($property->nearby_places && count($property->nearby_places) > 0)
                            <div class="mt-4 flex flex-wrap gap-3">
                                @foreach($property->nearby_places as $placeName => $placeDistance)
                                    <span class="flex items-center gap-1.5" style="font-size: 0.72rem; color: rgba(30,35,48,.6)">
                                        <svg class="size-3.5 shrink-0" style="color: #B8962E" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                                        {{ $placeName }}{{ $placeDistance ? ' · ' . $placeDistance : '' }}
                                    </span>
                                @endforeach
                            </div>
                        @endif
                    </div>

                </div>

                {{-- ── RIGHT STICKY SIDEBAR ──────────────────────────────── --}}
                <div class="w-full shrink-0 lg:w-[320px]">
                    <div class="sticky top-20">

                        {{-- Price card (ink) --}}
                        <div style="background: #1E2330; padding: 32px 28px">
                            @if($this->displayPrice > 0)
                                <span style="font-size: 0.6rem; font-weight: 600; letter-spacing: .16em; text-transform: uppercase; color: rgba(255,255,255,.4)">
                                    /{{ __($this->displayPriceUnit) }}
                                </span>
                                <p class="font-serif mt-1" style="font-size: 2rem; font-weight: 300; color: white; line-height: 1">
                                    {{ $currSym }}{{ number_format($this->displayPrice) }}
                                </p>
                            @else
                                <p class="font-serif" style="font-size: 1.2rem; font-weight: 300; color: rgba(255,255,255,.5)">{{ __('Price on request') }}</p>
                            @endif

                            @if($rating)
                                <div class="flex items-center gap-2 mt-3">
                                    <svg class="size-3.5" style="color: #B8962E" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd"/></svg>
                                    <span style="font-size: 0.78rem; font-weight: 600; color: white">{{ $rating }}</span>
                                    <span style="font-size: 0.72rem; color: rgba(255,255,255,.4)">({{ $property->reviews_count }} {{ __('reviews') }})</span>
                                </div>
                            @endif

                            <div style="height: 1px; background: rgba(255,255,255,.08); margin: 20px 0"></div>

                            {{-- Date range --}}
                            <div class="mb-4">
                                <label style="display: block; font-size: 0.65rem; font-weight: 600; letter-spacing: .1em; text-transform: uppercase; color: rgba(255,255,255,.4); margin-bottom: 8px">{{ __('Start Date') }}</label>
                                <input wire:model="moveInDate" type="date" min="{{ now()->toDateString() }}"
                                    style="width: 100%; background: rgba(255,255,255,.07); border: 1px solid rgba(255,255,255,.12); color: white; padding: 10px 12px; font-size: 0.82rem; outline: none"
                                    class="transition focus:border-[#B8962E]" />
                                @error('moveInDate') <p style="font-size: 0.7rem; color: #fca5a5; margin-top: 4px">{{ $message }}</p> @enderror
                            </div>
                            <div class="mb-4">
                                <label style="display: block; font-size: 0.65rem; font-weight: 600; letter-spacing: .1em; text-transform: uppercase; color: rgba(255,255,255,.4); margin-bottom: 8px">{{ __('End Date') }}</label>
                                <input wire:model="bookingEndDate" type="date" min="{{ now()->toDateString() }}"
                                    style="width: 100%; background: rgba(255,255,255,.07); border: 1px solid rgba(255,255,255,.12); color: white; padding: 10px 12px; font-size: 0.82rem; outline: none"
                                    class="transition focus:border-[#B8962E]" />
                                @error('bookingEndDate') <p style="font-size: 0.7rem; color: #fca5a5; margin-top: 4px">{{ $message }}</p> @enderror
                            </div>

                            {{-- Cost breakdown --}}
                            @if($this->displayPrice > 0)
                                <div style="height: 1px; background: rgba(255,255,255,.08); margin: 16px 0"></div>
                                <dl class="space-y-2">
                                    <div class="flex justify-between">
                                        <dt style="font-size: 0.76rem; color: rgba(255,255,255,.45)">{{ $this->firstPeriodRentLabel }}</dt>
                                        <dd style="font-size: 0.76rem; font-weight: 500; color: white">{{ $currSym }}{{ number_format($this->firstPeriodRent) }}</dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt style="font-size: 0.76rem; color: rgba(255,255,255,.45)">{{ __('Security deposit') }}</dt>
                                        <dd style="font-size: 0.76rem; font-weight: 500; color: white">{{ $currSym }}{{ number_format($this->securityDeposit) }}</dd>
                                    </div>
                                    @if($this->applicationFee > 0)
                                        <div class="flex justify-between">
                                            <dt style="font-size: 0.76rem; color: rgba(255,255,255,.45)">{{ __('Application fee') }}</dt>
                                            <dd style="font-size: 0.76rem; font-weight: 500; color: white">{{ $currSym }}{{ number_format($this->applicationFee) }}</dd>
                                        </div>
                                    @endif
                                </dl>
                                <div style="height: 1px; background: rgba(255,255,255,.08); margin: 16px 0"></div>
                                <div class="flex justify-between">
                                    <dt style="font-size: 0.72rem; font-weight: 600; letter-spacing: .08em; text-transform: uppercase; color: rgba(255,255,255,.5)">{{ __('Total at signing') }}</dt>
                                    <dd class="font-serif" style="font-size: 1.1rem; font-weight: 300; color: white">{{ $currSym }}{{ number_format($this->totalDueAtSigning) }}</dd>
                                </div>
                                <div style="height: 1px; background: rgba(255,255,255,.08); margin: 20px 0"></div>
                            @endif

                            {{-- Book Now --}}
                            @if($bookingSent)
                                <div style="border: 1px solid rgba(255,255,255,.12); padding: 12px 16px; text-align: center; font-size: 0.78rem; color: rgba(255,255,255,.7)">
                                    {{ __('Booking inquiry sent! The landlord will contact you.') }}
                                </div>
                            @else
                                <button wire:click="openBookingModal"
                                    style="width: 100%; padding: 14px; font-size: 0.7rem; font-weight: 600; letter-spacing: .16em; text-transform: uppercase; background: #B8962E; color: white; transition: opacity .2s"
                                    class="hover:opacity-90">
                                    {{ __('Book Now') }}
                                </button>
                            @endif

                            {{-- Schedule Tour --}}
                            @if($tourSent)
                                <div style="border: 1px solid rgba(255,255,255,.12); padding: 12px 16px; text-align: center; font-size: 0.78rem; color: rgba(255,255,255,.7); margin-top: 10px">
                                    {{ __('Tour request sent! The landlord will contact you.') }}
                                </div>
                            @else
                                @if(!$showTourForm)
                                    <button wire:click="openTourForm"
                                        style="width: 100%; padding: 13px; font-size: 0.7rem; font-weight: 600; letter-spacing: .14em; text-transform: uppercase; background: transparent; border: 1px solid rgba(255,255,255,.2); color: rgba(255,255,255,.6); transition: all .2s; margin-top: 10px"
                                        class="hover:border-white/40 hover:text-white">
                                        {{ __('Schedule Tour') }}
                                    </button>
                                @else
                                    <div style="margin-top: 14px" class="space-y-2.5">
                                        <div class="flex items-center justify-between">
                                            <span style="font-size: 0.65rem; font-weight: 600; letter-spacing: .1em; text-transform: uppercase; color: rgba(255,255,255,.4)">{{ __('Your Details') }}</span>
                                            <button wire:click="$set('showTourForm', false)" style="font-size: 0.72rem; color: rgba(255,255,255,.35)" class="transition hover:text-white/60">&times; {{ __('Cancel') }}</button>
                                        </div>
                                        <input wire:model="renterName" type="text" placeholder="{{ __('Full name') }}"
                                            style="width: 100%; background: rgba(255,255,255,.07); border: 1px solid rgba(255,255,255,.12); color: white; padding: 9px 12px; font-size: 0.8rem; outline: none"
                                            class="placeholder-white/25 transition focus:border-[#B8962E]" />
                                        @error('renterName') <p style="font-size: 0.7rem; color: #fca5a5">{{ $message }}</p> @enderror
                                        <input wire:model="renterEmail" type="email" placeholder="email@example.com"
                                            style="width: 100%; background: rgba(255,255,255,.07); border: 1px solid rgba(255,255,255,.12); color: white; padding: 9px 12px; font-size: 0.8rem; outline: none"
                                            class="placeholder-white/25 transition focus:border-[#B8962E]" />
                                        @error('renterEmail') <p style="font-size: 0.7rem; color: #fca5a5">{{ $message }}</p> @enderror
                                        <input wire:model="renterPhone" type="tel" placeholder="+225 07 00 00 00 00"
                                            style="width: 100%; background: rgba(255,255,255,.07); border: 1px solid rgba(255,255,255,.12); color: white; padding: 9px 12px; font-size: 0.8rem; outline: none"
                                            class="placeholder-white/25 transition focus:border-[#B8962E]" />
                                        @error('renterPhone') <p style="font-size: 0.7rem; color: #fca5a5">{{ $message }}</p> @enderror
                                        <textarea wire:model="tourMessage" rows="2" placeholder="{{ __('Optional message…') }}"
                                            style="width: 100%; background: rgba(255,255,255,.07); border: 1px solid rgba(255,255,255,.12); color: white; padding: 9px 12px; font-size: 0.8rem; outline: none; resize: none"
                                            class="placeholder-white/25 transition focus:border-[#B8962E]"></textarea>
                                        <button wire:click="scheduleTour" wire:loading.attr="disabled" wire:loading.class="opacity-70"
                                            style="width: 100%; padding: 12px; font-size: 0.7rem; font-weight: 600; letter-spacing: .14em; text-transform: uppercase; background: rgba(255,255,255,.1); border: 1px solid rgba(255,255,255,.2); color: white; transition: opacity .2s"
                                            class="hover:opacity-80">
                                            <span wire:loading.remove wire:target="scheduleTour">{{ __('Confirm Tour Request') }}</span>
                                            <span wire:loading wire:target="scheduleTour">{{ __('Submitting…') }}</span>
                                        </button>
                                    </div>
                                @endif
                            @endif

                            {{-- Security note --}}
                            <p class="flex items-center justify-center gap-1.5 mt-5" style="font-size: 0.68rem; color: rgba(255,255,255,.25)">
                                <svg class="size-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"/></svg>
                                {{ __('Your information is protected') }}
                            </p>
                        </div>

                        {{-- Back to listings --}}
                        <div class="mt-2">
                            <a href="{{ route('properties.index') }}" wire:navigate
                               class="flex items-center justify-center gap-2 transition hover:opacity-70"
                               style="padding: 13px; border: 1px solid #E8E2D8; background: white; font-size: 0.7rem; font-weight: 600; letter-spacing: .12em; text-transform: uppercase; color: #1E2330">
                                <svg class="size-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/></svg>
                                {{ __('All Properties') }}
                            </a>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>

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
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="mb-1 block text-xs font-semibold text-zinc-700">{{ __('Start Date') }} <span class="text-red-500">*</span></label>
                            <input wire:model="moveInDate" type="date" min="{{ now()->toDateString() }}"
                                class="w-full rounded-xl border border-zinc-200 px-3 py-2.5 text-sm outline-none focus:border-amber-400 focus:ring-2 focus:ring-amber-100 transition" />
                            @error('moveInDate') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-semibold text-zinc-700">{{ __('End Date') }}</label>
                            <input wire:model="bookingEndDate" type="date" min="{{ now()->toDateString() }}"
                                class="w-full rounded-xl border border-zinc-200 px-3 py-2.5 text-sm outline-none focus:border-amber-400 focus:ring-2 focus:ring-amber-100 transition" />
                            @error('bookingEndDate') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
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
