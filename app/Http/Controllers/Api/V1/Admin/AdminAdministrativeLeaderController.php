<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Api\V1\ApiController;
use App\Http\Requests\Api\V1\Admin\AdminEmecIndexRequest;
use App\Http\Requests\Api\V1\Admin\StoreAdministrativeLeaderRequest;
use App\Http\Requests\Api\V1\Admin\UpdateAdministrativeLeaderRequest;
use App\Http\Resources\Api\V1\Admin\AdminAdministrativeLeaderResource;
use App\Models\AdministrativeLeader;
use App\Support\Api\ApiQueryParameters;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AdminAdministrativeLeaderController extends ApiController
{
    public function index(AdminEmecIndexRequest $request): AnonymousResourceCollection
    {
        $this->authorize('viewAny', AdministrativeLeader::class);
        $query = AdministrativeLeader::query()
            ->when($request->filled('search'), fn ($query) => $query->where(fn ($query) => $query
                ->where('name', 'like', '%'.$request->string('search')->toString().'%')
                ->orWhere('responsibility', 'like', '%'.$request->string('search')->toString().'%')))
            ->when($request->filled('active'), fn ($query) => $query->where('active', $request->boolean('active')));
        $sort = ApiQueryParameters::sort($request, ['name', 'responsibility', 'created_at', 'updated_at'], 'name');

        return AdminAdministrativeLeaderResource::collection($query->orderBy($sort, ApiQueryParameters::direction($request))->paginate(ApiQueryParameters::perPage($request)));
    }

    public function show(AdministrativeLeader $administrativeLeader): AdminAdministrativeLeaderResource
    {
        $this->authorize('view', $administrativeLeader);

        return new AdminAdministrativeLeaderResource($administrativeLeader->load('media'));
    }

    public function store(StoreAdministrativeLeaderRequest $request): AdminAdministrativeLeaderResource
    {
        $this->authorize('create', AdministrativeLeader::class);

        return new AdminAdministrativeLeaderResource(AdministrativeLeader::create($request->validated())->load('media'));
    }

    public function update(UpdateAdministrativeLeaderRequest $request, AdministrativeLeader $administrativeLeader): AdminAdministrativeLeaderResource
    {
        $this->authorize('update', $administrativeLeader);
        $administrativeLeader->fill($request->validated())->save();

        return new AdminAdministrativeLeaderResource($administrativeLeader->load('media'));
    }

    public function destroy(AdministrativeLeader $administrativeLeader): JsonResponse
    {
        $this->authorize('delete', $administrativeLeader);
        $administrativeLeader->media()->detach();
        $administrativeLeader->delete();

        return response()->json(null, 204);
    }
}
