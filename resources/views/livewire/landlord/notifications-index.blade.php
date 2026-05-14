<div class="flex flex-col gap-6 p-6">

    {{-- Header --}}
    <div class="flex items-start justify-between gap-4">
        <div class="space-y-0.5">
            <h1 style="font-family: 'Playfair Display', serif; font-size: 1.5rem; font-weight: 300; color: #1E2330; line-height: 1.25">{{ __('Notification') }}</h1>
            <p style="font-size: 0.8rem; color: rgba(30,35,48,.5); font-weight: 400">{{ __('Manage your properties and bookings') }}</p>
        </div>

        @if($unreadCount > 0)
            <flux:button wire:click="markAllRead" variant="ghost" size="sm" class="transition hover:opacity-70" style="color: #B8962E">
                {{ __('Mark all as read') }}
            </flux:button>
        @endif
    </div>

    {{-- Notifications list --}}
    <div class="rounded-xl border border-zinc-100 bg-white shadow-sm">
        <div class="border-b border-zinc-100 px-6 py-4">
            <h3 class="text-sm font-semibold text-zinc-800">
                {{ __('Recent Activity') }}
                @if($unreadCount > 0)
                    <span class="ml-2 inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-semibold text-white" style="background: #B8962E">
                        {{ $unreadCount }} {{ __('new') }}
                    </span>
                @endif
            </h3>
        </div>

        @if($notifications->isEmpty())
            <div class="flex flex-col items-center justify-center py-20 text-center">
                <div class="flex size-14 items-center justify-center rounded-2xl bg-zinc-100">
                    <flux:icon.bell class="size-7 text-zinc-400" variant="outline" />
                </div>
                <p class="mt-4 text-sm font-semibold text-zinc-600">{{ __('No notifications yet') }}</p>
                <p class="mt-1 text-xs text-zinc-400">{{ __('Activity from your properties will appear here.') }}</p>
            </div>
        @else
            <div class="divide-y divide-zinc-50">
                @foreach($notifications as $notification)
                    @php
                        $data    = $notification->data;
                        $isRead  = ! is_null($notification->read_at);
                        $type     = $data['type'] ?? 'info';
                        $icon     = $data['icon'] ?? 'bell';
                        $color    = $data['color'] ?? 'zinc';
                        $title    = $data['title'] ?? 'Notification';
                        $subtitle = $data['subtitle'] ?? '';
                        $linkUrl  = $data['link_url'] ?? '';
                        $linkLabel = $data['link_label'] ?? '';

                        $iconBg = match($color) {
                            'green' => 'bg-green-100',
                            'blue'  => 'bg-blue-100',
                            'amber' => 'bg-[#F4EFE8]',
                            'red'   => 'bg-red-100',
                            default => 'bg-zinc-100',
                        };
                        $iconColor = match($color) {
                            'green' => 'text-green-600',
                            'blue'  => 'text-blue-600',
                            'amber' => 'text-[#B8962E]',
                            'red'   => 'text-red-600',
                            default => 'text-zinc-500',
                        };
                    @endphp
                    <div
                        class="flex items-start gap-4 px-6 py-4 transition-colors hover:bg-zinc-50/60 {{ $isRead ? 'opacity-70' : '' }}"
                        wire:key="notif-{{ $notification->id }}"
                    >
                        {{-- Icon --}}
                        <div class="flex size-9 shrink-0 items-center justify-center rounded-full {{ $iconBg }}">
                            <flux:icon
                                :name="$icon"
                                class="size-4.5 {{ $iconColor }}"
                                variant="solid"
                            />
                        </div>

                        {{-- Content --}}
                        <div class="min-w-0 flex-1">
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <p class="text-sm font-semibold text-zinc-800 {{ $isRead ? 'font-medium' : '' }}">
                                        {{ $title }}
                                        @if(! $isRead)
                                            <span class="ml-1.5 inline-block size-1.5 rounded-full align-middle" style="background: #B8962E"></span>
                                        @endif
                                    </p>
                                    @if($subtitle)
                                        <p class="mt-0.5 text-xs text-zinc-500">{{ $subtitle }}</p>
                                    @endif
                                    @if($linkUrl)
                                        <a href="{{ $linkUrl }}" wire:navigate
                                            class="mt-2 inline-flex items-center gap-1 rounded-lg px-2.5 py-1 text-xs font-semibold transition hover:opacity-80" style="background: #F4EFE8; color: #B8962E">
                                            {{ $linkLabel ?: __('View Details') }}
                                            <svg class="size-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
                                        </a>
                                    @endif
                                </div>
                                <div class="flex shrink-0 items-center gap-2">
                                    <span class="text-xs text-zinc-400 whitespace-nowrap">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </span>
                                    <div class="flex items-center gap-1">
                                        @if(! $isRead)
                                            <button
                                                wire:click="markRead('{{ $notification->id }}')"
                                                class="rounded p-1 text-zinc-300 transition" style="" onmouseenter="this.style.color='#B8962E'" onmouseleave="this.style.color=''"
                                                title="{{ __('Mark as read') }}"
                                            >
                                                <flux:icon.check class="size-3.5" />
                                            </button>
                                        @endif
                                        <button
                                            wire:click="deleteNotification('{{ $notification->id }}')"
                                            wire:confirm="{{ __('Delete this notification?') }}"
                                            class="rounded p-1 text-zinc-300 transition hover:text-red-400"
                                        >
                                            <flux:icon.x-mark class="size-3.5" />
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($notifications->hasPages())
                <div class="border-t border-zinc-100 px-6 py-3">
                    {{ $notifications->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
