<?php

namespace App\Http\Resources\Api\V1\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminChurchLeaderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'church_id' => $this->church_id,
            'name' => $this->name,
            'responsibility' => $this->responsibility,
            'image' => $this->image,
            'start_date' => $this->start_date?->toDateString(),
            'end_date' => $this->end_date?->toDateString(),
            'active' => $this->active,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
            'church' => $this->whenLoaded('church', fn () => $this->church ? [
                'id' => $this->church->id,
                'name' => $this->church->name,
                'slug' => $this->church->slug,
            ] : null),
        ];
    }
}
