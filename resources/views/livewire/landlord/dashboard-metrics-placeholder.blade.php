<div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
    @foreach (range(1, 4) as $i)
        <div class="flex items-center justify-between rounded-xl border border-zinc-100 bg-white p-5 shadow-sm">
            <div class="space-y-3">
                <flux:skeleton class="h-4 w-32" />
                <flux:skeleton class="h-8 w-16" />
            </div>
            <flux:skeleton class="size-12 rounded-xl" />
        </div>
    @endforeach
</div>
