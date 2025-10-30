<?php

namespace App\Nova\Filters;

use App\Models\States\PropertyStatus as PropertyStatusEnum;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Laravel\Nova\Filters\Filter;
use Laravel\Nova\Http\Requests\NovaRequest;

class PropertyStatus extends Filter
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
            'Draft' => PropertyStatusEnum::DRAFT->value,
            'In Review' => PropertyStatusEnum::IN_REVIEW->value,
            'Approved' => PropertyStatusEnum::APPROVED->value,
            'Rejected' => PropertyStatusEnum::REJECTED->value,
            'Suspended' => PropertyStatusEnum::SUSPENDED->value,
        ];
    }
}
