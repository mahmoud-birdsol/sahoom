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
            BelongsTo::make('Property')
                ->sortable()
                ->filterable()
                ->required()
                ->showCreateRelationButton(),

            BelongsTo::make('Landlord')
                ->sortable()
                ->filterable()
                ->required()
                ->readonly()
                ->help('Auto-populated from selected property'),

            Text::make('Renter Name')
                ->sortable()
                ->filterable()
                ->rules('required', 'max:255'),

            Text::make('Renter Company')
                ->sortable()
                ->filterable()
                ->nullable()
                ->hideFromIndex()
                ->help('Optional company name'),

            Date::make('Start Date')
                ->sortable()
                ->filterable()
                ->rules('required', 'date', 'before_or_equal:end_date'),

            Date::make('End Date')
                ->sortable()
                ->filterable()
                ->rules('required', 'date', 'after_or_equal:start_date'),

            Heading::make('Pricing Information')->onlyOnForms(),

            Select::make('Pricing Type')
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
                ->help('Select the primary pricing model for this contract'),

            Currency::make('Monthly Rent')
                ->currency('USD')
                ->sortable()
                ->nullable()
                ->rules('nullable', 'numeric', 'min:0')
                ->help('Rent amount per month'),

            Currency::make('Weekly Rent')
                ->currency('USD')
                ->sortable()
                ->nullable()
                ->hideFromIndex()
                ->rules('nullable', 'numeric', 'min:0')
                ->help('Rent amount per week'),

            Currency::make('Yearly Rent')
                ->currency('USD')
                ->sortable()
                ->nullable()
                ->hideFromIndex()
                ->rules('nullable', 'numeric', 'min:0')
                ->help('Rent amount per year'),

            Currency::make('Daily Rent')
                ->currency('USD')
                ->sortable()
                ->nullable()
                ->hideFromIndex()
                ->rules('nullable', 'numeric', 'min:0')
                ->help('Rent amount per day'),

            Heading::make('Additional Fees')->onlyOnForms(),

            Currency::make('Security Deposit')
                ->currency('USD')
                ->nullable()
                ->hideFromIndex()
                ->rules('nullable', 'numeric', 'min:0')
                ->help('Refundable security deposit'),

            Currency::make('Service Fee')
                ->currency('USD')
                ->nullable()
                ->hideFromIndex()
                ->rules('nullable', 'numeric', 'min:0')
                ->help('One-time service fee'),

            Currency::make('Cleaning Fee')
                ->currency('USD')
                ->nullable()
                ->hideFromIndex()
                ->rules('nullable', 'numeric', 'min:0')
                ->help('One-time cleaning fee'),

            Heading::make('Contract Summary')->onlyOnForms(),

            Text::make('Duration', function () {
                return "{$this->duration_in_months} months ({$this->duration_in_days} days)";
            })
                ->onlyOnDetail()
                ->help('Contract duration'),

            Currency::make('Active Rent', 'active_rent')
                ->currency('USD')
                ->onlyOnDetail()
                ->help('Current rent based on selected pricing type'),

            Number::make('Total Value')
                ->sortable()
                ->filterable()
                ->rules('required', 'numeric', 'min:0')
                ->step(0.01)
                ->displayUsing(fn ($value) => number_format($value, 2))
                ->help('Total contract value (calculated from pricing + fees)'),

            Select::make('Currency')
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

            Select::make('Contract Status')
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

            Badge::make('Contract Status')
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

            Select::make('Payment Status')
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

            Badge::make('Payment Status')
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

            Textarea::make('Notes Internal')
                ->nullable()
                ->hideFromIndex()
                ->help('Internal notes about this contract (not visible to renter or landlord)'),

            // Show related availability blocks inline
            HasMany::make('Availability Blocks', 'availabilityBlocks', AvailabilityBlock::class),
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
