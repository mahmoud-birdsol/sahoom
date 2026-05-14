<x-layouts.auth>
    <div class="flex flex-col gap-7">

        {{-- Icon --}}
        <div class="flex size-14 items-center justify-center" style="background: #F4EFE8">
            <svg class="size-7" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="color: #B8962E">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
            </svg>
        </div>

        <div>
            <h1 style="font-family: 'Playfair Display', serif; font-size: 1.75rem; font-weight: 300; color: #1E2330; line-height: 1.25; margin-bottom: 8px">{{ __('Forgot Your Password?') }}</h1>
            <p style="font-size: 0.8rem; font-weight: 400; color: rgba(30,35,48,.5); line-height: 1.6">{{ __('No worries — enter your email and we\'ll send you a reset link.') }}</p>
        </div>

        @if (session('status'))
            <div class="rounded-lg bg-green-50 px-4 py-3 text-sm font-medium text-green-700">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="flex flex-col gap-5">
            @csrf

            <flux:input
                name="email"
                :label="__('Email address')"
                type="email"
                required
                autofocus
                placeholder="email@example.com"
            />

            <button
                type="submit"
                data-test="email-password-reset-link-button"
                class="w-full px-4 py-3 text-xs font-semibold uppercase tracking-[.14em] text-white transition hover:opacity-80 focus:outline-none" style="background: #1E2330"
            >
                {{ __('Send Reset Link') }}
            </button>
        </form>

        <p class="text-center text-sm text-zinc-500">
            <a href="{{ route('login') }}" wire:navigate class="font-semibold transition hover:opacity-70" style="color: #B8962E">
                &larr; {{ __('Back to login') }}
            </a>
        </p>
    </div>
</x-layouts.auth>
