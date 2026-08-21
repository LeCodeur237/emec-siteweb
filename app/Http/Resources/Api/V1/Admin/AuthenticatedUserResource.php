<?php

namespace App\Http\Resources\Api\V1\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthenticatedUserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $this->resource->loadMissing('roles.permissions');

        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'avatar' => $this->avatar,
            'status' => $this->status,
            'roles' => $this->roles
                ->map(fn ($role) => [
                    'id' => $role->id,
                    'name' => $role->name,
                    'slug' => $role->slug,
                ])
                ->values(),
            'permissions' => $this->roles
                ->flatMap(fn ($role) => $role->permissions)
                ->unique('slug')
                ->sortBy('slug')
                ->map(fn ($permission) => [
                    'id' => $permission->id,
                    'name' => $permission->name,
                    'slug' => $permission->slug,
                ])
                ->values(),
        ];
    }
}
