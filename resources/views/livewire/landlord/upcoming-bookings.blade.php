<div class="flex flex-col rounded-xl border border-zinc-100 bg-white shadow-sm">
    <div class="flex items-center justify-between px-5 pt-5 pb-4">
        <h3 class="text-base font-semibold text-zinc-900">{{ __('Upcoming Bookings') }}</h3>
        <a href="#" class="text-sm font-medium text-amber-600 hover:text-amber-700">
            {{ __('View All') }}
        </a>
    </div>

    @if($bookings->isEmpty())
        <div class="flex flex-col items-center justify-center px-5 py-10 text-center">
            <div class="flex size-12 items-center justify-center rounded-xl bg-zinc-100">
                <flux:icon.calendar-days class="size-6 text-zinc-400" variant="outline" />
            </div>
            <p class="mt-3 text-sm font-medium text-zinc-600">{{ __('No upcoming bookings') }}</p>
            <p class="mt-1 text-xs text-zinc-400">{{ __('New bookings will appear here.') }}</p>
        </div>
    @else
        <div class="divide-y divide-zinc-100 px-5 pb-4">
            @foreach($bookings as $booking)
                @php
                    $statusStyles = match($booking->status) {
                        'confirmed' => 'bg-blue-50 text-blue-700',
                        'pending'   => 'bg-amber-50 text-amber-700',
                        'scheduled' => 'bg-yellow-50 text-yellow-700',
                        default     => 'bg-zinc-100 text-zinc-600',
                    };
                    $avatarColors = ['bg-blue-500', 'bg-green-500', 'bg-amber-500', 'bg-violet-500', 'bg-rose-500'];
                    $avatarColor  = $avatarColors[ord($booking->initials) % count($avatarColors)];
                @endphp
                <div class="flex items-start gap-3 py-4">
                    {{-- Avatar --}}
                    <div class="flex size-9 shrink-0 items-center justify-center rounded-full {{ $avatarColor }} text-sm font-semibold text-white">
                        {{ $booking->initials }}
                    </div>

                    {{-- Details --}}
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-semibold text-zinc-900">{{ $booking->name }}</p>
                        <p class="text-xs text-zinc-500">{{ $booking->property_title }}</p>
                        <p class="mt-0.5 text-xs text-zinc-400">
                            {{ $booking->date_label }}: {{ $booking->date }}
                        </p>
                    </div>

                    {{-- Status badge --}}
                    <span class="shrink-0 rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $statusStyles }}">
                        {{ $booking->status_label }}
                    </span>
                </div>
            @endforeach
        </div>
    @endif
</div>
