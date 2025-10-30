<?php

namespace App\Nova\Filters;

use App\Models\States\ViewingRequestStatus as StatusEnum;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Laravel\Nova\Filters\Filter;
use Laravel\Nova\Http\Requests\NovaRequest;

class ViewingRequestStatus extends Filter
{
    /**
     * The filter's component.
     *
     * @var string
     */
    public $component = 'select-filter';

    /**
     * The displayable name of the filter.
     *
     * @var string
     */
    public $name = 'Status';

    /**
     * Apply the filter to the given query.
     */
    public function apply(NovaRequest $request, Builder $query, mixed $value): Builder
    {
        return $query->where('status', $value);
    }

    /**
     * Get the filter's available options.
     *
     * @return array<string, string>
     */
    public function options(NovaRequest $request): array
    {
        return [
            StatusEnum::NEW->label() => StatusEnum::NEW->value,
            StatusEnum::CONTACTED->label() => StatusEnum::CONTACTED->value,
            StatusEnum::NO_SHOW->label() => StatusEnum::NO_SHOW->value,
            StatusEnum::CLOSED->label() => StatusEnum::CLOSED->value,
        ];
    }
}
