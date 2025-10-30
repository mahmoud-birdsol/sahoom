<?php

namespace App\Nova\Metrics;

use App\Models\AvailabilityBlock;
use App\Models\Property;
use App\Models\States\AvailabilityBlockStatus;
use App\Models\States\PropertyStatus;
use DateTimeInterface;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Value;
use Laravel\Nova\Metrics\ValueResult;

class VacancyRate extends Value
{
    public $name = 'Vacancy Rate (Next 30 Days)';

    /**
     * Calculate the value of the metric.
     */
    public function calculate(NovaRequest $request): ValueResult
    {
        $startDate = now();
        $endDate = now()->addDays(30);
        $daysInPeriod = 30;

        // Get published properties count
        $publishedPropertiesCount = Property::where('status', PropertyStatus::PUBLISHED->value)->count();

        if ($publishedPropertiesCount === 0) {
            return $this->result(0)
                ->format('0.0')
                ->suffix('% Available');
        }

        // Total possible days across all published properties
        $totalDays = $publishedPropertiesCount * $daysInPeriod;

        // Calculate occupied days from availability blocks
        $occupiedDays = AvailabilityBlock::whereIn('status', [
                AvailabilityBlockStatus::OCCUPIED->value,
                AvailabilityBlockStatus::RESERVED->value,
                AvailabilityBlockStatus::MAINTENANCE->value,
            ])
            ->whereHas('property', function ($query) {
                $query->where('status', PropertyStatus::PUBLISHED->value);
            })
            ->where(function ($query) use ($startDate, $endDate) {
                $query->where(function ($q) use ($startDate, $endDate) {
                    // Block overlaps with our period
                    $q->where('start_date', '<=', $endDate)
                      ->where('end_date', '>=', $startDate);
                });
            })
            ->get()
            ->sum(function ($block) use ($startDate, $endDate) {
                // Calculate actual overlapping days
                $blockStart = max($block->start_date, $startDate);
                $blockEnd = min($block->end_date, $endDate);
                return $blockStart->diffInDays($blockEnd) + 1;
            });

        // Calculate vacancy percentage
        $vacancyDays = $totalDays - $occupiedDays;
        $vacancyRate = ($vacancyDays / $totalDays) * 100;

        return $this->result(round($vacancyRate, 1))
            ->format('0.0')
            ->suffix('% Available');
    }

    /**
     * Get the URI key for the metric.
     */
    public function uriKey(): string
    {
        return 'vacancy-rate';
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
        return now()->addMinutes(10);
    }
}
