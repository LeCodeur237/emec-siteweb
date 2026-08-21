<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdministrativeLeaderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'responsibility' => $this->responsibility,
            'description' => $this->description,
            'image' => $this->image,
            'start_date' => $this->start_date?->toDateString(),
            'end_date' => $this->end_date?->toDateString(),
            'active' => $this->active,
            'media' => MediaResource::collection($this->whenLoaded('media')),
        ];
    }
}
