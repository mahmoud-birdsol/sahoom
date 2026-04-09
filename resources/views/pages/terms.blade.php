<x-layouts.public title="{{ __('Terms & Conditions') }}">

    {{-- Navbar --}}
    <x-public.navbar />

    {{-- Hero --}}
    <section class="border-b border-zinc-100 bg-zinc-50 py-14">
        <div class="mx-auto max-w-3xl px-6 text-center lg:px-10">
            <span class="text-xs font-semibold uppercase tracking-widest text-amber-600">{{ __('Legal') }}</span>
            <h1 class="mt-2 text-3xl font-extrabold text-zinc-900 lg:text-4xl">{{ __('Terms & Conditions') }}</h1>
            <p class="mt-3 text-sm text-zinc-500">{{ __('Last updated: January 1, 2025') }}</p>
        </div>
    </section>

    {{-- Content --}}
    <section class="py-14">
        <div class="mx-auto max-w-3xl space-y-10 px-6 lg:px-10">

            <div class="prose prose-zinc max-w-none prose-headings:font-bold prose-headings:text-zinc-900 prose-p:text-zinc-600 prose-p:leading-relaxed prose-li:text-zinc-600">

                <p>{{ __('Welcome to Sahoome. By accessing or using our platform, you agree to be bound by these Terms and Conditions. Please read them carefully before using our services.') }}</p>

                <h2>{{ __('1. Acceptance of Terms') }}</h2>
                <p>{{ __('By creating an account, browsing properties, or using any feature of Sahoome, you accept these Terms in full. If you disagree with any part of these Terms, you must not use our platform.') }}</p>

                <h2>{{ __('2. Description of Services') }}</h2>
                <p>{{ __('Sahoome is a marketplace that connects property owners (Landlords) with individuals seeking to rent commercial or retail spaces (Renters). We facilitate:') }}</p>
                <ul>
                    <li>{{ __('Property listings and search') }}</li>
                    <li>{{ __('Booking and showing requests') }}</li>
                    <li>{{ __('Contract management between landlords and renters') }}</li>
                    <li>{{ __('Communication between parties') }}</li>
                </ul>

                <h2>{{ __('3. User Accounts') }}</h2>
                <p>{{ __('You must be at least 18 years old to create an account. You are responsible for maintaining the confidentiality of your account credentials and for all activity under your account. You agree to provide accurate, current, and complete information.') }}</p>

                <h2>{{ __('4. Landlord Responsibilities') }}</h2>
                <p>{{ __('Landlords who list properties on Sahoome agree to:') }}</p>
                <ul>
                    <li>{{ __('Provide accurate and truthful information about their properties') }}</li>
                    <li>{{ __('Respond promptly to booking and showing requests') }}</li>
                    <li>{{ __('Comply with all applicable local, regional, and national laws') }}</li>
                    <li>{{ __('Ensure listed properties are available for the stated periods') }}</li>
                </ul>

                <h2>{{ __('5. Renter Responsibilities') }}</h2>
                <p>{{ __('Renters using Sahoome agree to:') }}</p>
                <ul>
                    <li>{{ __('Provide truthful information in booking and tour requests') }}</li>
                    <li>{{ __('Use properties only for lawful purposes') }}</li>
                    <li>{{ __('Respect the property and its surroundings during viewings') }}</li>
                    <li>{{ __('Honor agreed contract terms') }}</li>
                </ul>

                <h2>{{ __('6. Fees and Payments') }}</h2>
                <p>{{ __('Sahoome may charge service fees for certain transactions. All applicable fees will be clearly disclosed before any transaction is completed. Pricing for individual properties is set by landlords and may include security deposits, application fees, and rent amounts.') }}</p>

                <h2>{{ __('7. Intellectual Property') }}</h2>
                <p>{{ __('All content on Sahoome including logos, text, graphics, and software is the property of Sahoome or its licensors. You may not reproduce, distribute, or create derivative works without our express written permission.') }}</p>

                <h2>{{ __('8. Limitation of Liability') }}</h2>
                <p>{{ __('Sahoome acts as an intermediary platform. We are not a party to any rental agreement between landlords and renters. Sahoome shall not be liable for any direct, indirect, incidental, or consequential damages arising from the use of our platform or any transactions conducted through it.') }}</p>

                <h2>{{ __('9. Termination') }}</h2>
                <p>{{ __('We reserve the right to suspend or terminate accounts that violate these Terms or engage in fraudulent, abusive, or harmful activity. You may delete your account at any time through your account settings.') }}</p>

                <h2>{{ __('10. Changes to Terms') }}</h2>
                <p>{{ __('We may update these Terms from time to time. Continued use of Sahoome after changes become effective constitutes your acceptance of the revised Terms. We will notify registered users of material changes via email.') }}</p>

                <h2>{{ __('11. Contact Us') }}</h2>
                <p>{{ __('If you have questions about these Terms, please contact us at:') }} <a href="mailto:legal@sahoome.com" class="text-amber-600 hover:underline">legal@sahoome.com</a></p>

            </div>

        </div>
    </section>

    {{-- Footer --}}
    @include('partials.public-footer')

</x-layouts.public>
