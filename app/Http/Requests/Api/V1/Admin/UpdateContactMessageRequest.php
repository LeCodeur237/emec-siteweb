<?php

namespace App\Http\Requests\Api\V1\Admin;

use App\Http\Requests\Api\V1\ApiFormRequest;
use App\Models\ContactMessage;
use Illuminate\Validation\Rule;

class UpdateContactMessageRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        /** @var ContactMessage|null $contactMessage */
        $contactMessage = $this->route('contactMessage');

        return $contactMessage !== null && ($this->user()?->can('update', $contactMessage) ?? false);
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => ['sometimes', 'required', 'email:rfc', 'max:255'],
            'phone' => ['sometimes', 'nullable', 'string', 'max:255'],
            'subject' => ['sometimes', 'nullable', 'string', 'max:255'],
            'message' => ['sometimes', 'required', 'string'],
            'status' => ['sometimes', Rule::in(['new', 'read', 'answered', 'archived'])],
            'read_at' => ['sometimes', 'nullable', 'date'],
            'answered_at' => ['sometimes', 'nullable', 'date'],
        ];
    }
}
