<?php

namespace App\Http\Requests\Api\V1\Admin;

use App\Http\Requests\Api\V1\ApiFormRequest;
use App\Models\NewsletterSubscriber;
use Illuminate\Validation\Rule;

class StoreNewsletterSubscriberRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', NewsletterSubscriber::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'email:rfc', 'max:255', Rule::unique('newsletter_subscribers', 'email')],
            'status' => ['sometimes', Rule::in(['subscribed', 'unsubscribed'])],
            'subscribed_at' => ['nullable', 'date'],
            'unsubscribed_at' => ['nullable', 'date'],
        ];
    }

    protected function prepareForValidation(): void
    {
        parent::prepareForValidation();

        if (! $this->filled('subscribed_at')) {
            $this->merge(['subscribed_at' => now()->toDateTimeString()]);
        }
    }
}
