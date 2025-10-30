<?php

namespace App\Nova\Filters;

use App\Models\States\PricingType;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Laravel\Nova\Filters\Filter;
use Laravel\Nova\Http\Requests\NovaRequest;

class PricingTypeFilter extends Filter
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
    public $name = 'Pricing Type';

    /**
     * Apply the filter to the given query.
     */
    public function apply(NovaRequest $request, Builder $query, mixed $value): Builder
    {
        return $query->where('pricing_type', $value);
    }

    /**
     * Get the filter's available options.
     *
     * @return array<string, string>
     */
    public function options(NovaRequest $request): array
    {
        return [
            PricingType::MONTHLY->label() => PricingType::MONTHLY->value,
            PricingType::WEEKLY->label() => PricingType::WEEKLY->value,
            PricingType::YEARLY->label() => PricingType::YEARLY->value,
            PricingType::DAILY->label() => PricingType::DAILY->value,
        ];
    }
}
