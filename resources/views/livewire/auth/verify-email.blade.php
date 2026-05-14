<x-layouts.auth>
    <div class="flex flex-col gap-7">

        {{-- Icon --}}
        <div class="flex size-14 items-center justify-center" style="background: #F4EFE8">
            <svg class="size-7" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="color: #B8962E">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
            </svg>
        </div>

        <div>
            <h1 style="font-family: 'Playfair Display', serif; font-size: 1.75rem; font-weight: 300; color: #1E2330; line-height: 1.25; margin-bottom: 8px">{{ __('Check Your Inbox') }}</h1>
            <p style="font-size: 0.8rem; font-weight: 400; color: rgba(30,35,48,.5); line-height: 1.6">
                {{ __('Please verify your email address by clicking on the link we just emailed to you.') }}
            </p>
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="rounded-lg bg-green-50 px-4 py-3 text-sm font-medium text-green-700">
                {{ __('A new verification link has been sent to your email address.') }}
            </div>
        @endif

        <div class="flex flex-col gap-3">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button
                    type="submit"
                    class="w-full px-4 py-3 text-xs font-semibold uppercase tracking-[.14em] text-white transition hover:opacity-80 focus:outline-none" style="background: #1E2330"
                >
                    {{ __('Resend verification email') }}
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button
                    type="submit"
                    data-test="logout-button"
                    class="w-full rounded-lg border border-zinc-200 px-4 py-2.5 text-sm font-medium text-zinc-600 transition hover:bg-zinc-50 focus:outline-none"
                >
                    {{ __('Log out') }}
                </button>
            </form>
        </div>
    </div>
</x-layouts.auth>
