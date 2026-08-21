<?php

namespace App\Http\Controllers\Api\V1\Dosc;

use App\Http\Controllers\Api\V1\ApiController;
use App\Http\Requests\Api\V1\Dosc\SocialProjectIndexRequest;
use App\Http\Resources\Api\V1\Dosc\SocialProjectResource;
use App\Models\SocialProject;
use App\Support\Api\ApiQueryParameters;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SocialProjectController extends ApiController
{
    public function index(SocialProjectIndexRequest $request): AnonymousResourceCollection
    {
        $query = SocialProject::query()
            ->where('status', 'active')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search')->toString();
                $query->where(function ($query) use ($search) {
                    $query->where('title', 'like', "%{$search}%")
                        ->orWhere('short_description', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('featured'), fn ($query) => $query->where('featured', $request->boolean('featured')))
            ->when($request->filled('deadline_from'), fn ($query) => $query->whereDate('deadline', '>=', $request->date('deadline_from')->toDateString()))
            ->when($request->filled('deadline_to'), fn ($query) => $query->whereDate('deadline', '<=', $request->date('deadline_to')->toDateString()));

        $sort = ApiQueryParameters::sort($request, ['title', 'deadline', 'created_at', 'beneficiaries_count'], 'created_at');
        $direction = $request->filled('direction') ? ApiQueryParameters::direction($request) : 'desc';

        return SocialProjectResource::collection(
            $query->orderBy($sort, $direction)->paginate(ApiQueryParameters::perPage($request))
        );
    }

    public function show(string $slug): SocialProjectResource
    {
        $today = now()->toDateString();

        $project = SocialProject::query()
            ->where('slug', $slug)
            ->where('status', 'active')
            ->with([
                'actions' => fn ($query) => $query->where('status', 'published')->orderByDesc('action_date'),
                'donationCampaigns' => fn ($query) => $query
                    ->where('active', true)
                    ->where(function ($query) use ($today) {
                        $query->whereNull('start_date')->orWhereDate('start_date', '<=', $today);
                    })
                    ->where(function ($query) use ($today) {
                        $query->whereNull('end_date')->orWhereDate('end_date', '>=', $today);
                    })
                    ->orderByDesc('start_date'),
                'media',
            ])
            ->firstOrFail();

        return new SocialProjectResource($project);
    }
}
