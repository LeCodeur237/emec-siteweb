<?php

namespace App\Http\Requests\Api\V1\Admin;

use App\Http\Requests\Api\V1\ApiFormRequest;
use App\Models\Role;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StoreRoleRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', Role::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'alpha_dash:ascii', Rule::unique('roles', 'slug')],
            'description' => ['nullable', 'string'],
            'permission_ids' => ['sometimes', 'array'],
            'permission_ids.*' => ['integer', 'exists:permissions,id'],
        ];
    }

    protected function prepareForValidation(): void
    {
        parent::prepareForValidation();

        if (! $this->filled('slug') && $this->filled('name')) {
            $this->merge(['slug' => Str::slug($this->string('name')->toString())]);
        }
    }
}
