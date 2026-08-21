<?php

namespace App\Http\Resources\Api\V1\Admin;

use App\Http\Resources\Api\V1\MediaResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminSocialActionResource extends JsonResource
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
            'stats_count' => $this->whenCounted('stats'),
            'testimonials_count' => $this->whenCounted('testimonials'),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
            'project' => $this->whenLoaded('project', fn () => $this->project ? [
                'id' => $this->project->id,
                'title' => $this->project->title,
                'slug' => $this->project->slug,
                'status' => $this->project->status,
            ] : null),
            'stats' => AdminSocialActionStatResource::collection($this->whenLoaded('stats')),
            'testimonials' => AdminTestimonialResource::collection($this->whenLoaded('testimonials')),
            'media' => MediaResource::collection($this->whenLoaded('media')),
        ];
    }
}
