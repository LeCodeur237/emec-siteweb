<?php

namespace App\Http\Controllers\Api\V1\Dosc;

use App\Http\Controllers\Api\V1\ApiController;
use App\Http\Requests\Api\V1\Dosc\TestimonialIndexRequest;
use App\Http\Resources\Api\V1\Dosc\TestimonialResource;
use App\Models\Testimonial;
use App\Support\Api\ApiQueryParameters;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TestimonialController extends ApiController
{
    public function index(TestimonialIndexRequest $request): AnonymousResourceCollection
    {
        $query = Testimonial::query()
            ->where('published', true)
            ->where(function ($query) {
                $query->whereNull('social_action_id')
                    ->orWhereHas('action', function ($query) {
                        $query->where('status', 'published')
                            ->where(function ($query) {
                                $query->whereNull('social_project_id')
                                    ->orWhereHas('project', fn ($query) => $query->where('status', 'active'));
                            });
                    });
            })
            ->when($request->filled('social_action_id'), fn ($query) => $query->where('social_action_id', $request->integer('social_action_id')))
            ->when($request->filled('social_project_id'), function ($query) use ($request) {
                $query->whereHas('action', fn ($query) => $query->where('social_project_id', $request->integer('social_project_id')));
            });

        $direction = $request->filled('direction') ? ApiQueryParameters::direction($request) : 'desc';

        return TestimonialResource::collection(
            $query->orderBy('created_at', $direction)->paginate(ApiQueryParameters::perPage($request))
        );
    }
}
