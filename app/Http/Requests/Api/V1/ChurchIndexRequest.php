<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Validation\Rule;

class ChurchIndexRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'page' => ['sometimes', 'integer', 'min:1'],
            'per_page' => ['sometimes', 'integer', 'min:1'],
            'search' => ['sometimes', 'string', 'max:120'],
            'city' => ['sometimes', 'string', 'max:120'],
            'region' => ['sometimes', 'string', 'max:120'],
            'status' => ['sometimes', Rule::in(['published', 'archived'])],
            'active' => ['sometimes', 'boolean'],
            'sort' => ['sometimes', Rule::in(['name', 'city', 'region', 'created_at'])],
            'direction' => ['sometimes', Rule::in(['asc', 'desc'])],
        ];
    }
}
