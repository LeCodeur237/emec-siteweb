<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChurchLeaderResource extends JsonResource
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
        ];
    }
}
