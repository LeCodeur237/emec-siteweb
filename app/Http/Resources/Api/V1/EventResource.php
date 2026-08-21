<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
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
            'start_at' => $this->start_at?->toIso8601String(),
            'end_at' => $this->end_at?->toIso8601String(),
            'location' => $this->location,
            'city' => $this->city,
            'featured' => $this->featured,
            'status' => $this->status,
            'category' => new EventCategoryResource($this->whenLoaded('category')),
            'media' => MediaResource::collection($this->whenLoaded('media')),
        ];
    }
}
