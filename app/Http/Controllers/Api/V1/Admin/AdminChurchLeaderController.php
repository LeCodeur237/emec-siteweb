<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Api\V1\ApiController;
use App\Http\Requests\Api\V1\Admin\AdminEmecIndexRequest;
use App\Http\Requests\Api\V1\Admin\StoreChurchLeaderRequest;
use App\Http\Requests\Api\V1\Admin\UpdateChurchLeaderRequest;
use App\Http\Resources\Api\V1\Admin\AdminChurchLeaderResource;
use App\Models\ChurchLeader;
use App\Support\Api\ApiQueryParameters;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AdminChurchLeaderController extends ApiController
{
    public function index(AdminEmecIndexRequest $request): AnonymousResourceCollection
    {
        $this->authorize('viewAny', ChurchLeader::class);
        $query = ChurchLeader::query()->with('church')
            ->when($request->filled('church_id'), fn ($query) => $query->where('church_id', $request->integer('church_id')))
            ->when($request->filled('search'), fn ($query) => $query->where(fn ($query) => $query
                ->where('name', 'like', '%'.$request->string('search')->toString().'%')
                ->orWhere('responsibility', 'like', '%'.$request->string('search')->toString().'%')))
            ->when($request->filled('active'), fn ($query) => $query->where('active', $request->boolean('active')));
        $sort = ApiQueryParameters::sort($request, ['name', 'responsibility', 'created_at', 'updated_at'], 'name');

        return AdminChurchLeaderResource::collection($query->orderBy($sort, ApiQueryParameters::direction($request))->paginate(ApiQueryParameters::perPage($request)));
    }

    public function show(ChurchLeader $churchLeader): AdminChurchLeaderResource
    {
        $this->authorize('view', $churchLeader);

        return new AdminChurchLeaderResource($churchLeader->load('church'));
    }

    public function store(StoreChurchLeaderRequest $request): AdminChurchLeaderResource
    {
        $this->authorize('create', ChurchLeader::class);

        return new AdminChurchLeaderResource(ChurchLeader::create($request->validated())->load('church'));
    }

    public function update(UpdateChurchLeaderRequest $request, ChurchLeader $churchLeader): AdminChurchLeaderResource
    {
        $this->authorize('update', $churchLeader);
        $churchLeader->fill($request->validated())->save();

        return new AdminChurchLeaderResource($churchLeader->load('church'));
    }

    public function destroy(ChurchLeader $churchLeader): JsonResponse
    {
        $this->authorize('delete', $churchLeader);
        $churchLeader->delete();

        return response()->json(null, 204);
    }
}
