<?php

namespace App\Nova;

use App\Models\States\UserRole;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\MergeValue;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Auth\PasswordValidationRules;
use Laravel\Nova\Card;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Fields\Gravatar;
use Laravel\Nova\Fields\HasOne;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\MorphToMany;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Filters\Filter;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Lenses\Lens;
use Laravel\Nova\Panel;
use Laravel\Nova\ResourceTool;
use Sereny\NovaPermissions\Nova\Permission;
use Sereny\NovaPermissions\Nova\Role;

class User extends Resource
{
    use PasswordValidationRules;

    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\User>
     */
    public static string $model = \App\Models\User::class;

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
        'id', 'name', 'email', 'phone',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @return array<int, Field|Panel|ResourceTool|MergeValue>
     */
    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),

            Gravatar::make()->maxWidth(50)->onlyOnDetail(),

            // Index columns: name, email, role, is_active, last_login_at
            Text::make('Name')
                ->sortable()
                ->filterable()
                ->rules('required', 'max:255'),

            Text::make('Email')
                ->sortable()
                ->filterable()
                ->rules('required', 'email', 'max:254')
                ->creationRules('unique:users,email')
                ->updateRules('unique:users,email,{{resourceId}}'),

            Text::make('Phone')
                ->sortable()
                ->filterable()
                ->nullable()
                ->hideFromIndex()
                ->rules('nullable', 'max:20'),

            Password::make('Password')
                ->onlyOnForms()
                ->creationRules($this->passwordRules())
                ->updateRules($this->optionalPasswordRules()),

            Select::make('Role')
                ->options([
                    UserRole::LANDLORD->value => UserRole::LANDLORD->label(),
                    UserRole::ADMIN->value => UserRole::ADMIN->label(),
                    UserRole::SUPER_ADMIN->value => UserRole::SUPER_ADMIN->label(),
                ])
                ->displayUsingLabels()
                ->sortable()
                ->filterable()
                ->default(UserRole::LANDLORD->value)
                ->rules('required')
                ->readonly(fn () => !auth()->user()?->isSuperAdmin())
                ->help(fn () => auth()->user()?->isSuperAdmin() ? 'Only super admins can change roles' : 'Contact super admin to change role')
                ->hideFromIndex(),

            Badge::make('Role')
                ->map([
                    UserRole::LANDLORD->value => UserRole::LANDLORD->color(),
                    UserRole::ADMIN->value => UserRole::ADMIN->color(),
                    UserRole::SUPER_ADMIN->value => UserRole::SUPER_ADMIN->color(),
                ])
                ->labels([
                    UserRole::LANDLORD->value => UserRole::LANDLORD->label(),
                    UserRole::ADMIN->value => UserRole::ADMIN->label(),
                    UserRole::SUPER_ADMIN->value => UserRole::SUPER_ADMIN->label(),
                ])
                ->onlyOnIndex(),

            Boolean::make('Active', 'is_active')
                ->sortable()
                ->filterable()
                ->help('Deactivated users cannot log in'),

            DateTime::make('Last Login', 'last_login_at')
                ->sortable()
                ->filterable()
                ->nullable()
                ->exceptOnForms()
                ->displayUsing(fn ($value) => $value ? $value->diffForHumans() : 'Never'),

            // Landlord relationship (only for users with landlord role)
            HasOne::make('Landlord Profile', 'landlord', Landlord::class),

            // Spatie permissions
            MorphToMany::make('Roles', 'roles', Role::class),
            MorphToMany::make('Permissions', 'permissions', Permission::class),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @return array<int, Card>
     */
    public function cards(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @return array<int, Filter>
     */
    public function filters(NovaRequest $request): array
    {
        return [
            new Filters\InactiveUsers,
            new Filters\UserRole,
            new Filters\UserActiveStatus,
        ];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @return array<int, Lens>
     */
    public function lenses(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @return array<int, Action>
     */
    public function actions(NovaRequest $request): array
    {
        return [
            new Actions\ActivateUser,
            new Actions\DeactivateUser,
            new Actions\SendPasswordReset,
            new Actions\ChangeRole,
        ];
    }
}
