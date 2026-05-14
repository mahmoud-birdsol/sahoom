<div class="flex flex-col gap-6 p-6">

    {{-- Header --}}
    <div class="flex items-start justify-between gap-4">
        <div class="space-y-0.5">
            <h1 style="font-family: 'Playfair Display', serif; font-size: 1.5rem; font-weight: 300; color: #1E2330; line-height: 1.25">{{ __('Viewing Requests') }}</h1>
            <p style="font-size: 0.8rem; color: rgba(30,35,48,.5); font-weight: 400">{{ __('Manage showing and booking inquiries from renters') }}</p>
        </div>
        @if($pendingCount > 0)
            <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1.5 text-sm font-semibold ring-1" style="background: #F4EFE8; color: #B8962E; ring-color: #E8E2D8">
                <span class="size-2 rounded-full" style="background: #B8962E"></span>
                {{ $pendingCount }} {{ __('pending') }}
            </span>
        @endif
    </div>

    {{-- Filters --}}
    <div class="flex flex-wrap items-center gap-3">
        <select wire:model.live="filterPropertyId"
            class="rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-700 outline-none focus:border-zinc-400 focus:ring-2 focus:ring-zinc-100">
            <option value="">{{ __('All Properties') }}</option>
            @foreach($this->landlordProperties as $prop)
                <option value="{{ $prop->id }}">{{ $prop->title }}</option>
            @endforeach
        </select>

        <select wire:model.live="filterStatus"
            class="rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-700 outline-none focus:border-zinc-400 focus:ring-2 focus:ring-zinc-100">
            <option value="">{{ __('All Statuses') }}</option>
            <option value="new">{{ __('New') }}</option>
            <option value="contacted">{{ __('Contacted') }}</option>
            <option value="no_show">{{ __('No Show') }}</option>
            <option value="closed">{{ __('Closed') }}</option>
        </select>
    </div>

    {{-- Table --}}
    @if($requests->isEmpty())
        <div class="flex flex-col items-center justify-center rounded-xl border border-dashed border-zinc-200 bg-white py-20 text-center">
            <div class="flex size-16 items-center justify-center rounded-2xl bg-zinc-100">
                <flux:icon.eye class="size-8 text-zinc-400" variant="outline" />
            </div>
            <p class="mt-4 text-base font-semibold text-zinc-700">{{ __('No viewing requests yet') }}</p>
            <p class="mt-1 text-sm text-zinc-400">{{ __('Showing and booking inquiries will appear here.') }}</p>
        </div>
    @else
        <div class="overflow-hidden rounded-xl border border-zinc-100 bg-white shadow-sm">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-zinc-100 bg-zinc-50 text-left text-xs font-semibold uppercase tracking-wide text-zinc-400">
                        <th class="px-5 py-3">{{ __('Renter') }}</th>
                        <th class="px-5 py-3">{{ __('Property') }}</th>
                        <th class="px-5 py-3">{{ __('Preferred Date') }}</th>
                        <th class="px-5 py-3">{{ __('Type') }}</th>
                        <th class="px-5 py-3">{{ __('Status') }}</th>
                        <th class="px-5 py-3">{{ __('Received') }}</th>
                        <th class="px-5 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-50">
                    @foreach($requests as $request)
                        @php
                            $statusConfig = match($request->status?->value ?? 'new') {
                                'new'       => ['label' => __('New'),       'class' => 'bg-[#F4EFE8] text-[#B8962E] ring-[#E8E2D8]'],
                                'contacted' => ['label' => __('Contacted'), 'class' => 'bg-blue-50 text-blue-700 ring-blue-200'],
                                'no_show'   => ['label' => __('No Show'),   'class' => 'bg-red-50 text-red-600 ring-red-200'],
                                'closed'    => ['label' => __('Closed'),    'class' => 'bg-zinc-50 text-zinc-500 ring-zinc-200'],
                                default     => ['label' => __('New'),       'class' => 'bg-[#F4EFE8] text-[#B8962E] ring-[#E8E2D8]'],
                            };
                            $isBooking = str_contains(strtolower($request->message ?? ''), 'book now');
                        @endphp
                        <tr wire:click="openDetails({{ $request->id }})"
                            class="cursor-pointer transition-colors hover:bg-zinc-50 {{ ($request->status?->value ?? 'new') === 'new' ? 'font-medium' : '' }}"
                            wire:key="vr-{{ $request->id }}">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex size-8 shrink-0 items-center justify-center rounded-full text-xs font-bold text-white" style="background: #1E2330">
                                        {{ strtoupper(substr($request->renter_name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-zinc-800">{{ $request->renter_name }}</p>
                                        <p class="text-xs text-zinc-400">{{ $request->renter_email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <p class="text-sm text-zinc-700 line-clamp-1">{{ $request->property?->title ?? '—' }}</p>
                            </td>
                            <td class="px-5 py-4 text-sm text-zinc-600">
                                {{ $request->preferred_date?->format('M d, Y') ?? '—' }}
                            </td>
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-xs font-medium
                                    {{ $isBooking ? 'bg-violet-50 text-violet-700' : 'bg-sky-50 text-sky-700' }}">
                                    {{ $isBooking ? __('Booking') : __('Showing') }}
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold ring-1 {{ $statusConfig['class'] }}">
                                    {{ $statusConfig['label'] }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-xs text-zinc-400">{{ $request->created_at->diffForHumans() }}</td>
                            <td class="px-5 py-4 text-right">
                                <flux:icon.chevron-right class="size-4 text-zinc-300" />
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @if($requests->hasPages())
                <div class="border-t border-zinc-100 px-5 py-3">
                    {{ $requests->links() }}
                </div>
            @endif
        </div>
    @endif

    {{-- Details slide-over panel --}}
    @if($showDetails && $this->detailsRequest)
        @php $req = $this->detailsRequest; @endphp
        <div class="fixed inset-0 z-40 flex justify-end" x-data>
            {{-- Backdrop --}}
            <div class="absolute inset-0 bg-black/20 backdrop-blur-sm" wire:click="closeDetails"></div>

            {{-- Panel --}}
            <div class="relative z-50 flex h-full w-full max-w-md flex-col bg-white shadow-xl">
                {{-- Panel header --}}
                <div class="flex items-center justify-between border-b border-zinc-100 px-6 py-4">
                    <h2 class="text-base font-bold text-zinc-900">{{ __('Request Details') }}</h2>
                    <button wire:click="closeDetails" class="rounded-lg p-1.5 text-zinc-400 transition hover:bg-zinc-100 hover:text-zinc-700">
                        <flux:icon.x-mark class="size-5" />
                    </button>
                </div>

                {{-- Panel body --}}
                <div class="flex-1 overflow-y-auto p-6 space-y-5">

                    {{-- Renter info --}}
                    <div class="rounded-xl border border-zinc-100 bg-zinc-50 p-4 space-y-2">
                        <p class="text-xs font-semibold uppercase tracking-wider text-zinc-400">{{ __('Renter') }}</p>
                        <p class="text-base font-bold text-zinc-900">{{ $req->renter_name }}</p>
                        <a href="mailto:{{ $req->renter_email }}" class="flex items-center gap-1.5 text-sm hover:underline" style="color: #B8962E">
                            <flux:icon.envelope class="size-3.5" />{{ $req->renter_email }}
                        </a>
                        @if($req->renter_phone)
                            <a href="tel:{{ $req->renter_phone }}" class="flex items-center gap-1.5 text-sm text-zinc-600 hover:underline">
                                <flux:icon.phone class="size-3.5" />{{ $req->renter_phone }}
                            </a>
                        @endif
                    </div>

                    {{-- Property --}}
                    <div class="space-y-1">
                        <p class="text-xs font-semibold uppercase tracking-wider text-zinc-400">{{ __('Property') }}</p>
                        <p class="text-sm font-semibold text-zinc-800">{{ $req->property?->title }}</p>
                        <p class="text-xs text-zinc-500">{{ $req->property?->address_line_1 }}</p>
                    </div>

                    {{-- Preferred date --}}
                    <div class="space-y-1">
                        <p class="text-xs font-semibold uppercase tracking-wider text-zinc-400">{{ __('Preferred Viewing Date') }}</p>
                        <p class="text-sm text-zinc-700">{{ $req->preferred_date?->format('l, F j, Y') ?? __('Not specified') }}</p>
                    </div>

                    {{-- Lease period --}}
                    @if($req->start_date || $req->end_date)
                        <div class="rounded-xl border border-zinc-100 bg-zinc-50 p-4 space-y-2">
                            <p class="text-xs font-semibold uppercase tracking-wider text-zinc-400">{{ __('Requested Lease Period') }}</p>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <p class="text-xs text-zinc-500">{{ __('Start Date') }}</p>
                                    <p class="text-sm font-semibold text-zinc-800">{{ $req->start_date?->format('M j, Y') ?? '—' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-zinc-500">{{ __('End Date') }}</p>
                                    <p class="text-sm font-semibold text-zinc-800">{{ $req->end_date?->format('M j, Y') ?? '—' }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Message --}}
                    @if($req->message)
                        <div class="space-y-1">
                            <p class="text-xs font-semibold uppercase tracking-wider text-zinc-400">{{ __('Message') }}</p>
                            <p class="rounded-lg bg-zinc-50 p-3 text-sm text-zinc-700">{{ $req->message }}</p>
                        </div>
                    @endif

                    {{-- Status --}}
                    <div class="space-y-2">
                        <p class="text-xs font-semibold uppercase tracking-wider text-zinc-400">{{ __('Update Status') }}</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach(\App\Models\States\ViewingRequestStatus::cases() as $s)
                                <button
                                    wire:click="updateStatus({{ $req->id }}, '{{ $s->value }}')"
                                    class="rounded-lg border px-3 py-1.5 text-xs font-semibold transition
                                        {{ ($req->status?->value ?? 'new') === $s->value
                                            ? 'border-transparent text-white'
                                            : 'border-zinc-200 bg-white text-zinc-600 hover:border-zinc-300 hover:text-zinc-800' }}"
                                        @if(($req->status?->value ?? 'new') === $s->value) style="background: #1E2330" @endif">
                                    {{ $s->label() }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Panel footer --}}
                <div class="border-t border-zinc-100 px-6 py-4">
                    <p class="text-xs text-zinc-400">{{ __('Received') }} {{ $req->created_at->diffForHumans() }} · {{ $req->created_at->format('M d, Y H:i') }}</p>
                </div>
            </div>
        </div>
    @endif

</div>
