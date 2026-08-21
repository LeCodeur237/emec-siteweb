<?php

namespace App\Http\Requests\Api\V1\Admin;

use App\Http\Requests\Api\V1\ApiFormRequest;
use App\Models\Permission;
use Illuminate\Validation\Rule;

class UpdatePermissionRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        /** @var Permission|null $permission */
        $permission = $this->route('permission');

        return $permission !== null && ($this->user()?->can('update', $permission) ?? false);
    }

    public function rules(): array
    {
        /** @var Permission|null $permission */
        $permission = $this->route('permission');

        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'slug' => ['sometimes', 'required', 'string', 'max:255', 'regex:/^[a-z0-9_.-]+$/', Rule::unique('permissions', 'slug')->ignore($permission?->id)],
            'description' => ['sometimes', 'nullable', 'string'],
        ];
    }
}
