<?php

namespace App\Http\Requests\Api\V1\Admin;

use App\Http\Requests\Api\V1\ApiFormRequest;
use App\Models\ContactMessage;
use Illuminate\Validation\Rule;

class StoreContactMessageRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', ContactMessage::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email:rfc', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'subject' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string'],
            'status' => ['sometimes', Rule::in(['new', 'read', 'answered', 'archived'])],
            'read_at' => ['nullable', 'date'],
            'answered_at' => ['nullable', 'date'],
        ];
    }
}
