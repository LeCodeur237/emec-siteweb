<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Api\V1\ApiController;
use App\Http\Requests\Api\V1\Admin\AdminEmecIndexRequest;
use App\Http\Requests\Api\V1\Admin\StoreChurchRequest;
use App\Http\Requests\Api\V1\Admin\UpdateChurchRequest;
use App\Http\Resources\Api\V1\Admin\AdminChurchResource;
use App\Models\Church;
use App\Support\Api\ApiQueryParameters;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AdminChurchController extends ApiController
{
    public function index(AdminEmecIndexRequest $request): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Church::class);
        $query = Church::query()->withCount('leaders')
            ->when($request->filled('search'), fn ($query) => $query->where(fn ($query) => $query
                ->where('name', 'like', '%'.$request->string('search')->toString().'%')
                ->orWhere('city', 'like', '%'.$request->string('search')->toString().'%')
                ->orWhere('region', 'like', '%'.$request->string('search')->toString().'%')))
            ->when($request->filled('city'), fn ($query) => $query->where('city', $request->string('city')->toString()))
            ->when($request->filled('region'), fn ($query) => $query->where('region', $request->string('region')->toString()))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')->toString()))
            ->when($request->filled('active'), fn ($query) => $query->where('active', $request->boolean('active')));
        $sort = ApiQueryParameters::sort($request, ['name', 'city', 'region', 'created_at', 'updated_at'], 'name');

        return AdminChurchResource::collection($query->orderBy($sort, ApiQueryParameters::direction($request))->paginate(ApiQueryParameters::perPage($request)));
    }

    public function show(Church $church): AdminChurchResource
    {
        $this->authorize('view', $church);

        return new AdminChurchResource($church->load(['leaders', 'media'])->loadCount('leaders'));
    }

    public function store(StoreChurchRequest $request): AdminChurchResource
    {
        $this->authorize('create', Church::class);

        return new AdminChurchResource(Church::create($request->validated())->load(['leaders', 'media'])->loadCount('leaders'));
    }

    public function update(UpdateChurchRequest $request, Church $church): AdminChurchResource
    {
        $this->authorize('update', $church);
        $church->fill($request->validated())->save();

        return new AdminChurchResource($church->load(['leaders', 'media'])->loadCount('leaders'));
    }

    public function destroy(Church $church): JsonResponse
    {
        $this->authorize('delete', $church);
        $church->media()->detach();
        $church->delete();

        return response()->json(null, 204);
    }
}
