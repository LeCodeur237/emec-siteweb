<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Api\V1\ApiController;
use App\Http\Requests\Api\V1\Admin\AdminDoscIndexRequest;
use App\Http\Requests\Api\V1\Admin\StoreSocialProjectRequest;
use App\Http\Requests\Api\V1\Admin\UpdateSocialProjectRequest;
use App\Http\Resources\Api\V1\Admin\AdminSocialProjectResource;
use App\Models\SocialProject;
use App\Support\Api\ApiQueryParameters;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AdminSocialProjectController extends ApiController
{
    public function index(AdminDoscIndexRequest $request): AnonymousResourceCollection
    {
        $this->authorize('viewAny', SocialProject::class);

        $query = SocialProject::query()->withCount(['actions', 'donationCampaigns'])
            ->when($request->filled('search'), fn ($query) => $query->where(fn ($query) => $query
                ->where('title', 'like', '%'.$request->string('search')->toString().'%')
                ->orWhere('short_description', 'like', '%'.$request->string('search')->toString().'%')
                ->orWhere('description', 'like', '%'.$request->string('search')->toString().'%')))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')->toString()))
            ->when($request->filled('featured'), fn ($query) => $query->where('featured', $request->boolean('featured')))
            ->when($request->filled('deadline_from'), fn ($query) => $query->whereDate('deadline', '>=', $request->date('deadline_from')->toDateString()))
            ->when($request->filled('deadline_to'), fn ($query) => $query->whereDate('deadline', '<=', $request->date('deadline_to')->toDateString()));

        $sort = ApiQueryParameters::sort($request, ['title', 'deadline', 'created_at', 'updated_at', 'beneficiaries_count'], 'created_at');

        return AdminSocialProjectResource::collection(
            $query->orderBy($sort, ApiQueryParameters::direction($request))->paginate(ApiQueryParameters::perPage($request))
        );
    }

    public function show(SocialProject $project): AdminSocialProjectResource
    {
        $this->authorize('view', $project);

        return new AdminSocialProjectResource($project->load(['actions', 'media'])->loadCount(['actions', 'donationCampaigns']));
    }

    public function store(StoreSocialProjectRequest $request): AdminSocialProjectResource
    {
        $this->authorize('create', SocialProject::class);

        return new AdminSocialProjectResource(SocialProject::create($request->validated())->load(['actions', 'media'])->loadCount(['actions', 'donationCampaigns']));
    }

    public function update(UpdateSocialProjectRequest $request, SocialProject $project): AdminSocialProjectResource
    {
        $this->authorize('update', $project);

        $project->fill($request->validated())->save();

        return new AdminSocialProjectResource($project->load(['actions', 'media'])->loadCount(['actions', 'donationCampaigns']));
    }

    public function destroy(SocialProject $project): JsonResponse
    {
        $this->authorize('delete', $project);

        $project->media()->detach();
        $project->delete();

        return response()->json(null, 204);
    }
}
