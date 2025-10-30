<?php

namespace App\Nova\Filters;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Laravel\Nova\Filters\DateFilter;
use Laravel\Nova\Http\Requests\NovaRequest;

class ContractStartDate extends DateFilter
{
    /**
     * The displayable name of the filter.
     *
     * @var string
     */
    public $name = 'Start Date From';

    /**
     * Apply the filter to the given query.
     */
    public function apply(NovaRequest $request, Builder $query, mixed $value): Builder
    {
        return $query->where('start_date', '>=', $value);
    }
}
