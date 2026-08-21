<?php

namespace App\Policies;

use App\Models\User;

class DonationPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('donations.view') || $user->hasPermission('donations.manage');
    }

    public function view(User $user): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('donations.manage');
    }

    public function update(User $user): bool
    {
        return $user->hasPermission('donations.manage');
    }

    public function delete(User $user): bool
    {
        return $user->hasPermission('donations.manage');
    }
}
