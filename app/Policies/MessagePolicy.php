<?php

namespace App\Policies;

use App\Models\Message;
use App\Models\User;

class MessagePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('messages.view') || $user->hasPermission('messages.manage');
    }

    public function view(User $user, Message $message): bool
    {
        return $user->hasPermission('messages.view') || $user->hasPermission('messages.manage');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('messages.create') || $user->hasPermission('messages.manage');
    }

    public function update(User $user, Message $message): bool
    {
        return $user->hasPermission('messages.update') || $user->hasPermission('messages.manage');
    }

    public function delete(User $user, Message $message): bool
    {
        return $user->hasPermission('messages.delete') || $user->hasPermission('messages.manage');
    }

    public function publish(User $user): bool
    {
        return $user->hasPermission('messages.publish') || $user->hasPermission('messages.manage');
    }
}
