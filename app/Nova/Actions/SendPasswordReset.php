<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Password;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Http\Requests\NovaRequest;

class SendPasswordReset extends Action
{
    use InteractsWithQueue;
    use Queueable;

    /**
     * The displayable name of the action.
     *
     * @var string
     */
    public $name = 'Send Password Reset Email';

    /**
     * Perform the action on the given models.
     *
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        $sentCount = 0;
        $failedCount = 0;

        foreach ($models as $user) {
            // Send password reset notification
            $status = Password::sendResetLink(['email' => $user->email]);

            if ($status === Password::RESET_LINK_SENT) {
                $sentCount++;
                
                // Log the password reset request
                $user->auditLog('password_reset_sent', [
                    'email' => $user->email,
                ]);
            } else {
                $failedCount++;
            }
        }

        if ($failedCount > 0) {
            return Action::danger("Sent {$sentCount} password reset emails, but {$failedCount} failed.");
        }

        return Action::message("Successfully sent password reset email to {$sentCount} " . str('user')->plural($sentCount));
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
