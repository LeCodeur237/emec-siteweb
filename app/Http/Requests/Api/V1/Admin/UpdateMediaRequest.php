<?php

namespace App\Http\Requests\Api\V1\Admin;

use App\Http\Requests\Api\V1\ApiFormRequest;
use App\Models\Media;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class UpdateMediaRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        /** @var Media|null $media */
        $media = $this->route('medium') ?? $this->route('media');

        return $media !== null && ($this->user()?->can('update', $media) ?? false);
    }

    public function rules(): array
    {
        return [
            'alt_text' => ['sometimes', 'nullable', 'string', 'max:255'],
            'title' => ['sometimes', 'nullable', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
            'mediaable_type' => ['nullable', Rule::in(array_keys(config('media.attachable_models', [])))],
            'mediaable_id' => ['required_with:mediaable_type', 'integer', 'min:1'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            if ($this->filled('mediaable_type') && $this->filled('mediaable_id')) {
                $models = config('media.attachable_models', []);
                $class = $models[$this->string('mediaable_type')->toString()] ?? null;

                if (! is_string($class) || ! $class::query()->whereKey($this->integer('mediaable_id'))->exists()) {
                    $validator->errors()->add('mediaable_id', 'The selected mediaable is invalid.');
                }
            }
        });
    }
}
