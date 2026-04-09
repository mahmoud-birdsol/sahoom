<x-layouts.public title="{{ __('Privacy Policy') }}">

    {{-- Navbar --}}
    <x-public.navbar />

    {{-- Hero --}}
    <section class="border-b border-zinc-100 bg-zinc-50 py-14">
        <div class="mx-auto max-w-3xl px-6 text-center lg:px-10">
            <span class="text-xs font-semibold uppercase tracking-widest text-amber-600">{{ __('Legal') }}</span>
            <h1 class="mt-2 text-3xl font-extrabold text-zinc-900 lg:text-4xl">{{ __('Privacy Policy') }}</h1>
            <p class="mt-3 text-sm text-zinc-500">{{ __('Last updated: January 1, 2025') }}</p>
        </div>
    </section>

    {{-- Content --}}
    <section class="py-14">
        <div class="mx-auto max-w-3xl space-y-10 px-6 lg:px-10">
            <div class="prose prose-zinc max-w-none prose-headings:font-bold prose-headings:text-zinc-900 prose-p:text-zinc-600 prose-p:leading-relaxed prose-li:text-zinc-600">

                <p>{{ __('At Sahoome, we are committed to protecting your privacy. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you use our platform.') }}</p>

                <h2>{{ __('1. Information We Collect') }}</h2>
                <p>{{ __('We collect information you provide directly to us, such as:') }}</p>
                <ul>
                    <li>{{ __('Account registration information (name, email address, phone number, password)') }}</li>
                    <li>{{ __('Profile information and preferences') }}</li>
                    <li>{{ __('Property listings, booking requests, and viewing requests') }}</li>
                    <li>{{ __('Communications with other users and with us') }}</li>
                    <li>{{ __('Payment information (processed securely through our payment providers)') }}</li>
                </ul>
                <p>{{ __('We also collect information automatically, including:') }}</p>
                <ul>
                    <li>{{ __('Log data (IP address, browser type, pages visited, time spent)') }}</li>
                    <li>{{ __('Device information (device type, operating system)') }}</li>
                    <li>{{ __('Location information when you search for properties') }}</li>
                    <li>{{ __('Cookies and similar tracking technologies') }}</li>
                </ul>

                <h2>{{ __('2. How We Use Your Information') }}</h2>
                <p>{{ __('We use the information we collect to:') }}</p>
                <ul>
                    <li>{{ __('Provide, operate, and improve our platform and services') }}</li>
                    <li>{{ __('Connect landlords with prospective renters') }}</li>
                    <li>{{ __('Process bookings, viewing requests, and transactions') }}</li>
                    <li>{{ __('Send transactional emails and notifications about your account') }}</li>
                    <li>{{ __('Respond to comments, questions, and customer service requests') }}</li>
                    <li>{{ __('Monitor and analyse usage patterns to improve user experience') }}</li>
                    <li>{{ __('Detect, investigate, and prevent fraudulent or illegal activity') }}</li>
                </ul>

                <h2>{{ __('3. Sharing of Information') }}</h2>
                <p>{{ __('We do not sell your personal information. We may share your information with:') }}</p>
                <ul>
                    <li>{{ __('Other users as necessary to facilitate transactions (e.g., landlords receiving renter contact details for confirmed bookings)') }}</li>
                    <li>{{ __('Service providers who assist in our operations (payment processors, hosting providers, analytics tools)') }}</li>
                    <li>{{ __('Law enforcement or government authorities when required by law') }}</li>
                    <li>{{ __('Successors in connection with a merger, acquisition, or sale of assets') }}</li>
                </ul>

                <h2>{{ __('4. Cookies') }}</h2>
                <p>{{ __('We use cookies and similar tracking technologies to enhance your experience. Cookies help us remember your preferences, maintain your session, and understand how you interact with our platform. You can control cookie settings through your browser, but disabling cookies may limit certain features.') }}</p>

                <h2>{{ __('5. Data Security') }}</h2>
                <p>{{ __('We implement appropriate technical and organisational measures to protect your personal data against unauthorised access, alteration, disclosure, or destruction. However, no method of internet transmission or electronic storage is 100% secure. We encourage you to use a strong, unique password for your account.') }}</p>

                <h2>{{ __('6. Data Retention') }}</h2>
                <p>{{ __('We retain your personal data for as long as your account is active or as needed to provide you services. You may request deletion of your account and associated data at any time, subject to our legal obligations to retain certain records.') }}</p>

                <h2>{{ __('7. Your Rights') }}</h2>
                <p>{{ __('Depending on your location, you may have the right to:') }}</p>
                <ul>
                    <li>{{ __('Access the personal data we hold about you') }}</li>
                    <li>{{ __('Request correction of inaccurate data') }}</li>
                    <li>{{ __('Request deletion of your personal data') }}</li>
                    <li>{{ __('Object to or restrict processing of your data') }}</li>
                    <li>{{ __('Data portability') }}</li>
                </ul>
                <p>{{ __('To exercise any of these rights, please contact us at the email below.') }}</p>

                <h2>{{ __('8. Third-Party Links') }}</h2>
                <p>{{ __('Our platform may contain links to third-party websites. We are not responsible for the privacy practices of those sites. We encourage you to review their privacy policies before providing any personal information.') }}</p>

                <h2>{{ __('9. Changes to This Policy') }}</h2>
                <p>{{ __('We may update this Privacy Policy periodically. We will notify you of significant changes by posting a notice on our platform or by email. Your continued use of Sahoome after any changes indicates your acceptance of the updated policy.') }}</p>

                <h2>{{ __('10. Contact Us') }}</h2>
                <p>{{ __('If you have any questions or concerns about this Privacy Policy, please contact our privacy team at:') }} <a href="mailto:privacy@sahoome.com" class="text-amber-600 hover:underline">privacy@sahoome.com</a></p>

            </div>
        </div>
    </section>

    @include('partials.public-footer')

</x-layouts.public>
