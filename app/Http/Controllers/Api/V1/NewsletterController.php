<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\Public\SubscribeNewsletterRequest;
use App\Http\Requests\Api\V1\Public\UnsubscribeNewsletterRequest;
use App\Http\Resources\Api\V1\Public\PublicNewsletterSubscriberResource;
use App\Services\NewsletterService;

class NewsletterController extends ApiController
{
    public function subscribe(SubscribeNewsletterRequest $request, NewsletterService $service)
    {
        [$subscriber, $created] = $service->subscribe($request->validated());

        return (new PublicNewsletterSubscriberResource($subscriber))->response()->setStatusCode($created ? 201 : 200);
    }

    public function unsubscribe(UnsubscribeNewsletterRequest $request, NewsletterService $service): PublicNewsletterSubscriberResource
    {
        return new PublicNewsletterSubscriberResource($service->unsubscribe($request->validated()));
    }
}
