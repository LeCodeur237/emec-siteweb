<?php

namespace App\Policies;

use App\Models\Preacher;
use App\Models\User;

class PreacherPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('messages.view') || $user->hasPermission('messages.manage');
    }

    public function view(User $user, Preacher $preacher): bool
    {
        return $user->hasPermission('messages.view') || $user->hasPermission('messages.manage');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('messages.create') || $user->hasPermission('messages.manage');
    }

    public function update(User $user, Preacher $preacher): bool
    {
        return $user->hasPermission('messages.update') || $user->hasPermission('messages.manage');
    }

    public function delete(User $user, Preacher $preacher): bool
    {
        return $user->hasPermission('messages.delete') || $user->hasPermission('messages.manage');
    }
}
