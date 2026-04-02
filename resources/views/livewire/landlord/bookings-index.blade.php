<div class="flex flex-col gap-6 p-6">

    {{-- Header --}}
    <div class="flex items-start justify-between gap-4">
        <div class="space-y-0.5">
            <flux:heading size="xl" class="font-bold text-zinc-900">{{ __('Booking & calendar') }}</flux:heading>
            <flux:subheading class="text-zinc-500">{{ __('Manage your properties and bookings') }}</flux:subheading>
        </div>

        {{-- View toggle --}}
        <div class="flex items-center gap-1 rounded-lg border border-zinc-200 bg-white p-1 shadow-sm">
            <button
                wire:click="switchView('list')"
                class="flex items-center gap-1.5 rounded-md px-3 py-1.5 text-sm font-medium transition-colors
                    {{ $view === 'list' ? 'bg-amber-500 text-white shadow-sm' : 'text-zinc-500 hover:text-zinc-900' }}"
            >
                <flux:icon.list-bullet class="size-4" />
                {{ __('Booking') }}
            </button>
            <button
                wire:click="switchView('calendar')"
                class="flex items-center gap-1.5 rounded-md px-3 py-1.5 text-sm font-medium transition-colors
                    {{ $view === 'calendar' ? 'bg-amber-500 text-white shadow-sm' : 'text-zinc-500 hover:text-zinc-900' }}"
            >
                <flux:icon.calendar-days class="size-4" />
                {{ __('Calendar') }}
            </button>
        </div>
    </div>

    {{-- ── LIST VIEW ─────────────────────────────────────────────────────── --}}
    @if($view === 'list')

        {{-- Filter bar --}}
        <div class="flex items-center gap-3">
            <flux:select wire:model.live="filterPropertyId" class="w-52 text-sm">
                <flux:select.option value="">{{ __('All Properties') }}</flux:select.option>
                @foreach($this->landlordProperties as $prop)
                    <flux:select.option value="{{ $prop->id }}">{{ $prop->title }}</flux:select.option>
                @endforeach
            </flux:select>
        </div>

        @if($contracts->isEmpty())
            <div class="flex flex-col items-center justify-center rounded-xl border border-dashed border-zinc-200 bg-white py-20 text-center">
                <div class="flex size-16 items-center justify-center rounded-2xl bg-zinc-100">
                    <flux:icon.calendar-days class="size-8 text-zinc-400" variant="outline" />
                </div>
                <p class="mt-4 text-base font-semibold text-zinc-700">{{ __('No bookings yet') }}</p>
                <p class="mt-1 text-sm text-zinc-400">{{ __('Bookings will appear here once contracts are created.') }}</p>
            </div>
        @else
            <div class="overflow-hidden rounded-xl border border-zinc-100 bg-white shadow-sm">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-zinc-100 bg-zinc-50 text-left text-xs font-semibold uppercase tracking-wide text-zinc-400">
                            <th class="px-5 py-3">{{ __('Properties') }}</th>
                            <th class="px-5 py-3">{{ __('Renter') }}</th>
                            <th class="px-5 py-3">{{ __('Date') }}</th>
                            <th class="px-5 py-3">{{ __('Duration') }}</th>
                            <th class="px-5 py-3">{{ __('Status') }}</th>
                            <th class="px-5 py-3 text-right">{{ __('Total Cost') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-50">
                        @foreach($contracts as $contract)
                            @php
                                $statusConfig = match($contract->contract_status?->value ?? '') {
                                    'active'    => ['label' => __('Active'),    'class' => 'bg-green-50 text-green-700 ring-green-200'],
                                    'pending'   => ['label' => __('Pending'),   'class' => 'bg-amber-50 text-amber-700 ring-amber-200'],
                                    'cancelled' => ['label' => __('Cancelled'), 'class' => 'bg-red-50 text-red-600 ring-red-200'],
                                    'completed' => ['label' => __('Completed'), 'class' => 'bg-blue-50 text-blue-700 ring-blue-200'],
                                    default     => ['label' => __('Draft'),     'class' => 'bg-zinc-50 text-zinc-500 ring-zinc-200'],
                                };
                                $isUpcoming = $contract->start_date?->isFuture();
                            @endphp
                            <tr
                                wire:click="openDetails({{ $contract->id }})"
                                class="cursor-pointer transition-colors hover:bg-amber-50/30"
                            >
                                <td class="px-5 py-3.5">
                                    <div class="font-medium text-zinc-800">{{ $contract->property?->title ?? '—' }}</div>
                                    @if($contract->property?->address_line_1)
                                        <div class="mt-0.5 truncate text-xs text-zinc-400 max-w-[180px]">{{ $contract->property->address_line_1 }}</div>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-zinc-600">{{ $contract->renter_name }}</td>
                                <td class="px-5 py-3.5 text-zinc-500">
                                    {{ $contract->start_date?->format('M j, Y') }}
                                    <span class="text-zinc-300 mx-0.5">→</span>
                                    {{ $contract->end_date?->format('M j, Y') }}
                                </td>
                                <td class="px-5 py-3.5 text-zinc-500">
                                    @php
                                        $months = $contract->start_date?->diffInMonths($contract->end_date);
                                        $days   = $contract->start_date?->diffInDays($contract->end_date);
                                    @endphp
                                    {{ $months > 0 ? $months . ' ' . __('Months') : $days . ' ' . __('Days') }}
                                </td>
                                <td class="px-5 py-3.5">
                                    @if($isUpcoming)
                                        <span class="inline-flex items-center rounded-full bg-amber-50 px-2.5 py-0.5 text-xs font-medium text-amber-700 ring-1 ring-amber-200">
                                            {{ __('Up Coming') }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ring-1 {{ $statusConfig['class'] }}">
                                            {{ $statusConfig['label'] }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-right font-semibold text-zinc-800">
                                    ${{ $contract->total_value ? number_format((float) $contract->total_value, 0) : '—' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                @if($contracts->hasPages())
                    <div class="border-t border-zinc-100 px-5 py-3">
                        {{ $contracts->links() }}
                    </div>
                @endif
            </div>
        @endif

    @endif

    {{-- ── CALENDAR VIEW ─────────────────────────────────────────────────── --}}
    @if($view === 'calendar')
        <div class="flex gap-5">

            {{-- Left sidebar --}}
            <div class="w-56 shrink-0 space-y-4">

                {{-- Property filter --}}
                <div>
                    <p class="mb-1.5 text-xs font-semibold uppercase tracking-wide text-zinc-400">{{ __('All Bookings') }}</p>
                    <flux:select wire:model.live="filterPropertyId" class="w-full text-sm">
                        <flux:select.option value="">{{ __('All Properties') }}</flux:select.option>
                        @foreach($this->landlordProperties as $prop)
                            <flux:select.option value="{{ $prop->id }}">{{ $prop->title }}</flux:select.option>
                        @endforeach
                    </flux:select>
                </div>

                {{-- Upcoming bookings list --}}
                <div>
                    <p class="mb-2 text-xs font-semibold uppercase tracking-wide text-zinc-400">{{ __('Upcoming') }}</p>
                    <div class="space-y-2">
                        @forelse($this->upcomingContracts as $upcoming)
                            @php
                                $gradients = ['from-amber-300 to-orange-400','from-blue-300 to-blue-500','from-green-300 to-emerald-500','from-violet-300 to-purple-500','from-rose-300 to-pink-500'];
                                $g = $gradients[$upcoming->property_id % count($gradients)];
                                $firstImg = $upcoming->property?->images?->first();
                            @endphp
                            <button
                                wire:click="openDetails({{ $upcoming->id }})"
                                class="w-full rounded-lg border border-zinc-100 bg-white p-2.5 text-left shadow-sm transition hover:border-amber-200 hover:bg-amber-50/30"
                            >
                                <div class="flex items-center gap-2.5">
                                    <div class="size-8 shrink-0 overflow-hidden rounded-lg bg-gradient-to-br {{ $g }}">
                                        @if($firstImg)
                                            <img src="{{ $firstImg->url() }}" class="h-full w-full object-cover" />
                                        @endif
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="truncate text-xs font-semibold text-zinc-800">{{ $upcoming->property?->title }}</p>
                                        <p class="text-[10px] text-zinc-400">{{ $upcoming->start_date?->format('M j') }}</p>
                                    </div>
                                </div>
                                <p class="mt-1 truncate text-[10px] text-zinc-400">{{ $upcoming->renter_name }}</p>
                            </button>
                        @empty
                            <p class="text-xs text-zinc-400">{{ __('No upcoming bookings') }}</p>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Calendar grid --}}
            <div class="flex-1 overflow-hidden rounded-xl border border-zinc-100 bg-white shadow-sm">

                {{-- Calendar header --}}
                <div class="flex items-center justify-between border-b border-zinc-100 px-5 py-3.5">
                    <div class="flex items-center gap-3">
                        <button wire:click="prevMonth" class="flex size-7 items-center justify-center rounded-lg border border-zinc-200 text-zinc-500 hover:bg-zinc-50">
                            <flux:icon.chevron-left class="size-4" />
                        </button>
                        <h3 class="text-sm font-semibold text-zinc-800">
                            {{ $this->calendarDate->format('j M Y') }} →
                        </h3>
                        <button wire:click="nextMonth" class="flex size-7 items-center justify-center rounded-lg border border-zinc-200 text-zinc-500 hover:bg-zinc-50">
                            <flux:icon.chevron-right class="size-4" />
                        </button>
                    </div>
                    <span class="rounded-full bg-amber-50 px-2.5 py-0.5 text-xs font-semibold text-amber-700">
                        {{ $this->calendarContracts->count() }} {{ __('Booking') }}
                    </span>
                </div>

                {{-- Day-of-week headers --}}
                <div class="grid grid-cols-7 border-b border-zinc-100 bg-zinc-50">
                    @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $dow)
                        <div class="px-2 py-2 text-center text-xs font-semibold uppercase tracking-wide text-zinc-400">{{ __($dow) }}</div>
                    @endforeach
                </div>

                {{-- Calendar days --}}
                @php
                    $today          = now()->toDateString();
                    $currentMonthNr = $this->calendarDate->month;
                    $colorPalette   = ['bg-amber-500','bg-blue-500','bg-green-500','bg-violet-500','bg-rose-500'];
                @endphp
                <div class="grid grid-cols-7 divide-x divide-y divide-zinc-100">
                    @foreach($this->calendarDays as $day)
                        @php
                            $dayStr       = $day->toDateString();
                            $isToday      = $dayStr === $today;
                            $isCurrentMon = $day->month === $currentMonthNr;
                            $dayContracts = $this->calendarContracts->filter(
                                fn($c) => $c->start_date?->lte($day) && $c->end_date?->gte($day)
                            );
                        @endphp
                        <div class="min-h-[80px] p-1.5 {{ $isCurrentMon ? 'bg-white' : 'bg-zinc-50/50' }}">
                            <span class="flex size-6 items-center justify-center rounded-full text-xs font-medium
                                {{ $isToday
                                    ? 'bg-amber-500 text-white'
                                    : ($isCurrentMon ? 'text-zinc-700' : 'text-zinc-300') }}">
                                {{ $day->day }}
                            </span>

                            @foreach($dayContracts->take(2) as $idx => $contract)
                                @php
                                    $color        = $colorPalette[$contract->id % count($colorPalette)];
                                    $isStart      = $contract->start_date?->toDateString() === $dayStr;
                                    $isEnd        = $contract->end_date?->toDateString() === $dayStr;
                                    $isWeekStart  = $day->dayOfWeek === 0; // Sunday
                                    $showLabel    = $isStart || $isWeekStart;
                                @endphp
                                <button
                                    wire:click="openDetails({{ $contract->id }})"
                                    class="mt-0.5 w-full truncate rounded px-1 py-0.5 text-left text-[10px] font-medium text-white {{ $color }}
                                        {{ $isStart ? 'rounded-l-full pl-1.5' : '' }}
                                        {{ $isEnd   ? 'rounded-r-full pr-1.5' : '' }}"
                                    title="{{ $contract->property?->title }} — {{ $contract->renter_name }}"
                                >
                                    {{ $showLabel ? ($contract->property?->title ?? $contract->renter_name) : '' }}
                                </button>
                            @endforeach

                            @if($dayContracts->count() > 2)
                                <p class="mt-0.5 px-1 text-[10px] text-zinc-400">+{{ $dayContracts->count() - 2 }} {{ __('more') }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    {{-- ── Booking Details Modal ──────────────────────────────────────────── --}}
    @if($showDetails && $this->detailsContract)
        @php $c = $this->detailsContract; @endphp
        <div
            class="fixed inset-0 z-50 flex items-center justify-center p-4"
            x-data="{ init() { document.body.classList.add('overflow-hidden'); }, destroy() { document.body.classList.remove('overflow-hidden'); } }"
        >
            <div class="absolute inset-0 bg-black/50" wire:click="closeDetails"></div>

            <div class="relative z-10 w-full max-w-md rounded-xl bg-white shadow-2xl">

                {{-- Header --}}
                <div class="flex items-center justify-between border-b border-zinc-100 px-6 py-4">
                    <h2 class="text-lg font-semibold text-zinc-900">{{ __('Booking Details') }}</h2>
                    <button wire:click="closeDetails" class="flex size-7 items-center justify-center rounded-full border border-zinc-200 text-zinc-400 hover:bg-zinc-50">
                        <flux:icon.x-mark class="size-4" />
                    </button>
                </div>

                {{-- Body --}}
                <div class="px-6 py-5 space-y-5">

                    {{-- Property image --}}
                    @php
                        $gradients  = ['from-amber-300 to-orange-400','from-blue-300 to-blue-500','from-green-300 to-emerald-500','from-violet-300 to-purple-500','from-rose-300 to-pink-500'];
                        $g          = $gradients[$c->property_id % count($gradients)];
                        $firstImage = $c->property?->images?->first();
                    @endphp
                    <div class="h-36 overflow-hidden rounded-xl bg-gradient-to-br {{ $g }}">
                        @if($firstImage)
                            <img src="{{ $firstImage->url() }}" class="h-full w-full object-cover" />
                        @else
                            <div class="flex h-full items-center justify-center">
                                <flux:icon.building-office-2 class="size-12 text-white/40" variant="outline" />
                            </div>
                        @endif
                    </div>

                    {{-- Property name + address --}}
                    <div>
                        <h3 class="text-base font-bold text-zinc-900">{{ $c->property?->title ?? '—' }}</h3>
                        @if($c->property?->address_line_1)
                            <p class="mt-0.5 flex items-center gap-1.5 text-sm text-zinc-500">
                                <flux:icon.map-pin class="size-3.5 shrink-0 text-amber-500" variant="solid" />
                                {{ implode(', ', array_filter([$c->property->address_line_1, $c->property->city, $c->property->state])) }}
                            </p>
                        @endif
                    </div>

                    {{-- Details grid --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div class="rounded-lg bg-zinc-50 p-3">
                            <p class="text-xs text-zinc-400">{{ __('Start Date') }}</p>
                            <p class="mt-0.5 text-sm font-semibold text-zinc-800">{{ $c->start_date?->format('d/m/Y') }}</p>
                        </div>
                        <div class="rounded-lg bg-zinc-50 p-3">
                            <p class="text-xs text-zinc-400">{{ __('End Date') }}</p>
                            <p class="mt-0.5 text-sm font-semibold text-zinc-800">{{ $c->end_date?->format('d/m/Y') }}</p>
                        </div>
                        <div class="rounded-lg bg-zinc-50 p-3">
                            <p class="text-xs text-zinc-400">{{ __('Renter') }}</p>
                            <p class="mt-0.5 text-sm font-semibold text-zinc-800">{{ $c->renter_name }}</p>
                        </div>
                        <div class="rounded-lg bg-zinc-50 p-3">
                            <p class="text-xs text-zinc-400">{{ __('Rent') }}</p>
                            <p class="mt-0.5 text-sm font-semibold text-amber-600">
                                ${{ $c->active_rent ? number_format((float) $c->active_rent, 0) : '—' }}
                                <span class="text-xs font-normal text-zinc-400">/{{ $c->pricing_type?->value ?? 'month' }}</span>
                            </p>
                        </div>
                        @if($c->total_value)
                            <div class="col-span-2 rounded-lg bg-amber-50 p-3">
                                <p class="text-xs text-amber-600">{{ __('Total Value') }}</p>
                                <p class="mt-0.5 text-base font-bold text-amber-700">${{ number_format((float) $c->total_value, 0) }}</p>
                            </div>
                        @endif
                    </div>

                    {{-- Status badge --}}
                    @php
                        $statusConfig = match($c->contract_status?->value ?? '') {
                            'active'    => ['label' => __('Active'),    'class' => 'bg-green-50 text-green-700 ring-green-200'],
                            'pending'   => ['label' => __('Pending'),   'class' => 'bg-amber-50 text-amber-700 ring-amber-200'],
                            'cancelled' => ['label' => __('Cancelled'), 'class' => 'bg-red-50 text-red-600 ring-red-200'],
                            'completed' => ['label' => __('Completed'), 'class' => 'bg-blue-50 text-blue-700 ring-blue-200'],
                            default     => ['label' => __('Draft'),     'class' => 'bg-zinc-100 text-zinc-500 ring-zinc-200'],
                        };
                    @endphp
                    <div class="flex items-center justify-between">
                        <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-medium ring-1 {{ $statusConfig['class'] }}">
                            {{ $statusConfig['label'] }}
                        </span>
                        <span class="text-xs text-zinc-400">{{ __('Ref') }}: {{ $c->contract_reference }}</span>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
