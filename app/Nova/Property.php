<?php

namespace App\Nova;

use App\Models\States\PropertyStatus;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Slug;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class Property extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Property>
     */
    public static string $model = \App\Models\Property::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'title';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'title',
        'slug',
        'address_line_1',
        'city',
        'state',
        'country',
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

            // Relation
            BelongsTo::make('Landlord')
                ->sortable()
                ->filterable()
                ->required(),

            // Basic Information
            Text::make('Title')
                ->sortable()
                ->filterable()
                ->rules('required', 'max:255')
                ->showOnPreview(),

            Slug::make('Slug')
                ->from('Title')
                ->separator('-')
                ->sortable()
                ->hideWhenCreating()
                ->hideWhenUpdating()
                ->showOnPreview(),

            Textarea::make('Description')
                ->rules('required')
                ->alwaysShow()
                ->showOnPreview(),

            // Status
            Select::make('Status')
                ->options([
                    PropertyStatus::DRAFT->value => 'Draft',
                    PropertyStatus::IN_REVIEW->value => 'In Review',
                    PropertyStatus::APPROVED->value => 'Approved',
                    PropertyStatus::REJECTED->value => 'Rejected',
                    PropertyStatus::SUSPENDED->value => 'Suspended',
                ])
                ->displayUsingLabels()
                ->sortable()
                ->filterable()
                ->default(PropertyStatus::DRAFT->value)
                ->rules('required')
                ->hideFromIndex(),

            Badge::make('Status')
                ->map([
                    PropertyStatus::DRAFT->value => 'info',
                    PropertyStatus::IN_REVIEW->value => 'info',
                    PropertyStatus::APPROVED->value => 'success',
                    PropertyStatus::REJECTED->value => 'danger',
                    PropertyStatus::SUSPENDED->value => 'danger',
                ])
                ->labels([
                    PropertyStatus::DRAFT->value => 'Draft',
                    PropertyStatus::IN_REVIEW->value => 'In Review',
                    PropertyStatus::APPROVED->value => 'Approved',
                    PropertyStatus::REJECTED->value => 'Rejected',
                    PropertyStatus::SUSPENDED->value => 'Suspended',
                ])
                ->onlyOnIndex(),

            Textarea::make('Rejection Reason')
                ->hideFromIndex()
                ->hideWhenCreating()
                ->dependsOn(['status'], function (Textarea $field, NovaRequest $request, $formData) {
                    if ($formData->status === PropertyStatus::REJECTED->value) {
                        $field->show()->rules('required');
                    } else {
                        $field->hide();
                    }
                }),

            // Address Fields
            Text::make('Address Line 1')
                ->sortable()
                ->filterable()
                ->hideFromIndex(),

            Text::make('Address Line 2')
                ->sortable()
                ->filterable()
                ->hideFromIndex(),

            Text::make('City')
                ->sortable()
                ->filterable(),

            Text::make('State')
                ->sortable()
                ->filterable(),

            Text::make('Postal Code')
                ->sortable()
                ->filterable()
                ->hideFromIndex(),

            Text::make('Country')
                ->sortable()
                ->filterable(),

            // Coordinates
            Number::make('Latitude')
                ->step(0.00000001)
                ->min(-90)
                ->max(90)
                ->sortable()
                ->hideFromIndex(),

            Number::make('Longitude')
                ->step(0.00000001)
                ->min(-180)
                ->max(180)
                ->sortable()
                ->hideFromIndex(),

            // Property Details
            Number::make('Size (sqm)', 'size_sqm')
                ->min(0)
                ->sortable()
                ->filterable(),

            Number::make('Traffic Score')
                ->min(0)
                ->max(10)
                ->sortable()
                ->filterable()
                ->hideFromIndex(),

            Boolean::make('Featured', 'is_featured')
                ->sortable()
                ->filterable(),

            // Relations
            BelongsToMany::make('Amenities')
                ->searchable()
                ->showCreateRelationButton()
                ->fields(function () {
                    return [
                        // You can add pivot fields here if needed
                    ];
                }),

            HasMany::make('Availability Blocks', 'availabilityBlocks', AvailabilityBlock::class),
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
            new Filters\PropertyStatus,
            new Filters\PropertyCity,
            new Filters\PropertyLandlord,
            new Filters\FeaturedProperties,
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
            new Actions\ApproveProperty,
            new Actions\RejectProperty,
            new Actions\SuspendProperty,
            new Actions\ToggleFeaturedProperty,
            new Actions\AddAvailabilityBlock,
        ];
    }
}
