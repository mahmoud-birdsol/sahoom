<?php

namespace App\Nova\Filters;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Laravel\Nova\Filters\BooleanFilter;
use Laravel\Nova\Http\Requests\NovaRequest;

class InactiveUsers extends BooleanFilter
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
        if ($value['inactive']) {
            $query->where('is_active', false);
        }

        if ($value['never_logged_in']) {
            $query->whereNull('last_login_at');
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
            'Inactive / Locked Users' => 'inactive',
            'Never Logged In' => 'never_logged_in',
        ];
    }
}
