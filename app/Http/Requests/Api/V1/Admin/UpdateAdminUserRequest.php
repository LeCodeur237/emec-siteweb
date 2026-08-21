<?php

namespace App\Http\Requests\Api\V1\Admin;

use App\Http\Requests\Api\V1\ApiFormRequest;
use App\Models\User;
use Illuminate\Validation\Rule;

class UpdateAdminUserRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        /** @var User|null $user */
        $user = $this->route('user');

        return $user !== null && ($this->user()?->can('update', $user) ?? false);
    }

    public function rules(): array
    {
        /** @var User|null $user */
        $user = $this->route('user');

        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => ['sometimes', 'required', 'email:rfc', 'max:255', Rule::unique('users', 'email')->ignore($user?->id)],
            'password' => ['sometimes', 'nullable', 'string', 'min:8'],
            'phone' => ['sometimes', 'nullable', 'string', 'max:255'],
            'avatar' => ['sometimes', 'nullable', 'string', 'max:255'],
            'status' => ['sometimes', Rule::in(['active', 'inactive'])],
            'role_ids' => ['sometimes', 'array'],
            'role_ids.*' => ['integer', 'exists:roles,id'],
        ];
    }
}
