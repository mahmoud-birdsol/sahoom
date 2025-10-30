<?php

namespace App\Nova\Actions;

use App\Models\States\UserRole;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;

class ChangeRole extends Action
{
    use InteractsWithQueue;
    use Queueable;

    /**
     * The displayable name of the action.
     *
     * @var string
     */
    public $name = 'Change Role';

    /**
     * Determine if the action should be available for the given request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public function authorizedToSee($request)
    {
        // Only super admins can see this action
        return $request->user()?->isSuperAdmin() ?? false;
    }

    /**
     * Perform the action on the given models.
     *
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        $newRole = $fields->get('role');

        if (!$newRole) {
            return Action::danger('Please select a role.');
        }

        // Verify current user is super admin
        if (!auth()->user()?->isSuperAdmin()) {
            return Action::danger('Only super admins can change user roles.');
        }

        foreach ($models as $user) {
            // Prevent changing own role
            if ($user->id === auth()->id()) {
                return Action::danger('You cannot change your own role.');
            }

            $oldRole = $user->role->value;
            $user->update(['role' => $newRole]);
            
            // Audit log is automatically created by the model's boot method
            \Log::info('User role changed', [
                'user_id' => $user->id,
                'old_role' => $oldRole,
                'new_role' => $newRole,
                'changed_by' => auth()->id(),
            ]);
        }

        $count = $models->count();
        return Action::message("Successfully changed role for {$count} " . str('user')->plural($count));
    }

    /**
     * Get the fields available on the action.
     *
     * @return array<int, \Laravel\Nova\Fields\Field>
     */
    public function fields(NovaRequest $request): array
    {
        return [
            Select::make('Role')
                ->options([
                    UserRole::LANDLORD->value => UserRole::LANDLORD->label(),
                    UserRole::ADMIN->value => UserRole::ADMIN->label(),
                    UserRole::SUPER_ADMIN->value => UserRole::SUPER_ADMIN->label(),
                ])
                ->displayUsingLabels()
                ->rules('required')
                ->help('Select the new role for the selected users'),
        ];
    }
}
