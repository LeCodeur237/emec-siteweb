<?php

namespace App\Http\Requests\Api\V1\Admin;

use App\Http\Requests\Api\V1\ApiFormRequest;
use App\Models\Testimonial;

class UpdateTestimonialRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        /** @var Testimonial|null $testimonial */
        $testimonial = $this->route('testimonial');

        return $testimonial !== null && ($this->user()?->can('update', $testimonial) ?? false);
    }

    public function rules(): array
    {
        return [
            'social_action_id' => ['sometimes', 'nullable', 'integer', 'exists:social_actions,id'],
            'name' => ['sometimes', 'nullable', 'string', 'max:255'],
            'location' => ['sometimes', 'nullable', 'string', 'max:255'],
            'quote' => ['sometimes', 'required', 'string'],
            'avatar' => ['sometimes', 'nullable', 'string', 'max:255'],
            'published' => ['sometimes', 'boolean'],
        ];
    }
}
