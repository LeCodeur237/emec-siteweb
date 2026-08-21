<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class MediaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $disk = config('media.disk', 'public');

        return [
            'id' => $this->id,
            'file_name' => $this->file_name,
            'url' => $this->file_path ? Storage::disk($disk)->url($this->file_path) : null,
            'file_type' => $this->file_type,
            'mime_type' => $this->mime_type,
            'alt_text' => $this->alt_text,
            'title' => $this->title,
            'description' => $this->description,
        ];
    }
}
