<?php

namespace App\Policies\Concerns;

use App\Models\User;

trait AuthorizesAdminResource
{
    public function viewAny(User $user): bool
    {
        return $this->allows($user, 'view');
    }

    public function view(User $user): bool
    {
        return $this->allows($user, 'view');
    }

    public function create(User $user): bool
    {
        return $this->allows($user, 'create');
    }

    public function update(User $user): bool
    {
        return $this->allows($user, 'update');
    }

    public function delete(User $user): bool
    {
        return $this->allows($user, 'delete');
    }

    private function allows(User $user, string $ability): bool
    {
        return $user->hasPermission("{$this->permissionPrefix}.{$ability}")
            || $user->hasPermission("{$this->permissionPrefix}.manage")
            || $user->hasPermission($this->managePermission);
    }
}
