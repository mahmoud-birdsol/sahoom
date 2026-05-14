<x-layouts.auth>
    <div class="flex flex-col gap-7">

        <div>
            <h1 style="font-family: 'Playfair Display', serif; font-size: 1.75rem; font-weight: 300; color: #1E2330; line-height: 1.25; margin-bottom: 8px">{{ __('Create an Account') }}</h1>
            <p style="font-size: 0.8rem; font-weight: 400; color: rgba(30,35,48,.5); line-height: 1.6">{{ __('Enter your details below to get started') }}</p>
        </div>

        <x-auth-session-status :status="session('status')" />

        <form method="POST" action="{{ route('register.store') }}" class="flex flex-col gap-5">
            @csrf

            <flux:input
                name="name"
                :label="__('Full name')"
                type="text"
                required
                autofocus
                autocomplete="name"
                :placeholder="__('Full name')"
            />

            <flux:input
                name="email"
                :label="__('Email address')"
                type="email"
                required
                autocomplete="email"
                placeholder="email@example.com"
            />

            <flux:input
                name="password"
                :label="__('Password')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('Password')"
                viewable
            />

            <flux:input
                name="password_confirmation"
                :label="__('Confirm password')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('Confirm password')"
                viewable
            />

            <button
                type="submit"
                class="mt-1 w-full px-4 py-3 text-xs font-semibold uppercase tracking-[.14em] text-white transition hover:opacity-80 focus:outline-none" style="background: #1E2330"
            >
                {{ __('Create account') }}
            </button>
        </form>

        <p class="text-center text-sm text-zinc-500">
            {{ __('Already have an account?') }}
            <a href="{{ route('login') }}" wire:navigate class="font-semibold transition hover:opacity-70" style="color: #B8962E">
                {{ __('Log in') }}
            </a>
        </p>
    </div>
</x-layouts.auth>
