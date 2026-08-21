<?php

namespace App\Http\Requests\Api\V1\Admin;

use App\Http\Requests\Api\V1\ApiFormRequest;
use App\Models\GroupLeader;

class StoreGroupLeaderRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', GroupLeader::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'group_id' => ['required', 'integer', 'exists:groups,id'],
            'name' => ['required', 'string', 'max:255'],
            'responsibility' => ['required', 'string', 'max:255'],
            'image' => ['nullable', 'string', 'max:255'],
            'active' => ['sometimes', 'boolean'],
        ];
    }
}
