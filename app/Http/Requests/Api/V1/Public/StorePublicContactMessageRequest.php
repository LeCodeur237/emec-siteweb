<?php

namespace App\Http\Requests\Api\V1\Public;

use App\Http\Requests\Api\V1\ApiFormRequest;

class StorePublicContactMessageRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email:rfc', 'max:255'],
            'phone' => ['nullable', 'string', 'max:40'],
            'subject' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
            'website' => ['prohibited'],
        ];
    }
}
