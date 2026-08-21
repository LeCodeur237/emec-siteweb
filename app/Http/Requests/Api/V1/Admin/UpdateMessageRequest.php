<?php

namespace App\Http\Requests\Api\V1\Admin;

use App\Http\Requests\Api\V1\ApiFormRequest;
use App\Models\Message;
use Illuminate\Validation\Rule;

class UpdateMessageRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        /** @var Message|null $message */
        $message = $this->route('message');

        return $message !== null && ($this->user()?->can('update', $message) ?? false);
    }

    public function rules(): array
    {
        /** @var Message|null $message */
        $message = $this->route('message');

        return [
            'preacher_id' => ['sometimes', 'nullable', 'integer', 'exists:preachers,id'],
            'message_category_id' => ['sometimes', 'nullable', 'integer', 'exists:message_categories,id'],
            'message_series_id' => ['sometimes', 'nullable', 'integer', 'exists:message_series,id'],
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'slug' => ['sometimes', 'required', 'string', 'max:255', 'alpha_dash:ascii', Rule::unique('messages', 'slug')->ignore($message?->id)],
            'excerpt' => ['sometimes', 'nullable', 'string'],
            'content' => ['sometimes', 'nullable', 'string'],
            'preached_at' => ['sometimes', 'nullable', 'date'],
            'duration' => ['sometimes', 'nullable', 'string', 'max:50'],
            'youtube_video_id' => ['sometimes', 'nullable', 'string', 'max:100', 'regex:/^[A-Za-z0-9_-]+$/'],
            'youtube_url' => ['sometimes', 'nullable', 'url', 'max:255'],
            'audio_url' => ['sometimes', 'nullable', 'url', 'max:255'],
            'pdf_url' => ['sometimes', 'nullable', 'url', 'max:255'],
            'thumbnail' => ['sometimes', 'nullable', 'url', 'max:255'],
            'featured' => ['sometimes', 'boolean'],
            'status' => ['sometimes', Rule::in(['draft', 'published', 'archived'])],
        ];
    }
}
