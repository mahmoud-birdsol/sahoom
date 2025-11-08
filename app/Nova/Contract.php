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

            // Index columns: property, landlord, renter_name, start_date, end_date, contract_status, payment_status, total_value
            BelongsTo::make(__('Property'), 'property', Property::class)
                ->sortable()
                ->filterable()
                ->required()
                ->showCreateRelationButton(),

            BelongsTo::make(__('Landlord'), 'landlord', Landlord::class)
                ->sortable()
                ->filterable()
                ->required()
                ->readonly()
                ->help(__('Auto-populated from selected property')),

            Text::make(__('Renter Name'), 'renter_name')
                ->sortable()
                ->filterable()
                ->rules('required', 'max:255'),

            Text::make(__('Renter Company'), 'renter_company')
                ->sortable()
                ->filterable()
                ->nullable()
                ->hideFromIndex()
                ->help(__('Optional company name')),

            Date::make(__('Start Date'), 'start_date')
                ->sortable()
                ->filterable()
                ->rules('required', 'date', 'before_or_equal:end_date'),

            Date::make(__('End Date'), 'end_date')
                ->sortable()
                ->filterable()
                ->rules('required', 'date', 'after_or_equal:start_date'),

            Heading::make(__('Pricing Information'))->onlyOnForms(),

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
                ->rules('required')
                ->help(__('Select the primary pricing model for this contract')),

            Currency::make(__('Monthly Rent'), 'monthly_rent')
                ->currency('USD')
                ->sortable()
                ->nullable()
                ->rules('nullable', 'numeric', 'min:0')
                ->help(__('Rent amount per month')),

            Currency::make(__('Weekly Rent'), 'weekly_rent')
                ->currency('USD')
                ->sortable()
                ->nullable()
                ->hideFromIndex()
                ->rules('nullable', 'numeric', 'min:0')
                ->help(__('Rent amount per week')),

            Currency::make(__('Yearly Rent'), 'yearly_rent')
                ->currency('USD')
                ->sortable()
                ->nullable()
                ->hideFromIndex()
                ->rules('nullable', 'numeric', 'min:0')
                ->help(__('Rent amount per year')),

            Currency::make(__('Daily Rent'), 'daily_rent')
                ->currency('USD')
                ->sortable()
                ->nullable()
                ->hideFromIndex()
                ->rules('nullable', 'numeric', 'min:0')
                ->help(__('Rent amount per day')),

            Heading::make(__('Additional Fees'))->onlyOnForms(),

            Currency::make(__('Security Deposit'), 'security_deposit')
                ->currency('USD')
                ->nullable()
                ->hideFromIndex()
                ->rules('nullable', 'numeric', 'min:0')
                ->help(__('Refundable security deposit')),

            Currency::make(__('Service Fee'), 'service_fee')
                ->currency('USD')
                ->nullable()
                ->hideFromIndex()
                ->rules('nullable', 'numeric', 'min:0')
                ->help(__('One-time service fee')),

            Currency::make(__('Cleaning Fee'), 'cleaning_fee')
                ->currency('USD')
                ->nullable()
                ->hideFromIndex()
                ->rules('nullable', 'numeric', 'min:0')
                ->help(__('One-time cleaning fee')),

            Heading::make(__('Contract Summary'))->onlyOnForms(),

            Text::make(__('Duration'), function () {
                return "{$this->duration_in_months} months ({$this->duration_in_days} days)";
            })
                ->onlyOnDetail()
                ->help(__('Contract duration')),

            Currency::make(__('Active Rent'), 'active_rent')
                ->currency('USD')
                ->onlyOnDetail()
                ->help(__('Current rent based on selected pricing type')),

            Number::make(__('Total Value'), 'total_value')
                ->sortable()
                ->filterable()
                ->rules('required', 'numeric', 'min:0')
                ->step(0.01)
                ->displayUsing(fn ($value) => number_format($value, 2))
                ->help(__('Total contract value (calculated from pricing + fees)')),

            Select::make(__('Currency'), 'currency')
                ->options([
                    'USD' => 'USD',
                    'EUR' => 'EUR',
                    'GBP' => 'GBP',
                    'SAR' => 'SAR',
                    'AED' => 'AED',
                ])
                ->displayUsingLabels()
                ->default('USD')
                ->rules('required')
                ->hideFromIndex(),

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

            Textarea::make(__('Notes Internal'), 'notes_internal')
                ->nullable()
                ->hideFromIndex()
                ->help(__('Internal notes about this contract (not visible to renter or landlord)')),

            // Show related availability blocks inline
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
