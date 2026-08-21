<?php

namespace App\Http\Requests\Api\V1\Admin;

use App\Http\Requests\Api\V1\ApiFormRequest;
use App\Models\Message;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StoreMessageRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', Message::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'preacher_id' => ['nullable', 'integer', 'exists:preachers,id'],
            'message_category_id' => ['nullable', 'integer', 'exists:message_categories,id'],
            'message_series_id' => ['nullable', 'integer', 'exists:message_series,id'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'alpha_dash:ascii', Rule::unique('messages', 'slug')],
            'excerpt' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'preached_at' => ['nullable', 'date'],
            'duration' => ['nullable', 'string', 'max:50'],
            'youtube_video_id' => ['nullable', 'string', 'max:100', 'regex:/^[A-Za-z0-9_-]+$/'],
            'youtube_url' => ['nullable', 'url', 'max:255'],
            'audio_url' => ['nullable', 'url', 'max:255'],
            'pdf_url' => ['nullable', 'url', 'max:255'],
            'thumbnail' => ['nullable', 'url', 'max:255'],
            'featured' => ['sometimes', 'boolean'],
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
