<?php

namespace App\Http\Requests\Api\V1\Admin;

use App\Http\Requests\Api\V1\ApiFormRequest;
use App\Models\Media;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreMediaRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', Media::class) ?? false;
    }

    public function rules(): array
    {
        $max = max(config('media.max_size_kb.image', 10240), config('media.max_size_kb.document', 20480));

        return [
            'file' => [
                'required',
                'file',
                'max:'.$max,
                'extensions:'.implode(',', config('media.allowed_extensions', [])),
                'mimetypes:'.implode(',', config('media.allowed_mime_types', [])),
            ],
            'alt_text' => ['nullable', 'string', 'max:255'],
            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'mediaable_type' => ['nullable', Rule::in(array_keys(config('media.attachable_models', [])))],
            'mediaable_id' => ['required_with:mediaable_type', 'integer', 'min:1'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $file = $this->file('file');

            if ($file === null || ! $file->isValid()) {
                return;
            }

            $mimeType = $file->getMimeType();
            $originalName = mb_strtolower($file->getClientOriginalName());
            $sizeKb = (int) ceil((int) $file->getSize() / 1024);
            $imageTypes = config('media.image_mime_types', []);
            $documentTypes = config('media.document_mime_types', []);
            $dangerousExtensions = ['php', 'phtml', 'phar', 'cgi', 'pl', 'asp', 'aspx', 'jsp', 'sh', 'bat', 'cmd', 'exe', 'htaccess'];
            $nameParts = array_values(array_filter(explode('.', $originalName)));

            if (count($nameParts) > 2 && count(array_intersect($dangerousExtensions, array_slice($nameParts, 0, -1))) > 0) {
                $validator->errors()->add('file', 'The file name contains a forbidden executable extension.');
            }

            if (in_array(ltrim($originalName, '.'), $dangerousExtensions, true)) {
                $validator->errors()->add('file', 'The file name contains a forbidden executable extension.');
            }

            if (in_array($mimeType, $imageTypes, true) && $sizeKb > (int) config('media.max_size_kb.image', 10240)) {
                $validator->errors()->add('file', 'The image may not be greater than '.config('media.max_size_kb.image').' kilobytes.');
            }

            if (in_array($mimeType, $documentTypes, true) && $sizeKb > (int) config('media.max_size_kb.document', 20480)) {
                $validator->errors()->add('file', 'The document may not be greater than '.config('media.max_size_kb.document').' kilobytes.');
            }

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
