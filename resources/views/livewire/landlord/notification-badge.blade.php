<span wire:key="notif-badge">
    @if($count > 0)
        <span class="flex size-5 items-center justify-center rounded-full text-[10px] font-semibold text-white" style="background: #B8962E">
            {{ $count > 99 ? '99+' : $count }}
        </span>
    @endif
</span>
