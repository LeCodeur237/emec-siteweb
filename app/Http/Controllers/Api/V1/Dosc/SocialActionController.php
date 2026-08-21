<?php

namespace App\Http\Controllers\Api\V1\Dosc;

use App\Http\Controllers\Api\V1\ApiController;
use App\Http\Requests\Api\V1\Dosc\SocialActionIndexRequest;
use App\Http\Resources\Api\V1\Dosc\SocialActionResource;
use App\Models\SocialAction;
use App\Support\Api\ApiQueryParameters;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SocialActionController extends ApiController
{
    public function index(SocialActionIndexRequest $request): AnonymousResourceCollection
    {
        $query = SocialAction::query()
            ->where('status', 'published')
            ->with('project')
            ->where(function ($query) {
                $query->whereNull('social_project_id')
                    ->orWhereHas('project', fn ($query) => $query->where('status', 'active'));
            })
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search')->toString();
                $query->where(function ($query) use ($search) {
                    $query->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('category', 'like', "%{$search}%")
                        ->orWhere('location', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('social_project_id'), fn ($query) => $query->where('social_project_id', $request->integer('social_project_id')))
            ->when($request->filled('category'), fn ($query) => $query->where('category', $request->string('category')->toString()))
            ->when($request->filled('location'), fn ($query) => $query->where('location', 'like', '%'.$request->string('location')->toString().'%'))
            ->when($request->filled('from'), fn ($query) => $query->whereDate('action_date', '>=', $request->date('from')->toDateString()))
            ->when($request->filled('to'), fn ($query) => $query->whereDate('action_date', '<=', $request->date('to')->toDateString()));

        $sort = ApiQueryParameters::sort($request, ['action_date', 'title', 'created_at', 'beneficiaries_count'], 'action_date');
        $direction = $request->filled('direction') ? ApiQueryParameters::direction($request) : 'desc';

        return SocialActionResource::collection(
            $query->orderBy($sort, $direction)->paginate(ApiQueryParameters::perPage($request))
        );
    }

    public function show(string $slug): SocialActionResource
    {
        $action = SocialAction::query()
            ->where('slug', $slug)
            ->where('status', 'published')
            ->where(function ($query) {
                $query->whereNull('social_project_id')
                    ->orWhereHas('project', fn ($query) => $query->where('status', 'active'));
            })
            ->with([
                'project',
                'stats',
                'testimonials' => fn ($query) => $query->where('published', true),
                'media',
            ])
            ->firstOrFail();

        return new SocialActionResource($action);
    }
}
