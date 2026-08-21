<?php

namespace App\Http\Requests\Api\V1\Admin;

use App\Http\Requests\Api\V1\ApiFormRequest;
use App\Models\SocialAction;
use Illuminate\Validation\Rule;

class UpdateSocialActionRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        /** @var SocialAction|null $socialAction */
        $socialAction = $this->route('action');

        return $socialAction !== null && ($this->user()?->can('update', $socialAction) ?? false);
    }

    public function rules(): array
    {
        /** @var SocialAction|null $socialAction */
        $socialAction = $this->route('action');

        return [
            'social_project_id' => ['sometimes', 'nullable', 'integer', 'exists:social_projects,id'],
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'slug' => ['sometimes', 'required', 'string', 'max:255', 'alpha_dash:ascii', Rule::unique('social_actions', 'slug')->ignore($socialAction?->id)],
            'category' => ['sometimes', 'nullable', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
            'location' => ['sometimes', 'nullable', 'string', 'max:255'],
            'action_date' => ['sometimes', 'nullable', 'date'],
            'image' => ['sometimes', 'nullable', 'string', 'max:255'],
            'youtube_video_id' => ['sometimes', 'nullable', 'string', 'max:100', 'regex:/^[A-Za-z0-9_-]+$/'],
            'beneficiaries_count' => ['sometimes', 'integer', 'min:0'],
            'status' => ['sometimes', Rule::in(['draft', 'published', 'archived'])],
        ];
    }
}
