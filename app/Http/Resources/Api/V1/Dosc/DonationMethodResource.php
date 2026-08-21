<?php

namespace App\Http\Resources\Api\V1\Dosc;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DonationMethodResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'provider' => $this->provider,
            'account_name' => $this->account_name,
            'account_number' => $this->account_number,
            'instructions' => $this->instructions,
        ];
    }
}
