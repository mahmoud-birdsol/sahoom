@props(['transparent' => false])

@php
    $currentLocale = app()->getLocale();
    $locales = [
        'en' => ['name' => 'English', 'flag' => '🇺🇸'],
        'fr' => ['name' => 'Français', 'flag' => '🇫🇷'],
    ];
@endphp

<div x-data="{ open: false }" class="relative">
    {{-- Toggle button --}}
    <button @click="open = !open"
        class="flex items-center gap-1.5 rounded-lg border px-3 py-1.5 text-sm font-medium transition
            {{ $transparent
                ? 'border-white/30 text-white/80 hover:border-white hover:text-white'
                : 'border-zinc-200 text-zinc-600 hover:border-amber-300 hover:text-amber-600' }}">
        <span>{{ $locales[$currentLocale]['flag'] }}</span>
        <span>{{ $locales[$currentLocale]['name'] }}</span>
        <svg class="size-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="m19 9-7 7-7-7"/>
        </svg>
    </button>

    {{-- Dropdown --}}
    <div x-show="open" @click.outside="open = false" x-transition
        class="absolute right-0 top-full z-50 mt-1 w-40 overflow-hidden rounded-xl border border-zinc-100 bg-white shadow-lg">
        @foreach($locales as $code => $data)
            <form method="POST" action="{{ route('locale.switch') }}" class="block">
                @csrf
                <input type="hidden" name="locale" value="{{ $code }}" />
                <button type="submit"
                    class="flex w-full items-center gap-2 px-4 py-2 text-left text-sm text-zinc-700 transition hover:bg-amber-50 hover:text-amber-700
                        {{ $code === $currentLocale ? 'bg-amber-50 text-amber-700' : '' }}">
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
