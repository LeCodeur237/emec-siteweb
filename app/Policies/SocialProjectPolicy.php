<?php

namespace App\Policies;

use App\Policies\Concerns\AuthorizesAdminResource;

class SocialProjectPolicy
{
    use AuthorizesAdminResource;

    protected string $permissionPrefix = 'dosc.projects';

    protected string $managePermission = 'dosc.manage';
}
