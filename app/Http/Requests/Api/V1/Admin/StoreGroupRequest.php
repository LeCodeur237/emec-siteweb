<?php

namespace App\Http\Requests\Api\V1\Admin;

use App\Http\Requests\Api\V1\ApiFormRequest;
use App\Models\Group;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StoreGroupRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', Group::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'alpha_dash:ascii', Rule::unique('groups', 'slug')],
            'description' => ['nullable', 'string'],
            'short_description' => ['nullable', 'string'],
            'image' => ['nullable', 'string', 'max:255'],
            'color' => ['nullable', 'string', 'max:30'],
            'contact' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email:rfc', 'max:255'],
            'active' => ['sometimes', 'boolean'],
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
