<x-layouts.auth>
    <div class="flex flex-col gap-6">
        <x-auth-header
            :title="__('Confirm password')"
            :description="__('This is a secure area of the application. Please confirm your password before continuing.')"
        />

        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('password.confirm.store') }}" class="flex flex-col gap-6">
            @csrf

            <flux:input
                name="password"
                :label="__('Password')"
                type="password"
                required
                autocomplete="current-password"
                :placeholder="__('Password')"
                viewable
            />

            <button type="submit" data-test="confirm-password-button"
                    class="w-full px-4 py-3 text-xs font-semibold uppercase tracking-[.14em] text-white transition hover:opacity-80 focus:outline-none"
                    style="background: #1E2330">
                {{ __('Confirm') }}
            </button>
        </form>
    </div>
</x-layouts.auth>
