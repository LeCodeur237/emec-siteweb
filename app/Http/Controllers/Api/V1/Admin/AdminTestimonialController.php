<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Api\V1\ApiController;
use App\Http\Requests\Api\V1\Admin\AdminDoscIndexRequest;
use App\Http\Requests\Api\V1\Admin\StoreTestimonialRequest;
use App\Http\Requests\Api\V1\Admin\UpdateTestimonialRequest;
use App\Http\Resources\Api\V1\Admin\AdminTestimonialResource;
use App\Models\Testimonial;
use App\Support\Api\ApiQueryParameters;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AdminTestimonialController extends ApiController
{
    public function index(AdminDoscIndexRequest $request): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Testimonial::class);

        $query = Testimonial::query()->with('action')
            ->when($request->filled('social_action_id'), fn ($query) => $query->where('social_action_id', $request->integer('social_action_id')))
            ->when($request->filled('social_project_id'), fn ($query) => $query->whereHas('action', fn ($query) => $query->where('social_project_id', $request->integer('social_project_id'))))
            ->when($request->filled('published'), fn ($query) => $query->where('published', $request->boolean('published')))
            ->when($request->filled('search'), fn ($query) => $query->where(fn ($query) => $query
                ->where('name', 'like', '%'.$request->string('search')->toString().'%')
                ->orWhere('location', 'like', '%'.$request->string('search')->toString().'%')
                ->orWhere('quote', 'like', '%'.$request->string('search')->toString().'%')));

        return AdminTestimonialResource::collection(
            $query->orderBy('created_at', ApiQueryParameters::direction($request))->paginate(ApiQueryParameters::perPage($request))
        );
    }

    public function show(Testimonial $testimonial): AdminTestimonialResource
    {
        $this->authorize('view', $testimonial);

        return new AdminTestimonialResource($testimonial->load('action'));
    }

    public function store(StoreTestimonialRequest $request): AdminTestimonialResource
    {
        $this->authorize('create', Testimonial::class);

        return new AdminTestimonialResource(Testimonial::create($request->validated())->load('action'));
    }

    public function update(UpdateTestimonialRequest $request, Testimonial $testimonial): AdminTestimonialResource
    {
        $this->authorize('update', $testimonial);

        $testimonial->fill($request->validated())->save();

        return new AdminTestimonialResource($testimonial->load('action'));
    }

    public function destroy(Testimonial $testimonial): JsonResponse
    {
        $this->authorize('delete', $testimonial);

        $testimonial->delete();

        return response()->json(null, 204);
    }
}
