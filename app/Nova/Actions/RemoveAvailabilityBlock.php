<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Actions\DestructiveAction;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Http\Requests\NovaRequest;

class RemoveAvailabilityBlock extends DestructiveAction
{
    use InteractsWithQueue;
    use Queueable;

    /**
     * The displayable name of the action.
     *
     * @var string
     */
    public $name = 'Remove Availability Block';

    /**
     * The text to be used for the action's confirm button.
     *
     * @var string
     */
    public $confirmButtonText = 'Remove Block';

    /**
     * The text to be used for the action's confirmation text.
     *
     * @var string
     */
    public $confirmText = 'Are you sure you want to remove this availability block? This action cannot be undone.';

    /**
     * Perform the action on the given models.
     *
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        $confirm = $fields->get('confirm_deletion');

        if (!$confirm) {
            return Action::danger('You must check the confirmation box to proceed with deletion.');
        }

        $count = 0;
        foreach ($models as $block) {
            // Audit log will be automatically created by the model's boot method
            $block->delete();
            $count++;
        }

        return Action::message("Successfully removed {$count} availability " . str('block')->plural($count));
    }

    /**
     * Get the fields available on the action.
     *
     * @return array<int, \Laravel\Nova\Fields\Field>
     */
    public function fields(NovaRequest $request): array
    {
        return [
            Boolean::make('Confirm Deletion', 'confirm_deletion')
                ->rules('required', 'accepted')
                ->help('Check this box to confirm you want to permanently delete the selected availability block(s).'),
        ];
    }
}
