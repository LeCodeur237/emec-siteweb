<?php

namespace App\Policies;

use App\Models\User;

class SiteSettingPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('settings.manage');
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
