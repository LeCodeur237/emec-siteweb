<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\Public\StorePublicContactMessageRequest;
use App\Http\Resources\Api\V1\Public\PublicContactMessageResource;
use App\Services\ContactMessageService;

class ContactMessageController extends ApiController
{
    public function store(StorePublicContactMessageRequest $request, ContactMessageService $service): PublicContactMessageResource
    {
        return new PublicContactMessageResource($service->create($request->validated()));
    }
}
