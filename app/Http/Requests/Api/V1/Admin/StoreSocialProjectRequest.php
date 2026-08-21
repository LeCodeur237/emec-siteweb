<?php

namespace App\Http\Requests\Api\V1\Admin;

use App\Http\Requests\Api\V1\ApiFormRequest;
use App\Models\SocialProject;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StoreSocialProjectRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', SocialProject::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'alpha_dash:ascii', Rule::unique('social_projects', 'slug')],
            'short_description' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'string', 'max:255'],
            'goal_amount' => ['nullable', 'numeric', 'min:0'],
            'raised_amount' => ['sometimes', 'numeric', 'min:0'],
            'beneficiaries_count' => ['sometimes', 'integer', 'min:0'],
            'deadline' => ['nullable', 'date'],
            'status' => ['sometimes', Rule::in(['draft', 'active', 'archived'])],
            'featured' => ['sometimes', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        parent::prepareForValidation();

        if (! $this->filled('slug') && $this->filled('title')) {
            $this->merge(['slug' => Str::slug($this->string('title')->toString())]);
        }
    }
}
