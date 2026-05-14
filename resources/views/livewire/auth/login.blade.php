<x-layouts.auth>
    <div class="flex flex-col gap-7">

        <div>
            <h1 style="font-family: 'Playfair Display', serif; font-size: 1.75rem; font-weight: 300; color: #1E2330; line-height: 1.25; margin-bottom: 8px">{{ __('Welcome back') }}</h1>
            <p style="font-size: 0.8rem; font-weight: 400; color: rgba(30,35,48,.5); line-height: 1.6">{{ __('Enter your email and password to access your account') }}</p>
        </div>

        <x-auth-session-status :status="session('status')" />

        <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-5">
            @csrf

            <flux:input
                name="email"
                :label="__('Email address')"
                type="email"
                required
                autofocus
                autocomplete="email"
                placeholder="email@example.com"
            />

            <div class="flex flex-col gap-1.5">
                <div class="flex items-center justify-between">
                    <label class="text-sm font-medium text-zinc-700">{{ __('Password') }}</label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" wire:navigate class="text-xs transition hover:opacity-70" style="color: #B8962E">
                            {{ __('Forgot password?') }}
                        </a>
                    @endif
                </div>
                <flux:input
                    name="password"
                    type="password"
                    required
                    autocomplete="current-password"
                    :placeholder="__('Password')"
                    viewable
                />
            </div>

            <flux:checkbox name="remember" :label="__('Remember me')" :checked="old('remember')" />

            <button
                type="submit"
                data-test="login-button"
                class="mt-1 w-full px-4 py-3 text-xs font-semibold uppercase tracking-[.14em] text-white transition hover:opacity-80 focus:outline-none" style="background: #1E2330"
            >
                {{ __('Log in') }}
            </button>
        </form>

        @if (Route::has('register'))
            <p class="text-center text-sm text-zinc-500">
                {{ __("Don't have an account?") }}
                <a href="{{ route('register') }}" wire:navigate class="font-semibold transition hover:opacity-70" style="color: #B8962E">
                    {{ __('Sign up') }}
                </a>
            </p>
        @endif
    </div>
</x-layouts.auth>
