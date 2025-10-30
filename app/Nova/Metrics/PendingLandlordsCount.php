<?php

namespace App\Nova\Metrics;

use App\Models\Landlord;
use App\Models\States\LandlordKycStatus;
use DateTimeInterface;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Value;
use Laravel\Nova\Metrics\ValueResult;
use Laravel\Nova\Nova;

class PendingLandlordsCount extends Value
{
    public $name = 'Pending Landlords (KYC)';

    /**
     * Calculate the value of the metric.
     */
    public function calculate(NovaRequest $request): ValueResult
    {
        $count = Landlord::where('kyc_status', LandlordKycStatus::PENDING->value)->count();
        
        return $this->result($count)
            ->format('0,0')
            ->suffix('Pending Review');
    }

    /**
     * Get the URI key for the metric.
     */
    public function uriKey(): string
    {
        return 'pending-landlords-count';
    }

    /**
     * Get the ranges available for the metric.
     *
     * @return array<int|string, string>
     */
    public function ranges(): array
    {
        return [];
    }

    /**
     * Determine the amount of time the results of the metric should be cached.
     */
    public function cacheFor(): DateTimeInterface|null
    {
        return now()->addMinutes(5);
    }
}
