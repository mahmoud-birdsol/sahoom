<div>

    <x-public.navbar />

    {{-- ══ HERO ═════════════════════════════════════════════════════════════ --}}
    <section class="relative flex min-h-[520px] items-center overflow-hidden bg-zinc-900">
        {{-- Background pattern / gradient --}}
        <div class="absolute inset-0 bg-gradient-to-br from-zinc-900 via-zinc-800 to-amber-950 opacity-90"></div>
        <div class="absolute inset-0 opacity-10" style="background-image: url(\"data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E\");"></div>

        {{-- Amber diagonal accent --}}
        <div class="absolute -right-20 top-0 h-full w-96 bg-amber-600/20 skew-x-[-8deg]"></div>
        <div class="absolute -right-40 top-0 h-full w-80 bg-amber-600/10 skew-x-[-8deg]"></div>

        <div class="relative z-10 mx-auto max-w-7xl px-6 py-20 lg:px-10">
            <div class="max-w-2xl">
                <span class="mb-4 inline-flex items-center gap-2 rounded-full bg-amber-600/20 px-3 py-1 text-xs font-semibold text-amber-400 uppercase tracking-wider">
                    <span class="size-1.5 rounded-full bg-amber-400"></span>
                    {{ __('The #1 Property Platform') }}
                </span>
                <h1 class="mt-4 text-4xl font-extrabold leading-tight text-white lg:text-5xl">
                    {{ __('Find Your Perfect') }}<br>
                    <span class="text-amber-400">{{ __('Popup Store') }}</span>
                    {{ __('with Sahoome') }}
                </h1>
                <p class="mt-5 max-w-lg text-base leading-relaxed text-zinc-300">
                    {{ __('Discover the best retail spaces, pop-up stores, and commercial properties to bring your business to life. We help you find the perfect space for your unique shopping experience.') }}
                </p>
                <div class="mt-8 flex flex-wrap items-center gap-4">
                    <a href="#properties" class="rounded-xl bg-amber-600 px-6 py-3 text-sm font-bold text-white transition hover:bg-amber-700 shadow-lg shadow-amber-900/40">
                        {{ __('Explore Now') }}
                    </a>
                    <a href="#about" class="rounded-xl border border-white/20 px-6 py-3 text-sm font-semibold text-white transition hover:bg-white/10">
                        {{ __('Learn More') }}
                    </a>
                </div>

                {{-- Stats --}}
                <div class="mt-12 flex flex-wrap gap-8">
                    <div>
                        <p class="text-2xl font-extrabold text-amber-400">500+</p>
                        <p class="text-xs text-zinc-400 mt-0.5">{{ __('Properties Listed') }}</p>
                    </div>
                    <div>
                        <p class="text-2xl font-extrabold text-amber-400">1,200+</p>
                        <p class="text-xs text-zinc-400 mt-0.5">{{ __('Happy Landlords') }}</p>
                    </div>
                    <div>
                        <p class="text-2xl font-extrabold text-amber-400">98%</p>
                        <p class="text-xs text-zinc-400 mt-0.5">{{ __('Satisfaction Rate') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ══ MOST POPULAR PROPERTIES ══════════════════════════════════════════ --}}
    <section class="bg-white py-16 lg:py-24" id="properties">
        <div class="mx-auto max-w-7xl px-6 lg:px-10">

            <div class="mb-10 text-center">
                <span class="text-xs font-semibold uppercase tracking-widest text-amber-600">{{ __('Discover our properties') }}</span>
                <h2 class="mt-2 text-3xl font-extrabold text-zinc-900">{{ __('Most Popular Properties') }}</h2>
            </div>

            @if($popularProperties->isEmpty())
                <p class="text-center text-sm text-zinc-400">{{ __('No featured properties yet. Check back soon!') }}</p>
            @else
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach($popularProperties as $property)
                        @php
                            $gradients = ['from-amber-300 to-orange-500','from-blue-300 to-blue-500','from-green-300 to-emerald-500','from-violet-300 to-purple-500'];
                            $g = $gradients[$property->id % count($gradients)];
                            $img = $property->images->first();
                            $rating = $property->reviews_avg_rating ? round((float)$property->reviews_avg_rating, 1) : null;
                            $price = $property->monthly_rent ?? $property->weekly_rent ?? $property->daily_rent;
                            $priceLabel = $property->monthly_rent ? __('/mo') : ($property->weekly_rent ? __('/wk') : __('/day'));
                        @endphp
                        <div class="group overflow-hidden rounded-2xl border border-zinc-100 bg-white shadow-sm transition hover:shadow-lg hover:-translate-y-0.5">
                            {{-- Image --}}
                            <div class="relative h-44 overflow-hidden bg-gradient-to-br {{ $g }}">
                                @if($img)
                                    <img src="{{ $img->url() }}" alt="{{ $property->title }}" class="h-full w-full object-cover transition group-hover:scale-105" />
                                @else
                                    <div class="flex h-full items-center justify-center">
                                        <svg class="size-12 text-white/50" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Z" /></svg>
                                    </div>
                                @endif
                                {{-- Featured badge --}}
                                <span class="absolute left-3 top-3 rounded-full bg-amber-500 px-2 py-0.5 text-xs font-bold text-white">
                                    {{ __('Featured') }}
                                </span>
                                {{-- Favorites --}}
                                <div class="absolute right-3 top-3 flex items-center gap-1 rounded-full bg-white/90 px-2 py-0.5 shadow-sm">
                                    <svg class="size-3.5 text-rose-500" fill="currentColor" viewBox="0 0 24 24"><path d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 01-.383-.218 25.18 25.18 0 01-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0112 5.052 5.5 5.5 0 0116.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 01-4.244 3.17 15.247 15.247 0 01-.383.219l-.022.012-.007.004-.003.001a.752.752 0 01-.704 0l-.003-.001z"/></svg>
                                    <span class="text-xs font-semibold text-zinc-700">{{ $property->favorites_count }}</span>
                                </div>
                            </div>

                            {{-- Card body --}}
                            <div class="p-4">
                                {{-- Rating --}}
                                <div class="mb-2 flex items-center gap-1">
                                    @if($rating)
                                        <svg class="size-3.5 text-amber-400" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd"/></svg>
                                        <span class="text-xs font-semibold text-zinc-700">{{ $rating }}</span>
                                        <span class="text-xs text-zinc-400">({{ $property->reviews_count }})</span>
                                    @else
                                        <span class="text-xs text-zinc-400">{{ __('No reviews') }}</span>
                                    @endif
                                </div>

                                <h3 class="mb-1 text-sm font-bold text-zinc-900 leading-snug line-clamp-1">{{ $property->title }}</h3>

                                @if($price)
                                    <p class="mb-2 text-sm font-extrabold text-amber-600">
                                        ${{ number_format($price) }}<span class="text-xs font-normal text-zinc-400">{{ $priceLabel }}</span>
                                    </p>
                                @endif

                                <p class="flex items-center gap-1 text-xs text-zinc-500 truncate">
                                    <svg class="size-3.5 shrink-0 text-zinc-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                                    {{ implode(', ', array_filter([$property->address_line_1, $property->city])) ?: __('Location TBA') }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- ══ ABOUT / MISSION / VISION ══════════════════════════════════════════ --}}
    <section class="bg-zinc-50 py-16 lg:py-24" id="about">
        <div class="mx-auto grid max-w-7xl grid-cols-1 items-center gap-12 px-6 lg:grid-cols-2 lg:px-10">

            {{-- Left: Image collage --}}
            <div class="relative">
                <div class="grid grid-cols-2 gap-3">
                    <div class="col-span-2 h-56 overflow-hidden rounded-2xl bg-gradient-to-br from-amber-300 to-orange-500 shadow-lg">
                        <div class="flex h-full items-center justify-center">
                            <svg class="size-16 text-white/50" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72l1.189-1.19A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72M6.75 18h3.75a.75.75 0 0 0 .75-.75V13.5a.75.75 0 0 0-.75-.75H6.75a.75.75 0 0 0-.75.75v3.75c0 .414.336.75.75.75Z" /></svg>
                        </div>
                    </div>
                    <div class="h-32 overflow-hidden rounded-2xl bg-gradient-to-br from-blue-300 to-blue-500 shadow"></div>
                    <div class="h-32 overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-300 to-green-500 shadow"></div>
                </div>
                {{-- Amber badge --}}
                <div class="absolute -bottom-4 -right-4 rounded-2xl bg-amber-600 px-5 py-3 text-center text-white shadow-lg shadow-amber-300/40">
                    <p class="text-2xl font-extrabold">10+</p>
                    <p class="text-xs font-medium opacity-90">{{ __('Years Experience') }}</p>
                </div>
            </div>

            {{-- Right: Text --}}
            <div>
                <span class="text-xs font-semibold uppercase tracking-widest text-amber-600">{{ __('Who we are') }}</span>
                <h2 class="mt-2 text-3xl font-extrabold leading-snug text-zinc-900">
                    {{ __('Unveiling our identity,') }}<br>{{ __('vision and values') }}
                </h2>
                <p class="mt-4 text-sm leading-relaxed text-zinc-500">
                    {{ __('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam quis nostrud.') }}
                </p>

                <div class="mt-7 space-y-5">
                    {{-- Mission --}}
                    <div class="flex gap-4">
                        <div class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-amber-100">
                            <svg class="size-5 text-amber-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" /></svg>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-zinc-900">{{ __('Our Mission') }}</h4>
                            <p class="mt-1 text-xs leading-relaxed text-zinc-500">{{ __('Our mission is to help retailers and businesses find the perfect commercial space, simplifying the process of leasing retail and pop-up locations.') }}</p>
                        </div>
                    </div>

                    {{-- Vision --}}
                    <div class="flex gap-4">
                        <div class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-amber-100">
                            <svg class="size-5 text-amber-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-zinc-900">{{ __('Our Vision') }}</h4>
                            <p class="mt-1 text-xs leading-relaxed text-zinc-500">{{ __('Our vision is to be the leading platform connecting landlords and tenants for retail spaces, creating a seamless experience for both parties.') }}</p>
                        </div>
                    </div>
                </div>

                <a href="#contact" class="mt-8 inline-flex items-center gap-2 rounded-xl bg-amber-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-amber-700">
                    {{ __('Get in Touch') }}
                    <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" /></svg>
                </a>
            </div>
        </div>
    </section>

    {{-- ══ OUR PROPERTIES ════════════════════════════════════════════════════ --}}
    <section class="bg-white py-16 lg:py-24">
        <div class="mx-auto max-w-7xl px-6 lg:px-10">

            <div class="mb-8 flex items-center justify-between">
                <h2 class="text-2xl font-extrabold text-zinc-900">{{ __('Our Properties') }}</h2>
                <a href="#" class="rounded-lg border border-zinc-200 px-4 py-2 text-sm font-semibold text-zinc-600 transition hover:bg-zinc-50">
                    {{ __('Find All') }}
                </a>
            </div>

            @if($properties->isEmpty())
                <p class="text-sm text-zinc-400">{{ __('No properties available right now.') }}</p>
            @else
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    @foreach($properties as $property)
                        @php
                            $gradients = ['from-amber-300 to-orange-500','from-blue-300 to-blue-500','from-green-300 to-emerald-500','from-violet-300 to-purple-500'];
                            $g = $gradients[$property->id % count($gradients)];
                            $img = $property->images->first();
                            $rating = $property->reviews_avg_rating ? round((float)$property->reviews_avg_rating, 1) : null;
                            $price = $property->monthly_rent ?? $property->weekly_rent ?? $property->daily_rent;
                            $priceLabel = $property->monthly_rent ? __('/mo') : ($property->weekly_rent ? __('/wk') : __('/day'));
                            $isOccupied = $property->contracts->isNotEmpty();
                        @endphp
                        <div class="group flex gap-4 overflow-hidden rounded-2xl border border-zinc-100 bg-white p-3 shadow-sm transition hover:shadow-md hover:-translate-y-0.5">

                            {{-- Thumbnail --}}
                            <div class="relative h-28 w-28 shrink-0 overflow-hidden rounded-xl bg-gradient-to-br {{ $g }}">
                                @if($img)
                                    <img src="{{ $img->url() }}" alt="{{ $property->title }}" class="h-full w-full object-cover transition group-hover:scale-105" />
                                @else
                                    <div class="flex h-full items-center justify-center">
                                        <svg class="size-8 text-white/50" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21" /></svg>
                                    </div>
                                @endif
                            </div>

                            {{-- Info --}}
                            <div class="flex flex-1 flex-col justify-between py-1">
                                <div>
                                    <div class="mb-1 flex items-center gap-2">
                                        <span class="rounded-full px-2 py-0.5 text-xs font-semibold {{ $isOccupied ? 'bg-blue-50 text-blue-700' : 'bg-green-50 text-green-700' }}">
                                            {{ $isOccupied ? __('Occupied') : __('Available') }}
                                        </span>
                                        @if($rating)
                                            <div class="flex items-center gap-0.5">
                                                <svg class="size-3 text-amber-400" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd"/></svg>
                                                <span class="text-xs font-semibold text-zinc-700">{{ $rating }}</span>
                                                <span class="text-xs text-zinc-400">({{ $property->reviews_count }})</span>
                                            </div>
                                        @endif
                                    </div>
                                    <h3 class="text-sm font-bold text-zinc-900 leading-snug line-clamp-1">{{ $property->title }}</h3>
                                    <p class="mt-0.5 flex items-center gap-1 text-xs text-zinc-500 truncate">
                                        <svg class="size-3.5 shrink-0 text-zinc-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                                        {{ implode(', ', array_filter([$property->address_line_1, $property->city])) ?: __('Location TBA') }}
                                    </p>
                                </div>
                                @if($price)
                                    <p class="text-sm font-extrabold text-amber-600">
                                        ${{ number_format($price) }}<span class="text-xs font-normal text-zinc-400">{{ $priceLabel }}</span>
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- ══ BECOME A LANDLORD ═════════════════════════════════════════════════ --}}
    <section class="relative overflow-hidden bg-zinc-900 py-20">
        {{-- Geometric pattern --}}
        <div class="absolute inset-0 opacity-5" style="background-image: url(\"data:image/svg+xml,%3Csvg width='80' height='80' viewBox='0 0 80 80' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='1'%3E%3Cpath d='M50 50c0-5.523 4.477-10 10-10s10 4.477 10 10-4.477 10-10 10c0 5.523-4.477 10-10 10s-10-4.477-10-10 4.477-10 10-10zM10 10c0-5.523 4.477-10 10-10s10 4.477 10 10-4.477 10-10 10c0 5.523-4.477 10-10 10S0 25.523 0 20s4.477-10 10-10zm10 8c4.418 0 8-3.582 8-8s-3.582-8-8-8-8 3.582-8 8 3.582 8 8 8zm40 40c4.418 0 8-3.582 8-8s-3.582-8-8-8-8 3.582-8 8 3.582 8 8 8z' /%3E%3C/g%3E%3C/g%3E%3C/svg%3E\");"></div>
        <div class="absolute right-0 top-0 h-full w-1/3 bg-gradient-to-l from-amber-600/20 to-transparent"></div>

        <div class="relative z-10 mx-auto max-w-7xl px-6 lg:px-10">
            <div class="max-w-xl">
                <span class="text-xs font-semibold uppercase tracking-widest text-amber-400">{{ __('Join our network') }}</span>
                <h2 class="mt-3 text-3xl font-extrabold text-white lg:text-4xl">
                    {{ __('Become a') }} <span class="italic text-amber-400">{{ __('landlord') }}</span>
                </h2>
                <p class="mt-4 text-sm leading-relaxed text-zinc-300">
                    {{ __('List your property on Sahoome and connect with thousands of happy landlords and tenants who have found success with our platform.') }}
                </p>
                @auth
                    <a href="{{ route('landlord.dashboard') }}" wire:navigate class="mt-7 inline-flex items-center gap-2 rounded-xl bg-amber-600 px-6 py-3 text-sm font-bold text-white transition hover:bg-amber-700 shadow-lg shadow-amber-900/30">
                        {{ __('Go to Dashboard') }}
                        <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" /></svg>
                    </a>
                @else
                    <a href="{{ route('register') }}" wire:navigate class="mt-7 inline-flex items-center gap-2 rounded-xl bg-amber-600 px-6 py-3 text-sm font-bold text-white transition hover:bg-amber-700 shadow-lg shadow-amber-900/30">
                        {{ __('Start Now') }}
                        <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" /></svg>
                    </a>
                @endauth
            </div>
        </div>
    </section>

    {{-- ══ CONTACT / SEND MESSAGE ════════════════════════════════════════════ --}}
    <section class="bg-white py-16 lg:py-24" id="contact">
        <div class="mx-auto grid max-w-7xl grid-cols-1 items-center gap-12 px-6 lg:grid-cols-2 lg:px-10">

            {{-- Left: Form --}}
            <div>
                <span class="text-xs font-semibold uppercase tracking-widest text-amber-600">{{ __('Reach out') }}</span>
                <h2 class="mt-2 text-3xl font-extrabold text-zinc-900">{{ __('Send Us a Message') }}</h2>

                @if($messageSent)
                    <div class="mt-6 rounded-xl bg-green-50 border border-green-200 px-5 py-4 text-sm font-medium text-green-700">
                        {{ __('Thank you! Your message has been sent. We\'ll be in touch shortly.') }}
                    </div>
                @endif

                <form wire:submit="sendMessage" class="mt-7 flex flex-col gap-4">

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div class="flex flex-col gap-1.5">
                            <label class="text-sm font-medium text-zinc-700">{{ __('Your Name') }}</label>
                            <input wire:model="contactName" type="text" placeholder="{{ __('Full name') }}"
                                class="rounded-xl border border-zinc-200 px-4 py-2.5 text-sm outline-none placeholder-zinc-400 focus:border-amber-400 focus:ring-2 focus:ring-amber-100 transition" />
                            @error('contactName') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label class="text-sm font-medium text-zinc-700">{{ __('Email') }}</label>
                            <input wire:model="contactEmail" type="email" placeholder="{{ __('email@example.com') }}"
                                class="rounded-xl border border-zinc-200 px-4 py-2.5 text-sm outline-none placeholder-zinc-400 focus:border-amber-400 focus:ring-2 focus:ring-amber-100 transition" />
                            @error('contactEmail') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <label class="text-sm font-medium text-zinc-700">{{ __('Phone Number') }}</label>
                        <input wire:model="contactPhone" type="tel" placeholder="{{ __('+1 (555) 000-0000') }}"
                            class="rounded-xl border border-zinc-200 px-4 py-2.5 text-sm outline-none placeholder-zinc-400 focus:border-amber-400 focus:ring-2 focus:ring-amber-100 transition" />
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <label class="text-sm font-medium text-zinc-700">{{ __('Your Message') }}</label>
                        <textarea wire:model="contactMessage" rows="4" placeholder="{{ __('Tell us how we can help...') }}"
                            class="rounded-xl border border-zinc-200 px-4 py-2.5 text-sm outline-none placeholder-zinc-400 focus:border-amber-400 focus:ring-2 focus:ring-amber-100 transition resize-none"></textarea>
                        @error('contactMessage') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit"
                        class="w-full rounded-xl bg-amber-600 px-6 py-3 text-sm font-bold text-white transition hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2"
                        wire:loading.attr="disabled" wire:loading.class="opacity-75">
                        <span wire:loading.remove>{{ __('Send Message') }}</span>
                        <span wire:loading>{{ __('Sending...') }}</span>
                    </button>
                </form>
            </div>

            {{-- Right: Illustration placeholder --}}
            <div class="hidden lg:flex items-center justify-center">
                <div class="relative flex h-80 w-80 items-center justify-center rounded-3xl bg-gradient-to-br from-amber-50 to-orange-100">
                    {{-- Simple illustration using SVG --}}
                    <svg class="h-64 w-64 text-amber-600/60" fill="none" stroke="currentColor" stroke-width="0.8" viewBox="0 0 200 200">
                        <rect x="30" y="80" width="140" height="100" rx="8" fill="currentColor" opacity="0.08"/>
                        <rect x="50" y="60" width="100" height="130" rx="6" fill="currentColor" opacity="0.12"/>
                        <rect x="70" y="100" width="20" height="25" rx="3" fill="currentColor" opacity="0.4"/>
                        <rect x="110" y="100" width="20" height="25" rx="3" fill="currentColor" opacity="0.4"/>
                        <rect x="85" y="140" width="30" height="50" rx="2" fill="currentColor" opacity="0.5"/>
                        <circle cx="100" cy="45" r="18" fill="currentColor" opacity="0.3"/>
                        <path d="M88 45 L96 53 L112 37" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" opacity="0.8"/>
                    </svg>
                    {{-- Floating badge --}}
                    <div class="absolute -right-4 -top-4 rounded-2xl bg-amber-600 px-4 py-2 text-center text-white shadow-lg">
                        <p class="text-lg font-extrabold">24/7</p>
                        <p class="text-xs opacity-90">{{ __('Support') }}</p>
                    </div>
                    <div class="absolute -bottom-4 -left-4 rounded-2xl bg-white border border-zinc-100 px-4 py-2 shadow-lg">
                        <p class="text-sm font-bold text-zinc-900">{{ __('Quick Response') }}</p>
                        <p class="text-xs text-zinc-400">{{ __('Within 2 hours') }}</p>
                    </div>
                </div>
            </div>

        </div>
    </section>

    {{-- ══ FOOTER ════════════════════════════════════════════════════════════ --}}
    <footer class="border-t border-zinc-100 bg-white">
        <div class="mx-auto max-w-7xl px-6 py-12 lg:px-10">
            <div class="grid grid-cols-1 gap-10 lg:grid-cols-4">

                {{-- Brand column --}}
                <div class="lg:col-span-1">
                    <span class="text-base font-extrabold tracking-widest text-amber-600 uppercase">SAHOOME</span>
                    <p class="mt-3 text-xs leading-relaxed text-zinc-500">
                        8 Market St, The First City of Sharing<br>
                        Ample St, 1234 New York, US<br>
                        New York, CA 95624 / Aqaba, AlAkabah
                    </p>
                    <div class="mt-5 flex gap-3">
                        <a href="#" class="flex size-8 items-center justify-center rounded-full border border-zinc-200 text-zinc-500 transition hover:border-amber-400 hover:text-amber-600">
                            <svg class="size-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        <a href="#" class="flex size-8 items-center justify-center rounded-full border border-zinc-200 text-zinc-500 transition hover:border-amber-400 hover:text-amber-600">
                            <svg class="size-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                        </a>
                        <a href="#" class="flex size-8 items-center justify-center rounded-full border border-zinc-200 text-zinc-500 transition hover:border-amber-400 hover:text-amber-600">
                            <svg class="size-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </a>
                    </div>
                </div>

                {{-- Supports --}}
                <div>
                    <h5 class="mb-4 text-xs font-bold uppercase tracking-wider text-zinc-900">{{ __('Supports') }}</h5>
                    <ul class="space-y-2.5 text-sm text-zinc-500">
                        <li><a href="#" class="transition hover:text-amber-600">{{ __('Privacy Policy') }}</a></li>
                        <li><a href="#" class="transition hover:text-amber-600">{{ __('Terms & Conditions') }}</a></li>
                        <li><a href="#" class="transition hover:text-amber-600">{{ __('FAQ') }}</a></li>
                    </ul>
                </div>

                {{-- Quick Links --}}
                <div>
                    <h5 class="mb-4 text-xs font-bold uppercase tracking-wider text-zinc-900">{{ __('Quick Links') }}</h5>
                    <ul class="space-y-2.5 text-sm text-zinc-500">
                        <li><a href="{{ route('home') }}" wire:navigate class="transition hover:text-amber-600">{{ __('Home') }}</a></li>
                        <li><a href="#properties" class="transition hover:text-amber-600">{{ __('Properties') }}</a></li>
                        <li><a href="#about" class="transition hover:text-amber-600">{{ __('About Us') }}</a></li>
                        <li><a href="#contact" class="transition hover:text-amber-600">{{ __('Contact Us') }}</a></li>
                    </ul>
                </div>

                {{-- Contact Info --}}
                <div>
                    <h5 class="mb-4 text-xs font-bold uppercase tracking-wider text-zinc-900">{{ __('Contact Info') }}</h5>
                    <ul class="space-y-3 text-sm text-zinc-500">
                        <li class="flex items-start gap-2">
                            <svg class="mt-0.5 size-4 shrink-0 text-amber-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" /></svg>
                            <span>Sahoome@gmail.com</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="mt-0.5 size-4 shrink-0 text-amber-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                            <span>1614 los angeles CA</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="mt-0.5 size-4 shrink-0 text-amber-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" /></svg>
                            <span>+960 354-6411</span>
                        </li>
                    </ul>
                </div>

            </div>

            <div class="mt-10 border-t border-zinc-100 pt-6 text-center text-xs text-zinc-400">
                &copy; {{ date('Y') }} {{ config('app.name') }}. {{ __('All Rights Reserved') }}.
            </div>
        </div>
    </footer>

</div>
