<?php

namespace App\Nova\Actions;

use App\Models\States\ViewingRequestStatus;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Http\Requests\NovaRequest;

class CloseLead extends Action
{
    use InteractsWithQueue;
    use Queueable;

    /**
     * The displayable name of the action.
     *
     * @var string
     */
    public $name = 'Close Lead';

    /**
     * Perform the action on the given models.
     *
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        foreach ($models as $viewingRequest) {
            $viewingRequest->update([
                'status' => ViewingRequestStatus::CLOSED->value,
            ]);

            // Audit log is automatically created by the model's boot method
        }

        $count = $models->count();
        return Action::message("Closed {$count} " . str('lead')->plural($count) . ' successfully');
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
