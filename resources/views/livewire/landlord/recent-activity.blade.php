<div class="flex flex-col rounded-xl border border-zinc-100 bg-white shadow-sm">
    <div class="px-5 pt-5 pb-4">
        <h3 class="text-base font-semibold text-zinc-900">{{ __('Recent Activity') }}</h3>
    </div>

    @if($activities->isEmpty())
        <div class="flex flex-col items-center justify-center px-5 py-10 text-center">
            <div class="flex size-12 items-center justify-center rounded-xl bg-zinc-100">
                <flux:icon.clock class="size-6 text-zinc-400" variant="outline" />
            </div>
            <p class="mt-3 text-sm font-medium text-zinc-600">{{ __('No recent activity') }}</p>
        </div>
    @else
        <div class="divide-y divide-zinc-100 px-5 pb-4">
            @foreach($activities as $activity)
                @php
                    $iconStyles = match($activity->icon_type) {
                        'contract' => ['bg' => 'bg-green-100',  'text' => 'text-green-700',  'icon' => 'currency-dollar'],
                        'viewing'  => ['bg' => 'bg-blue-100',   'text' => 'text-blue-700',   'icon' => 'calendar-days'],
                        default    => ['bg' => 'bg-zinc-100',   'text' => 'text-zinc-600',   'icon' => 'bell'],
                    };
                @endphp
                <div class="flex items-center gap-4 py-4">
                    {{-- Icon --}}
                    <div class="flex size-9 shrink-0 items-center justify-center rounded-full {{ $iconStyles['bg'] }}">
                        <flux:icon :icon="$iconStyles['icon']" class="size-4 {{ $iconStyles['text'] }}" variant="outline" />
                    </div>

                    {{-- Details --}}
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-semibold text-zinc-900">{{ $activity->title }}</p>
                        <p class="truncate text-xs text-zinc-500">{{ $activity->subtitle }}</p>
                    </div>

                    {{-- Timestamp --}}
                    <p class="shrink-0 text-xs text-zinc-400">{{ $activity->time->diffForHumans() }}</p>
                </div>
            @endforeach
        </div>
    @endif
</div>
