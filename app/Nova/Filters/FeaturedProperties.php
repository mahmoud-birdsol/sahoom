<?php

namespace App\Nova\Filters;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Laravel\Nova\Filters\BooleanFilter;
use Laravel\Nova\Http\Requests\NovaRequest;

class FeaturedProperties extends BooleanFilter
{
    /**
     * The displayable name of the filter.
     *
     * @var string
     */
    public $name = 'Featured';

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
        if ($value['featured']) {
            return $query->where('is_featured', true);
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
            'Featured Properties Only' => 'featured',
        ];
    }
}
