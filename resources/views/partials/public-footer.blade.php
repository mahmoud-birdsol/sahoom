<footer style="background: #111318; padding: 72px 6% 32px">

    {{-- Wordmark --}}
    <span class="font-sans font-light text-[0.88rem] tracking-[.35em] uppercase text-white">
        {{ \App\Models\SiteSetting::get('site_name', 'Sahoome') }}
    </span>

    {{-- Grid --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-12 pb-14 mt-9 mb-7"
         style="border-bottom: 1px solid rgba(255,255,255,.08)">

        {{-- Brand column --}}
        <div class="col-span-2 sm:col-span-3 lg:col-span-1">
            <p class="text-[0.8rem] font-light leading-[1.85] mt-4" style="color: rgba(255,255,255,.35); max-width: 220px">
                {{ \App\Models\SiteSetting::get('site_description', 'Your trusted real estate platform — residential and commercial rentals for every project.') }}
            </p>
            <div class="flex gap-2.5 mt-5">
                <a href="{{ \App\Models\SiteSetting::get('facebook_url', '#') }}"
                   class="flex size-[34px] items-center justify-center no-underline text-[0.72rem] transition-all duration-200 hover:border-gold hover:text-gold"
                   style="border: 1px solid rgba(255,255,255,.12); color: rgba(255,255,255,.4)">f</a>
                <a href="{{ \App\Models\SiteSetting::get('instagram_url', '#') }}"
                   class="flex size-[34px] items-center justify-center no-underline text-[0.72rem] transition-all duration-200 hover:border-gold hover:text-gold"
                   style="border: 1px solid rgba(255,255,255,.12); color: rgba(255,255,255,.4)">ig</a>
                <a href="#"
                   class="flex size-[34px] items-center justify-center no-underline text-[0.72rem] transition-all duration-200 hover:border-gold hover:text-gold"
                   style="border: 1px solid rgba(255,255,255,.12); color: rgba(255,255,255,.4)">in</a>
            </div>
        </div>

        {{-- Rental --}}
        <div>
            <h4 class="text-[0.6rem] font-semibold tracking-[.2em] uppercase mb-4" style="color: rgba(255,255,255,.7)">{{ __('Rental') }}</h4>
            <ul class="space-y-2.5">
                <li><a href="{{ route('properties.index') }}" wire:navigate class="text-[0.78rem] font-light no-underline transition-colors duration-200 hover:text-gold-light" style="color: rgba(255,255,255,.35)">{{ __('Apartments') }}</a></li>
                <li><a href="{{ route('properties.index') }}" wire:navigate class="text-[0.78rem] font-light no-underline transition-colors duration-200 hover:text-gold-light" style="color: rgba(255,255,255,.35)">{{ __('Villas') }}</a></li>
                <li><a href="{{ route('properties.index') }}" wire:navigate class="text-[0.78rem] font-light no-underline transition-colors duration-200 hover:text-gold-light" style="color: rgba(255,255,255,.35)">{{ __('Studios') }}</a></li>
                <li><a href="{{ route('properties.index') }}" wire:navigate class="text-[0.78rem] font-light no-underline transition-colors duration-200 hover:text-gold-light" style="color: rgba(255,255,255,.35)">{{ __('Offices') }}</a></li>
            </ul>
        </div>

        {{-- Commercial --}}
        <div>
            <h4 class="text-[0.6rem] font-semibold tracking-[.2em] uppercase mb-4" style="color: rgba(255,255,255,.7)">{{ __('Commercial') }}</h4>
            <ul class="space-y-2.5">
                <li><a href="{{ route('properties.index') }}?propertyType=commercial" wire:navigate class="text-[0.78rem] font-light no-underline transition-colors duration-200 hover:text-gold-light" style="color: rgba(255,255,255,.35)">{{ __('Offices') }}</a></li>
                <li><a href="{{ route('properties.index') }}?propertyType=commercial" wire:navigate class="text-[0.78rem] font-light no-underline transition-colors duration-200 hover:text-gold-light" style="color: rgba(255,255,255,.35)">{{ __('Showrooms') }}</a></li>
                <li><a href="{{ route('properties.index') }}?propertyType=commercial&isShortTerm=1" wire:navigate class="text-[0.78rem] font-light no-underline transition-colors duration-200 hover:text-gold-light" style="color: rgba(255,255,255,.35)">{{ __('Short-term') }}</a></li>
                <li><a href="{{ route('properties.index') }}?propertyType=commercial" wire:navigate class="text-[0.78rem] font-light no-underline transition-colors duration-200 hover:text-gold-light" style="color: rgba(255,255,255,.35)">{{ __('Retail') }}</a></li>
            </ul>
        </div>

        {{-- Short-term --}}
        <div>
            <h4 class="text-[0.6rem] font-semibold tracking-[.2em] uppercase mb-4" style="color: rgba(255,255,255,.7)">{{ __('Short-term') }}</h4>
            <ul class="space-y-2.5">
                <li><a href="{{ route('properties.index') }}?isShortTerm=1&propertyType=commercial" wire:navigate class="text-[0.78rem] font-light no-underline transition-colors duration-200 hover:text-gold-light" style="color: rgba(255,255,255,.35)">{{ __('Commercial') }}</a></li>
                <li><a href="{{ route('properties.index') }}?isShortTerm=1&propertyType=residential" wire:navigate class="text-[0.78rem] font-light no-underline transition-colors duration-200 hover:text-gold-light" style="color: rgba(255,255,255,.35)">{{ __('Residential') }}</a></li>
                <li><a href="{{ route('properties.index') }}?isShortTerm=1" wire:navigate class="text-[0.78rem] font-light no-underline transition-colors duration-200 hover:text-gold-light" style="color: rgba(255,255,255,.35)">{{ __('All Short-term') }}</a></li>
            </ul>
        </div>

        {{-- Contact --}}
        <div>
            <h4 class="text-[0.6rem] font-semibold tracking-[.2em] uppercase mb-4" style="color: rgba(255,255,255,.7)">{{ __('Company') }}</h4>
            <ul class="space-y-2.5">
                <li><a href="{{ route('home') }}#about" class="text-[0.78rem] font-light no-underline transition-colors duration-200 hover:text-gold-light" style="color: rgba(255,255,255,.35)">{{ __('About us') }}</a></li>
                <li><a href="{{ route('home') }}#contact" class="text-[0.78rem] font-light no-underline transition-colors duration-200 hover:text-gold-light" style="color: rgba(255,255,255,.35)">{{ __('Contact') }}</a></li>
                <li><a href="{{ route('faq') }}" wire:navigate class="text-[0.78rem] font-light no-underline transition-colors duration-200 hover:text-gold-light" style="color: rgba(255,255,255,.35)">{{ __('FAQ') }}</a></li>
                <li><a href="{{ route('privacy') }}" wire:navigate class="text-[0.78rem] font-light no-underline transition-colors duration-200 hover:text-gold-light" style="color: rgba(255,255,255,.35)">{{ __('Privacy') }}</a></li>
                <li><a href="{{ route('terms') }}" wire:navigate class="text-[0.78rem] font-light no-underline transition-colors duration-200 hover:text-gold-light" style="color: rgba(255,255,255,.35)">{{ __('Terms') }}</a></li>
                <li>
                    <a href="mailto:{{ \App\Models\SiteSetting::get('contact_email', 'sahoome@gmail.com') }}"
                       class="text-[0.78rem] font-light no-underline transition-colors duration-200 hover:text-gold-light"
                       style="color: rgba(255,255,255,.35)">
                        {{ \App\Models\SiteSetting::get('contact_email', 'sahoome@gmail.com') }}
                    </a>
                </li>
            </ul>
        </div>

    </div>

    {{-- Bottom bar --}}
    <div class="flex flex-wrap justify-between gap-2 text-[0.68rem] font-light tracking-[.04em]"
         style="color: rgba(255,255,255,.25)">
        <span>{{ \App\Models\SiteSetting::get('copyright', '© ' . date('Y') . ' SAHOOME — All rights reserved') }}</span>
        <span>{{ \App\Models\SiteSetting::get('contact_address', 'Abidjan, Ivory Coast') }}</span>
    </div>

</footer>
