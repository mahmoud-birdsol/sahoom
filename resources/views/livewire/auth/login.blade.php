<x-layouts.auth>
    <div class="flex flex-col gap-7">

        <div>
            <h1 class="text-2xl font-bold text-zinc-900">{{ __('Login to Your Account') }}</h1>
            <p class="mt-1.5 text-sm text-zinc-500">{{ __('Enter your email and password below to log in') }}</p>
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
                        <a href="{{ route('password.request') }}" wire:navigate class="text-xs text-amber-600 hover:text-amber-700 transition">
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
                class="mt-1 w-full rounded-lg bg-amber-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2"
            >
                {{ __('Log in') }}
            </button>
        </form>

        @if (Route::has('register'))
            <p class="text-center text-sm text-zinc-500">
                {{ __("Don't have an account?") }}
                <a href="{{ route('register') }}" wire:navigate class="font-semibold text-amber-600 hover:text-amber-700 transition">
                    {{ __('Sign up') }}
                </a>
            </p>
        @endif
    </div>
</x-layouts.auth>
