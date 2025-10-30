<?php

namespace App\Nova\Actions;

use App\Models\States\PropertyStatus;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class RejectProperty extends Action
{
    use InteractsWithQueue;
    use Queueable;

    /**
     * The displayable name of the action.
     *
     * @var string
     */
    public $name = 'Reject Property';

    /**
     * Perform the action on the given models.
     *
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        $rejectionReason = $fields->get('rejection_reason');

        if (empty($rejectionReason)) {
            return Action::danger('Rejection reason is required.');
        }

        foreach ($models as $property) {
            $property->update([
                'status' => PropertyStatus::REJECTED->value,
                'rejection_reason' => $rejectionReason,
            ]);

            Log::info('Property rejected', [
                'property_id' => $property->id,
                'property_title' => $property->title,
                'rejection_reason' => $rejectionReason,
                'user_id' => auth()->id(),
                'user_name' => auth()->user()?->name,
                'timestamp' => now(),
            ]);
        }

        return Action::message('Property(ies) rejected successfully!');
    }

    /**
     * Get the fields available on the action.
     *
     * @return array<int, \Laravel\Nova\Fields\Field>
     */
    public function fields(NovaRequest $request): array
    {
        return [
            Textarea::make('Rejection Reason')
                ->rules('required')
                ->help('Please provide a reason for rejecting this property.'),
        ];
    }
}
