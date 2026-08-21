<?php

namespace App\Services\Notifications;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class AdminNotificationRecipientService
{
    public function usersFor(string $permission): Collection
    {
        return User::query()
            ->where('status', 'active')
            ->whereHas('roles.permissions', fn ($query) => $query->whereIn('slug', [$permission, 'notifications.view']))
            ->get();
    }
}
