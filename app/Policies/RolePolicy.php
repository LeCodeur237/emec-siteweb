<?php

namespace App\Policies;

use App\Models\User;

class RolePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('roles.manage');
    }

    public function view(User $user): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $this->viewAny($user);
    }

    public function update(User $user): bool
    {
        return $this->viewAny($user);
    }

    public function delete(User $user): bool
    {
        return $this->viewAny($user);
    }
}
