<?php

namespace App\Http\Resources\Api\V1\Admin;

use App\Http\Resources\Api\V1\MediaResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminPreacherResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'role' => $this->role,
            'bio' => $this->bio,
            'image' => $this->image,
            'active' => $this->active,
            'messages_count' => $this->whenCounted('messages'),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
            'media' => MediaResource::collection($this->whenLoaded('media')),
        ];
    }
}
