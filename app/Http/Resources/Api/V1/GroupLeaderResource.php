<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupLeaderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'group_id' => $this->group_id,
            'name' => $this->name,
            'responsibility' => $this->responsibility,
            'image' => $this->image,
            'active' => $this->active,
        ];
    }
}
