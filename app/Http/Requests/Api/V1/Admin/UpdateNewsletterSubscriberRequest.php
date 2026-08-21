<?php

namespace App\Http\Requests\Api\V1\Admin;

use App\Http\Requests\Api\V1\ApiFormRequest;
use App\Models\NewsletterSubscriber;
use Illuminate\Validation\Rule;

class UpdateNewsletterSubscriberRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        /** @var NewsletterSubscriber|null $newsletterSubscriber */
        $newsletterSubscriber = $this->route('newsletterSubscriber');

        return $newsletterSubscriber !== null && ($this->user()?->can('update', $newsletterSubscriber) ?? false);
    }

    public function rules(): array
    {
        /** @var NewsletterSubscriber|null $newsletterSubscriber */
        $newsletterSubscriber = $this->route('newsletterSubscriber');

        return [
            'name' => ['sometimes', 'nullable', 'string', 'max:255'],
            'email' => ['sometimes', 'required', 'email:rfc', 'max:255', Rule::unique('newsletter_subscribers', 'email')->ignore($newsletterSubscriber?->id)],
            'status' => ['sometimes', Rule::in(['subscribed', 'unsubscribed'])],
            'subscribed_at' => ['sometimes', 'nullable', 'date'],
            'unsubscribed_at' => ['sometimes', 'nullable', 'date'],
        ];
    }
}
