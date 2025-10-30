<?php

namespace App\Nova\Actions;

use App\Models\States\ContractStatus;
use App\Models\States\PaymentStatus;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;

class UpdateContractStatus extends Action
{
    use InteractsWithQueue;
    use Queueable;

    /**
     * The displayable name of the action.
     *
     * @var string
     */
    public $name = 'Update Contract Status';

    /**
     * Perform the action on the given models.
     *
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        $contractStatus = $fields->get('contract_status');
        $paymentStatus = $fields->get('payment_status');
        $freeAvailability = $fields->get('free_availability');

        foreach ($models as $contract) {
            // Update contract statuses
            $updates = [];
            
            if ($contractStatus) {
                $updates['contract_status'] = $contractStatus;
            }
            
            if ($paymentStatus) {
                $updates['payment_status'] = $paymentStatus;
            }

            if (!empty($updates)) {
                $contract->update($updates);
            }

            // If contract is canceled and user chose to free availability
            if ($contractStatus === ContractStatus::CANCELED->value && $freeAvailability) {
                $contract->freeAvailability();
                
                \Log::info('Availability freed for canceled contract', [
                    'contract_id' => $contract->id,
                    'property_id' => $contract->property_id,
                    'user_id' => auth()->id(),
                ]);
            }

            // Audit log is automatically created by the model's boot method
        }

        $count = $models->count();
        return Action::message("Successfully updated {$count} " . str('contract')->plural($count));
    }

    /**
     * Get the fields available on the action.
     *
     * @return array<int, \Laravel\Nova\Fields\Field>
     */
    public function fields(NovaRequest $request): array
    {
        return [
            Select::make('Contract Status')
                ->options([
                    ContractStatus::ACTIVE->value => ContractStatus::ACTIVE->label(),
                    ContractStatus::COMPLETED->value => ContractStatus::COMPLETED->label(),
                    ContractStatus::CANCELED->value => ContractStatus::CANCELED->label(),
                ])
                ->displayUsingLabels()
                ->nullable()
                ->help('Update the contract status'),

            Select::make('Payment Status')
                ->options([
                    PaymentStatus::NOT_COLLECTED->value => PaymentStatus::NOT_COLLECTED->label(),
                    PaymentStatus::PARTIALLY_COLLECTED->value => PaymentStatus::PARTIALLY_COLLECTED->label(),
                    PaymentStatus::PAID->value => PaymentStatus::PAID->label(),
                    PaymentStatus::REFUNDED->value => PaymentStatus::REFUNDED->label(),
                ])
                ->displayUsingLabels()
                ->nullable()
                ->help('Update the payment status'),

            Boolean::make('Free Availability', 'free_availability')
                ->help('If contract is canceled, check this to delete the associated availability block and free up those dates'),
        ];
    }
}
