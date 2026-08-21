<?php

namespace App\Http\Resources\Api\V1\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminRoleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'users_count' => $this->whenCounted('users'),
            'permissions_count' => $this->whenCounted('permissions'),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
            'permissions' => AdminPermissionResource::collection($this->whenLoaded('permissions')),
        ];
    }
}
