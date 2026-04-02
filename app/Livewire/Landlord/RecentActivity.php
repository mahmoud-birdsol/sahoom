<?php

namespace App\Livewire\Landlord;

use App\Models\Contract;
use App\Models\ViewingRequest;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
class RecentActivity extends Component
{
    public function placeholder(): \Illuminate\Contracts\View\View
    {
        return view('livewire.landlord.recent-activity-placeholder');
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        $landlord = auth()->user()->landlord;

        if (! $landlord) {
            return view('livewire.landlord.recent-activity', ['activities' => collect()]);
        }

        $propertyIds = $landlord->properties()->pluck('id');

        $contractActivities = Contract::whereIn('property_id', $propertyIds)
            ->with('property')
            ->latest()
            ->take(5)
            ->get()
            ->map(fn ($c) => (object) [
                'icon_type' => 'contract',
                'title' => __('Contract signed with :name', ['name' => $c->renter_name]),
                'subtitle' => ($c->property->title ?? '') . ' — ' . $c->currency . ' ' . number_format((float) $c->total_value, 0),
                'time' => $c->created_at,
            ]);

        $viewingActivities = ViewingRequest::whereIn('property_id', $propertyIds)
            ->with('property')
            ->latest()
            ->take(5)
            ->get()
            ->map(fn ($v) => (object) [
                'icon_type' => 'viewing',
                'title' => __('Viewing request from :name', ['name' => $v->renter_name]),
                'subtitle' => $v->property->title ?? '',
                'time' => $v->created_at,
            ]);

        $activities = $contractActivities->concat($viewingActivities)
            ->sortByDesc('time')
            ->take(5)
            ->values();

        return view('livewire.landlord.recent-activity', compact('activities'));
    }
}
