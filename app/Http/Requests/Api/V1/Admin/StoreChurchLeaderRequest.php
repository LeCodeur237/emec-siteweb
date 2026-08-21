<?php

namespace App\Http\Requests\Api\V1\Admin;

use App\Http\Requests\Api\V1\ApiFormRequest;
use App\Models\ChurchLeader;

class StoreChurchLeaderRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', ChurchLeader::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'church_id' => ['required', 'integer', 'exists:churches,id'],
            'name' => ['required', 'string', 'max:255'],
            'responsibility' => ['required', 'string', 'max:255'],
            'image' => ['nullable', 'string', 'max:255'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'active' => ['sometimes', 'boolean'],
        ];
    }
}
