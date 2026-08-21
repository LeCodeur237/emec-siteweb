<?php

namespace App\Http\Resources\Api\V1\Dosc;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ImpactStatResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'label' => $this->label,
            'value' => $this->value,
            'suffix' => $this->suffix,
            'icon' => $this->icon,
            'sort_order' => $this->sort_order,
        ];
    }
}
