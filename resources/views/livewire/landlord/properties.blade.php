<div class="flex flex-col rounded-xl border border-zinc-100 bg-white shadow-sm">
    <div class="flex items-center justify-between px-5 pt-5 pb-4">
        <h3 class="text-base font-semibold text-zinc-900">{{ __('Your Properties') }}</h3>
        <a href="{{ route('landlord.properties') }}" wire:navigate class="text-sm font-medium transition hover:opacity-70" style="color: #B8962E">
            {{ __('View All') }}
        </a>
    </div>

    @if($properties->isEmpty())
        <div class="flex flex-col items-center justify-center px-5 py-10 text-center">
            <div class="flex size-12 items-center justify-center rounded-xl bg-zinc-100">
                <flux:icon.building-office-2 class="size-6 text-zinc-400" variant="outline" />
            </div>
            <p class="mt-3 text-sm font-medium text-zinc-600">{{ __('No properties yet') }}</p>
            <p class="mt-1 text-xs text-zinc-400">{{ __('Add your first property to get started.') }}</p>
        </div>
    @else
        <div class="divide-y divide-zinc-100 px-5 pb-4">
            @foreach($properties as $property)
                @php
                    $activeContract = $property->contracts->first();
                    $isOccupied = $activeContract !== null;
                    $colors = ['bg-zinc-200', 'bg-blue-200', 'bg-green-200', 'bg-violet-200', 'bg-rose-200'];
                    $bgColor = $colors[$property->id % count($colors)];
                @endphp
                <div class="flex items-center gap-4 py-4">
                    {{-- Property image placeholder --}}
                    <div class="size-16 shrink-0 overflow-hidden rounded-lg {{ $bgColor }} flex items-center justify-center">
                        <flux:icon.building-office-2 class="size-7 text-white" variant="outline" />
                    </div>

                    {{-- Property details --}}
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-semibold text-zinc-900">{{ $property->title }}</p>
                        @if($property->address_line_1 || $property->city)
                            <p class="truncate text-xs text-zinc-500">
                                {{ implode(', ', array_filter([$property->address_line_1, $property->city])) }}
                            </p>
                        @endif
                        @if($activeContract && $activeContract->monthly_rent)
                            <p class="mt-0.5 text-xs font-semibold" style="color: #B8962E">
                                ${{ number_format((float) $activeContract->monthly_rent, 0) }}/mo
                            </p>
                        @endif
                    </div>

                    {{-- Status --}}
                    <div class="shrink-0 text-right">
                        @if($isOccupied)
                            <span class="text-xs font-semibold text-green-600">{{ __('Occupied') }}</span>
                        @else
                            <span class="text-xs font-medium text-zinc-400">{{ __('Available') }}</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
