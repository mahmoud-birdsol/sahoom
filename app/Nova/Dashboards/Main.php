<?php

namespace App\Nova\Dashboards;

use App\Nova\Metrics\InReviewPropertiesCount;
use App\Nova\Metrics\PendingLandlordsCount;
use App\Nova\Metrics\PublishedPropertiesCount;
use App\Nova\Metrics\RecentAdminActivity;
use App\Nova\Metrics\UpcomingContractsCount;
use App\Nova\Metrics\VacancyRate;
use Laravel\Nova\Dashboards\Main as Dashboard;
use Laravel\Nova\Http\Requests\NovaRequest;

class Main extends Dashboard
{
    /**
     * Get the displayable name of the dashboard.
     *
     * @return string
     */
    public function name()
    {
        return 'Super Admin Dashboard';
    }

    /**
     * Get the cards for the dashboard.
     *
     * @return array<int, \Laravel\Nova\Card>
     */
    public function cards(): array
    {
        return [
            // Row 1: Key Metrics
            (new PublishedPropertiesCount)->width('1/4'),
            (new InReviewPropertiesCount)->width('1/4'),
            (new PendingLandlordsCount)->width('1/4'),
            (new UpcomingContractsCount)->width('1/4'),
            
            // Row 2: Vacancy Rate (wider card)
            (new VacancyRate)->width('full'),
            
            // Row 3: Recent Activity Feed
            (new RecentAdminActivity)->width('full'),
        ];
    }

    /**
     * Determine if the user can view the dashboard.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return bool
     */
    public function authorizedToSee(NovaRequest $request)
    {
        // Only admin and super_admin roles can see the dashboard
        return $request->user()?->isAdmin() ?? false;
    }
}
