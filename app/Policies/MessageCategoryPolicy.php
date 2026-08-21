<?php

namespace App\Policies;

use App\Models\MessageCategory;
use App\Models\User;

class MessageCategoryPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('messages.view') || $user->hasPermission('messages.manage');
    }

    public function view(User $user, MessageCategory $messageCategory): bool
    {
        return $user->hasPermission('messages.view') || $user->hasPermission('messages.manage');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('messages.create') || $user->hasPermission('messages.manage');
    }

    public function update(User $user, MessageCategory $messageCategory): bool
    {
        return $user->hasPermission('messages.update') || $user->hasPermission('messages.manage');
    }

    public function delete(User $user, MessageCategory $messageCategory): bool
    {
        return $user->hasPermission('messages.delete') || $user->hasPermission('messages.manage');
    }
}
