<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Api\V1\ApiController;
use App\Http\Requests\Api\V1\Admin\AdminCommunicationIndexRequest;
use App\Http\Requests\Api\V1\Admin\StoreNewsletterSubscriberRequest;
use App\Http\Requests\Api\V1\Admin\UpdateNewsletterSubscriberRequest;
use App\Http\Resources\Api\V1\Admin\AdminNewsletterSubscriberResource;
use App\Models\NewsletterSubscriber;
use App\Support\Api\ApiQueryParameters;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AdminNewsletterSubscriberController extends ApiController
{
    public function index(AdminCommunicationIndexRequest $request): AnonymousResourceCollection
    {
        $this->authorize('viewAny', NewsletterSubscriber::class);

        $query = NewsletterSubscriber::query()
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')->toString()))
            ->when($request->filled('from'), fn ($query) => $query->whereDate('subscribed_at', '>=', $request->date('from')->toDateString()))
            ->when($request->filled('to'), fn ($query) => $query->whereDate('subscribed_at', '<=', $request->date('to')->toDateString()))
            ->when($request->filled('search'), fn ($query) => $query->where(fn ($query) => $query
                ->where('name', 'like', '%'.$request->string('search')->toString().'%')
                ->orWhere('email', 'like', '%'.$request->string('search')->toString().'%')));

        $sort = ApiQueryParameters::sort($request, ['created_at', 'updated_at', 'subscribed_at', 'unsubscribed_at', 'name', 'email'], 'subscribed_at');

        return AdminNewsletterSubscriberResource::collection(
            $query->orderBy($sort, ApiQueryParameters::direction($request))->paginate(ApiQueryParameters::perPage($request))
        );
    }

    public function show(NewsletterSubscriber $newsletterSubscriber): AdminNewsletterSubscriberResource
    {
        $this->authorize('view', $newsletterSubscriber);

        return new AdminNewsletterSubscriberResource($newsletterSubscriber);
    }

    public function store(StoreNewsletterSubscriberRequest $request): AdminNewsletterSubscriberResource
    {
        $this->authorize('create', NewsletterSubscriber::class);

        return new AdminNewsletterSubscriberResource(NewsletterSubscriber::create($request->validated()));
    }

    public function update(UpdateNewsletterSubscriberRequest $request, NewsletterSubscriber $newsletterSubscriber): AdminNewsletterSubscriberResource
    {
        $this->authorize('update', $newsletterSubscriber);

        $newsletterSubscriber->fill($request->validated())->save();

        return new AdminNewsletterSubscriberResource($newsletterSubscriber);
    }

    public function destroy(NewsletterSubscriber $newsletterSubscriber): JsonResponse
    {
        $this->authorize('delete', $newsletterSubscriber);

        $newsletterSubscriber->delete();

        return response()->json(null, 204);
    }
}
