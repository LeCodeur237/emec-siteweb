<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChurchResource extends JsonResource
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
            'leaders' => ChurchLeaderResource::collection($this->whenLoaded('leaders')),
            'media' => MediaResource::collection($this->whenLoaded('media')),
        ];
    }
}
