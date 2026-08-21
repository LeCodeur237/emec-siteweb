<?php

namespace App\Http\Requests\Api\V1\Admin;

use App\Http\Requests\Api\V1\ApiFormRequest;
use App\Models\EventCategory;
use Illuminate\Validation\Rule;

class UpdateEventCategoryRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        /** @var EventCategory|null $eventCategory */
        $eventCategory = $this->route('eventCategory');

        return $eventCategory !== null && ($this->user()?->can('update', $eventCategory) ?? false);
    }

    public function rules(): array
    {
        /** @var EventCategory|null $eventCategory */
        $eventCategory = $this->route('eventCategory');

        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'slug' => ['sometimes', 'required', 'string', 'max:255', 'alpha_dash:ascii', Rule::unique('event_categories', 'slug')->ignore($eventCategory?->id)],
            'description' => ['sometimes', 'nullable', 'string'],
            'active' => ['sometimes', 'boolean'],
        ];
    }
}
