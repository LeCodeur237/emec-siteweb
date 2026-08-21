<?php

namespace App\Http\Resources\Api\V1\Admin;

use App\Services\MediaService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminMediaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'file_name' => $this->file_name,
            'file_path' => $this->file_path,
            'url' => app(MediaService::class)->url($this->resource),
            'file_type' => $this->file_type,
            'mime_type' => $this->mime_type,
            'alt_text' => $this->alt_text,
            'title' => $this->title,
            'description' => $this->description,
            'size' => $this->size,
            'uploaded_by' => $this->uploaded_by,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
            'uploader' => $this->whenLoaded('uploadedBy', fn () => $this->uploadedBy ? [
                'id' => $this->uploadedBy->id,
                'name' => $this->uploadedBy->name,
                'email' => $this->uploadedBy->email,
            ] : null),
        ];
    }
}
