<?php

namespace App\Nova\Filters;

use App\Models\States\PaymentStatus as PaymentStatusEnum;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Laravel\Nova\Filters\Filter;
use Laravel\Nova\Http\Requests\NovaRequest;

class ContractPaymentStatus extends Filter
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
    public $name = 'Payment Status';

    /**
     * Apply the filter to the given query.
     */
    public function apply(NovaRequest $request, Builder $query, mixed $value): Builder
    {
        return $query->where('payment_status', $value);
    }

    /**
     * Get the filter's available options.
     *
     * @return array<string, string>
     */
    public function options(NovaRequest $request): array
    {
        return [
            PaymentStatusEnum::NOT_COLLECTED->label() => PaymentStatusEnum::NOT_COLLECTED->value,
            PaymentStatusEnum::PARTIALLY_COLLECTED->label() => PaymentStatusEnum::PARTIALLY_COLLECTED->value,
            PaymentStatusEnum::PAID->label() => PaymentStatusEnum::PAID->value,
            PaymentStatusEnum::REFUNDED->label() => PaymentStatusEnum::REFUNDED->value,
        ];
    }
}
