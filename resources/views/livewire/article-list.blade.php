<div>
    <x-public.navbar />

    {{-- ══ HERO ════════════════════════════════════════════════════════════════ --}}
    <section style="background: #1E2330; padding: 100px 6% 70px">
        <div style="max-width: 1200px; margin: 0 auto">
            <nav class="mb-6 flex items-center gap-2" style="font-size: 0.7rem; color: rgba(255,255,255,.4); letter-spacing: .1em; text-transform: uppercase">
                <a href="{{ route('home') }}" wire:navigate style="color: rgba(255,255,255,.4)" class="transition hover:text-white">{{ __('Home') }}</a>
                <span style="color: #B8962E">/</span>
                <span style="color: rgba(255,255,255,.7)">{{ __('Articles') }}</span>
            </nav>

            <div class="flex flex-wrap items-end justify-between gap-8">
                <div>
                    <div class="flex items-center gap-4 mb-4">
                        <div class="h-px w-8 shrink-0" style="background: #B8962E"></div>
                        <span style="font-size: 0.62rem; font-weight: 600; letter-spacing: .18em; text-transform: uppercase; color: #B8962E">{{ __('Guides & Insights') }}</span>
                    </div>
                    <h1 class="font-serif" style="font-size: clamp(2.2rem,4vw,3.5rem); font-weight: 300; color: #fff; line-height: 1.1">
                        {{ __('Real estate') }} <em class="italic">{{ __('knowledge') }}</em>
                    </h1>
                    <p style="margin-top: 12px; font-size: 0.85rem; color: rgba(255,255,255,.45)">
                        {{ __('Market insights, regulations, and investment guides for Côte d\'Ivoire') }}
                    </p>
                </div>

                <div class="flex items-center gap-3 w-full max-w-sm"
                     style="background: rgba(255,255,255,.07); border: 1px solid rgba(255,255,255,.12); padding: 14px 18px">
                    <svg class="size-4 shrink-0" style="color: #B8962E" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                    </svg>
                    <input wire:model.live.debounce.400ms="search" type="text"
                           placeholder="{{ __('Search articles…') }}"
                           style="flex: 1; background: transparent; font-size: 0.85rem; color: #fff; outline: none"
                           class="placeholder-white/30" />
                    @if($search)
                        <button wire:click="$set('search', '')" style="color: rgba(255,255,255,.4)" class="transition hover:text-white">
                            <svg class="size-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- ══ CATEGORY FILTER ════════════════════════════════════════════════════ --}}
    <div style="background: #FAFAF7; border-bottom: 1px solid #E8E2D8; padding: 16px 6%">
        <div style="max-width: 1200px; margin: 0 auto" class="flex flex-wrap items-center gap-2">
            <button wire:click="$set('category', '')"
                style="padding: 7px 16px; font-size: 0.68rem; font-weight: 600; letter-spacing: .1em; text-transform: uppercase; border: 1px solid {{ $category === '' ? '#1E2330' : '#E8E2D8' }}; background: {{ $category === '' ? '#1E2330' : 'white' }}; color: {{ $category === '' ? '#fff' : '#1E2330' }}; transition: all .2s">
                {{ __('All') }}
            </button>
            @foreach($categories as $cat)
                <button wire:click="$set('category', '{{ $cat }}')"
                    style="padding: 7px 16px; font-size: 0.68rem; font-weight: 600; letter-spacing: .1em; text-transform: uppercase; border: 1px solid {{ $category === $cat ? '#1E2330' : '#E8E2D8' }}; background: {{ $category === $cat ? '#1E2330' : 'white' }}; color: {{ $category === $cat ? '#fff' : '#1E2330' }}; transition: all .2s">
                    {{ $cat }}
                </button>
            @endforeach
        </div>
    </div>

    {{-- ══ ARTICLES GRID ═══════════════════════════════════════════════════════ --}}
    <section style="background: #FAFAF7; padding: 50px 6% 90px">
        <div style="max-width: 1200px; margin: 0 auto">

            @if($articles->isEmpty())
                <div class="flex flex-col items-center justify-center py-24 text-center" style="border: 1px solid #E8E2D8; background: white">
                    <svg class="mb-5 size-12" style="color: #E8E2D8" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 0 1-2.25 2.25M16.5 7.5V18a2.25 2.25 0 0 0 2.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 0 0 2.25 2.25h13.5M6 7.5h3v3H6V7.5Z"/>
                    </svg>
                    <h3 class="font-serif" style="font-size: 1.3rem; font-weight: 300; color: #1E2330; margin-bottom: 8px">{{ __('No articles found') }}</h3>
                    <p style="font-size: 0.82rem; color: rgba(30,35,48,.5)">{{ __('Try a different search or category.') }}</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3" style="gap: 2px; background: #E8E2D8"
                     wire:loading.class="opacity-50 transition-opacity">
                    @foreach($articles as $article)
                        <a href="{{ route('articles.show', $article->slug) }}" wire:navigate
                           class="group block bg-white overflow-hidden no-underline transition-all duration-300 hover:shadow-[0_20px_50px_rgba(0,0,0,.08)] hover:-translate-y-0.5">
                            <div class="overflow-hidden" style="height: 200px; background: #F4EFE8">
                                @if($article->cover_image_url)
                                    <img src="{{ $article->cover_image_url }}" alt="{{ $article->title }}"
                                         class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-[1.05]" />
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <svg class="size-10" style="color: #E8E2D8" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 0 1-2.25 2.25M16.5 7.5V18a2.25 2.25 0 0 0 2.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 0 0 2.25 2.25h13.5M6 7.5h3v3H6V7.5Z"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div style="padding: 22px 24px 26px">
                                <div class="flex items-center gap-3 mb-3">
                                    <span style="font-size: 0.58rem; font-weight: 600; letter-spacing: .14em; text-transform: uppercase; color: #B8962E">{{ $article->category }}</span>
                                    <span style="font-size: 0.58rem; color: rgba(30,35,48,.35); letter-spacing: .04em">
                                        {{ $article->published_at?->translatedFormat('d M Y') }}
                                    </span>
                                </div>
                                <h3 class="font-serif" style="font-size: 1.02rem; font-weight: 300; color: #1E2330; line-height: 1.4; margin-bottom: 12px">
                                    {{ $article->title }}
                                </h3>
                                <p style="font-size: 0.79rem; line-height: 1.75; color: rgba(30,35,48,.55); margin-bottom: 16px" class="line-clamp-3">
                                    {{ $article->excerpt }}
                                </p>
                                <div class="flex items-center gap-2 transition-all duration-200 group-hover:gap-3"
                                     style="font-size: 0.62rem; font-weight: 600; letter-spacing: .14em; text-transform: uppercase; color: #B8962E">
                                    {{ __('Read') }} →
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if($articles->hasPages())
                    <div class="mt-12 flex items-center justify-center gap-1">
                        @if($articles->onFirstPage())
                            <span class="flex size-9 items-center justify-center" style="border: 1px solid #E8E2D8; color: #E8E2D8">
                                <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/></svg>
                            </span>
                        @else
                            <button wire:click="previousPage" class="flex size-9 items-center justify-center transition hover:opacity-70" style="border: 1px solid #E8E2D8; color: #1E2330">
                                <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/></svg>
                            </button>
                        @endif
                        @foreach($articles->getUrlRange(max(1, $articles->currentPage() - 2), min($articles->lastPage(), $articles->currentPage() + 2)) as $page => $url)
                            @if($page === $articles->currentPage())
                                <span class="flex size-9 items-center justify-center text-sm font-semibold" style="background: #1E2330; color: white">{{ $page }}</span>
                            @else
                                <button wire:click="gotoPage({{ $page }})" class="flex size-9 items-center justify-center text-sm transition hover:opacity-70" style="border: 1px solid #E8E2D8; color: #1E2330">{{ $page }}</button>
                            @endif
                        @endforeach
                        @if($articles->hasMorePages())
                            <button wire:click="nextPage" class="flex size-9 items-center justify-center transition hover:opacity-70" style="border: 1px solid #E8E2D8; color: #1E2330">
                                <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
                            </button>
                        @else
                            <span class="flex size-9 items-center justify-center" style="border: 1px solid #E8E2D8; color: #E8E2D8">
                                <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
                            </span>
                        @endif
                    </div>
                @endif
            @endif

        </div>
    </section>

    @include('partials.public-footer')
</div>
