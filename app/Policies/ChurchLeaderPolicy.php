<?php

namespace App\Policies;

use App\Policies\Concerns\AuthorizesAdminResource;

class ChurchLeaderPolicy
{
    use AuthorizesAdminResource;

    protected string $permissionPrefix = 'churches';

    protected string $managePermission = 'churches.manage';
}
