<?php

namespace App\Policies;

use App\Policies\Concerns\AuthorizesAdminResource;

class EventPolicy
{
    use AuthorizesAdminResource;

    protected string $permissionPrefix = 'events';

    protected string $managePermission = 'events.manage';
}
