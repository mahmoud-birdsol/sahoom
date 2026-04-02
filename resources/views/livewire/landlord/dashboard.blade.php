<div class="flex flex-col gap-6 p-6">
    <x-landlord.dashboard-header />

    <livewire:landlord.dashboard-metrics />

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <livewire:landlord.properties />
        <livewire:landlord.upcoming-bookings />
    </div>

    <livewire:landlord.recent-activity />
</div>
