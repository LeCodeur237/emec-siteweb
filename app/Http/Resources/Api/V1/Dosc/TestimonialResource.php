<?php

namespace App\Http\Resources\Api\V1\Dosc;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TestimonialResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'location' => $this->location,
            'quote' => $this->quote,
            'avatar' => $this->avatar,
        ];
    }
}
