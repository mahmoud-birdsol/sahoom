<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Http\Requests\NovaRequest;

class ToggleFeaturedProperty extends Action
{
    use InteractsWithQueue;
    use Queueable;

    /**
     * The displayable name of the action.
     *
     * @var string
     */
    public $name = 'Toggle Featured';

    /**
     * Perform the action on the given models.
     *
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        foreach ($models as $property) {
            $wasFeatured = $property->is_featured;
            $property->update([
                'is_featured' => !$wasFeatured,
            ]);

            Log::info('Property featured status toggled', [
                'property_id' => $property->id,
                'property_title' => $property->title,
                'was_featured' => $wasFeatured,
                'now_featured' => !$wasFeatured,
                'user_id' => auth()->id(),
                'user_name' => auth()->user()?->name,
                'timestamp' => now(),
            ]);
        }

        return Action::message('Featured status toggled successfully!');
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
