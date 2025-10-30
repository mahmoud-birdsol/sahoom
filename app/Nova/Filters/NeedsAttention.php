<?php

namespace App\Nova\Filters;

use App\Models\States\ViewingRequestStatus;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Laravel\Nova\Filters\BooleanFilter;
use Laravel\Nova\Http\Requests\NovaRequest;

class NeedsAttention extends BooleanFilter
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
        if ($value['needs_attention']) {
            $query->where('status', ViewingRequestStatus::NEW->value);
        }

        if ($value['unassigned']) {
            $query->whereNull('handled_by_user_id');
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
            'Needs Attention (New)' => 'needs_attention',
            'Unassigned' => 'unassigned',
        ];
    }
}
