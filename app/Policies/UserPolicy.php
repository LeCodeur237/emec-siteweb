<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('users.view') || $user->hasPermission('users.manage');
    }

    public function view(User $user): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('users.create') || $user->hasPermission('users.manage');
    }

    public function update(User $user): bool
    {
        return $user->hasPermission('users.update') || $user->hasPermission('users.manage');
    }

    public function delete(User $user): bool
    {
        return $user->hasPermission('users.delete') || $user->hasPermission('users.manage');
    }
}
