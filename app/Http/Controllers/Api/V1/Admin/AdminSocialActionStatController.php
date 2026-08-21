<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Api\V1\ApiController;
use App\Http\Requests\Api\V1\Admin\AdminDoscIndexRequest;
use App\Http\Requests\Api\V1\Admin\StoreSocialActionStatRequest;
use App\Http\Requests\Api\V1\Admin\UpdateSocialActionStatRequest;
use App\Http\Resources\Api\V1\Admin\AdminSocialActionStatResource;
use App\Models\SocialActionStat;
use App\Support\Api\ApiQueryParameters;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AdminSocialActionStatController extends ApiController
{
    public function index(AdminDoscIndexRequest $request): AnonymousResourceCollection
    {
        $this->authorize('viewAny', SocialActionStat::class);

        $query = SocialActionStat::query()->with('action')
            ->when($request->filled('social_action_id'), fn ($query) => $query->where('social_action_id', $request->integer('social_action_id')))
            ->when($request->filled('search'), fn ($query) => $query->where(fn ($query) => $query
                ->where('label', 'like', '%'.$request->string('search')->toString().'%')
                ->orWhere('value', 'like', '%'.$request->string('search')->toString().'%')));

        $sort = ApiQueryParameters::sort($request, ['label', 'created_at', 'updated_at'], 'created_at');

        return AdminSocialActionStatResource::collection(
            $query->orderBy($sort, ApiQueryParameters::direction($request))->paginate(ApiQueryParameters::perPage($request))
        );
    }

    public function show(SocialActionStat $actionStat): AdminSocialActionStatResource
    {
        $this->authorize('view', $actionStat);

        return new AdminSocialActionStatResource($actionStat->load('action'));
    }

    public function store(StoreSocialActionStatRequest $request): AdminSocialActionStatResource
    {
        $this->authorize('create', SocialActionStat::class);

        return new AdminSocialActionStatResource(SocialActionStat::create($request->validated())->load('action'));
    }

    public function update(UpdateSocialActionStatRequest $request, SocialActionStat $actionStat): AdminSocialActionStatResource
    {
        $this->authorize('update', $actionStat);

        $actionStat->fill($request->validated())->save();

        return new AdminSocialActionStatResource($actionStat->load('action'));
    }

    public function destroy(SocialActionStat $actionStat): JsonResponse
    {
        $this->authorize('delete', $actionStat);

        $actionStat->delete();

        return response()->json(null, 204);
    }
}
