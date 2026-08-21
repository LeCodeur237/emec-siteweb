<?php

namespace App\Http\Requests\Api\V1\Admin;

use App\Http\Requests\Api\V1\ApiFormRequest;
use App\Models\Role;
use Illuminate\Validation\Rule;

class UpdateRoleRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        /** @var Role|null $role */
        $role = $this->route('role');

        return $role !== null && ($this->user()?->can('update', $role) ?? false);
    }

    public function rules(): array
    {
        /** @var Role|null $role */
        $role = $this->route('role');

        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'slug' => ['sometimes', 'required', 'string', 'max:255', 'alpha_dash:ascii', Rule::unique('roles', 'slug')->ignore($role?->id)],
            'description' => ['sometimes', 'nullable', 'string'],
            'permission_ids' => ['sometimes', 'array'],
            'permission_ids.*' => ['integer', 'exists:permissions,id'],
        ];
    }
}
