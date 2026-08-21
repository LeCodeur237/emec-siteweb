<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Api\V1\ApiController;
use App\Http\Requests\Api\V1\Admin\AdminMediaIndexRequest;
use App\Http\Requests\Api\V1\Admin\StoreMediaRequest;
use App\Http\Requests\Api\V1\Admin\UpdateMediaRequest;
use App\Http\Resources\Api\V1\Admin\AdminMediaResource;
use App\Models\Media;
use App\Services\MediaService;
use App\Support\Api\ApiQueryParameters;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;

class AdminMediaController extends ApiController
{
    public function __construct(private readonly MediaService $mediaService) {}

    public function index(AdminMediaIndexRequest $request): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Media::class);

        $query = Media::query()->with('uploadedBy')
            ->when($request->filled('search'), fn ($query) => $query->where(fn ($query) => $query
                ->where('file_name', 'like', '%'.$request->string('search')->toString().'%')
                ->orWhere('title', 'like', '%'.$request->string('search')->toString().'%')
                ->orWhere('alt_text', 'like', '%'.$request->string('search')->toString().'%')
                ->orWhere('description', 'like', '%'.$request->string('search')->toString().'%')))
            ->when($request->filled('mime_type'), fn ($query) => $query->where('mime_type', $request->string('mime_type')->toString()))
            ->when($request->filled('file_type'), fn ($query) => $query->where('file_type', $request->string('file_type')->toString()))
            ->when($request->filled('uploaded_by'), fn ($query) => $query->where('uploaded_by', $request->integer('uploaded_by')))
            ->when($request->filled('from'), fn ($query) => $query->whereDate('created_at', '>=', $request->date('from')->toDateString()))
            ->when($request->filled('to'), fn ($query) => $query->whereDate('created_at', '<=', $request->date('to')->toDateString()))
            ->when($request->boolean('orphaned'), fn ($query) => $query->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('mediaables')
                    ->whereColumn('mediaables.media_id', 'media.id');
            }));

        $sort = ApiQueryParameters::sort($request, ['created_at', 'file_name', 'size', 'mime_type'], 'created_at');

        return AdminMediaResource::collection(
            $query->orderBy($sort, ApiQueryParameters::direction($request))->paginate(ApiQueryParameters::perPage($request))
        );
    }

    public function show(Media $media): AdminMediaResource
    {
        $this->authorize('view', $media);

        return new AdminMediaResource($media->load('uploadedBy'));
    }

    public function store(StoreMediaRequest $request): AdminMediaResource
    {
        $this->authorize('create', Media::class);

        $media = $this->mediaService->upload($request->file('file'), $request->validated(), $request->user());

        return new AdminMediaResource($media->load('uploadedBy'));
    }

    public function update(UpdateMediaRequest $request, Media $media): AdminMediaResource
    {
        $this->authorize('update', $media);

        $media = $this->mediaService->update($media, $request->validated());

        return new AdminMediaResource($media->load('uploadedBy'));
    }

    public function destroy(Media $media): JsonResponse
    {
        $this->authorize('delete', $media);

        $this->mediaService->delete($media);

        return response()->json(null, 204);
    }
}
