<?php

namespace App\Http\Resources\Api\V1\Admin;

use App\Http\Resources\Api\V1\MediaResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminChurchResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'baptism_name' => $this->baptism_name,
            'city' => $this->city,
            'address' => $this->address,
            'neighborhood' => $this->neighborhood,
            'locality' => $this->locality,
            'sector' => $this->sector,
            'district' => $this->district,
            'circumscription' => $this->circumscription,
            'mission_field' => $this->mission_field,
            'region' => $this->region,
            'description' => $this->description,
            'pastor_vision' => $this->pastor_vision,
            'contact' => $this->contact,
            'map_url' => $this->map_url,
            'image' => $this->image,
            'status' => $this->status,
            'active' => $this->active,
            'leaders_count' => $this->whenCounted('leaders'),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
            'leaders' => AdminChurchLeaderResource::collection($this->whenLoaded('leaders')),
            'media' => MediaResource::collection($this->whenLoaded('media')),
        ];
    }
}
