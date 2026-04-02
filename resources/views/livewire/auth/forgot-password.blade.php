<x-layouts.auth>
    <div class="flex flex-col gap-7">

        {{-- Icon --}}
        <div class="flex size-14 items-center justify-center rounded-2xl bg-amber-50">
            <svg class="size-7 text-amber-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
            </svg>
        </div>

        <div>
            <h1 class="text-2xl font-bold text-zinc-900">{{ __('Forgot Your Password?') }}</h1>
            <p class="mt-1.5 text-sm text-zinc-500">{{ __('No worries — enter your email and we\'ll send you a reset link.') }}</p>
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
                class="w-full rounded-lg bg-amber-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2"
            >
                {{ __('Send Reset Link') }}
            </button>
        </form>

        <p class="text-center text-sm text-zinc-500">
            <a href="{{ route('login') }}" wire:navigate class="font-semibold text-amber-600 hover:text-amber-700 transition">
                &larr; {{ __('Back to login') }}
            </a>
        </p>
    </div>
</x-layouts.auth>
