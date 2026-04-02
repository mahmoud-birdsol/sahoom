<div class="flex flex-col gap-6 p-6">

    {{-- Header --}}
    <div class="flex items-start justify-between gap-4">
        <div class="space-y-0.5">
            <flux:heading size="xl" class="font-bold text-zinc-900">{{ __('Properties') }}</flux:heading>
            <flux:subheading class="text-zinc-500">{{ __('Welcome back, manage your properties and bookings') }}</flux:subheading>
        </div>
        <flux:button wire:click="openCreateForm" variant="primary" icon="plus" class="shrink-0 bg-amber-500 hover:bg-amber-600 border-amber-500 hover:border-amber-600">
            {{ __('New Property') }}
        </flux:button>
    </div>

    {{-- Properties Grid --}}
    @if($properties->isEmpty())
        <div class="flex flex-col items-center justify-center rounded-xl border border-dashed border-zinc-200 bg-white py-20 text-center">
            <div class="flex size-16 items-center justify-center rounded-2xl bg-zinc-100">
                <flux:icon.building-office-2 class="size-8 text-zinc-400" variant="outline" />
            </div>
            <p class="mt-4 text-base font-semibold text-zinc-700">{{ __('No properties yet') }}</p>
            <p class="mt-1 text-sm text-zinc-400">{{ __('Add your first property to get started.') }}</p>
            <flux:button wire:click="openCreateForm" variant="primary" icon="plus" class="mt-6 bg-amber-500 hover:bg-amber-600 border-amber-500 hover:border-amber-600">
                {{ __('Add Property') }}
            </flux:button>
        </div>
    @else
        <div class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">
            @foreach($properties as $property)
                <x-landlord.property-card
                    :property="$property"
                    :reviews-count="$property->reviews_count ?? 0"
                    :reviews-avg-rating="$property->reviews_avg_rating"
                    :favorites-count="$property->favorites_count ?? 0"
                    :active-contract="$property->contracts->first()"
                />
            @endforeach
        </div>

        @if($properties->hasPages())
            <div class="flex justify-center">
                {{ $properties->links() }}
            </div>
        @endif
    @endif

    {{-- ── Create / Edit Modal ─────────────────────────────────────────── --}}
    @if($showPropertyForm)
        <div
            class="fixed inset-0 z-50 flex items-center justify-center p-4"
            x-data="{ init() { document.body.classList.add('overflow-hidden'); }, destroy() { document.body.classList.remove('overflow-hidden'); } }"
        >
            {{-- Backdrop --}}
            <div
                class="absolute inset-0 bg-black/50"
                wire:click="closeForm"
            ></div>

            {{-- Panel --}}
            <div class="relative z-10 w-full max-w-lg rounded-xl bg-white shadow-2xl">

                {{-- Header --}}
                <div class="flex items-center justify-between border-b border-zinc-100 px-6 py-4">
                    <h2 class="text-lg font-semibold text-zinc-900">
                        {{ $isEditing ? __('Edit Property') : __('New Property') }}
                    </h2>
                    <button wire:click="closeForm" class="flex size-7 items-center justify-center rounded-full border border-zinc-200 text-zinc-400 hover:bg-zinc-50">
                        <flux:icon.x-mark class="size-4" />
                    </button>
                </div>

                {{-- Body --}}
                <div class="max-h-[75vh] overflow-y-auto px-6 py-5 space-y-4">

                    {{-- Name --}}
                    <flux:field>
                        <flux:label>{{ __('Name') }}</flux:label>
                        <flux:input wire:model="formTitle" placeholder="{{ __('Enter your name') }}" />
                        <flux:error name="formTitle" />
                    </flux:field>

                    {{-- Address with Google Places Autocomplete --}}
                    <flux:field>
                        <flux:label>{{ __('Address') }}</flux:label>
                        <div
                            x-data="{}"
                            x-init="
                                const waitForMaps = () => {
                                    if (typeof google === 'undefined' || typeof google.maps === 'undefined') {
                                        setTimeout(waitForMaps, 200);
                                        return;
                                    }
                                    const input = $el.querySelector('input');
                                    const ac = new google.maps.places.Autocomplete(input, {
                                        types: ['address'],
                                        fields: ['formatted_address', 'geometry', 'address_components'],
                                    });
                                    ac.addListener('place_changed', () => {
                                        const place = ac.getPlace();
                                        if (!place.geometry) return;
                                        $wire.set('formAddress', place.formatted_address ?? '');
                                        $wire.set('formLatitude', place.geometry.location.lat());
                                        $wire.set('formLongitude', place.geometry.location.lng());
                                        let city = '', state = '';
                                        (place.address_components ?? []).forEach(c => {
                                            if (c.types.includes('locality')) city = c.long_name;
                                            if (c.types.includes('administrative_area_level_1')) state = c.long_name;
                                        });
                                        if (city) $wire.set('formCity', city);
                                        if (state) $wire.set('formAddress', place.formatted_address ?? '');
                                    });
                                };
                                waitForMaps();
                            "
                        >
                            <flux:input wire:model="formAddress" placeholder="{{ __('321 Market St, Los Angeles, CA') }}" />
                        </div>
                        <flux:error name="formAddress" />
                    </flux:field>

                    {{-- Price + Pricing Type --}}
                    <flux:field>
                        <flux:label>{{ __('Price') }}</flux:label>
                        <div class="flex gap-2">
                            <flux:input wire:model="formPrice" type="number" min="0" step="0.01" placeholder="{{ __('Enter your Price') }}" class="flex-1" />
                            <flux:select wire:model="formPricingType" class="w-32">
                                <flux:select.option value="daily">{{ __('Day') }}</flux:select.option>
                                <flux:select.option value="weekly">{{ __('Week') }}</flux:select.option>
                                <flux:select.option value="monthly">{{ __('Month') }}</flux:select.option>
                                <flux:select.option value="yearly">{{ __('Year') }}</flux:select.option>
                            </flux:select>
                        </div>
                        <flux:error name="formPrice" />
                    </flux:field>

                    {{-- Description --}}
                    <flux:field>
                        <flux:label>{{ __('Description') }}</flux:label>
                        <flux:textarea wire:model="formDescription" rows="3" placeholder="{{ __('Enter your Description') }}" />
                        <flux:error name="formDescription" />
                    </flux:field>

                    {{-- Property Details --}}
                    <div>
                        <flux:label class="mb-1.5 block">{{ __('Property Details') }}</flux:label>
                        <div class="grid grid-cols-3 gap-2">
                            <flux:input wire:model="formFloor" placeholder="{{ __('Floor') }}" />
                            <flux:input wire:model="formSpaceNumber" placeholder="{{ __('Space No.') }}" />
                            <flux:input wire:model="formCity" placeholder="{{ __('City') }}" />
                        </div>
                    </div>

                    {{-- Coordinates (auto-filled by Places, editable manually) --}}
                    <div>
                        <flux:label class="mb-1.5 block">{{ __('Coordinates') }} <span class="ml-1 text-xs font-normal text-zinc-400">({{ __('auto-filled from address') }})</span></flux:label>
                        <div class="grid grid-cols-2 gap-2">
                            <flux:input wire:model="formLatitude" type="number" step="any" placeholder="{{ __('Latitude') }}" />
                            <flux:input wire:model="formLongitude" type="number" step="any" placeholder="{{ __('Longitude') }}" />
                        </div>
                        @if($formLatitude && $formLongitude)
                            <div class="mt-2 overflow-hidden rounded-lg border border-zinc-100">
                                <iframe
                                    src="https://www.google.com/maps/embed/v1/place?key={{ config('services.google.maps_key') }}&q={{ $formLatitude }},{{ $formLongitude }}&zoom=15"
                                    class="h-32 w-full"
                                    loading="lazy"
                                    allowfullscreen
                                ></iframe>
                            </div>
                        @endif
                    </div>

                    {{-- Image Upload --}}
                    <div>
                        {{-- Existing saved images (edit mode) --}}
                        @if($isEditing && $this->existingImages->isNotEmpty())
                            <p class="mb-1.5 text-xs font-medium text-zinc-500">{{ __('Saved images') }}</p>
                            <div class="mb-3 flex flex-wrap gap-2">
                                @foreach($this->existingImages as $img)
                                    <div class="group relative">
                                        <img src="{{ $img->url() }}" class="size-16 rounded-lg object-cover ring-1 ring-zinc-200" />
                                        <button
                                            wire:click="removeExistingImage({{ $img->id }})"
                                            wire:confirm="{{ __('Remove this image?') }}"
                                            class="absolute -right-1.5 -top-1.5 hidden size-5 items-center justify-center rounded-full bg-red-500 text-white group-hover:flex"
                                        >
                                            <flux:icon.x-mark class="size-3" />
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        {{-- New uploads preview --}}
                        @if(count($formImages))
                            <p class="mb-1.5 text-xs font-medium text-zinc-500">{{ __('New images') }} ({{ count($formImages) }})</p>
                            <div class="mb-3 flex flex-wrap gap-2">
                                @foreach($formImages as $i => $img)
                                    <div class="group relative">
                                        <img src="{{ $img->temporaryUrl() }}" class="size-16 rounded-lg object-cover ring-1 ring-amber-300" />
                                        <button
                                            wire:click="removeFormImage({{ $i }})"
                                            class="absolute -right-1.5 -top-1.5 hidden size-5 items-center justify-center rounded-full bg-red-500 text-white group-hover:flex"
                                        >
                                            <flux:icon.x-mark class="size-3" />
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        {{-- Upload zone (always visible) --}}
                        <label class="flex cursor-pointer flex-col items-center justify-center rounded-lg border-2 border-dashed border-amber-300 bg-amber-50/30 px-4 py-6 text-center transition hover:bg-amber-50">
                            <flux:icon.arrow-up-tray class="size-6 text-amber-500 mb-2" variant="outline" />
                            <span class="text-sm font-medium text-amber-600">{{ __('Drop Files Here or Click to Browse') }}</span>
                            <span class="mt-1 text-xs text-zinc-400">{{ __('Multiple images, up to 10 MB each') }}</span>
                            <input type="file" wire:model="formImages" accept="image/*" multiple class="hidden" />
                        </label>
                        <div wire:loading wire:target="formImages" class="mt-1 flex items-center gap-1.5 text-xs text-amber-600">
                            <flux:icon.arrow-path class="size-3 animate-spin" />
                            {{ __('Uploading…') }}
                        </div>
                        @error('formImages.*') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Footer --}}
                <div class="border-t border-zinc-100 px-6 py-4">
                    <flux:button wire:click="saveProperty" wire:loading.attr="disabled" variant="primary" class="w-full bg-amber-700 hover:bg-amber-800 border-amber-700">
                        <span wire:loading.remove wire:target="saveProperty">
                            {{ $isEditing ? __('Save Changes') : __('Add Property') }}
                        </span>
                        <span wire:loading wire:target="saveProperty">{{ __('Saving…') }}</span>
                    </flux:button>
                </div>
            </div>
        </div>
    @endif

    {{-- ── Property Details Modal ──────────────────────────────────────── --}}
    @if($showPropertyDetails && $this->detailsProperty)
        @php $prop = $this->detailsProperty; @endphp
        <div
            class="fixed inset-0 z-50 flex items-center justify-center p-4"
            x-data="{ init() { document.body.classList.add('overflow-hidden'); }, destroy() { document.body.classList.remove('overflow-hidden'); } }"
        >
            {{-- Backdrop --}}
            <div class="absolute inset-0 bg-black/50" wire:click="closeDetails"></div>

            {{-- Panel --}}
            <div class="relative z-10 w-full max-w-xl rounded-xl bg-white shadow-2xl">

                {{-- Header --}}
                <div class="flex items-center justify-between border-b border-zinc-100 px-6 py-4">
                    <h2 class="text-lg font-semibold text-zinc-900">{{ __('Property Details') }}</h2>
                    <button wire:click="closeDetails" class="flex size-7 items-center justify-center rounded-full border border-zinc-200 text-zinc-400 hover:bg-zinc-50">
                        <flux:icon.x-mark class="size-4" />
                    </button>
                </div>

                {{-- Body --}}
                <div class="max-h-[80vh] overflow-y-auto px-6 py-5 space-y-5">

                    {{-- Image Gallery --}}
                    @php
                        $gradients = ['from-amber-300 to-orange-400','from-blue-300 to-blue-500','from-green-300 to-emerald-500','from-violet-300 to-purple-500','from-rose-300 to-pink-500'];
                        $g = $gradients[$prop->id % count($gradients)];
                        $imgs = $prop->images ?? collect();
                    @endphp
                    <div class="grid grid-cols-3 gap-2">
                        {{-- Main large image --}}
                        <div class="col-span-1 row-span-2 h-44 overflow-hidden rounded-lg bg-gradient-to-br {{ $g }} flex items-center justify-center">
                            @if($imgs->isNotEmpty())
                                <img src="{{ $imgs->first()->url() }}" class="h-full w-full object-cover" />
                            @else
                                <flux:icon.building-office-2 class="size-10 text-white/50" variant="outline" />
                            @endif
                        </div>
                        {{-- 4 thumbnails --}}
                        @foreach(range(0, 3) as $i)
                            @php $thumbImg = $imgs->get($i + 1) ?? $imgs->first(); @endphp
                            <div class="h-20 overflow-hidden rounded-lg bg-gradient-to-br {{ $g }} flex items-center justify-center {{ $i >= 2 ? 'opacity-60' : '' }}">
                                @if($thumbImg)
                                    <img src="{{ $thumbImg->url() }}" class="h-full w-full object-cover" />
                                @else
                                    <flux:icon.building-office-2 class="size-5 text-white/50" variant="outline" />
                                @endif
                            </div>
                        @endforeach
                    </div>

                    {{-- Title + Rating + Actions --}}
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <h3 class="text-base font-bold text-zinc-900">{{ $prop->title }}</h3>
                            @php $avgRating = $prop->reviews_avg_rating ? round((float)$prop->reviews_avg_rating,1) : null; @endphp
                            @if($avgRating)
                                <div class="mt-1 flex items-center gap-1">
                                    <flux:icon.star class="size-3.5 text-amber-400" variant="solid" />
                                    <span class="text-sm font-semibold text-amber-600">{{ $avgRating }}</span>
                                    <span class="text-xs text-zinc-400">({{ $prop->reviews_count }} {{ __('Review') }})</span>
                                </div>
                            @else
                                <p class="mt-1 text-xs text-zinc-400">{{ __('No reviews yet') }}</p>
                            @endif
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            <button wire:click="openEditForm({{ $prop->id }})" class="flex size-8 items-center justify-center rounded-lg border border-zinc-200 text-zinc-500 hover:bg-zinc-50">
                                <flux:icon.pencil-square class="size-4" variant="outline" />
                            </button>
                            <button wire:click="deleteProperty({{ $prop->id }})" wire:confirm="{{ __('Delete this property?') }}" class="flex size-8 items-center justify-center rounded-lg border border-red-100 text-red-500 hover:bg-red-50">
                                <flux:icon.trash class="size-4" variant="outline" />
                            </button>
                        </div>
                    </div>

                    {{-- Status + Action Buttons --}}
                    @php
                        $propStatusValue = $prop->status?->value ?? $prop->status;
                        [$propStatusLabel, $propStatusClass] = match($propStatusValue) {
                            'approved'  => [__('Approved / Published'), 'bg-green-50 text-green-700'],
                            'in_review' => [__('In Review'),            'bg-sky-50 text-sky-700'],
                            'rejected'  => [__('Rejected'),             'bg-red-50 text-red-700'],
                            'suspended' => [__('Suspended'),            'bg-orange-50 text-orange-700'],
                            default     => [__('Draft'),                 'bg-zinc-100 text-zinc-500'],
                        };
                    @endphp
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $propStatusClass }}">
                            {{ $propStatusLabel }}
                        </span>
                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold
                            {{ $prop->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-zinc-100 text-zinc-400' }}">
                            {{ $prop->is_active ? __('Active') : __('Inactive') }}
                        </span>

                        @if($propStatusValue !== 'approved')
                            <button
                                wire:click="publishProperty({{ $prop->id }})"
                                wire:confirm="{{ __('Publish this property and make it publicly available?') }}"
                                class="ml-auto flex h-8 items-center gap-1.5 rounded-lg border border-amber-200 bg-amber-50 px-3 text-xs font-semibold text-amber-700 transition hover:bg-amber-100"
                            >
                                <flux:icon.globe-alt class="size-3.5" variant="outline" />
                                {{ __('Publish') }}
                            </button>
                        @endif

                        @if($prop->is_active)
                            <button
                                wire:click="toggleActiveProperty({{ $prop->id }})"
                                wire:confirm="{{ __('Deactivate this property? It will be hidden from listings.') }}"
                                class="flex h-8 items-center gap-1.5 rounded-lg border border-zinc-200 px-3 text-xs font-semibold text-zinc-600 transition hover:bg-zinc-50
                                    {{ $propStatusValue !== 'approved' ? '' : 'ml-auto' }}"
                            >
                                <flux:icon.pause-circle class="size-3.5" variant="outline" />
                                {{ __('Deactivate') }}
                            </button>
                        @else
                            <button
                                wire:click="toggleActiveProperty({{ $prop->id }})"
                                class="flex h-8 items-center gap-1.5 rounded-lg border border-green-200 bg-green-50 px-3 text-xs font-semibold text-green-700 transition hover:bg-green-100
                                    {{ $propStatusValue !== 'approved' ? '' : 'ml-auto' }}"
                            >
                                <flux:icon.play-circle class="size-3.5" variant="outline" />
                                {{ __('Activate') }}
                            </button>
                        @endif
                    </div>

                    {{-- Specs --}}
                    <div class="grid grid-cols-3 gap-3">
                        <div class="flex flex-col items-center justify-center rounded-lg border border-zinc-100 bg-zinc-50 py-4 gap-1.5">
                            <flux:icon.building-office class="size-5 text-amber-600" variant="outline" />
                            <span class="text-xs font-semibold text-zinc-700">{{ $prop->floor ? $prop->floor . ' ' . __('Floor') : __('N/A') }}</span>
                        </div>
                        <div class="flex flex-col items-center justify-center rounded-lg border border-zinc-100 bg-zinc-50 py-4 gap-1.5">
                            <flux:icon.arrows-pointing-out class="size-5 text-amber-600" variant="outline" />
                            <span class="text-xs font-semibold text-zinc-700">{{ $prop->size_sqm ? number_format($prop->size_sqm) . ' sq ft' : __('N/A') }}</span>
                        </div>
                        <div class="flex flex-col items-center justify-center rounded-lg border border-zinc-100 bg-zinc-50 py-4 gap-1.5">
                            <flux:icon.map-pin class="size-5 text-amber-600" variant="outline" />
                            <span class="text-xs font-semibold text-zinc-700">{{ $prop->space_number ?: __('N/A') }}</span>
                        </div>
                    </div>

                    {{-- Description --}}
                    @if($prop->description)
                        <p class="text-sm text-zinc-600 leading-relaxed">{{ $prop->description }}</p>
                    @endif

                    {{-- Location --}}
                    <div>
                        <p class="mb-2 text-sm font-semibold text-zinc-800">{{ __('Location') }}</p>
                        @if($prop->latitude && $prop->longitude)
                            <div class="overflow-hidden rounded-lg border border-zinc-100">
                                <iframe
                                    src="https://www.google.com/maps/embed/v1/place?key={{ config('services.google.maps_key') }}&q={{ $prop->latitude }},{{ $prop->longitude }}&zoom=16"
                                    class="h-40 w-full"
                                    loading="lazy"
                                    allowfullscreen
                                ></iframe>
                            </div>
                        @else
                            <div class="flex h-32 items-center justify-center rounded-lg border border-dashed border-zinc-200 bg-zinc-50">
                                <div class="text-center">
                                    <flux:icon.map-pin class="mx-auto size-6 text-zinc-300" variant="outline" />
                                    <p class="mt-1 text-xs text-zinc-400">{{ __('No coordinates set — use address autocomplete') }}</p>
                                </div>
                            </div>
                        @endif
                        @if($prop->address_line_1)
                            <p class="mt-2 flex items-center gap-1.5 text-xs text-zinc-500">
                                <flux:icon.map-pin class="size-3.5 shrink-0 text-amber-500" variant="solid" />
                                {{ implode(', ', array_filter([$prop->address_line_1, $prop->city, $prop->state, $prop->postal_code])) }}
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
