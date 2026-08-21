<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Api\V1\ApiController;
use App\Http\Requests\Api\V1\Admin\AdminDoscIndexRequest;
use App\Http\Requests\Api\V1\Admin\StoreImpactStatRequest;
use App\Http\Requests\Api\V1\Admin\UpdateImpactStatRequest;
use App\Http\Resources\Api\V1\Admin\AdminImpactStatResource;
use App\Models\ImpactStat;
use App\Support\Api\ApiQueryParameters;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AdminImpactStatController extends ApiController
{
    public function index(AdminDoscIndexRequest $request): AnonymousResourceCollection
    {
        $this->authorize('viewAny', ImpactStat::class);

        $query = ImpactStat::query()
            ->when($request->filled('active'), fn ($query) => $query->where('active', $request->boolean('active')))
            ->when($request->filled('search'), fn ($query) => $query->where(fn ($query) => $query
                ->where('label', 'like', '%'.$request->string('search')->toString().'%')
                ->orWhere('value', 'like', '%'.$request->string('search')->toString().'%')));

        $sort = ApiQueryParameters::sort($request, ['label', 'sort_order', 'created_at', 'updated_at'], 'sort_order');

        return AdminImpactStatResource::collection(
            $query->orderBy($sort, ApiQueryParameters::direction($request))->paginate(ApiQueryParameters::perPage($request))
        );
    }

    public function show(ImpactStat $impactStat): AdminImpactStatResource
    {
        $this->authorize('view', $impactStat);

        return new AdminImpactStatResource($impactStat);
    }

    public function store(StoreImpactStatRequest $request): AdminImpactStatResource
    {
        $this->authorize('create', ImpactStat::class);

        return new AdminImpactStatResource(ImpactStat::create($request->validated()));
    }

    public function update(UpdateImpactStatRequest $request, ImpactStat $impactStat): AdminImpactStatResource
    {
        $this->authorize('update', $impactStat);

        $impactStat->fill($request->validated())->save();

        return new AdminImpactStatResource($impactStat);
    }

    public function destroy(ImpactStat $impactStat): JsonResponse
    {
        $this->authorize('delete', $impactStat);

        $impactStat->delete();

        return response()->json(null, 204);
    }
}
