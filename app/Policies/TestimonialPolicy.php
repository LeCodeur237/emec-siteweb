<?php

namespace App\Policies;

use App\Policies\Concerns\AuthorizesAdminResource;

class TestimonialPolicy
{
    use AuthorizesAdminResource;

    protected string $permissionPrefix = 'dosc.actions';

    protected string $managePermission = 'dosc.manage';
}
