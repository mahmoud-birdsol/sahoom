<?php

namespace App\Nova\Filters;

use App\Models\States\AvailabilityBlockSource as SourceEnum;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Laravel\Nova\Filters\Filter;
use Laravel\Nova\Http\Requests\NovaRequest;

class AvailabilityBlockSource extends Filter
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
    public $name = 'Source';

    /**
     * Apply the filter to the given query.
     */
    public function apply(NovaRequest $request, Builder $query, mixed $value): Builder
    {
        return $query->where('source', $value);
    }

    /**
     * Get the filter's available options.
     *
     * @return array<string, string>
     */
    public function options(NovaRequest $request): array
    {
        return [
            SourceEnum::PLATFORM->label() => SourceEnum::PLATFORM->value,
            SourceEnum::OFFLINE->label() => SourceEnum::OFFLINE->value,
            SourceEnum::LANDLORD->label() => SourceEnum::LANDLORD->value,
            SourceEnum::ADMIN->label() => SourceEnum::ADMIN->value,
            SourceEnum::SYSTEM->label() => SourceEnum::SYSTEM->value,
        ];
    }
}
