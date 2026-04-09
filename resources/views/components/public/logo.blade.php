@props([
    'white' => false,
    'size'  => 'base',
])

@php
    $textSize = match($size) {
        'sm'  => 'text-sm',
        'lg'  => 'text-lg',
        'xl'  => 'text-2xl',
        '2xl' => 'text-4xl',
        default => 'text-base',
    };
    $iconSize = match($size) {
        'sm'  => 'size-4',
        'lg'  => 'size-6',
        'xl'  => 'size-7',
        '2xl' => 'size-9',
        default => 'size-5',
    };
    $colorClass = $white ? 'text-white' : 'text-amber-600';
@endphp

<span {{ $attributes->merge(['class' => "flex items-center gap-2 font-extrabold tracking-widest uppercase {$textSize} {$colorClass}"]) }}>
    <svg class="{{ $iconSize }} shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75"/>
    </svg>
    SAHOOME
</span>
