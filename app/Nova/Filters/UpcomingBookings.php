<?php

namespace App\Nova\Filters;

use App\Models\States\ContractStatus;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Laravel\Nova\Filters\BooleanFilter;
use Laravel\Nova\Http\Requests\NovaRequest;

class UpcomingBookings extends BooleanFilter
{
    /**
     * The displayable name of the filter.
     *
     * @var string
     */
    public $name = 'Quick Filters';

    /**
     * Apply the filter to the given query.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Contracts\Database\Eloquent\Builder  $query
     * @param  mixed  $value
     * @return \Illuminate\Contracts\Database\Eloquent\Builder
     */
    public function apply(NovaRequest $request, Builder $query, mixed $value): Builder
    {
        if ($value['upcoming']) {
            $query->where('contract_status', ContractStatus::ACTIVE->value)
                ->whereBetween('start_date', [now(), now()->addDays(14)]);
        }

        if ($value['active']) {
            $query->where('contract_status', ContractStatus::ACTIVE->value);
        }

        return $query;
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function options(NovaRequest $request): array
    {
        return [
            'Upcoming (Next 14 Days)' => 'upcoming',
            'Active Contracts' => 'active',
        ];
    }
}
