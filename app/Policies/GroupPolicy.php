<?php

namespace App\Policies;

use App\Policies\Concerns\AuthorizesAdminResource;

class GroupPolicy
{
    use AuthorizesAdminResource;

    protected string $permissionPrefix = 'groups';

    protected string $managePermission = 'groups.manage';
}
