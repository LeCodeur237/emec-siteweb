<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Api\V1\ApiController;
use App\Http\Requests\Api\V1\Admin\AdminDoscIndexRequest;
use App\Http\Requests\Api\V1\Admin\StoreSocialActionRequest;
use App\Http\Requests\Api\V1\Admin\UpdateSocialActionRequest;
use App\Http\Resources\Api\V1\Admin\AdminSocialActionResource;
use App\Models\SocialAction;
use App\Support\Api\ApiQueryParameters;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AdminSocialActionController extends ApiController
{
    public function index(AdminDoscIndexRequest $request): AnonymousResourceCollection
    {
        $this->authorize('viewAny', SocialAction::class);

        $query = SocialAction::query()->with('project')->withCount(['stats', 'testimonials'])
            ->when($request->filled('social_project_id'), fn ($query) => $query->where('social_project_id', $request->integer('social_project_id')))
            ->when($request->filled('search'), fn ($query) => $query->where(fn ($query) => $query
                ->where('title', 'like', '%'.$request->string('search')->toString().'%')
                ->orWhere('description', 'like', '%'.$request->string('search')->toString().'%')
                ->orWhere('category', 'like', '%'.$request->string('search')->toString().'%')
                ->orWhere('location', 'like', '%'.$request->string('search')->toString().'%')))
            ->when($request->filled('category'), fn ($query) => $query->where('category', $request->string('category')->toString()))
            ->when($request->filled('location'), fn ($query) => $query->where('location', 'like', '%'.$request->string('location')->toString().'%'))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')->toString()))
            ->when($request->filled('from'), fn ($query) => $query->whereDate('action_date', '>=', $request->date('from')->toDateString()))
            ->when($request->filled('to'), fn ($query) => $query->whereDate('action_date', '<=', $request->date('to')->toDateString()));

        $sort = ApiQueryParameters::sort($request, ['action_date', 'title', 'created_at', 'updated_at', 'beneficiaries_count'], 'created_at');

        return AdminSocialActionResource::collection(
            $query->orderBy($sort, ApiQueryParameters::direction($request))->paginate(ApiQueryParameters::perPage($request))
        );
    }

    public function show(SocialAction $action): AdminSocialActionResource
    {
        $this->authorize('view', $action);

        return new AdminSocialActionResource($action->load(['project', 'stats', 'testimonials', 'media'])->loadCount(['stats', 'testimonials']));
    }

    public function store(StoreSocialActionRequest $request): AdminSocialActionResource
    {
        $this->authorize('create', SocialAction::class);

        return new AdminSocialActionResource(SocialAction::create($request->validated())->load(['project', 'stats', 'testimonials', 'media'])->loadCount(['stats', 'testimonials']));
    }

    public function update(UpdateSocialActionRequest $request, SocialAction $action): AdminSocialActionResource
    {
        $this->authorize('update', $action);

        $action->fill($request->validated())->save();

        return new AdminSocialActionResource($action->load(['project', 'stats', 'testimonials', 'media'])->loadCount(['stats', 'testimonials']));
    }

    public function destroy(SocialAction $action): JsonResponse
    {
        $this->authorize('delete', $action);

        $action->media()->detach();
        $action->delete();

        return response()->json(null, 204);
    }
}
