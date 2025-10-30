<?php

namespace App\Nova\Filters;

use App\Models\Landlord;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Laravel\Nova\Filters\Filter;
use Laravel\Nova\Http\Requests\NovaRequest;

class ContractLandlord extends Filter
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
    public $name = 'Landlord';

    /**
     * Apply the filter to the given query.
     */
    public function apply(NovaRequest $request, Builder $query, mixed $value): Builder
    {
        return $query->where('landlord_id', $value);
    }

    /**
     * Get the filter's available options.
     *
     * @return array<string, string>
     */
    public function options(NovaRequest $request): array
    {
        return Landlord::all()
            ->mapWithKeys(function ($landlord) {
                $label = $landlord->company_name ?: $landlord->contact_name ?: "Landlord #{$landlord->id}";
                return [$label => $landlord->id];
            })
            ->toArray();
    }
}
