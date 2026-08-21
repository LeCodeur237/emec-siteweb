<?php

namespace App\Http\Resources\Api\V1\Admin;

use App\Http\Resources\Api\V1\MediaResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminEventResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'event_category_id' => $this->event_category_id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'image' => $this->image,
            'start_at' => $this->start_at?->toISOString(),
            'end_at' => $this->end_at?->toISOString(),
            'location' => $this->location,
            'city' => $this->city,
            'featured' => $this->featured,
            'status' => $this->status,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
            'category' => new AdminEventCategoryResource($this->whenLoaded('category')),
            'media' => MediaResource::collection($this->whenLoaded('media')),
        ];
    }
}
