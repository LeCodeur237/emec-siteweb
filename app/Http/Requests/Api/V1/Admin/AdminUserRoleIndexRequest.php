<?php

namespace App\Http\Requests\Api\V1\Admin;

use App\Http\Requests\Api\V1\ApiFormRequest;
use Illuminate\Validation\Rule;

class AdminUserRoleIndexRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'page' => ['sometimes', 'integer', 'min:1'],
            'per_page' => ['sometimes', 'integer', 'min:1'],
            'search' => ['sometimes', 'string', 'max:255'],
            'status' => ['sometimes', 'string', 'max:30'],
            'role_id' => ['sometimes', 'integer', 'exists:roles,id'],
            'permission_id' => ['sometimes', 'integer', 'exists:permissions,id'],
            'sort' => ['sometimes', 'string', 'max:50'],
            'direction' => ['sometimes', Rule::in(['asc', 'desc'])],
        ];
    }
}
