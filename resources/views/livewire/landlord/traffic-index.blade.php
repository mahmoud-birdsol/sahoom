<div class="flex flex-col gap-6 p-6">

    {{-- Header --}}
    <div class="flex items-start justify-between gap-4">
        <div class="space-y-0.5">
            <flux:heading size="xl" class="font-bold text-zinc-900">{{ __('Traffic') }}</flux:heading>
            <flux:subheading class="text-zinc-500">{{ __('Track views and performance for your properties') }}</flux:subheading>
        </div>

        {{-- Period filter --}}
        <div class="flex items-center gap-2">
            <span class="text-sm text-zinc-500">{{ __('Period') }}:</span>
            <flux:select wire:model.live="period" class="w-32 text-sm">
                <flux:select.option value="7">{{ __('Last 7 days') }}</flux:select.option>
                <flux:select.option value="30">{{ __('Last 30 days') }}</flux:select.option>
                <flux:select.option value="90">{{ __('Last 90 days') }}</flux:select.option>
                <flux:select.option value="365">{{ __('Last year') }}</flux:select.option>
            </flux:select>
        </div>
    </div>

    {{-- Summary metrics --}}
    <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
        <div class="rounded-xl border border-zinc-100 bg-white p-4 shadow-sm">
            <p class="text-xs font-medium uppercase tracking-wide text-zinc-400">{{ __('Total Views') }}</p>
            <p class="mt-1.5 text-2xl font-bold text-zinc-900">{{ number_format($this->totals['views']) }}</p>
        </div>
        <div class="rounded-xl border border-zinc-100 bg-white p-4 shadow-sm">
            <p class="text-xs font-medium uppercase tracking-wide text-zinc-400">{{ __('Total Favourites') }}</p>
            <p class="mt-1.5 text-2xl font-bold text-zinc-900">{{ number_format($this->totals['favourites']) }}</p>
        </div>
        <div class="rounded-xl border border-zinc-100 bg-white p-4 shadow-sm">
            <p class="text-xs font-medium uppercase tracking-wide text-zinc-400">{{ __('Properties') }}</p>
            <p class="mt-1.5 text-2xl font-bold text-zinc-900">{{ $properties->total() }}</p>
        </div>
        <div class="rounded-xl border border-zinc-100 bg-white p-4 shadow-sm">
            <p class="text-xs font-medium uppercase tracking-wide text-zinc-400">{{ __('Avg Views / Property') }}</p>
            <p class="mt-1.5 text-2xl font-bold text-zinc-900">
                {{ $properties->total() > 0 ? number_format($this->totals['views'] / $properties->total(), 1) : '0' }}
            </p>
        </div>
    </div>

    {{-- Table --}}
    @if($properties->isEmpty())
        <div class="flex flex-col items-center justify-center rounded-xl border border-dashed border-zinc-200 bg-white py-20 text-center">
            <div class="flex size-16 items-center justify-center rounded-2xl bg-zinc-100">
                <flux:icon.chart-bar class="size-8 text-zinc-400" variant="outline" />
            </div>
            <p class="mt-4 text-base font-semibold text-zinc-700">{{ __('No properties yet') }}</p>
            <p class="mt-1 text-sm text-zinc-400">{{ __('Add properties to start tracking traffic.') }}</p>
        </div>
    @else
        <flux:table>
            <flux:table.columns>
                <flux:table.column>{{ __('Property') }}</flux:table.column>
                <flux:table.column>{{ __('Location') }}</flux:table.column>
                <flux:table.column>{{ __('Status') }}</flux:table.column>
                <flux:table.column>{{ __('Views') }}</flux:table.column>
                <flux:table.column>{{ __('Favourites') }}</flux:table.column>
                <flux:table.column>{{ __('Rent') }}</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @foreach($properties as $property)
                    @php
                        $activeContract = $property->contracts->first();
                        $rent = $activeContract
                            ? match($activeContract->pricing_type?->value ?? '') {
                                'weekly'  => '$' . number_format((float) $activeContract->weekly_rent, 0) . '/wk',
                                'yearly'  => '$' . number_format((float) $activeContract->yearly_rent, 0) . '/yr',
                                'daily'   => '$' . number_format((float) $activeContract->daily_rent, 0) . '/day',
                                default   => '$' . number_format((float) $activeContract->monthly_rent, 0) . '/mo',
                            }
                            : ($property->monthly_rent ? '$' . number_format((float) $property->monthly_rent, 0) . '/mo' : '—');

                        $maxViews = $properties->max('total_views') ?: 1;
                        $barWidth = $property->total_views > 0 ? round(($property->total_views / $maxViews) * 100) : 0;
                    @endphp
                    <flux:table.row>
                        <flux:table.cell>
                            <div class="flex items-center gap-3">
                                @php $firstImg = $property->images?->first(); @endphp
                                @php
                                    $gradients = ['from-zinc-700 to-zinc-900','from-blue-300 to-blue-500','from-green-300 to-emerald-500','from-violet-300 to-purple-500','from-rose-300 to-pink-500'];
                                    $g = $gradients[$property->id % count($gradients)];
                                @endphp
                                <div class="size-9 shrink-0 overflow-hidden rounded-lg bg-gradient-to-br {{ $g }}">
                                    @if($firstImg)
                                        <img src="{{ $firstImg->url() }}" class="h-full w-full object-cover" />
                                    @endif
                                </div>
                                <span class="font-medium text-zinc-800">{{ $property->title }}</span>
                            </div>
                        </flux:table.cell>
                        <flux:table.cell class="text-zinc-500 text-sm">
                            {{ implode(', ', array_filter([$property->address_line_1, $property->city])) ?: '—' }}
                        </flux:table.cell>
                        <flux:table.cell>
                            @php
                                $statusConfig = match($property->status ?? '') {
                                    'available'  => ['label' => __('Available'),  'class' => 'bg-green-50 text-green-700 ring-green-200'],
                                    'occupied'   => ['label' => __('Occupied'),   'class' => 'bg-blue-50 text-blue-700 ring-blue-200'],
                                    'pending'    => ['label' => __('Pending'),    'class' => 'bg-amber-50 text-amber-700 ring-amber-200'],
                                    'inactive'   => ['label' => __('Inactive'),   'class' => 'bg-zinc-100 text-zinc-500 ring-zinc-200'],
                                    default      => ['label' => ucfirst($property->status ?? 'Draft'), 'class' => 'bg-zinc-100 text-zinc-500 ring-zinc-200'],
                                };
                            @endphp
                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium ring-1 {{ $statusConfig['class'] }}">
                                {{ $statusConfig['label'] }}
                            </span>
                        </flux:table.cell>
                        <flux:table.cell>
                            <div class="flex items-center gap-2">
                                <div class="h-1.5 w-20 overflow-hidden rounded-full bg-zinc-100">
                                    <div class="h-full rounded-full" style="background: #B8962E; width: {{ $barWidth }}%"></div>
                                </div>
                                <span class="text-sm font-semibold text-zinc-700">{{ number_format($property->total_views) }}</span>
                            </div>
                        </flux:table.cell>
                        <flux:table.cell>
                            <div class="flex items-center gap-1.5 text-sm text-zinc-600">
                                <flux:icon.heart class="size-3.5 text-rose-400" variant="solid" />
                                {{ number_format($property->total_favourites) }}
                            </div>
                        </flux:table.cell>
                        <flux:table.cell class="font-semibold text-zinc-800">
                            {{ $rent }}
                        </flux:table.cell>
                    </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>

        @if($properties->hasPages())
            <div class="mt-2">
                {{ $properties->links() }}
            </div>
        @endif
    @endif
</div>
