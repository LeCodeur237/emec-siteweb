<?php

namespace App\Policies;

use App\Models\MessageSeries;
use App\Models\User;

class MessageSeriesPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('messages.view') || $user->hasPermission('messages.manage');
    }

    public function view(User $user, MessageSeries $messageSeries): bool
    {
        return $user->hasPermission('messages.view') || $user->hasPermission('messages.manage');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('messages.create') || $user->hasPermission('messages.manage');
    }

    public function update(User $user, MessageSeries $messageSeries): bool
    {
        return $user->hasPermission('messages.update') || $user->hasPermission('messages.manage');
    }

    public function delete(User $user, MessageSeries $messageSeries): bool
    {
        return $user->hasPermission('messages.delete') || $user->hasPermission('messages.manage');
    }
}
