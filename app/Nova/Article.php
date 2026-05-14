<?php

namespace App\Nova;

use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Slug;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Http\Requests\NovaRequest;

class Article extends Resource
{
    public static string $model = \App\Models\Article::class;

    public static $title = 'title';

    public static $search = ['id', 'title', 'slug', 'category', 'author'];

    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),

            Text::make(__('Title'), 'title')
                ->rules('required', 'max:255')
                ->sortable(),

            Slug::make(__('Slug'), 'slug')
                ->from('title')
                ->rules('required', 'unique:articles,slug,{{resourceId}}'),

            Text::make(__('Category'), 'category')
                ->rules('required', 'max:100')
                ->sortable(),

            Text::make(__('Author'), 'author')
                ->nullable()
                ->hideFromIndex(),

            Text::make(__('Cover Image URL'), 'cover_image_url')
                ->nullable()
                ->hideFromIndex(),

            Textarea::make(__('Excerpt'), 'excerpt')
                ->rules('required')
                ->hideFromIndex(),

            Trix::make(__('Body'), 'body')
                ->rules('required')
                ->hideFromIndex(),

            Boolean::make(__('Published'), 'is_published')
                ->sortable(),

            DateTime::make(__('Published At'), 'published_at')
                ->nullable()
                ->sortable(),
        ];
    }

    public function cards(NovaRequest $request): array
    {
        return [];
    }

    public function filters(NovaRequest $request): array
    {
        return [];
    }

    public function lenses(NovaRequest $request): array
    {
        return [];
    }

    public function actions(NovaRequest $request): array
    {
        return [];
    }
}
