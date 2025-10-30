<?php

namespace App\Nova;

use AlexAzartsev\Heroicon\Heroicon;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class Amenity extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Amenity>
     */
    public static string $model = \App\Models\Amenity::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'name',
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

            Text::make('Name')
                ->sortable()
                ->filterable()
                ->rules('required', 'max:255')
                ->showOnPreview(),

            Textarea::make('Description')
                ->nullable()
                ->hideFromIndex()
                ->showOnPreview(),

            Heroicon::make('Icon')
                ->sortable()
                ->filterable()
                ->nullable()
                ->help('Select an icon from Heroicons or Font Awesome'),

            Boolean::make('Active', 'is_active')
                ->sortable()
                ->filterable()
                ->default(true),

            // Relations
            BelongsToMany::make('Properties', 'properties', Property::class),
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
        return [];
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
        return [];
    }
}
