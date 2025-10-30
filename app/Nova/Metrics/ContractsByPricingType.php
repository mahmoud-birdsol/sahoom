<?php

namespace App\Nova\Metrics;

use App\Models\Contract;
use App\Models\States\PricingType;
use DateTimeInterface;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Partition;
use Laravel\Nova\Metrics\PartitionResult;

class ContractsByPricingType extends Partition
{
    public $name = 'Contracts by Pricing Type';

    /**
     * Calculate the value of the metric.
     */
    public function calculate(NovaRequest $request): PartitionResult
    {
        return $this->count($request, Contract::class, 'pricing_type')
            ->label(function ($value) {
                return PricingType::tryFrom($value)?->label() ?? $value;
            })
            ->colors([
                PricingType::MONTHLY->value => '#3B82F6', // Blue
                PricingType::WEEKLY->value => '#10B981',  // Green
                PricingType::YEARLY->value => '#F59E0B',  // Amber
                PricingType::DAILY->value => '#EF4444',   // Red
            ]);
    }

    /**
     * Determine the amount of time the results of the metric should be cached.
     */
    public function cacheFor(): DateTimeInterface|null
    {
        return now()->addMinutes(5);
    }

    /**
     * Get the URI key for the metric.
     */
    public function uriKey(): string
    {
        return 'contracts-by-pricing-type';
    }
}
