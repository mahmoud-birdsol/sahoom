<?php

namespace App\Livewire\Landlord;

use App\Models\Contract;
use App\Models\States\ContractStatus;
use App\Models\States\ViewingRequestStatus;
use App\Models\ViewingRequest;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
class UpcomingBookings extends Component
{
    public function placeholder(): \Illuminate\Contracts\View\View
    {
        return view('livewire.landlord.upcoming-bookings-placeholder');
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        $landlord = auth()->user()->landlord;

        if (! $landlord) {
            return view('livewire.landlord.upcoming-bookings', ['bookings' => collect()]);
        }

        $propertyIds = $landlord->properties()->pluck('id');

        $contracts = Contract::whereIn('property_id', $propertyIds)
            ->where('contract_status', ContractStatus::ACTIVE->value)
            ->where('end_date', '>=', now())
            ->orderBy('start_date')
            ->with('property')
            ->take(4)
            ->get()
            ->map(fn ($contract) => (object) [
                'type' => 'contract',
                'name' => $contract->renter_name,
                'property_title' => $contract->property->title,
                'date_label' => now()->lt($contract->start_date) ? __('Move-in') : __('Move-out'),
                'date' => now()->lt($contract->start_date)
                    ? $contract->start_date->format('M d, Y')
                    : $contract->end_date->format('M d, Y'),
                'status' => 'confirmed',
                'status_label' => __('Confirmed'),
                'initials' => strtoupper(substr($contract->renter_name, 0, 1)),
                'created_at' => $contract->created_at,
            ]);

        $viewingRequests = ViewingRequest::whereIn('property_id', $propertyIds)
            ->whereIn('status', [
                ViewingRequestStatus::NEW->value,
                ViewingRequestStatus::CONTACTED->value,
            ])
            ->with('property')
            ->latest()
            ->take(4)
            ->get()
            ->map(fn ($req) => (object) [
                'type' => 'viewing',
                'name' => $req->renter_name,
                'property_title' => $req->property->title,
                'date_label' => __('Viewing'),
                'date' => $req->preferred_date?->format('M d, Y') ?? __('TBD'),
                'status' => $req->status === ViewingRequestStatus::NEW ? 'pending' : 'scheduled',
                'status_label' => $req->status === ViewingRequestStatus::NEW ? __('Pending') : __('Scheduled'),
                'initials' => strtoupper(substr($req->renter_name, 0, 1)),
                'created_at' => $req->created_at,
            ]);

        $bookings = $contracts->concat($viewingRequests)
            ->sortByDesc('created_at')
            ->take(5)
            ->values();

        return view('livewire.landlord.upcoming-bookings', compact('bookings'));
    }
}
