<?php

namespace App\Nova\Actions;

use App\Models\States\PropertyStatus;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Http\Requests\NovaRequest;

class ApproveProperty extends Action
{
    use InteractsWithQueue;
    use Queueable;

    /**
     * The displayable name of the action.
     *
     * @var string
     */
    public $name = 'Approve Property';

    /**
     * Perform the action on the given models.
     *
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        foreach ($models as $property) {
            $property->update([
                'status' => PropertyStatus::APPROVED->value,
                'rejection_reason' => null, // Clear rejection reason if any
            ]);

            Log::info('Property approved', [
                'property_id' => $property->id,
                'property_title' => $property->title,
                'user_id' => auth()->id(),
                'user_name' => auth()->user()?->name,
                'timestamp' => now(),
            ]);
        }

        return Action::message('Property(ies) approved successfully!');
    }

    /**
     * Get the fields available on the action.
     *
     * @return array<int, \Laravel\Nova\Fields\Field>
     */
    public function fields(NovaRequest $request): array
    {
        return [];
    }
}
