@php
    $user       = auth()->user();
    $navItems   = [
        ['key' => 'profile',   'label' => __('Profile Management'), 'icon' => 'user'],
        ['key' => 'password',  'label' => __('Change Password'),     'icon' => 'lock'],
        ['key' => 'rents',     'label' => __('My Rents'),            'icon' => 'building'],
        ['key' => 'favorites', 'label' => __('Favorite List'),       'icon' => 'heart'],
    ];
    $gradients = ['from-amber-300 to-orange-500','from-blue-300 to-blue-500','from-green-300 to-emerald-500','from-violet-300 to-purple-500'];
@endphp

<div>

    <x-public.navbar />

    {{-- ══ PAGE BODY ═════════════════════════════════════════════════════════ --}}
    <div class="min-h-screen bg-zinc-50">
        <div class="mx-auto max-w-7xl px-6 py-10 lg:px-10">
            <div class="flex flex-col gap-8 lg:flex-row lg:items-start">

                {{-- ══ SIDEBAR ════════════════════════════════════════════════ --}}
                <aside class="w-full shrink-0 lg:w-64">
                    <div class="rounded-2xl border border-zinc-100 bg-white p-6 shadow-sm">
                        <h2 class="mb-6 text-lg font-extrabold text-zinc-900">{{ __('My Account') }}</h2>

                        <nav class="space-y-1">
                            @foreach($navItems as $item)
                                <button wire:click="setSection('{{ $item['key'] }}')"
                                    class="flex w-full items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-medium transition
                                        {{ $section === $item['key']
                                            ? 'bg-amber-600 text-white'
                                            : 'text-zinc-600 hover:bg-zinc-50 hover:text-zinc-900' }}">

                                    {{-- Icons --}}
                                    @if($item['icon'] === 'user')
                                        <svg class="size-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/></svg>
                                    @elseif($item['icon'] === 'lock')
                                        <svg class="size-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"/></svg>
                                    @elseif($item['icon'] === 'building')
                                        <svg class="size-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"/></svg>
                                    @elseif($item['icon'] === 'heart')
                                        <svg class="size-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/></svg>
                                    @endif

                                    {{ $item['label'] }}
                                </button>
                            @endforeach

                            {{-- Logout --}}
                            <button wire:click="logout"
                                class="flex w-full items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-medium text-zinc-600 transition hover:bg-red-50 hover:text-red-600">
                                <svg class="size-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15M12 9l3 3m0 0-3 3m3-3H2.25"/></svg>
                                {{ __('Logout') }}
                            </button>
                        </nav>
                    </div>
                </aside>

                {{-- ══ CONTENT ═════════════════════════════════════════════════ --}}
                <div class="flex-1 min-w-0">

                    {{-- ── PROFILE MANAGEMENT ──────────────────────────────── --}}
                    @if($section === 'profile')
                        <div class="rounded-2xl border border-zinc-100 bg-white p-8 shadow-sm">

                            {{-- Avatar --}}
                            <div class="mb-8 flex justify-center">
                                <div class="relative">
                                    <div class="flex size-24 items-center justify-center rounded-full bg-gradient-to-br from-amber-400 to-orange-500 text-3xl font-extrabold text-white shadow-md">
                                        {{ $user?->initials() }}
                                    </div>
                                    <button class="absolute bottom-0 right-0 flex size-7 items-center justify-center rounded-full border-2 border-white bg-amber-600 text-white shadow transition hover:bg-amber-700">
                                        <svg class="size-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125"/></svg>
                                    </button>
                                </div>
                            </div>

                            @if($profileSaved)
                                <div class="mb-5 rounded-xl bg-green-50 border border-green-200 px-4 py-3 text-sm font-medium text-green-700">
                                    {{ __('Profile updated successfully.') }}
                                </div>
                            @endif

                            <div class="space-y-5">
                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-zinc-700">{{ __('Name') }}</label>
                                    <input wire:model="name" type="text"
                                        class="w-full rounded-xl border border-zinc-200 px-4 py-2.5 text-sm outline-none focus:border-amber-400 focus:ring-2 focus:ring-amber-100 transition" />
                                    @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-zinc-700">{{ __('Email') }}</label>
                                    <input wire:model="email" type="email"
                                        class="w-full rounded-xl border border-zinc-200 px-4 py-2.5 text-sm outline-none focus:border-amber-400 focus:ring-2 focus:ring-amber-100 transition" />
                                    @error('email') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-zinc-700">{{ __('Phone Number') }}</label>
                                    <input wire:model="phone" type="tel"
                                        class="w-full rounded-xl border border-zinc-200 px-4 py-2.5 text-sm outline-none focus:border-amber-400 focus:ring-2 focus:ring-amber-100 transition" />
                                    @error('phone') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                                </div>

                                <div class="flex gap-3 pt-2">
                                    <button wire:click="fillProfileFromUser" type="button"
                                        class="flex-1 rounded-xl border border-zinc-200 py-2.5 text-sm font-semibold text-zinc-700 transition hover:bg-zinc-50">
                                        {{ __('Cancel') }}
                                    </button>
                                    <button wire:click="saveProfile" type="button"
                                        class="flex-1 rounded-xl bg-amber-600 py-2.5 text-sm font-bold text-white transition hover:bg-amber-700"
                                        wire:loading.attr="disabled" wire:loading.class="opacity-75">
                                        <span wire:loading.remove wire:target="saveProfile">{{ __('Save Changes') }}</span>
                                        <span wire:loading wire:target="saveProfile">{{ __('Saving...') }}</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                    {{-- ── CHANGE PASSWORD ──────────────────────────────────── --}}
                    @elseif($section === 'password')
                        <div class="rounded-2xl border border-zinc-100 bg-white p-8 shadow-sm">
                            <h2 class="mb-1 text-xl font-extrabold text-zinc-900">{{ __('Change Your Password') }}</h2>
                            <p class="mb-7 text-sm text-zinc-500">{{ __('Enter Your New Currently Password To Change Password') }}</p>

                            @if($passwordSaved)
                                <div class="mb-5 rounded-xl bg-green-50 border border-green-200 px-4 py-3 text-sm font-medium text-green-700">
                                    {{ __('Password changed successfully.') }}
                                </div>
                            @endif

                            @if($passwordError)
                                <div class="mb-5 rounded-xl bg-red-50 border border-red-200 px-4 py-3 text-sm font-medium text-red-700">
                                    {{ $passwordError }}
                                </div>
                            @endif

                            <div class="space-y-5">
                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-zinc-700">{{ __('Currently Password') }}</label>
                                    <input wire:model="currentPassword" type="password" placeholder="{{ __('Enter your currently Password') }}"
                                        class="w-full rounded-xl border border-zinc-200 px-4 py-2.5 text-sm outline-none focus:border-amber-400 focus:ring-2 focus:ring-amber-100 transition" />
                                    @error('currentPassword') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-zinc-700">{{ __('New Password') }}</label>
                                    <input wire:model="newPassword" type="password" placeholder="{{ __('Enter your New Password') }}"
                                        class="w-full rounded-xl border border-zinc-200 px-4 py-2.5 text-sm outline-none focus:border-amber-400 focus:ring-2 focus:ring-amber-100 transition" />
                                    @error('newPassword') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-zinc-700">{{ __('Confirm Password') }}</label>
                                    <input wire:model="confirmPassword" type="password" placeholder="{{ __('Enter your New Password again') }}"
                                        class="w-full rounded-xl border border-zinc-200 px-4 py-2.5 text-sm outline-none focus:border-amber-400 focus:ring-2 focus:ring-amber-100 transition" />
                                    @error('confirmPassword') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                                </div>

                                <button wire:click="changePassword" type="button"
                                    class="w-full rounded-xl bg-amber-600 py-3 text-sm font-bold text-white transition hover:bg-amber-700"
                                    wire:loading.attr="disabled" wire:loading.class="opacity-75">
                                    <span wire:loading.remove wire:target="changePassword">{{ __('Change Password') }}</span>
                                    <span wire:loading wire:target="changePassword">{{ __('Updating...') }}</span>
                                </button>
                            </div>
                        </div>

                    {{-- ── MY RENTS ─────────────────────────────────────────── --}}
                    @elseif($section === 'rents')
                        @if($this->rents->isEmpty())
                            <div class="rounded-2xl border border-dashed border-zinc-200 bg-white py-16 text-center shadow-sm">
                                <svg class="mx-auto mb-3 size-12 text-zinc-200" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"/></svg>
                                <p class="text-sm font-medium text-zinc-400">{{ __('No rental contracts found.') }}</p>
                                <a href="{{ route('properties.index') }}" wire:navigate
                                    class="mt-4 inline-block rounded-xl bg-amber-600 px-5 py-2 text-sm font-bold text-white transition hover:bg-amber-700">
                                    {{ __('Browse Properties') }}
                                </a>
                            </div>
                        @else
                            <div class="space-y-4">
                                @foreach($this->rents as $contract)
                                    @php
                                        $prop  = $contract->property;
                                        $img   = $prop?->images->first();
                                        $g     = $gradients[($prop?->id ?? 0) % count($gradients)];
                                        $price = $contract->monthly_rent ?? $contract->weekly_rent ?? $contract->daily_rent ?? $contract->yearly_rent;
                                        $priceLabel = $contract->monthly_rent ? '/month' : ($contract->weekly_rent ? '/week' : ($contract->daily_rent ? '/day' : '/year'));
                                        $avgRating = $prop?->reviews_avg_rating ? round((float)$prop->reviews_avg_rating, 1) : null;
                                    @endphp
                                    <div class="flex gap-0 overflow-hidden rounded-2xl border border-zinc-100 bg-white shadow-sm">
                                        {{-- Image --}}
                                        <div class="h-36 w-36 shrink-0 overflow-hidden bg-gradient-to-br {{ $g }}">
                                            @if($img)
                                                <img src="{{ $img->url() }}" alt="{{ $prop?->title }}" class="h-full w-full object-cover" />
                                            @endif
                                        </div>

                                        {{-- Info --}}
                                        <div class="flex flex-1 items-start justify-between gap-4 px-5 py-4">
                                            <div>
                                                {{-- Status badge --}}
                                                @php
                                                    $statusColor = match($contract->contract_status) {
                                                        'active'    => 'bg-green-100 text-green-700',
                                                        'completed' => 'bg-blue-100 text-blue-700',
                                                        'cancelled' => 'bg-red-100 text-red-600',
                                                        default     => 'bg-zinc-100 text-zinc-600',
                                                    };
                                                    $statusLabel = ucfirst($contract->contract_status ?? 'Pending');
                                                @endphp
                                                <span class="inline-block rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $statusColor }}">
                                                    {{ $statusLabel }}
                                                </span>

                                                <h3 class="mt-1.5 text-base font-bold text-zinc-900">{{ $prop?->title ?? __('Property') }}</h3>
                                                <p class="mt-0.5 text-xs text-zinc-500">
                                                    {{ implode(', ', array_filter([$prop?->address_line_1, $prop?->city, $prop?->state])) ?: '—' }}
                                                </p>
                                                @if($prop?->size_sqm)
                                                    <p class="mt-1 flex items-center gap-1 text-xs text-zinc-400">
                                                        <svg class="size-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3.75v4.5m0-4.5h4.5m-4.5 0L9 9M3.75 20.25v-4.5m0 4.5h4.5m-4.5 0L9 15M20.25 3.75h-4.5m4.5 0v4.5m0-4.5L15 9m5.25 11.25h-4.5m4.5 0v-4.5m0 4.5L15 15"/></svg>
                                                        {{ number_format($prop->size_sqm) }} {{ __('sq ft') }}
                                                    </p>
                                                @endif
                                            </div>

                                            <div class="shrink-0 text-right">
                                                @if($avgRating)
                                                    <div class="flex items-center justify-end gap-1">
                                                        <svg class="size-3.5 text-amber-400" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd"/></svg>
                                                        <span class="text-xs font-semibold text-zinc-700">{{ $avgRating }}</span>
                                                    </div>
                                                @endif
                                                @if($price)
                                                    <p class="mt-1 text-sm font-extrabold text-amber-600">${{ number_format($price) }}<span class="text-xs font-normal text-zinc-400">{{ $priceLabel }}</span></p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                    {{-- ── FAVORITE LIST ────────────────────────────────────── --}}
                    @elseif($section === 'favorites')
                        @if($this->favorites->isEmpty())
                            <div class="rounded-2xl border border-dashed border-zinc-200 bg-white py-16 text-center shadow-sm">
                                <svg class="mx-auto mb-3 size-12 text-zinc-200" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/></svg>
                                <p class="text-sm font-medium text-zinc-400">{{ __('No favorite properties yet.') }}</p>
                                <a href="{{ route('properties.index') }}" wire:navigate
                                    class="mt-4 inline-block rounded-xl bg-amber-600 px-5 py-2 text-sm font-bold text-white transition hover:bg-amber-700">
                                    {{ __('Browse Properties') }}
                                </a>
                            </div>
                        @else
                            <div class="space-y-4">
                                @foreach($this->favorites as $fav)
                                    @php
                                        $prop       = $fav->property;
                                        $img        = $prop?->images->first();
                                        $g          = $gradients[($prop?->id ?? 0) % count($gradients)];
                                        $price      = $prop?->monthly_rent ?? $prop?->weekly_rent ?? $prop?->daily_rent ?? $prop?->yearly_rent;
                                        $priceLabel = $prop?->monthly_rent ? '/month' : ($prop?->weekly_rent ? '/week' : ($prop?->daily_rent ? '/day' : '/year'));
                                        $avgRating  = $prop?->reviews_avg_rating ? round((float)$prop->reviews_avg_rating, 1) : null;
                                        $reviewCount = $prop?->reviews_count ?? 0;
                                        $isOccupied = ($prop?->contracts_count ?? 0) > 0;
                                    @endphp
                                    <div class="flex gap-0 overflow-hidden rounded-2xl border border-zinc-100 bg-white shadow-sm transition hover:shadow-md">
                                        {{-- Image with heart overlay --}}
                                        <div class="relative h-36 w-36 shrink-0 overflow-hidden bg-gradient-to-br {{ $g }}">
                                            @if($img)
                                                <img src="{{ $img->url() }}" alt="{{ $prop?->title }}" class="h-full w-full object-cover" />
                                            @endif
                                            <button wire:click="removeFavorite({{ $prop?->id }})"
                                                class="absolute left-2 top-2 flex size-7 items-center justify-center rounded-full bg-white/90 text-rose-500 shadow transition hover:bg-white">
                                                <svg class="size-4" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 0 1-.383-.218 25.18 25.18 0 0 1-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0 1 12 5.052 5.5 5.5 0 0 1 16.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 0 1-4.244 3.17 15.247 15.247 0 0 1-.383.219l-.022.012-.007.004-.003.001a.752.752 0 0 1-.704 0l-.003-.001Z" clip-rule="evenodd"/></svg>
                                            </button>
                                        </div>

                                        {{-- Info --}}
                                        <div class="flex flex-1 items-start justify-between gap-4 px-5 py-4">
                                            <div>
                                                <span class="inline-block rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $isOccupied ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' }}">
                                                    {{ $isOccupied ? __('Occupied') : __('Available') }}
                                                </span>
                                                <h3 class="mt-1.5 text-base font-bold text-zinc-900">{{ $prop?->title ?? __('Property') }}</h3>
                                                <p class="mt-0.5 text-xs text-zinc-500">
                                                    {{ implode(', ', array_filter([$prop?->address_line_1, $prop?->city, $prop?->state])) ?: '—' }}
                                                </p>
                                                @if($prop?->size_sqm)
                                                    <p class="mt-1 flex items-center gap-1 text-xs text-zinc-400">
                                                        <svg class="size-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3.75v4.5m0-4.5h4.5m-4.5 0L9 9M3.75 20.25v-4.5m0 4.5h4.5m-4.5 0L9 15M20.25 3.75h-4.5m4.5 0v4.5m0-4.5L15 9m5.25 11.25h-4.5m4.5 0v-4.5m0 4.5L15 15"/></svg>
                                                        {{ number_format($prop->size_sqm) }} {{ __('sq ft') }}
                                                    </p>
                                                @endif
                                            </div>

                                            <div class="shrink-0 text-right">
                                                @if($avgRating)
                                                    <div class="flex items-center justify-end gap-1">
                                                        <svg class="size-3.5 text-amber-400" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd"/></svg>
                                                        <span class="text-xs font-semibold text-zinc-700">{{ $avgRating }}</span>
                                                        <span class="text-xs text-zinc-400">({{ $reviewCount }} {{ __('Review') }})</span>
                                                    </div>
                                                @endif
                                                @if($price)
                                                    <p class="mt-1 text-sm font-extrabold text-amber-600">${{ number_format($price) }}<span class="text-xs font-normal text-zinc-400">{{ $priceLabel }}</span></p>
                                                @endif
                                                @if($prop)
                                                    <a href="{{ route('properties.show', $prop->slug) }}" wire:navigate
                                                        class="mt-2 inline-block text-xs font-medium text-amber-600 hover:underline">
                                                        {{ __('View') }} →
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                    @endif

                </div>
            </div>
        </div>
    </div>

    {{-- ══ FOOTER ══════════════════════════════════════════════════════════════ --}}
    <footer class="border-t border-zinc-100 bg-white">
        <div class="mx-auto max-w-7xl px-6 py-12 lg:px-10">
            <div class="grid grid-cols-1 gap-10 lg:grid-cols-4">
                <div class="lg:col-span-1">
                    <span class="text-base font-extrabold tracking-widest text-amber-600 uppercase">SAHOOME</span>
                    <p class="mt-3 text-xs leading-relaxed text-zinc-500">It Refers To The Practice Of Sharing<br>Access To Real Estate (Such As<br>Homes, Offices, Or Tourist Rentals).</p>
                    <div class="mt-4 h-px w-16 bg-zinc-200"></div>
                    <p class="mt-3 text-xs font-semibold text-zinc-500">{{ __('Social Links') }}</p>
                    <div class="mt-3 flex gap-3">
                        <a href="#" class="flex size-8 items-center justify-center rounded-full border border-zinc-200 text-zinc-500 transition hover:border-amber-400 hover:text-amber-600">
                            <svg class="size-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        <a href="#" class="flex size-8 items-center justify-center rounded-full border border-zinc-200 text-zinc-500 transition hover:border-amber-400 hover:text-amber-600">
                            <svg class="size-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                        </a>
                        <a href="#" class="flex size-8 items-center justify-center rounded-full border border-zinc-200 text-zinc-500 transition hover:border-amber-400 hover:text-amber-600">
                            <svg class="size-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </a>
                    </div>
                    <p class="mt-5 text-xs text-zinc-400">{{ __('All Copywrite Reserved For Sahoome@2025') }}</p>
                </div>
                <div>
                    <h5 class="mb-4 text-xs font-bold uppercase tracking-wider text-zinc-900">{{ __('Supports') }}</h5>
                    <ul class="space-y-2.5 text-sm text-zinc-500">
                        <li><a href="#" class="transition hover:text-amber-600">{{ __('Privacy & Policy') }}</a></li>
                        <li><a href="#" class="transition hover:text-amber-600">{{ __('Terms & Conidlitian') }}</a></li>
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
                            <svg class="mt-0.5 size-4 shrink-0 text-amber-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/></svg>
                            <span>Sahoome@gmail.com</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="mt-0.5 size-4 shrink-0 text-amber-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                            <span>321 Market St, Los Angeles, CA.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="mt-0.5 size-4 shrink-0 text-amber-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z"/></svg>
                            <span>+996-554-648</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

</div>
