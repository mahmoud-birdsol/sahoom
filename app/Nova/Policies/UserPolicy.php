<?php

namespace App\Nova\Policies;

use App\Models\User;
use App\Nova\Resource;
use Illuminate\Auth\Access\Response;
use Sereny\NovaPermissions\Policies\BasePolicy;

class UserPolicy extends BasePolicy
{
    protected $key = 'user';
}
