<?php

namespace App\Http\Resources\Api\V1\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminSocialActionStatResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'social_action_id' => $this->social_action_id,
            'label' => $this->label,
            'value' => $this->value,
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
