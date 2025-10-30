<?php

namespace App\Nova\Actions;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;

class AssignHandler extends Action
{
    use InteractsWithQueue;
    use Queueable;

    /**
     * The displayable name of the action.
     *
     * @var string
     */
    public $name = 'Assign Handler';

    /**
     * Perform the action on the given models.
     *
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        $handlerId = $fields->get('handler_id');

        if (!$handlerId) {
            return Action::danger('Please select a handler.');
        }

        $handler = User::find($handlerId);

        if (!$handler) {
            return Action::danger('Selected user not found.');
        }

        foreach ($models as $viewingRequest) {
            $viewingRequest->update([
                'handled_by_user_id' => $handlerId,
            ]);

            // Audit log is automatically created by the model's boot method
        }

        $count = $models->count();
        return Action::message("Assigned {$count} viewing " . str('request')->plural($count) . " to {$handler->name}");
    }

    /**
     * Get the fields available on the action.
     *
     * @return array<int, \Laravel\Nova\Fields\Field>
     */
    public function fields(NovaRequest $request): array
    {
        // Get all users as potential handlers
        $users = User::all()->pluck('name', 'id')->toArray();

        return [
            Select::make('Handler', 'handler_id')
                ->options($users)
                ->displayUsingLabels()
                ->rules('required')
                ->help('Select the internal account manager to handle this request'),
        ];
    }
}
