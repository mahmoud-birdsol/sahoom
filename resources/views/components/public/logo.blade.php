@props([
    'white' => false,
    'size'  => 'base',
])

@php
    $textSize = match($size) {
        'sm'  => 'text-[0.8rem]',
        'lg'  => 'text-[1.05rem]',
        'xl'  => 'text-xl',
        '2xl' => 'text-3xl',
        default => 'text-[0.95rem]',
    };
    $svgSize = match($size) {
        'sm'  => [22, 25],
        'lg'  => [30, 34],
        'xl'  => [34, 39],
        '2xl' => [44, 50],
        default => [26, 30],
    };
    $textColorClass = $white ? 'text-white' : 'text-ink';
@endphp

<span {{ $attributes->merge(['class' => "flex items-center gap-3 font-sans font-light tracking-[.35em] uppercase {$textSize} {$textColorClass}"]) }}>
    <svg width="{{ $svgSize[0] }}" height="{{ $svgSize[1] }}" viewBox="0 0 80 90" fill="none" class="shrink-0">
        <path d="M40 5C25 5 13 17.5 13 33C13 53 40 85 40 85C40 85 67 53 67 33C67 17.5 55 5 40 5Z"
              fill="none" stroke="#B8962E" stroke-width="5" stroke-linejoin="round"/>
        <path d="M40 16L24 29V47H33V37H47V47H56V29L40 16Z"
              fill="none" stroke="#B8962E" stroke-width="4.5" stroke-linejoin="round"/>
        <rect x="36.5" y="37" width="7" height="10" rx="1" fill="#B8962E"/>
    </svg>
    Sahoome
</span>
