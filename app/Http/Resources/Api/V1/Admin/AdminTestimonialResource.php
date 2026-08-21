<?php

namespace App\Http\Resources\Api\V1\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminTestimonialResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'social_action_id' => $this->social_action_id,
            'name' => $this->name,
            'location' => $this->location,
            'quote' => $this->quote,
            'avatar' => $this->avatar,
            'published' => $this->published,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
            'action' => $this->whenLoaded('action', fn () => $this->action ? [
                'id' => $this->action->id,
                'title' => $this->action->title,
                'slug' => $this->action->slug,
            ] : null),
        ];
    }
}
