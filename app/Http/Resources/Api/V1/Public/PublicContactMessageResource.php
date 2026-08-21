<?php

namespace App\Http\Resources\Api\V1\Public;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PublicContactMessageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
