<div class="flex flex-col rounded-xl border border-zinc-100 bg-white shadow-sm">
    <div class="flex items-center justify-between px-5 pt-5 pb-4">
        <flux:skeleton class="h-5 w-40" />
        <flux:skeleton class="h-4 w-14" />
    </div>
    <div class="divide-y divide-zinc-100 px-5 pb-4">
        @foreach (range(1, 3) as $i)
            <div class="flex items-start gap-3 py-4">
                <flux:skeleton class="size-9 shrink-0 rounded-full" />
                <div class="flex-1 space-y-2">
                    <flux:skeleton class="h-4 w-32" />
                    <flux:skeleton class="h-3 w-28" />
                    <flux:skeleton class="h-3 w-24" />
                </div>
                <flux:skeleton class="h-5 w-20 shrink-0 rounded-full" />
            </div>
        @endforeach
    </div>
</div>
