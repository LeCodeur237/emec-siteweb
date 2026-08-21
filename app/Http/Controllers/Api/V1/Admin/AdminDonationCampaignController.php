<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Api\V1\ApiController;
use App\Http\Requests\Api\V1\Admin\AdminDonationIndexRequest;
use App\Http\Requests\Api\V1\Admin\StoreDonationCampaignRequest;
use App\Http\Requests\Api\V1\Admin\UpdateDonationCampaignRequest;
use App\Http\Resources\Api\V1\Admin\AdminDonationCampaignResource;
use App\Models\DonationCampaign;
use App\Support\Api\ApiQueryParameters;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AdminDonationCampaignController extends ApiController
{
    public function index(AdminDonationIndexRequest $request): AnonymousResourceCollection
    {
        $this->authorize('viewAny', DonationCampaign::class);

        $query = DonationCampaign::query()->with('project')->withCount('donations')
            ->when($request->filled('social_project_id'), fn ($query) => $query->where('social_project_id', $request->integer('social_project_id')))
            ->when($request->filled('active'), fn ($query) => $query->where('active', $request->boolean('active')))
            ->when($request->filled('search'), fn ($query) => $query->where(fn ($query) => $query
                ->where('title', 'like', '%'.$request->string('search')->toString().'%')
                ->orWhere('description', 'like', '%'.$request->string('search')->toString().'%')))
            ->when($request->filled('from'), fn ($query) => $query->whereDate('start_date', '>=', $request->date('from')->toDateString()))
            ->when($request->filled('to'), fn ($query) => $query->whereDate('end_date', '<=', $request->date('to')->toDateString()));

        $sort = ApiQueryParameters::sort($request, ['title', 'start_date', 'end_date', 'created_at', 'updated_at', 'goal_amount'], 'created_at');

        return AdminDonationCampaignResource::collection(
            $query->orderBy($sort, ApiQueryParameters::direction($request))->paginate(ApiQueryParameters::perPage($request))
        );
    }

    public function show(DonationCampaign $donationCampaign): AdminDonationCampaignResource
    {
        $this->authorize('view', $donationCampaign);

        return new AdminDonationCampaignResource($donationCampaign->load('project')->loadCount('donations'));
    }

    public function store(StoreDonationCampaignRequest $request): AdminDonationCampaignResource
    {
        $this->authorize('create', DonationCampaign::class);

        return new AdminDonationCampaignResource(DonationCampaign::create($request->validated())->load('project')->loadCount('donations'));
    }

    public function update(UpdateDonationCampaignRequest $request, DonationCampaign $donationCampaign): AdminDonationCampaignResource
    {
        $this->authorize('update', $donationCampaign);

        $donationCampaign->fill($request->validated())->save();

        return new AdminDonationCampaignResource($donationCampaign->load('project')->loadCount('donations'));
    }

    public function destroy(DonationCampaign $donationCampaign): JsonResponse
    {
        $this->authorize('delete', $donationCampaign);

        $donationCampaign->delete();

        return response()->json(null, 204);
    }
}
