<?php

namespace App\Nova\Filters;

use App\Models\States\AvailabilityBlockStatus as StatusEnum;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Laravel\Nova\Filters\Filter;
use Laravel\Nova\Http\Requests\NovaRequest;

class AvailabilityBlockStatus extends Filter
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
    public $name = 'Block Status';

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
            StatusEnum::OCCUPIED->label() => StatusEnum::OCCUPIED->value,
            StatusEnum::RESERVED->label() => StatusEnum::RESERVED->value,
            StatusEnum::MAINTENANCE->label() => StatusEnum::MAINTENANCE->value,
            StatusEnum::AVAILABLE_OVERRIDE->label() => StatusEnum::AVAILABLE_OVERRIDE->value,
        ];
    }
}
