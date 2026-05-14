<div class="flex items-start justify-between gap-4">
    <div class="space-y-1">
        <h1 style="font-family: 'Playfair Display', serif; font-size: 1.6rem; font-weight: 300; color: #1E2330; line-height: 1.2">{{ __('Overview') }}</h1>
        <p style="font-size: 0.8rem; color: rgba(30,35,48,.5); font-weight: 400">{{ __('Welcome back — manage your properties and bookings') }}</p>
    </div>

    <a href="{{ route('landlord.properties') }}" wire:navigate
       class="flex shrink-0 items-center gap-2 px-4 py-2.5 text-xs font-semibold uppercase tracking-[.12em] text-white transition hover:opacity-80"
       style="background: #1E2330">
        <svg class="size-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
        </svg>
        {{ __('New Property') }}
    </a>
</div>
