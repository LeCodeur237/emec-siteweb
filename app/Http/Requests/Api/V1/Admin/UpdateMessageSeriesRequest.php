<?php

namespace App\Http\Requests\Api\V1\Admin;

use App\Http\Requests\Api\V1\ApiFormRequest;
use App\Models\MessageSeries;
use Illuminate\Validation\Rule;

class UpdateMessageSeriesRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        /** @var MessageSeries|null $messageSeries */
        $messageSeries = $this->route('messageSeries');

        return $messageSeries !== null && ($this->user()?->can('update', $messageSeries) ?? false);
    }

    public function rules(): array
    {
        /** @var MessageSeries|null $messageSeries */
        $messageSeries = $this->route('messageSeries');

        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'slug' => ['sometimes', 'required', 'string', 'max:255', 'alpha_dash:ascii', Rule::unique('message_series', 'slug')->ignore($messageSeries?->id)],
            'description' => ['sometimes', 'nullable', 'string'],
            'cover_image' => ['sometimes', 'nullable', 'string', 'max:255'],
            'active' => ['sometimes', 'boolean'],
        ];
    }
}
