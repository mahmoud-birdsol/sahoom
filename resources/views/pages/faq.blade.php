<x-layouts.public title="{{ __('FAQ') }}">

    {{-- Navbar --}}
    <x-public.navbar />

    {{-- Hero --}}
    <section class="border-b border-zinc-100 bg-zinc-50 py-14">
        <div class="mx-auto max-w-3xl px-6 text-center lg:px-10">
            <span class="text-xs font-semibold uppercase tracking-widest text-amber-600">{{ __('Help Center') }}</span>
            <h1 class="mt-2 text-3xl font-extrabold text-zinc-900 lg:text-4xl">{{ __('Frequently Asked Questions') }}</h1>
            <p class="mt-3 text-sm text-zinc-500">{{ __('Everything you need to know about Sahoome.') }}</p>
        </div>
    </section>

    {{-- FAQ Sections --}}
    <section class="py-14">
        <div class="mx-auto max-w-3xl px-6 lg:px-10" x-data="{ open: null }">

            {{-- General --}}
            <h2 class="mb-5 text-base font-bold uppercase tracking-wider text-zinc-400">{{ __('General') }}</h2>

            @php
            $faqs = [
                'general' => [
                    ['q' => 'What is Sahoome?', 'a' => 'Sahoome is an online marketplace that connects property owners (landlords) with businesses and individuals looking to rent commercial or popup retail spaces. We make it easy to discover, book, and manage property rentals in one place.'],
                    ['q' => 'Is Sahoome free to use?', 'a' => 'Browsing and searching properties on Sahoome is completely free. Creating a renter account is also free. Landlords and certain premium features may involve service fees, which are clearly disclosed before any transaction.'],
                    ['q' => 'What types of properties are available?', 'a' => 'Sahoome lists a variety of commercial and retail spaces including popup stores, showrooms, studios, office spaces, and short-term retail units. Properties vary by location, size, and rental period.'],
                    ['q' => 'Do I need an account to browse properties?', 'a' => 'No. You can browse and view property listings without an account. However, to submit a booking request, schedule a showing, or save favourite properties, you will need to create a free account.'],
                ],
                'renters' => [
                    ['q' => 'How do I book a property?', 'a' => 'Navigate to the property page you are interested in and click "Book Now". Fill in your contact details and preferred move-in date, then submit your request. The landlord will be notified and will get back to you to confirm availability.'],
                    ['q' => 'How do I schedule a property viewing?', 'a' => 'On any property page, click "Schedule a Tour" and provide your preferred date and contact information. The landlord will review your request and contact you to arrange a time.'],
                    ['q' => 'Can I save properties I am interested in?', 'a' => 'Yes. When logged in, click the heart icon on any property card or on the property details page to add it to your favourites. You can view all your saved properties from your account dashboard under "Favourites".'],
                    ['q' => 'What happens after I submit a booking request?', 'a' => 'Your request is sent directly to the landlord. They will review it and contact you via the email or phone you provided. Sahoome does not automatically confirm bookings — finalising the arrangement is agreed directly between you and the landlord.'],
                ],
                'landlords' => [
                    ['q' => 'How do I list my property on Sahoome?', 'a' => 'Register or log in, then navigate to your Landlord Dashboard. Click "Add Property" and fill in your property details including title, description, address, pricing, and photos. Once submitted, your property will be reviewed and made live on the platform.'],
                    ['q' => 'How will I be notified of new requests?', 'a' => 'You will receive a notification in your landlord dashboard whenever a renter submits a booking inquiry or requests a showing for one of your properties. You can also manage all requests from the "Viewing Requests" section.'],
                    ['q' => 'Can I set different rental prices for different periods?', 'a' => 'Yes. Sahoome supports daily, weekly, monthly, and yearly pricing. You can configure the pricing type and amount that best suits your property when creating or editing a listing.'],
                    ['q' => 'How do I manage contracts and tenants?', 'a' => 'Your Landlord Dashboard includes a Booking & Calendar section where you can view and manage all active and upcoming contracts. Click on any contract to see full details including renter information, dates, and financial terms.'],
                ],
                'payments' => [
                    ['q' => 'What currencies are supported?', 'a' => 'Sahoome currently supports pricing in USD (US Dollar), SAR (Saudi Riyal), EUR (Euro), and GBP (British Pound). Landlords select the currency for their property when creating a listing.'],
                    ['q' => 'Is there a security deposit?', 'a' => 'Security deposits are set at the landlord\'s discretion and are shown clearly on the property details page before you submit any booking request. Not all properties require a security deposit.'],
                    ['q' => 'How are payments processed?', 'a' => 'Payment arrangements are agreed between the landlord and renter. Sahoome facilitates the introduction and agreement but does not process direct payments at this time. Please confirm payment terms directly with the landlord.'],
                ],
            ];
            @endphp

            @foreach($faqs as $section => $items)
                @if($section !== 'general')
                    <h2 class="mb-5 mt-12 text-base font-bold uppercase tracking-wider text-zinc-400">
                        {{ match($section) {
                            'renters'   => __('For Renters'),
                            'landlords' => __('For Landlords'),
                            'payments'  => __('Payments & Pricing'),
                            default     => ucfirst($section),
                        } }}
                    </h2>
                @endif

                <div class="mb-3 divide-y divide-zinc-100 overflow-hidden rounded-xl border border-zinc-100 bg-white shadow-sm">
                    @foreach($items as $i => $faq)
                        <div x-data="{ open: false }" class="group">
                            <button
                                @click="open = !open"
                                class="flex w-full items-center justify-between px-5 py-4 text-left transition hover:bg-amber-50/40"
                            >
                                <span class="text-sm font-semibold text-zinc-800 group-hover:text-amber-700">{{ __($faq['q']) }}</span>
                                <svg class="size-4 shrink-0 text-zinc-400 transition-transform duration-200"
                                     :class="open ? 'rotate-180 text-amber-600' : ''"
                                     fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m19 9-7 7-7-7"/>
                                </svg>
                            </button>
                            <div x-show="open" x-collapse class="border-t border-zinc-50 bg-zinc-50/50 px-5 py-4">
                                <p class="text-sm leading-relaxed text-zinc-600">{{ __($faq['a']) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach

            {{-- Still have questions --}}
            <div class="mt-12 rounded-2xl border border-amber-100 bg-amber-50 p-8 text-center">
                <h3 class="text-base font-bold text-zinc-900">{{ __('Still have questions?') }}</h3>
                <p class="mt-2 text-sm text-zinc-600">{{ __("Can't find the answer you're looking for? Reach out to our support team.") }}</p>
                <a href="{{ route('home') }}#contact" wire:navigate
                    class="mt-5 inline-flex items-center gap-2 rounded-lg bg-amber-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-amber-700">
                    <svg class="size-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/></svg>
                    {{ __('Contact Us') }}
                </a>
            </div>

        </div>
    </section>

    @include('partials.public-footer')

</x-layouts.public>
