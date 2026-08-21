<?php

namespace App\Policies;

use App\Policies\Concerns\AuthorizesAdminResource;

class WeeklyProgramPolicy
{
    use AuthorizesAdminResource;

    protected string $permissionPrefix = 'events';

    protected string $managePermission = 'events.manage';
}
