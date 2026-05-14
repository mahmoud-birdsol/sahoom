@props([
    'label',
    'value',
    'icon' => 'chart-bar',
    'color' => 'gold',
])

@php
$colorMap = [
    'gold'   => ['bg' => 'bg-[#F4EFE8]',  'icon' => 'text-[#B8962E]'],
    'amber'  => ['bg' => 'bg-[#F4EFE8]',  'icon' => 'text-[#B8962E]'],
    'green'  => ['bg' => 'bg-green-50',   'icon' => 'text-green-600'],
    'blue'   => ['bg' => 'bg-blue-50',    'icon' => 'text-blue-600'],
    'orange' => ['bg' => 'bg-violet-50',  'icon' => 'text-violet-600'],
    'violet' => ['bg' => 'bg-violet-50',  'icon' => 'text-violet-600'],
];
$colors = $colorMap[$color] ?? $colorMap['gold'];
@endphp

<div class="flex items-center justify-between rounded-xl border border-zinc-100 bg-white p-5 shadow-sm">
    <div class="space-y-1.5">
        <p class="text-sm font-medium text-zinc-500">{{ $label }}</p>
        <p class="text-3xl font-bold tracking-tight text-zinc-800">{{ $value }}</p>
    </div>

    <div class="flex size-12 shrink-0 items-center justify-center rounded-xl {{ $colors['bg'] }}">
        <flux:icon :icon="$icon" class="size-6 {{ $colors['icon'] }}" variant="outline" />
    </div>
</div>
