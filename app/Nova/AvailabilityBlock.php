<?php

namespace App\Nova;

use App\Models\States\AvailabilityBlockSource;
use App\Models\States\AvailabilityBlockStatus;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class AvailabilityBlock extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\AvailabilityBlock>
     */
    public static string $model = \App\Models\AvailabilityBlock::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'contract_reference',
        'notes',
    ];

    /**
     * Default ordering for the resource index.
     *
     * @var array
     */
    public static $indexDefaultOrder = [
        'start_date' => 'asc',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @return array<int, \Laravel\Nova\Fields\Field>
     */
    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),

            BelongsTo::make('Property')
                ->sortable()
                ->filterable()
                ->required()
                ->showCreateRelationButton(),

            Date::make('Start Date')
                ->sortable()
                ->filterable()
                ->rules('required', 'date', 'before_or_equal:end_date')
                ->help('The first day of the availability block'),

            Date::make('End Date')
                ->sortable()
                ->filterable()
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
                ->sortable()
                ->filterable()
                ->rules('required')
                ->default(AvailabilityBlockStatus::OCCUPIED->value)
                ->help('The availability status for this period')
                ->hideFromIndex(),

            Badge::make('Status')
                ->map([
                    AvailabilityBlockStatus::OCCUPIED->value => AvailabilityBlockStatus::OCCUPIED->color(),
                    AvailabilityBlockStatus::RESERVED->value => AvailabilityBlockStatus::RESERVED->color(),
                    AvailabilityBlockStatus::MAINTENANCE->value => AvailabilityBlockStatus::MAINTENANCE->color(),
                    AvailabilityBlockStatus::AVAILABLE_OVERRIDE->value => AvailabilityBlockStatus::AVAILABLE_OVERRIDE->color(),
                ])
                ->labels([
                    AvailabilityBlockStatus::OCCUPIED->value => AvailabilityBlockStatus::OCCUPIED->label(),
                    AvailabilityBlockStatus::RESERVED->value => AvailabilityBlockStatus::RESERVED->label(),
                    AvailabilityBlockStatus::MAINTENANCE->value => AvailabilityBlockStatus::MAINTENANCE->label(),
                    AvailabilityBlockStatus::AVAILABLE_OVERRIDE->value => AvailabilityBlockStatus::AVAILABLE_OVERRIDE->label(),
                ])
                ->onlyOnIndex(),

            Select::make('Source')
                ->options([
                    AvailabilityBlockSource::PLATFORM->value => AvailabilityBlockSource::PLATFORM->label(),
                    AvailabilityBlockSource::OFFLINE->value => AvailabilityBlockSource::OFFLINE->label(),
                    AvailabilityBlockSource::LANDLORD->value => AvailabilityBlockSource::LANDLORD->label(),
                    AvailabilityBlockSource::ADMIN->value => AvailabilityBlockSource::ADMIN->label(),
                    AvailabilityBlockSource::SYSTEM->value => AvailabilityBlockSource::SYSTEM->label(),
                ])
                ->displayUsingLabels()
                ->sortable()
                ->filterable()
                ->default(AvailabilityBlockSource::ADMIN->value)
                ->rules('required')
                ->help('The origin of this availability block'),

            Text::make('Contract Reference')
                ->sortable()
                ->filterable()
                ->nullable()
                ->hideFromIndex()
                ->help('Reference number for platform/offline/landlord contract'),

            Textarea::make('Notes')
                ->nullable()
                ->hideFromIndex()
                ->help('Additional notes about this availability block'),
        ];
    }

    /**
     * Get the cards available for the resource.
     *
     * @return array<int, \Laravel\Nova\Card>
     */
    public function cards(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @return array<int, \Laravel\Nova\Filters\Filter>
     */
    public function filters(NovaRequest $request): array
    {
        return [
            new Filters\AvailabilityBlockStatus,
            new Filters\AvailabilityBlockSource,
            new Filters\AvailabilityStartDate,
            new Filters\AvailabilityEndDate,
        ];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @return array<int, \Laravel\Nova\Lenses\Lens>
     */
    public function lenses(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @return array<int, \Laravel\Nova\Actions\Action>
     */
    public function actions(NovaRequest $request): array
    {
        return [
            new Actions\RemoveAvailabilityBlock,
        ];
    }
}
