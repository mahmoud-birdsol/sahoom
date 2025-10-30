<?php

namespace App\Nova\Metrics;

use App\Models\Property;
use App\Models\States\PropertyStatus;
use DateTimeInterface;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Value;
use Laravel\Nova\Metrics\ValueResult;

class PublishedPropertiesCount extends Value
{
    public $name = 'Published Properties';

    /**
     * Calculate the value of the metric.
     */
    public function calculate(NovaRequest $request): ValueResult
    {
        $count = Property::where('status', PropertyStatus::APPROVED->value)->count();

        return $this->result($count)
            ->format('0,0')
            ->suffix('Live Properties');
    }

    /**
     * Get the URI key for the metric.
     */
    public function uriKey(): string
    {
        return 'published-properties-count';
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
