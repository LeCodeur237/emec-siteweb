<?php

namespace App\Http\Resources\Api\V1\Dosc;

use App\Http\Resources\Api\V1\MediaResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SocialActionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'social_project_id' => $this->social_project_id,
            'title' => $this->title,
            'slug' => $this->slug,
            'category' => $this->category,
            'description' => $this->description,
            'location' => $this->location,
            'action_date' => $this->action_date?->toDateString(),
            'image' => $this->image,
            'youtube_video_id' => $this->youtube_video_id,
            'beneficiaries_count' => $this->beneficiaries_count,
            'status' => $this->status,
            'project' => $this->whenLoaded('project', fn () => $this->project ? [
                'id' => $this->project->id,
                'title' => $this->project->title,
                'slug' => $this->project->slug,
            ] : null),
            'stats' => SocialActionStatResource::collection($this->whenLoaded('stats')),
            'testimonials' => TestimonialResource::collection($this->whenLoaded('testimonials')),
            'media' => MediaResource::collection($this->whenLoaded('media')),
        ];
    }
}
