<?php

namespace App\Http\Requests\Api\V1\Admin;

use App\Http\Requests\Api\V1\ApiFormRequest;
use App\Models\SocialProject;
use Illuminate\Validation\Rule;

class UpdateSocialProjectRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        /** @var SocialProject|null $socialProject */
        $socialProject = $this->route('project');

        return $socialProject !== null && ($this->user()?->can('update', $socialProject) ?? false);
    }

    public function rules(): array
    {
        /** @var SocialProject|null $socialProject */
        $socialProject = $this->route('project');

        return [
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'slug' => ['sometimes', 'required', 'string', 'max:255', 'alpha_dash:ascii', Rule::unique('social_projects', 'slug')->ignore($socialProject?->id)],
            'short_description' => ['sometimes', 'nullable', 'string'],
            'description' => ['sometimes', 'nullable', 'string'],
            'image' => ['sometimes', 'nullable', 'string', 'max:255'],
            'goal_amount' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'raised_amount' => ['sometimes', 'numeric', 'min:0'],
            'beneficiaries_count' => ['sometimes', 'integer', 'min:0'],
            'deadline' => ['sometimes', 'nullable', 'date'],
            'status' => ['sometimes', Rule::in(['draft', 'active', 'archived'])],
            'featured' => ['sometimes', 'boolean'],
        ];
    }
}
