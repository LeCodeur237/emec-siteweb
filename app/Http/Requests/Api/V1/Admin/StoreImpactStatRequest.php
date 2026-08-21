<?php

namespace App\Http\Requests\Api\V1\Admin;

use App\Http\Requests\Api\V1\ApiFormRequest;
use App\Models\ImpactStat;

class StoreImpactStatRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', ImpactStat::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'label' => ['required', 'string', 'max:255'],
            'value' => ['required', 'string', 'max:255'],
            'suffix' => ['nullable', 'string', 'max:50'],
            'icon' => ['nullable', 'string', 'max:100'],
            'sort_order' => ['sometimes', 'integer', 'min:0'],
            'active' => ['sometimes', 'boolean'],
        ];
    }
}
