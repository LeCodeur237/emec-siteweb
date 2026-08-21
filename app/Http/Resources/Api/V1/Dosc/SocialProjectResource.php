<?php

namespace App\Http\Resources\Api\V1\Dosc;

use App\Http\Resources\Api\V1\MediaResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SocialProjectResource extends JsonResource
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
            'actions' => SocialActionResource::collection($this->whenLoaded('actions')),
            'donation_campaigns' => DonationCampaignResource::collection($this->whenLoaded('donationCampaigns')),
            'media' => MediaResource::collection($this->whenLoaded('media')),
        ];
    }
}
