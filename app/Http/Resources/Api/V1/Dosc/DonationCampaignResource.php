<?php

namespace App\Http\Resources\Api\V1\Dosc;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DonationCampaignResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'social_project_id' => $this->social_project_id,
            'title' => $this->title,
            'description' => $this->description,
            'goal_amount' => $this->goal_amount,
            'active' => $this->active,
            'start_date' => $this->start_date?->toDateString(),
            'end_date' => $this->end_date?->toDateString(),
            'project' => $this->whenLoaded('project', fn () => $this->project ? [
                'id' => $this->project->id,
                'title' => $this->project->title,
                'slug' => $this->project->slug,
            ] : null),
        ];
    }
}
