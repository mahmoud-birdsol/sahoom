<?php

namespace App\Nova\Filters;

use App\Models\States\UserRole as RoleEnum;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Laravel\Nova\Filters\Filter;
use Laravel\Nova\Http\Requests\NovaRequest;

class UserRole extends Filter
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
    public $name = 'Role';

    /**
     * Apply the filter to the given query.
     */
    public function apply(NovaRequest $request, Builder $query, mixed $value): Builder
    {
        return $query->where('role', $value);
    }

    /**
     * Get the filter's available options.
     *
     * @return array<string, string>
     */
    public function options(NovaRequest $request): array
    {
        return [
            RoleEnum::LANDLORD->label() => RoleEnum::LANDLORD->value,
            RoleEnum::ADMIN->label() => RoleEnum::ADMIN->value,
            RoleEnum::SUPER_ADMIN->label() => RoleEnum::SUPER_ADMIN->value,
        ];
    }
}
