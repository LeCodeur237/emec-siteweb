<?php

namespace App\Policies;

use App\Policies\Concerns\AuthorizesAdminResource;

class GroupLeaderPolicy
{
    use AuthorizesAdminResource;

    protected string $permissionPrefix = 'groups';

    protected string $managePermission = 'groups.manage';
}
