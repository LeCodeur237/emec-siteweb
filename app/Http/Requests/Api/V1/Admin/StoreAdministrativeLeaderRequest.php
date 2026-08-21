<?php

namespace App\Http\Requests\Api\V1\Admin;

use App\Http\Requests\Api\V1\ApiFormRequest;
use App\Models\AdministrativeLeader;

class StoreAdministrativeLeaderRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', AdministrativeLeader::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'responsibility' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'string', 'max:255'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'active' => ['sometimes', 'boolean'],
        ];
    }
}
