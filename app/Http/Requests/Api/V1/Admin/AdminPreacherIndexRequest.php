<?php

namespace App\Http\Requests\Api\V1\Admin;

use App\Http\Requests\Api\V1\ApiFormRequest;
use App\Models\Preacher;
use Illuminate\Validation\Rule;

class AdminPreacherIndexRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('viewAny', Preacher::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'page' => ['sometimes', 'integer', 'min:1'],
            'per_page' => ['sometimes', 'integer', 'min:1'],
            'search' => ['sometimes', 'string', 'max:255'],
            'active' => ['sometimes', 'boolean'],
            'sort' => ['sometimes', Rule::in(['name', 'role', 'created_at', 'updated_at'])],
            'direction' => ['sometimes', Rule::in(['asc', 'desc'])],
        ];
    }
}
