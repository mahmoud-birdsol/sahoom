@props(['transparent' => false])

@php
    $locales = [
        'en' => ['name' => 'English', 'flag' => '🇺🇸'],
        'fr' => ['name' => 'Français', 'flag' => '🇫🇷'],
    ];
    $currentLocale = array_key_exists(app()->getLocale(), $locales) ? app()->getLocale() : 'en';
@endphp

<div x-data="{ open: false }" class="relative">
    {{-- Toggle button --}}
    <button @click="open = !open"
        @if($transparent)
            :class="solid
                ? 'border-[#E8E2D8] text-muted hover:border-gold hover:text-gold'
                : 'border-white/30 text-white/80 hover:border-white hover:text-white'"
        @endif
        class="flex items-center gap-1.5 rounded-sm border px-3 py-1.5 text-[0.68rem] font-medium tracking-[.06em] transition-all duration-300
            {{ !$transparent ? 'border-[#E8E2D8] text-muted hover:border-gold hover:text-gold' : '' }}">
        <span>{{ $locales[$currentLocale]['flag'] }}</span>
        <span>{{ $locales[$currentLocale]['name'] }}</span>
        <svg class="size-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="m19 9-7 7-7-7"/>
        </svg>
    </button>

    {{-- Dropdown --}}
    <div x-show="open" @click.outside="open = false" x-transition
        class="absolute right-0 top-full z-50 mt-1 w-36 overflow-hidden border border-[#E8E2D8] bg-white shadow-xl">
        @foreach($locales as $code => $data)
            <form method="POST" action="{{ route('locale.switch') }}" class="block">
                @csrf
                <input type="hidden" name="locale" value="{{ $code }}" />
                <button type="submit"
                    class="flex w-full items-center gap-2 px-4 py-2 text-left text-xs text-ink transition hover:bg-[#F4EFE8] hover:text-gold
                        {{ $code === $currentLocale ? 'bg-[#F4EFE8] text-gold' : '' }}">
                    <span>{{ $data['flag'] }}</span>
                    <span>{{ $data['name'] }}</span>
                    @if($code === $currentLocale)
                        <svg class="ml-auto size-3.5" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                    @endif
                </button>
            </form>
        @endforeach
    </div>
</div>
