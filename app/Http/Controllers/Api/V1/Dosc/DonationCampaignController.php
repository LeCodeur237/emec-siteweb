<?php

namespace App\Http\Controllers\Api\V1\Dosc;

use App\Http\Controllers\Api\V1\ApiController;
use App\Http\Requests\Api\V1\Dosc\DonationCampaignIndexRequest;
use App\Http\Resources\Api\V1\Dosc\DonationCampaignResource;
use App\Models\DonationCampaign;
use App\Support\Api\ApiQueryParameters;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class DonationCampaignController extends ApiController
{
    public function index(DonationCampaignIndexRequest $request): AnonymousResourceCollection
    {
        $query = DonationCampaign::query()
            ->with('project');

        $this->applyPublicVisibility($query);

        $query
            ->when($request->filled('active') && ! $request->boolean('active'), fn ($query) => $query->whereRaw('1 = 0'))
            ->when($request->filled('social_project_id'), fn ($query) => $query->where('social_project_id', $request->integer('social_project_id')))
            ->when($request->filled('from'), fn ($query) => $query->where(function ($query) use ($request) {
                $query->whereNull('end_date')->orWhereDate('end_date', '>=', $request->date('from')->toDateString());
            }))
            ->when($request->filled('to'), fn ($query) => $query->where(function ($query) use ($request) {
                $query->whereNull('start_date')->orWhereDate('start_date', '<=', $request->date('to')->toDateString());
            }));

        $sort = ApiQueryParameters::sort($request, ['start_date', 'end_date', 'created_at', 'title'], 'created_at');
        $direction = $request->filled('direction') ? ApiQueryParameters::direction($request) : 'desc';

        return DonationCampaignResource::collection(
            $query->orderBy($sort, $direction)->paginate(ApiQueryParameters::perPage($request))
        );
    }

    public function show(int $id): DonationCampaignResource
    {
        $query = DonationCampaign::query()
            ->whereKey($id)
            ->with('project');

        $this->applyPublicVisibility($query);

        return new DonationCampaignResource($query->firstOrFail());
    }

    private function applyPublicVisibility($query): void
    {
        $today = now()->toDateString();

        $query
            ->where('active', true)
            ->where(function ($query) use ($today) {
                $query->whereNull('start_date')->orWhereDate('start_date', '<=', $today);
            })
            ->where(function ($query) use ($today) {
                $query->whereNull('end_date')->orWhereDate('end_date', '>=', $today);
            })
            ->where(function ($query) {
                $query->whereNull('social_project_id')
                    ->orWhereHas('project', fn ($query) => $query->where('status', 'active'));
            });
    }
}
