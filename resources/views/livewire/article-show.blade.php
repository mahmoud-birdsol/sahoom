<div>
    <x-public.navbar />

    {{-- ══ BREADCRUMB ════════════════════════════════════════════════════════ --}}
    <div style="background: #FAFAF7; border-bottom: 1px solid #E8E2D8; padding: 14px 6%">
        <div style="max-width: 1200px; margin: 0 auto">
            <nav class="flex items-center gap-2" style="font-size: 0.68rem; color: rgba(30,35,48,.4); letter-spacing: .08em; text-transform: uppercase">
                <a href="{{ route('home') }}" wire:navigate style="color: rgba(30,35,48,.4)" class="transition hover:opacity-70">{{ __('Home') }}</a>
                <span style="color: #B8962E">/</span>
                <a href="{{ route('articles.index') }}" wire:navigate style="color: rgba(30,35,48,.4)" class="transition hover:opacity-70">{{ __('Articles') }}</a>
                <span style="color: #B8962E">/</span>
                <span class="truncate max-w-xs" style="color: #1E2330">{{ $article->title }}</span>
            </nav>
        </div>
    </div>

    {{-- ══ ARTICLE HERO ════════════════════════════════════════════════════════ --}}
    <section style="background: #1E2330; padding: 80px 6% 60px">
        <div style="max-width: 800px; margin: 0 auto; text-align: center">
            <div class="flex items-center justify-center gap-4 mb-5">
                <span style="font-size: 0.6rem; font-weight: 600; letter-spacing: .18em; text-transform: uppercase; color: #B8962E">{{ $article->category }}</span>
                @if($article->published_at)
                    <span style="font-size: 0.6rem; color: rgba(255,255,255,.3)">·</span>
                    <span style="font-size: 0.6rem; color: rgba(255,255,255,.3); letter-spacing: .06em">{{ $article->published_at->translatedFormat('d F Y') }}</span>
                @endif
                @if($article->author)
                    <span style="font-size: 0.6rem; color: rgba(255,255,255,.3)">·</span>
                    <span style="font-size: 0.6rem; color: rgba(255,255,255,.3); letter-spacing: .06em">{{ $article->author }}</span>
                @endif
            </div>
            <h1 class="font-serif" style="font-size: clamp(1.8rem,4vw,2.8rem); font-weight: 300; color: #fff; line-height: 1.2; margin-bottom: 20px">
                {{ $article->title }}
            </h1>
            <p style="font-size: 0.92rem; color: rgba(255,255,255,.5); line-height: 1.75; max-width: 640px; margin: 0 auto">
                {{ $article->excerpt }}
            </p>
        </div>
    </section>

    {{-- ══ COVER IMAGE ═════════════════════════════════════════════════════════ --}}
    @if($article->cover_image_url)
        <div style="max-width: 1000px; margin: -1px auto 0; overflow: hidden; height: 440px">
            <img src="{{ $article->cover_image_url }}" alt="{{ $article->title }}" class="w-full h-full object-cover" />
        </div>
    @endif

    {{-- ══ ARTICLE BODY ════════════════════════════════════════════════════════ --}}
    <section style="background: #FAFAF7; padding: 60px 6% 90px">
        <div style="max-width: 1200px; margin: 0 auto">
            <div class="flex flex-col lg:flex-row gap-10 lg:items-start">

                {{-- Main content --}}
                <div class="flex-1 min-w-0">
                    <div class="prose-article" style="background: white; border: 1px solid #E8E2D8; padding: 40px 44px">
                        {!! $article->body !!}
                    </div>

                    {{-- Back link --}}
                    <div class="mt-6">
                        <a href="{{ route('articles.index') }}" wire:navigate
                           class="inline-flex items-center gap-2 transition hover:opacity-70"
                           style="font-size: 0.7rem; font-weight: 600; letter-spacing: .12em; text-transform: uppercase; color: #1E2330">
                            <svg class="size-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/></svg>
                            {{ __('All articles') }}
                        </a>
                    </div>
                </div>

                {{-- Sidebar --}}
                <div class="w-full shrink-0 lg:w-[300px]">

                    {{-- Article info --}}
                    <div style="background: #1E2330; padding: 28px 24px; margin-bottom: 2px">
                        <div style="font-size: 0.6rem; font-weight: 600; letter-spacing: .16em; text-transform: uppercase; color: rgba(255,255,255,.35); margin-bottom: 16px">{{ __('About this article') }}</div>
                        <dl class="space-y-3">
                            @if($article->author)
                                <div>
                                    <dt style="font-size: 0.6rem; color: rgba(255,255,255,.35); text-transform: uppercase; letter-spacing: .1em; margin-bottom: 2px">{{ __('Author') }}</dt>
                                    <dd style="font-size: 0.82rem; color: white">{{ $article->author }}</dd>
                                </div>
                            @endif
                            @if($article->published_at)
                                <div>
                                    <dt style="font-size: 0.6rem; color: rgba(255,255,255,.35); text-transform: uppercase; letter-spacing: .1em; margin-bottom: 2px">{{ __('Published') }}</dt>
                                    <dd style="font-size: 0.82rem; color: white">{{ $article->published_at->translatedFormat('d F Y') }}</dd>
                                </div>
                            @endif
                            <div>
                                <dt style="font-size: 0.6rem; color: rgba(255,255,255,.35); text-transform: uppercase; letter-spacing: .1em; margin-bottom: 2px">{{ __('Category') }}</dt>
                                <dd style="font-size: 0.82rem; color: #B8962E; font-weight: 600">{{ $article->category }}</dd>
                            </div>
                        </dl>
                    </div>

                    {{-- Related articles --}}
                    @if($related->isNotEmpty())
                        <div style="background: white; border: 1px solid #E8E2D8; padding: 24px">
                            <div style="font-size: 0.6rem; font-weight: 600; letter-spacing: .16em; text-transform: uppercase; color: #B8962E; margin-bottom: 16px">{{ __('Related articles') }}</div>
                            <div class="space-y-4">
                                @foreach($related as $rel)
                                    <a href="{{ route('articles.show', $rel->slug) }}" wire:navigate
                                       class="group block no-underline transition hover:opacity-70">
                                        <div style="font-size: 0.58rem; color: rgba(30,35,48,.4); letter-spacing: .06em; margin-bottom: 4px">
                                            {{ $rel->published_at?->translatedFormat('d M Y') }}
                                        </div>
                                        <div class="font-serif" style="font-size: 0.88rem; font-weight: 300; color: #1E2330; line-height: 1.4">
                                            {{ $rel->title }}
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- CTA --}}
                    <div style="background: #F4EFE8; border: 1px solid #E8E2D8; padding: 24px; margin-top: 2px; text-align: center">
                        <p class="font-serif" style="font-size: 1rem; font-weight: 300; color: #1E2330; margin-bottom: 14px">{{ __('Looking for a property?') }}</p>
                        <a href="{{ route('properties.index') }}" wire:navigate
                           style="display: inline-block; padding: 11px 24px; background: #1E2330; color: white; font-size: 0.65rem; font-weight: 600; letter-spacing: .14em; text-transform: uppercase; text-decoration: none; transition: opacity .2s"
                           class="hover:opacity-80">
                            {{ __('Browse Properties') }}
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    @include('partials.public-footer')
</div>

@push('head')
<style>
.prose-article p  { font-size: 0.9rem; line-height: 1.9; color: rgba(30,35,48,.75); margin-bottom: 1.4em }
.prose-article h2 { font-family: var(--font-serif, serif); font-size: 1.4rem; font-weight: 300; color: #1E2330; margin: 2em 0 .6em }
.prose-article h3 { font-size: 1.05rem; font-weight: 600; color: #1E2330; margin: 1.6em 0 .5em }
.prose-article ul { list-style: disc; padding-left: 1.4em; margin-bottom: 1.4em }
.prose-article ul li { font-size: 0.9rem; line-height: 1.8; color: rgba(30,35,48,.75); margin-bottom: .4em }
.prose-article strong { color: #1E2330; font-weight: 600 }
</style>
@endpush
