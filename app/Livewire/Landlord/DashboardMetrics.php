<?php

namespace App\Livewire\Landlord;

use App\Models\States\ContractStatus;
use App\Models\States\ViewingRequestStatus;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
class DashboardMetrics extends Component
{
    public int $totalProperties = 0;

    public int $occupiedUnits = 0;

    public float $monthlyRevenue = 0;

    public int $pendingApplications = 0;

    public function mount(): void
    {
        $landlord = auth()->user()->landlord;

        if (! $landlord) {
            return;
        }

        $propertyIds = $landlord->properties()->pluck('id');

        $this->totalProperties = $landlord->properties()->count();

        $this->occupiedUnits = $landlord->contracts()
            ->where('contract_status', ContractStatus::ACTIVE->value)
            ->count();

        $this->monthlyRevenue = (float) $landlord->contracts()
            ->where('contract_status', ContractStatus::ACTIVE->value)
            ->sum('monthly_rent');

        $this->pendingApplications = \App\Models\ViewingRequest::whereIn('property_id', $propertyIds)
            ->where('status', ViewingRequestStatus::NEW->value)
            ->count();
    }

    public function placeholder(): \Illuminate\Contracts\View\View
    {
        return view('livewire.landlord.dashboard-metrics-placeholder');
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.landlord.dashboard-metrics');
    }
}
