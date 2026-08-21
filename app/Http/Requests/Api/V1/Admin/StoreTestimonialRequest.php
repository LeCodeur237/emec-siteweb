<?php

namespace App\Http\Requests\Api\V1\Admin;

use App\Http\Requests\Api\V1\ApiFormRequest;
use App\Models\Testimonial;

class StoreTestimonialRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', Testimonial::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'social_action_id' => ['nullable', 'integer', 'exists:social_actions,id'],
            'name' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'quote' => ['required', 'string'],
            'avatar' => ['nullable', 'string', 'max:255'],
            'published' => ['sometimes', 'boolean'],
        ];
    }
}
