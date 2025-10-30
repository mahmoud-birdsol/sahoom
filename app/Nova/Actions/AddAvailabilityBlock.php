<?php

namespace App\Nova\Actions;

use App\Models\AvailabilityBlock;
use App\Models\States\AvailabilityBlockSource;
use App\Models\States\AvailabilityBlockStatus;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class AddAvailabilityBlock extends Action
{
    use InteractsWithQueue;
    use Queueable;

    /**
     * The displayable name of the action.
     *
     * @var string
     */
    public $name = 'Add Availability Block';

    /**
     * Perform the action on the given models (properties).
     *
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        $startDate = $fields->get('start_date');
        $endDate = $fields->get('end_date');
        $status = $fields->get('status');
        $source = $fields->get('source');

        foreach ($models as $property) {
            // Check for overlaps with occupied/reserved blocks
            $hasOverlap = AvailabilityBlock::hasOverlap($property->id, $startDate, $endDate);

            if ($hasOverlap && !$this->isSuperAdmin()) {
                return Action::danger("Cannot create block for '{$property->title}': Overlapping occupied/reserved dates exist. Only super admins can force overlaps.");
            }

            if ($hasOverlap && $this->isSuperAdmin()) {
                // Log the forced overlap by super admin
                Log::warning('Super admin forced overlapping availability block', [
                    'property_id' => $property->id,
                    'property_title' => $property->title,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'status' => $status,
                    'user_id' => auth()->id(),
                    'user_name' => auth()->user()?->name,
                ]);
            }

            // Create the availability block
            AvailabilityBlock::create([
                'property_id' => $property->id,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'status' => $status,
                'source' => $source,
                'contract_reference' => $fields->get('contract_reference'),
                'notes' => $fields->get('notes'),
            ]);

            // Audit log is automatically created by the model's boot method
        }

        $count = $models->count();
        return Action::message("Successfully added availability block to {$count} " . str('property')->plural($count));
    }

    /**
     * Check if the current user is a super admin.
     * Adjust this based on your actual role/permission system.
     */
    protected function isSuperAdmin(): bool
    {
        $user = auth()->user();
        
        // Check if user has a 'super_admin' role (adjust based on your implementation)
        if (method_exists($user, 'hasRole')) {
            return $user->hasRole('super_admin');
        }
        
        // Fallback: check if user has specific permission
        if (method_exists($user, 'can')) {
            return $user->can('force_availability_overlaps');
        }
        
        return false;
    }

    /**
     * Get the fields available on the action.
     *
     * @return array<int, \Laravel\Nova\Fields\Field>
     */
    public function fields(NovaRequest $request): array
    {
        return [
            Date::make('Start Date')
                ->rules('required', 'date')
                ->help('The first day of the availability block'),

            Date::make('End Date')
                ->rules('required', 'date', 'after_or_equal:start_date')
                ->help('The last day of the availability block'),

            Select::make('Status')
                ->options([
                    AvailabilityBlockStatus::OCCUPIED->value => AvailabilityBlockStatus::OCCUPIED->label(),
                    AvailabilityBlockStatus::RESERVED->value => AvailabilityBlockStatus::RESERVED->label(),
                    AvailabilityBlockStatus::MAINTENANCE->value => AvailabilityBlockStatus::MAINTENANCE->label(),
                    AvailabilityBlockStatus::AVAILABLE_OVERRIDE->value => AvailabilityBlockStatus::AVAILABLE_OVERRIDE->label(),
                ])
                ->displayUsingLabels()
                ->rules('required')
                ->default(AvailabilityBlockStatus::OCCUPIED->value)
                ->help('The availability status for this period'),

            Select::make('Source')
                ->options([
                    AvailabilityBlockSource::PLATFORM->value => AvailabilityBlockSource::PLATFORM->label(),
                    AvailabilityBlockSource::OFFLINE->value => AvailabilityBlockSource::OFFLINE->label(),
                    AvailabilityBlockSource::LANDLORD->value => AvailabilityBlockSource::LANDLORD->label(),
                    AvailabilityBlockSource::ADMIN->value => AvailabilityBlockSource::ADMIN->label(),
                ])
                ->displayUsingLabels()
                ->rules('required')
                ->default(AvailabilityBlockSource::ADMIN->value)
                ->help('The origin/source of this availability block'),

            Text::make('Contract Reference')
                ->nullable()
                ->help('Optional: Contract or booking reference number'),

            Textarea::make('Notes')
                ->nullable()
                ->help('Optional: Additional notes about this availability block'),
        ];
    }
}
