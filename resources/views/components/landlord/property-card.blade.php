@props([
    'property',
    'reviewsCount'   => 0,
    'reviewsAvgRating' => null,
    'favoritesCount' => 0,
    'activeContract' => null,
])

@php
    $gradients = [
        'from-zinc-700 to-zinc-900',
        'from-blue-300 to-blue-500',
        'from-green-300 to-emerald-500',
        'from-violet-300 to-purple-500',
        'from-rose-300 to-pink-500',
        'from-cyan-300 to-teal-500',
    ];
    $gradient = $gradients[$property->id % count($gradients)];

    $isOccupied = $activeContract !== null;
    $rating     = $reviewsAvgRating ? round((float) $reviewsAvgRating, 1) : null;
@endphp

<div class="overflow-hidden rounded-xl border border-zinc-100 bg-white shadow-sm">

    {{-- Image / Placeholder --}}
    @php $firstImage = $property->images?->first(); @endphp
    <div
        wire:click="openDetails({{ $property->id }})"
        class="relative h-44 cursor-pointer overflow-hidden bg-gradient-to-br {{ $gradient }}"
    >
        @if($firstImage)
            <img src="{{ $firstImage->url() }}" class="absolute inset-0 h-full w-full object-cover" />
        @else
            <div class="absolute inset-0 flex items-center justify-center">
                <flux:icon.building-office-2 class="size-14 text-white/40" variant="outline" />
            </div>
        @endif

        {{-- Favorite count badge (landlord view — not a button) --}}
        <div class="absolute top-3 right-3 flex items-center gap-1 rounded-full bg-white/90 px-2.5 py-1 shadow-sm backdrop-blur-sm">
            <flux:icon.heart class="size-4 text-rose-500" variant="solid" />
            <span class="text-xs font-semibold text-zinc-700">{{ $favoritesCount }}</span>
        </div>
    </div>

    {{-- Card body --}}
    <div class="p-4">

        {{-- Actions row --}}
        <div class="mb-3 flex flex-wrap items-center gap-2">
            <button
                wire:click="openEditForm({{ $property->id }})"
                class="flex size-8 items-center justify-center rounded-lg border border-zinc-200 text-zinc-500 transition hover:border-zinc-300 hover:bg-zinc-50"
            >
                <flux:icon.pencil-square class="size-4" variant="outline" />
            </button>

            {{-- Publish: only when not yet approved --}}
            @if(($property->status?->value ?? $property->status) !== 'approved')
                <button
                    wire:click="publishProperty({{ $property->id }})"
                    wire:confirm="{{ __('Publish this property and make it publicly available?') }}"
                    class="flex h-8 items-center gap-1.5 rounded-lg border px-2.5 text-xs font-semibold transition hover:opacity-80" style="border-color: #E8E2D8; background: #F4EFE8; color: #B8962E"
                >
                    <flux:icon.globe-alt class="size-3.5" variant="outline" />
                    {{ __('Publish') }}
                </button>
            @endif

            {{-- Activate / Deactivate --}}
            @if($property->is_active)
                <button
                    wire:click="toggleActiveProperty({{ $property->id }})"
                    wire:confirm="{{ __('Deactivate this property? It will be hidden from listings.') }}"
                    class="flex h-8 items-center gap-1.5 rounded-lg border border-zinc-200 px-2.5 text-xs font-semibold text-zinc-600 transition hover:bg-zinc-50"
                >
                    <flux:icon.pause-circle class="size-3.5" variant="outline" />
                    {{ __('Deactivate') }}
                </button>
            @else
                <button
                    wire:click="toggleActiveProperty({{ $property->id }})"
                    class="flex h-8 items-center gap-1.5 rounded-lg border border-green-200 bg-green-50 px-2.5 text-xs font-semibold text-green-700 transition hover:bg-green-100"
                >
                    <flux:icon.play-circle class="size-3.5" variant="outline" />
                    {{ __('Activate') }}
                </button>
            @endif

            <button
                wire:click="deleteProperty({{ $property->id }})"
                wire:confirm="{{ __('Delete this property? This cannot be undone.') }}"
                class="ml-auto flex size-8 items-center justify-center rounded-lg border border-red-100 text-red-500 transition hover:bg-red-50"
            >
                <flux:icon.trash class="size-4" variant="outline" />
            </button>
        </div>

        {{-- Status + Rating --}}
        <div class="mb-2 flex items-center justify-between">
            @php
                $statusValue = $property->status?->value ?? $property->status;
                [$statusLabel, $statusClass] = match($statusValue) {
                    'approved'   => $isOccupied
                        ? [__('Occupied'),   'bg-blue-50 text-blue-700']
                        : [__('Available'),  'bg-green-50 text-green-700'],
                    'in_review'  => [__('In Review'),  'bg-sky-50 text-sky-700'],
                    'rejected'   => [__('Rejected'),   'bg-red-50 text-red-700'],
                    'suspended'  => [__('Suspended'),  'bg-orange-50 text-orange-700'],
                    default      => [__('Draft'),       'bg-zinc-100 text-zinc-500'],
                };
                if (! $property->is_active) {
                    $statusLabel = __('Inactive');
                    $statusClass = 'bg-zinc-100 text-zinc-400';
                }
            @endphp
            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $statusClass }}">
                {{ $statusLabel }}
            </span>

            @if($rating)
                <div class="flex items-center gap-1">
                    <flux:icon.star class="size-3.5 text-amber-400" variant="solid" />
                    <span class="text-xs font-semibold text-amber-600">{{ $rating }}</span>
                    <span class="text-xs text-zinc-400">({{ $reviewsCount }} {{ __('Review', [], null) }})</span>
                </div>
            @else
                <span class="text-xs text-zinc-400">{{ __('No reviews') }}</span>
            @endif
        </div>

        {{-- Title + Price --}}
        <div class="mb-1 flex items-start justify-between gap-2">
            <h3 class="line-clamp-1 text-sm font-bold text-zinc-900">{{ $property->title }}</h3>
            @php
                $listingPrice = match($property->pricing_type ?? 'monthly') {
                    'daily'  => $property->daily_rent,
                    'weekly' => $property->weekly_rent,
                    'yearly' => $property->yearly_rent,
                    default  => $property->monthly_rent,
                };
                $listingLabel = match($property->pricing_type ?? 'monthly') {
                    'daily'  => __('day'),
                    'weekly' => __('week'),
                    'yearly' => __('year'),
                    default  => __('month'),
                };
            @endphp
            @if($listingPrice)
                <span class="shrink-0 text-sm font-bold" style="color: #B8962E">
                    ${{ number_format((float) $listingPrice, 0) }}/{{ $listingLabel }}
                </span>
            @endif
        </div>

        {{-- Address --}}
        @if($property->address_line_1 || $property->city)
            <p class="line-clamp-1 text-xs text-zinc-500">
                {{ implode(', ', array_filter([$property->address_line_1, $property->city, $property->state])) }}
            </p>
        @endif

        {{-- Size --}}
        @if($property->size_sqm)
            <div class="mt-2 flex items-center gap-1.5">
                <flux:icon.arrows-pointing-out class="size-3.5 text-zinc-400" variant="outline" />
                <span class="text-xs text-zinc-500">{{ $property->size_sqm }} {{ __('sq ft') }}</span>
            </div>
        @endif
    </div>
</div>
