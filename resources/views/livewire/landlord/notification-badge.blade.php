<span wire:key="notif-badge">
    @if($count > 0)
        <span class="flex size-5 items-center justify-center rounded-full bg-amber-500 text-[10px] font-semibold text-white">
            {{ $count > 99 ? '99+' : $count }}
        </span>
    @endif
</span>
