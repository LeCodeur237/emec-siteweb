<?php

namespace App\Http\Resources\Api\V1\Admin;

use App\Http\Resources\Api\V1\MediaResource;
use App\Http\Resources\Api\V1\MessageCategoryResource;
use App\Http\Resources\Api\V1\MessageSeriesResource;
use App\Http\Resources\Api\V1\PreacherResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminMessageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'preacher_id' => $this->preacher_id,
            'message_category_id' => $this->message_category_id,
            'message_series_id' => $this->message_series_id,
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt,
            'content' => $this->content,
            'preached_at' => $this->preached_at?->toDateString(),
            'duration' => $this->duration,
            'youtube_video_id' => $this->youtube_video_id,
            'youtube_url' => $this->youtube_url,
            'audio_url' => $this->audio_url,
            'pdf_url' => $this->pdf_url,
            'thumbnail' => $this->thumbnail,
            'featured' => $this->featured,
            'status' => $this->status,
            'views' => $this->views,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
            'preacher' => new PreacherResource($this->whenLoaded('preacher')),
            'category' => new MessageCategoryResource($this->whenLoaded('category')),
            'series' => new MessageSeriesResource($this->whenLoaded('series')),
            'media' => MediaResource::collection($this->whenLoaded('media')),
        ];
    }
}
