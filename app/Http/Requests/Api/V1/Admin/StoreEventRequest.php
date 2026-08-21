<?php

namespace App\Http\Requests\Api\V1\Admin;

use App\Http\Requests\Api\V1\ApiFormRequest;
use App\Models\Event;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StoreEventRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', Event::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'event_category_id' => ['nullable', 'integer', 'exists:event_categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'alpha_dash:ascii', Rule::unique('events', 'slug')],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'string', 'max:255'],
            'start_at' => ['required', 'date'],
            'end_at' => ['nullable', 'date', 'after_or_equal:start_at'],
            'location' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'featured' => ['sometimes', 'boolean'],
            'status' => ['sometimes', Rule::in(['draft', 'published', 'cancelled', 'completed'])],
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
