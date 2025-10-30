<?php

namespace App\Nova\Metrics;

use App\Models\Contract;
use DateTimeInterface;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Value;
use Laravel\Nova\Metrics\ValueResult;

class UpcomingContractsCount extends Value
{
    public $name = 'Upcoming Contracts (14 Days)';

    /**
     * Calculate the value of the metric.
     */
    public function calculate(NovaRequest $request): ValueResult
    {
        $count = Contract::upcoming(14)->count();
        
        return $this->result($count)
            ->format('0,0')
            ->suffix('Starting Soon');
    }

    /**
     * Get the URI key for the metric.
     */
    public function uriKey(): string
    {
        return 'upcoming-contracts-count';
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
