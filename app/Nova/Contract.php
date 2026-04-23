<?php

namespace App\Nova;

use App\Models\States\ContractStatus;
use App\Models\States\PaymentStatus;
use App\Models\States\PricingType;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Heading;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;

class Contract extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Contract>
     */
    public static string $model = \App\Models\Contract::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'renter_name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'renter_name',
        'renter_company',
    ];

    /**
     * Default ordering for the resource index.
     *
     * @var array
     */
    public static $indexDefaultOrder = [
        'start_date' => 'desc',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @return array<int, \Laravel\Nova\Fields\Field>
     */
    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),

            // ── Property & Landlord ──────────────────────────────────────────
            Panel::make(__('Property'), [
                BelongsTo::make(__('Property'), 'property', Property::class)
                    ->sortable()
                    ->filterable()
                    ->required()
                    ->showCreateRelationButton(),

                BelongsTo::make(__('Landlord'), 'landlord', Landlord::class)
                    ->sortable()
                    ->filterable()
                    ->hideWhenCreating()
                    ->help(__('Auto-populated from selected property')),
            ]),

            // ── Renter Information ───────────────────────────────────────────
            Panel::make(__('Renter Information'), [
                Text::make(__('Renter Name'), 'renter_name')
                    ->sortable()
                    ->filterable()
                    ->rules('required', 'max:255'),

                Text::make(__('Renter Company'), 'renter_company')
                    ->nullable()
                    ->hideFromIndex()
                    ->rules('nullable', 'max:255'),

                Text::make(__('Renter Email'), 'renter_email')
                    ->nullable()
                    ->hideFromIndex()
                    ->rules('nullable', 'email', 'max:255'),

                Text::make(__('Renter Phone'), 'renter_phone')
                    ->nullable()
                    ->hideFromIndex()
                    ->rules('nullable', 'max:50'),
            ]),

            // ── Contract Period ──────────────────────────────────────────────
            Panel::make(__('Contract Period'), [
                Date::make(__('Start Date'), 'start_date')
                    ->sortable()
                    ->filterable()
                    ->rules('required', 'date', 'before_or_equal:end_date'),

                Date::make(__('End Date'), 'end_date')
                    ->sortable()
                    ->filterable()
                    ->rules('required', 'date', 'after_or_equal:start_date'),

                Text::make(__('Duration'), function () {
                    return "{$this->duration_in_months} months ({$this->duration_in_days} days)";
                })->onlyOnDetail(),
            ]),

            // ── Pricing ──────────────────────────────────────────────────────
            Panel::make(__('Pricing'), [
                Select::make(__('Pricing Type'), 'pricing_type')
                    ->options([
                        PricingType::MONTHLY->value => PricingType::MONTHLY->label(),
                        PricingType::WEEKLY->value => PricingType::WEEKLY->label(),
                        PricingType::YEARLY->value => PricingType::YEARLY->label(),
                        PricingType::DAILY->value => PricingType::DAILY->label(),
                    ])
                    ->displayUsingLabels()
                    ->sortable()
                    ->filterable()
                    ->default(PricingType::MONTHLY->value)
                    ->rules('required'),

                Select::make(__('Currency'), 'currency')
                    ->options([
                        'USD' => 'USD ($)',
                        'EUR' => 'EUR (€)',
                        'GBP' => 'GBP (£)',
                        'SAR' => 'SAR (﷼)',
                        'AED' => 'AED (د.إ)',
                        'CFA' => 'CFA (Fr)',
                    ])
                    ->displayUsingLabels()
                    ->default('USD')
                    ->rules('required')
                    ->hideFromIndex(),

                Number::make(__('Monthly Rent'), 'monthly_rent')
                    ->sortable()
                    ->nullable()
                    ->step(0.01)
                    ->rules('nullable', 'numeric', 'min:0'),

                Number::make(__('Weekly Rent'), 'weekly_rent')
                    ->nullable()
                    ->step(0.01)
                    ->hideFromIndex()
                    ->rules('nullable', 'numeric', 'min:0'),

                Number::make(__('Yearly Rent'), 'yearly_rent')
                    ->nullable()
                    ->step(0.01)
                    ->hideFromIndex()
                    ->rules('nullable', 'numeric', 'min:0'),

                Number::make(__('Daily Rent'), 'daily_rent')
                    ->nullable()
                    ->step(0.01)
                    ->hideFromIndex()
                    ->rules('nullable', 'numeric', 'min:0'),
            ]),

            // ── Contract Summary ─────────────────────────────────────────────
            Panel::make(__('Contract Summary'), [
                Number::make(__('Security Deposit'), 'security_deposit')
                    ->nullable()
                    ->step(0.01)
                    ->hideFromIndex()
                    ->rules('nullable', 'numeric', 'min:0'),

                Number::make(__('Service Fee'), 'service_fee')
                    ->nullable()
                    ->step(0.01)
                    ->hideFromIndex()
                    ->rules('nullable', 'numeric', 'min:0'),

                Number::make(__('Cleaning Fee'), 'cleaning_fee')
                    ->nullable()
                    ->step(0.01)
                    ->hideFromIndex()
                    ->rules('nullable', 'numeric', 'min:0'),

                Number::make(__('Total Value'), 'total_value')
                    ->sortable()
                    ->filterable()
                    ->rules('required', 'numeric', 'min:0')
                    ->step(0.01)
                    ->displayUsing(fn ($value) => number_format((float) $value, 2)),

                Select::make(__('Contract Status'), 'contract_status')
                    ->options([
                        ContractStatus::ACTIVE->value => ContractStatus::ACTIVE->label(),
                        ContractStatus::COMPLETED->value => ContractStatus::COMPLETED->label(),
                        ContractStatus::CANCELED->value => ContractStatus::CANCELED->label(),
                    ])
                    ->displayUsingLabels()
                    ->sortable()
                    ->filterable()
                    ->default(ContractStatus::ACTIVE->value)
                    ->rules('required')
                    ->hideFromIndex(),

                Badge::make(__('Contract Status'), 'contract_status')
                    ->map([
                        ContractStatus::ACTIVE->value => ContractStatus::ACTIVE->color(),
                        ContractStatus::COMPLETED->value => ContractStatus::COMPLETED->color(),
                        ContractStatus::CANCELED->value => ContractStatus::CANCELED->color(),
                    ])
                    ->labels([
                        ContractStatus::ACTIVE->value => ContractStatus::ACTIVE->label(),
                        ContractStatus::COMPLETED->value => ContractStatus::COMPLETED->label(),
                        ContractStatus::CANCELED->value => ContractStatus::CANCELED->label(),
                    ])
                    ->onlyOnIndex(),

                Select::make(__('Payment Status'), 'payment_status')
                    ->options([
                        PaymentStatus::NOT_COLLECTED->value => PaymentStatus::NOT_COLLECTED->label(),
                        PaymentStatus::PARTIALLY_COLLECTED->value => PaymentStatus::PARTIALLY_COLLECTED->label(),
                        PaymentStatus::PAID->value => PaymentStatus::PAID->label(),
                        PaymentStatus::REFUNDED->value => PaymentStatus::REFUNDED->label(),
                    ])
                    ->displayUsingLabels()
                    ->sortable()
                    ->filterable()
                    ->default(PaymentStatus::NOT_COLLECTED->value)
                    ->rules('required')
                    ->hideFromIndex(),

                Badge::make(__('Payment Status'), 'payment_status')
                    ->map([
                        PaymentStatus::NOT_COLLECTED->value => PaymentStatus::NOT_COLLECTED->color(),
                        PaymentStatus::PARTIALLY_COLLECTED->value => PaymentStatus::PARTIALLY_COLLECTED->color(),
                        PaymentStatus::PAID->value => PaymentStatus::PAID->color(),
                        PaymentStatus::REFUNDED->value => PaymentStatus::REFUNDED->color(),
                    ])
                    ->labels([
                        PaymentStatus::NOT_COLLECTED->value => PaymentStatus::NOT_COLLECTED->label(),
                        PaymentStatus::PARTIALLY_COLLECTED->value => PaymentStatus::PARTIALLY_COLLECTED->label(),
                        PaymentStatus::PAID->value => PaymentStatus::PAID->label(),
                        PaymentStatus::REFUNDED->value => PaymentStatus::REFUNDED->label(),
                    ])
                    ->onlyOnIndex(),

                Textarea::make(__('Internal Notes'), 'notes_internal')
                    ->nullable()
                    ->hideFromIndex(),
            ]),

            // ── Related Records ──────────────────────────────────────────────
            HasMany::make(__('Availability Blocks'), 'availabilityBlocks', AvailabilityBlock::class),
        ];
    }

    /**
     * Get the cards available for the resource.
     *
     * @return array<int, \Laravel\Nova\Card>
     */
    public function cards(NovaRequest $request): array
    {
        return [
            new Metrics\ContractsByPricingType,
        ];
    }

    /**
     * Get the filters available for the resource.
     *
     * @return array<int, \Laravel\Nova\Filters\Filter>
     */
    public function filters(NovaRequest $request): array
    {
        return [
            new Filters\UpcomingBookings,
            new Filters\ContractStatus,
            new Filters\ContractPaymentStatus,
            new Filters\PricingTypeFilter,
            new Filters\ContractLandlord,
            new Filters\ContractProperty,
            new Filters\ContractStartDate,
            new Filters\ContractEndDate,
        ];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @return array<int, \Laravel\Nova\Lenses\Lens>
     */
    public function lenses(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @return array<int, \Laravel\Nova\Actions\Action>
     */
    public function actions(NovaRequest $request): array
    {
        return [
            new Actions\UpdateContractStatus,
        ];
    }
}
