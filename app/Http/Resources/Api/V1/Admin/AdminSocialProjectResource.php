<?php

namespace App\Http\Resources\Api\V1\Admin;

use App\Http\Resources\Api\V1\MediaResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminSocialProjectResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'short_description' => $this->short_description,
            'description' => $this->description,
            'image' => $this->image,
            'goal_amount' => $this->goal_amount,
            'raised_amount' => $this->raised_amount,
            'beneficiaries_count' => $this->beneficiaries_count,
            'deadline' => $this->deadline?->toDateString(),
            'status' => $this->status,
            'featured' => $this->featured,
            'actions_count' => $this->whenCounted('actions'),
            'donation_campaigns_count' => $this->whenCounted('donationCampaigns'),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
            'actions' => AdminSocialActionResource::collection($this->whenLoaded('actions')),
            'media' => MediaResource::collection($this->whenLoaded('media')),
        ];
    }
}
