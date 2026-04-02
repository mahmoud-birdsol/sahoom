<div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
    <x-landlord.metric
        :label="__('Total Properties')"
        :value="$totalProperties"
        icon="building-office-2"
        color="amber"
    />

    <x-landlord.metric
        :label="__('Occupied Units')"
        :value="$occupiedUnits"
        icon="key"
        color="green"
    />

    <x-landlord.metric
        :label="__('Monthly Revenue')"
        :value="'$' . number_format($monthlyRevenue, 0)"
        icon="currency-dollar"
        color="blue"
    />

    <x-landlord.metric
        :label="__('Pending Applications')"
        :value="$pendingApplications"
        icon="document-text"
        color="orange"
    />
</div>
