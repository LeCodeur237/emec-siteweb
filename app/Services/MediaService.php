<?php

namespace App\Services;

use App\Models\Media;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class MediaService
{
    public function upload(UploadedFile $file, array $metadata = [], ?User $uploadedBy = null): Media
    {
        $disk = $this->disk();
        $directory = 'media/'.now()->format('Y/m');
        $extension = strtolower($file->guessExtension() ?: $file->extension());
        $path = $directory.'/'.(string) Str::uuid().'.'.$extension;

        Storage::disk($disk)->putFileAs($directory, $file, basename($path));

        try {
            return DB::transaction(function () use ($file, $metadata, $uploadedBy, $path) {
                $media = Media::create([
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'file_type' => $this->fileType($file->getMimeType()),
                    'mime_type' => (string) $file->getMimeType(),
                    'alt_text' => $metadata['alt_text'] ?? null,
                    'title' => $metadata['title'] ?? null,
                    'description' => $metadata['description'] ?? null,
                    'size' => $file->getSize(),
                    'uploaded_by' => $uploadedBy?->id,
                ]);

                if (! empty($metadata['mediaable_type']) && ! empty($metadata['mediaable_id'])) {
                    $this->attach($media, $metadata['mediaable_type'], (int) $metadata['mediaable_id']);
                }

                return $media;
            });
        } catch (\Throwable $exception) {
            Storage::disk($disk)->delete($path);

            throw $exception;
        }
    }

    public function update(Media $media, array $metadata): Media
    {
        return DB::transaction(function () use ($media, $metadata) {
            $media->fill([
                'alt_text' => $metadata['alt_text'] ?? $media->alt_text,
                'title' => $metadata['title'] ?? $media->title,
                'description' => $metadata['description'] ?? $media->description,
            ])->save();

            if (! empty($metadata['mediaable_type']) && ! empty($metadata['mediaable_id'])) {
                $this->attach($media, $metadata['mediaable_type'], (int) $metadata['mediaable_id']);
            }

            return $media;
        });
    }

    public function delete(Media $media): void
    {
        DB::transaction(function () use ($media) {
            $path = $media->file_path;
            $media->churches()->detach();
            $media->groups()->detach();
            $media->events()->detach();
            $media->administrativeLeaders()->detach();
            $media->churchLeaders()->detach();
            $media->groupLeaders()->detach();
            $media->preachers()->detach();
            $media->messages()->detach();
            $media->socialProjects()->detach();
            $media->socialActions()->detach();
            $media->testimonials()->detach();
            $media->users()->detach();
            $media->delete();

            if ($path !== null && ! Media::query()->where('file_path', $path)->exists()) {
                Storage::disk($this->disk())->delete($path);
            }
        });
    }

    public function attach(Media $media, string $type, int $id): void
    {
        $model = $this->resolveAttachable($type, $id);
        $model->media()->syncWithoutDetaching([$media->id]);
    }

    public function detach(Media $media, string $type, int $id): void
    {
        $model = $this->resolveAttachable($type, $id);
        $model->media()->detach($media->id);
    }

    public function url(Media $media): ?string
    {
        return $media->file_path ? Storage::disk($this->disk())->url($media->file_path) : null;
    }

    private function resolveAttachable(string $type, int $id): Model
    {
        $models = config('media.attachable_models', []);
        $class = $models[$type] ?? null;

        if (! is_string($class) || ! is_a($class, Model::class, true)) {
            throw new RuntimeException('Unsupported mediaable type.');
        }

        return $class::query()->findOrFail($id);
    }

    private function fileType(?string $mimeType): string
    {
        if (in_array($mimeType, config('media.image_mime_types', []), true)) {
            return 'image';
        }

        return 'document';
    }

    private function disk(): string
    {
        return (string) config('media.disk', 'public');
    }
}
