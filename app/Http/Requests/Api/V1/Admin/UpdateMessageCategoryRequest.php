<?php

namespace App\Http\Requests\Api\V1\Admin;

use App\Http\Requests\Api\V1\ApiFormRequest;
use App\Models\MessageCategory;
use Illuminate\Validation\Rule;

class UpdateMessageCategoryRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        /** @var MessageCategory|null $messageCategory */
        $messageCategory = $this->route('messageCategory');

        return $messageCategory !== null && ($this->user()?->can('update', $messageCategory) ?? false);
    }

    public function rules(): array
    {
        /** @var MessageCategory|null $messageCategory */
        $messageCategory = $this->route('messageCategory');

        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'slug' => ['sometimes', 'required', 'string', 'max:255', 'alpha_dash:ascii', Rule::unique('message_categories', 'slug')->ignore($messageCategory?->id)],
            'description' => ['sometimes', 'nullable', 'string'],
            'active' => ['sometimes', 'boolean'],
        ];
    }
}
