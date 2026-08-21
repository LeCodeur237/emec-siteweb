<?php

namespace App\Http\Requests\Api\V1\Admin;

use App\Http\Requests\Api\V1\ApiFormRequest;
use App\Models\Permission;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StorePermissionRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', Permission::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'regex:/^[a-z0-9_.-]+$/', Rule::unique('permissions', 'slug')],
            'description' => ['nullable', 'string'],
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
