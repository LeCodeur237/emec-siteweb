<?php

namespace App\Policies;

use App\Models\User;

class MediaPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('media.view') || $user->hasPermission('media.manage');
    }

    public function view(User $user): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('media.upload') || $user->hasPermission('media.manage');
    }

    public function update(User $user): bool
    {
        return $user->hasPermission('media.update') || $user->hasPermission('media.manage');
    }

    public function delete(User $user): bool
    {
        return $user->hasPermission('media.delete') || $user->hasPermission('media.manage');
    }
}
