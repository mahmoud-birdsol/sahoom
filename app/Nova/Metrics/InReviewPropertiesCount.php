<?php

namespace App\Nova\Metrics;

use App\Models\Property;
use App\Models\States\PropertyStatus;
use DateTimeInterface;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Value;
use Laravel\Nova\Metrics\ValueResult;

class InReviewPropertiesCount extends Value
{
    public $name = 'In Review Properties';

    /**
     * Calculate the value of the metric.
     */
    public function calculate(NovaRequest $request): ValueResult
    {
        $count = Property::where('status', PropertyStatus::IN_REVIEW->value)->count();
        
        return $this->result($count)
            ->format('0,0')
            ->suffix('Need Review');
    }

    /**
     * Get the URI key for the metric.
     */
    public function uriKey(): string
    {
        return 'in-review-properties-count';
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
