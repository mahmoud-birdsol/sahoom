<?php

namespace App\Nova\Filters;

use App\Models\States\ContractStatus as StatusEnum;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Laravel\Nova\Filters\Filter;
use Laravel\Nova\Http\Requests\NovaRequest;

class ContractStatus extends Filter
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
    public $name = 'Contract Status';

    /**
     * Apply the filter to the given query.
     */
    public function apply(NovaRequest $request, Builder $query, mixed $value): Builder
    {
        return $query->where('contract_status', $value);
    }

    /**
     * Get the filter's available options.
     *
     * @return array<string, string>
     */
    public function options(NovaRequest $request): array
    {
        return [
            StatusEnum::ACTIVE->label() => StatusEnum::ACTIVE->value,
            StatusEnum::COMPLETED->label() => StatusEnum::COMPLETED->value,
            StatusEnum::CANCELED->label() => StatusEnum::CANCELED->value,
        ];
    }
}
