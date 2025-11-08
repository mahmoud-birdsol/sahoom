<?php

namespace App\Nova;

use App\Models\States\ViewingRequestStatus;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class ViewingRequest extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\ViewingRequest>
     */
    public static string $model = \App\Models\ViewingRequest::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'renter_name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'renter_name',
        'renter_email',
        'renter_phone',
        'message',
    ];

    /**
     * Default ordering for the resource index.
     *
     * @var array
     */
    public static $indexDefaultOrder = [
        'created_at' => 'desc',
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

            // Index columns: renter_name, property, status, created_at
            Text::make(__('Renter Name'), 'renter_name')
                ->sortable()
                ->filterable()
                ->rules('required', 'max:255'),

            BelongsTo::make(__('Property'), 'property', Property::class)
                ->sortable()
                ->filterable()
                ->required()
                ->showCreateRelationButton(),

            Select::make(__('Status'), 'status')
                ->options([
                    ViewingRequestStatus::NEW->value => ViewingRequestStatus::NEW->label(),
                    ViewingRequestStatus::CONTACTED->value => ViewingRequestStatus::CONTACTED->label(),
                    ViewingRequestStatus::NO_SHOW->value => ViewingRequestStatus::NO_SHOW->label(),
                    ViewingRequestStatus::CLOSED->value => ViewingRequestStatus::CLOSED->label(),
                ])
                ->displayUsingLabels()
                ->sortable()
                ->filterable()
                ->default(ViewingRequestStatus::NEW->value)
                ->rules('required')
                ->hideFromIndex(),

            Badge::make(__('Status'), 'status')
                ->map([
                    ViewingRequestStatus::NEW->value => ViewingRequestStatus::NEW->color(),
                    ViewingRequestStatus::CONTACTED->value => ViewingRequestStatus::CONTACTED->color(),
                    ViewingRequestStatus::NO_SHOW->value => ViewingRequestStatus::NO_SHOW->color(),
                    ViewingRequestStatus::CLOSED->value => ViewingRequestStatus::CLOSED->color(),
                ])
                ->labels([
                    ViewingRequestStatus::NEW->value => ViewingRequestStatus::NEW->label(),
                    ViewingRequestStatus::CONTACTED->value => ViewingRequestStatus::CONTACTED->label(),
                    ViewingRequestStatus::NO_SHOW->value => ViewingRequestStatus::NO_SHOW->label(),
                    ViewingRequestStatus::CLOSED->value => ViewingRequestStatus::CLOSED->label(),
                ])
                ->onlyOnIndex(),

            DateTime::make(__('Created At'), 'created_at')
                ->sortable()
                ->filterable()
                ->exceptOnForms(),

            // Contact Information (detail view)
            Text::make(__('Renter Email'), 'renter_email')
                ->sortable()
                ->filterable()
                ->rules('required', 'email', 'max:255')
                ->hideFromIndex(),

            Text::make(__('Renter Phone'), 'renter_phone')
                ->sortable()
                ->filterable()
                ->nullable()
                ->hideFromIndex()
                ->help(__('Optional phone number')),

            // Request Details
            Date::make(__('Preferred Date'), 'preferred_date')
                ->sortable()
                ->filterable()
                ->nullable()
                ->hideFromIndex()
                ->help(__('Preferred date for property viewing')),

            Textarea::make(__('Message'), 'message')
                ->nullable()
                ->hideFromIndex()
                ->help(__('Message from the renter')),

            // Handler Assignment
            BelongsTo::make(__('Handled By'), 'handledBy', User::class)
                ->sortable()
                ->filterable()
                ->nullable()
                ->searchable()
                ->hideFromIndex()
                ->help(__('Internal account manager handling this request')),

            DateTime::make(__('Updated At'), 'updated_at')
                ->sortable()
                ->exceptOnForms()
                ->hideFromIndex(),
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
            new Filters\NeedsAttention,
            new Filters\ViewingRequestStatus,
            new Filters\ViewingRequestProperty,
            new Filters\ViewingRequestDateRange,
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
            new Actions\MarkAsContacted,
            new Actions\MarkAsNoShow,
            new Actions\CloseLead,
            new Actions\AssignHandler,
        ];
    }
}
