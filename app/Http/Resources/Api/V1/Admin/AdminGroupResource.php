<?php

namespace App\Http\Resources\Api\V1\Admin;

use App\Http\Resources\Api\V1\MediaResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminGroupResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'short_description' => $this->short_description,
            'image' => $this->image,
            'color' => $this->color,
            'contact' => $this->contact,
            'email' => $this->email,
            'active' => $this->active,
            'leaders_count' => $this->whenCounted('leaders'),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
            'leaders' => AdminGroupLeaderResource::collection($this->whenLoaded('leaders')),
            'media' => MediaResource::collection($this->whenLoaded('media')),
        ];
    }
}
