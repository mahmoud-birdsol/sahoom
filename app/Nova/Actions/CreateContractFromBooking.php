<?php

namespace App\Nova\Actions;

use App\Models\Contract;
use App\Models\States\ContractStatus;
use App\Models\States\PaymentStatus;
use App\Models\States\PricingType;
use App\Models\ViewingRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Heading;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class CreateContractFromBooking extends Action
{
    use InteractsWithQueue;
    use Queueable;

    /**
     * The displayable name of the action.
     *
     * @var string
     */
    public $name = 'Create Contract';

    /**
     * Perform the action on the given models.
     *
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models): mixed
    {
        if ($models->count() > 1) {
            return Action::danger('Please select a single booking request to create a contract from.');
        }

        /** @var ViewingRequest $viewingRequest */
        $viewingRequest = $models->first();
        $property = $viewingRequest->property;

        if (! $property) {
            return Action::danger('Property not found for this booking request.');
        }

        $contract = Contract::create([
            'property_id'     => $property->id,
            'landlord_id'     => $property->landlord_id,
            'renter_name'     => $fields->get('renter_name'),
            'renter_company'  => $fields->get('renter_company'),
            'renter_email'    => $fields->get('renter_email'),
            'renter_phone'    => $fields->get('renter_phone'),
            'start_date'      => $fields->get('start_date'),
            'end_date'        => $fields->get('end_date'),
            'pricing_type'    => $fields->get('pricing_type'),
            'monthly_rent'    => $fields->get('monthly_rent'),
            'weekly_rent'     => $fields->get('weekly_rent'),
            'yearly_rent'     => $fields->get('yearly_rent'),
            'daily_rent'      => $fields->get('daily_rent'),
            'security_deposit' => $fields->get('security_deposit'),
            'total_value'     => $fields->get('total_value'),
            'currency'        => $fields->get('currency'),
            'contract_status' => ContractStatus::ACTIVE->value,
            'payment_status'  => PaymentStatus::NOT_COLLECTED->value,
            'notes_internal'  => $fields->get('notes_internal'),
        ]);

        $viewingRequest->close();

        return Action::redirect("/nova/resources/contracts/{$contract->id}");
    }

    /**
     * Get the fields available on the action.
     *
     * @return array<int, \Laravel\Nova\Fields\Field>
     */
    public function fields(NovaRequest $request): array
    {
        $resourceIds = $request->resources ? explode(',', $request->resources) : [];
        $viewingRequest = ViewingRequest::with('property')->find($resourceIds[0] ?? null);
        $property = $viewingRequest?->property;

        return [
            // ── Renter Information ───────────────────────────────────────────
            Heading::make('Renter Information'),

            Text::make('Renter Name', 'renter_name')
                ->default($viewingRequest?->renter_name ?? '')
                ->rules('required', 'max:255'),

            Text::make('Renter Email', 'renter_email')
                ->default($viewingRequest?->renter_email ?? '')
                ->rules('nullable', 'email', 'max:255'),

            Text::make('Renter Phone', 'renter_phone')
                ->default($viewingRequest?->renter_phone ?? '')
                ->rules('nullable', 'max:50'),

            Text::make('Renter Company', 'renter_company')
                ->nullable()
                ->rules('nullable', 'max:255'),

            // ── Contract Period ──────────────────────────────────────────────
            Heading::make('Contract Period'),

            Date::make('Start Date', 'start_date')
                ->default(
                    $viewingRequest?->start_date?->toDateString()
                    ?? $viewingRequest?->preferred_date?->toDateString()
                    ?? now()->toDateString()
                )
                ->rules('required', 'date', 'before_or_equal:end_date'),

            Date::make('End Date', 'end_date')
                ->default($viewingRequest?->end_date?->toDateString())
                ->rules('required', 'date', 'after_or_equal:start_date'),

            // ── Pricing ──────────────────────────────────────────────────────
            Heading::make('Pricing'),

            Select::make('Pricing Type', 'pricing_type')
                ->options([
                    PricingType::MONTHLY->value => PricingType::MONTHLY->label(),
                    PricingType::WEEKLY->value  => PricingType::WEEKLY->label(),
                    PricingType::YEARLY->value  => PricingType::YEARLY->label(),
                    PricingType::DAILY->value   => PricingType::DAILY->label(),
                ])
                ->displayUsingLabels()
                ->default($property?->pricing_type?->value ?? PricingType::MONTHLY->value)
                ->rules('required'),

            Select::make('Currency', 'currency')
                ->options([
                    'USD' => 'USD ($)',
                    'EUR' => 'EUR (€)',
                    'GBP' => 'GBP (£)',
                    'SAR' => 'SAR (﷼)',
                    'AED' => 'AED (د.إ)',
                    'CFA' => 'CFA (Fr)',
                ])
                ->displayUsingLabels()
                ->default($property?->currency ?? 'USD')
                ->rules('required'),

            Number::make('Monthly Rent', 'monthly_rent')
                ->default($property?->monthly_rent)
                ->nullable()
                ->step(0.01)
                ->rules('nullable', 'numeric', 'min:0'),

            Number::make('Weekly Rent', 'weekly_rent')
                ->default($property?->weekly_rent)
                ->nullable()
                ->step(0.01)
                ->rules('nullable', 'numeric', 'min:0'),

            Number::make('Yearly Rent', 'yearly_rent')
                ->default($property?->yearly_rent)
                ->nullable()
                ->step(0.01)
                ->rules('nullable', 'numeric', 'min:0'),

            Number::make('Daily Rent', 'daily_rent')
                ->default($property?->daily_rent)
                ->nullable()
                ->step(0.01)
                ->rules('nullable', 'numeric', 'min:0'),

            // ── Contract Summary ─────────────────────────────────────────────
            Heading::make('Contract Summary'),

            Number::make('Security Deposit', 'security_deposit')
                ->default($property?->security_deposit)
                ->nullable()
                ->step(0.01)
                ->rules('nullable', 'numeric', 'min:0'),

            Number::make('Total Value', 'total_value')
                ->rules('required', 'numeric', 'min:0')
                ->step(0.01)
                ->help('Total contract value (rent × duration + fees)'),

            Textarea::make('Internal Notes', 'notes_internal')
                ->nullable(),
        ];
    }
}
