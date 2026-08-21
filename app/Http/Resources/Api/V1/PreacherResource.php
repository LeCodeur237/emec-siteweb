<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PreacherResource extends JsonResource
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
            'media' => MediaResource::collection($this->whenLoaded('media')),
        ];
    }
}
