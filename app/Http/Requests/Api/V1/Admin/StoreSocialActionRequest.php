<?php

namespace App\Http\Requests\Api\V1\Admin;

use App\Http\Requests\Api\V1\ApiFormRequest;
use App\Models\SocialAction;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StoreSocialActionRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', SocialAction::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'social_project_id' => ['nullable', 'integer', 'exists:social_projects,id'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'alpha_dash:ascii', Rule::unique('social_actions', 'slug')],
            'category' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'location' => ['nullable', 'string', 'max:255'],
            'action_date' => ['nullable', 'date'],
            'image' => ['nullable', 'string', 'max:255'],
            'youtube_video_id' => ['nullable', 'string', 'max:100', 'regex:/^[A-Za-z0-9_-]+$/'],
            'beneficiaries_count' => ['sometimes', 'integer', 'min:0'],
            'status' => ['sometimes', Rule::in(['draft', 'published', 'archived'])],
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
