<?php

namespace App\Nova;

use App\Models\States\LandlordKycStatus;
use App\Models\States\LandlordStatus;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Http\Requests\NovaRequest;

class Landlord extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Landlord>
     */
    public static string $model = \App\Models\Landlord::class;

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
        'id', 'company_name', 'contact_name', 'user.name', 'user.email', 'contact_phone', 'contact_email',
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

            BelongsTo::make(__('User'), 'user', User::class)
                ->searchable()
                ->display('name')
                ->rules('required'),

            Text::make(__('Company Name'), 'company_name')
                ->nullable()
                ->rules('nullable', 'string', 'max:255'),

            Text::make(__('Contact Name'), 'contact_name')
                ->nullable()
                ->rules('nullable', 'string', 'max:255'),

            Text::make(__('Contact Phone'), 'contact_phone')
                ->nullable()
                ->rules('nullable', 'string', 'max:255'),

            Text::make(__('Contact Email'), 'contact_email')
                ->nullable()
                ->rules('nullable', 'string', 'max:255'),

            Badge::make('Status')->map(
                collect(LandlordStatus::cases())->mapWithKeys(fn ($state) => [$state->value => $state->color()])->toArray()
            )->icons(
                collect(LandlordKycStatus::cases())->mapWithKeys(fn ($state) => [$state->color() => $state->icon()])->toArray()
            )->sortable()->filterable(),

            Badge::make('KYC Status', 'kyc_status')->map(
                collect(LandlordKycStatus::cases())->mapWithKeys(fn ($state) => [$state->value => $state->color()])->toArray()
            )->icons(
                collect(LandlordKycStatus::cases())->mapWithKeys(fn ($state) => [$state->color() => $state->icon()])->toArray()
            )->sortable()->filterable(),

            Trix::make(__('Verification Notes'), 'verification_notes')
                ->nullable()
                ->rules('nullable'),

            // Relations
            HasMany::make('Properties'),
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
