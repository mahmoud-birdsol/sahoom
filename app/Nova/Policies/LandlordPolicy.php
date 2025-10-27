<?php

namespace App\Nova\Policies;

use App\Models\User;
use App\Nova\Resource;
use Illuminate\Auth\Access\Response;
use Sereny\NovaPermissions\Policies\BasePolicy;

class LandlordPolicy extends BasePolicy
{
    protected $key = 'landlord';
}
