<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupResource extends JsonResource
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
            'leaders' => GroupLeaderResource::collection($this->whenLoaded('leaders')),
            'media' => MediaResource::collection($this->whenLoaded('media')),
        ];
    }
}
