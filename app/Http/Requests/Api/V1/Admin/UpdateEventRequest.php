<?php

namespace App\Http\Requests\Api\V1\Admin;

use App\Http\Requests\Api\V1\ApiFormRequest;
use App\Models\Event;
use Illuminate\Validation\Rule;

class UpdateEventRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        /** @var Event|null $event */
        $event = $this->route('event');

        return $event !== null && ($this->user()?->can('update', $event) ?? false);
    }

    public function rules(): array
    {
        /** @var Event|null $event */
        $event = $this->route('event');

        return [
            'event_category_id' => ['sometimes', 'nullable', 'integer', 'exists:event_categories,id'],
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'slug' => ['sometimes', 'required', 'string', 'max:255', 'alpha_dash:ascii', Rule::unique('events', 'slug')->ignore($event?->id)],
            'description' => ['sometimes', 'nullable', 'string'],
            'image' => ['sometimes', 'nullable', 'string', 'max:255'],
            'start_at' => ['sometimes', 'required', 'date'],
            'end_at' => ['sometimes', 'nullable', 'date', 'after_or_equal:start_at'],
            'location' => ['sometimes', 'nullable', 'string', 'max:255'],
            'city' => ['sometimes', 'nullable', 'string', 'max:255'],
            'featured' => ['sometimes', 'boolean'],
            'status' => ['sometimes', Rule::in(['draft', 'published', 'cancelled', 'completed'])],
        ];
    }
}
