<?php

namespace App\Http\Requests\Api\V1\Admin;

use App\Http\Requests\Api\V1\ApiFormRequest;
use App\Models\ImpactStat;

class UpdateImpactStatRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        /** @var ImpactStat|null $impactStat */
        $impactStat = $this->route('impactStat');

        return $impactStat !== null && ($this->user()?->can('update', $impactStat) ?? false);
    }

    public function rules(): array
    {
        return [
            'label' => ['sometimes', 'required', 'string', 'max:255'],
            'value' => ['sometimes', 'required', 'string', 'max:255'],
            'suffix' => ['sometimes', 'nullable', 'string', 'max:50'],
            'icon' => ['sometimes', 'nullable', 'string', 'max:100'],
            'sort_order' => ['sometimes', 'integer', 'min:0'],
            'active' => ['sometimes', 'boolean'],
        ];
    }
}
