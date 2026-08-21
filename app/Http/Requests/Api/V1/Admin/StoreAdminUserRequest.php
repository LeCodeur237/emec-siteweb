<?php

namespace App\Http\Requests\Api\V1\Admin;

use App\Http\Requests\Api\V1\ApiFormRequest;
use App\Models\User;
use Illuminate\Validation\Rule;

class StoreAdminUserRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', User::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email:rfc', 'max:255', Rule::unique('users', 'email')],
            'password' => ['required', 'string', 'min:8'],
            'phone' => ['nullable', 'string', 'max:255'],
            'avatar' => ['nullable', 'string', 'max:255'],
            'status' => ['sometimes', Rule::in(['active', 'inactive'])],
            'role_ids' => ['sometimes', 'array'],
            'role_ids.*' => ['integer', 'exists:roles,id'],
        ];
    }
}
