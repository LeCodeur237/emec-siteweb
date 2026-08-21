<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Api\V1\ApiController;
use App\Http\Requests\Api\V1\Admin\AdminCommunicationIndexRequest;
use App\Http\Requests\Api\V1\Admin\StoreContactMessageRequest;
use App\Http\Requests\Api\V1\Admin\UpdateContactMessageRequest;
use App\Http\Resources\Api\V1\Admin\AdminContactMessageResource;
use App\Models\ContactMessage;
use App\Support\Api\ApiQueryParameters;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AdminContactMessageController extends ApiController
{
    public function index(AdminCommunicationIndexRequest $request): AnonymousResourceCollection
    {
        $this->authorize('viewAny', ContactMessage::class);

        $query = ContactMessage::query()
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')->toString()))
            ->when($request->filled('from'), fn ($query) => $query->whereDate('created_at', '>=', $request->date('from')->toDateString()))
            ->when($request->filled('to'), fn ($query) => $query->whereDate('created_at', '<=', $request->date('to')->toDateString()))
            ->when($request->filled('search'), fn ($query) => $query->where(fn ($query) => $query
                ->where('name', 'like', '%'.$request->string('search')->toString().'%')
                ->orWhere('email', 'like', '%'.$request->string('search')->toString().'%')
                ->orWhere('phone', 'like', '%'.$request->string('search')->toString().'%')
                ->orWhere('subject', 'like', '%'.$request->string('search')->toString().'%')
                ->orWhere('message', 'like', '%'.$request->string('search')->toString().'%')));

        $sort = ApiQueryParameters::sort($request, ['created_at', 'updated_at', 'read_at', 'answered_at', 'name', 'email'], 'created_at');

        return AdminContactMessageResource::collection(
            $query->orderBy($sort, ApiQueryParameters::direction($request))->paginate(ApiQueryParameters::perPage($request))
        );
    }

    public function show(ContactMessage $contactMessage): AdminContactMessageResource
    {
        $this->authorize('view', $contactMessage);

        return new AdminContactMessageResource($contactMessage);
    }

    public function store(StoreContactMessageRequest $request): AdminContactMessageResource
    {
        $this->authorize('create', ContactMessage::class);

        return new AdminContactMessageResource(ContactMessage::create($request->validated()));
    }

    public function update(UpdateContactMessageRequest $request, ContactMessage $contactMessage): AdminContactMessageResource
    {
        $this->authorize('update', $contactMessage);

        $contactMessage->fill($request->validated())->save();

        return new AdminContactMessageResource($contactMessage);
    }

    public function destroy(ContactMessage $contactMessage): JsonResponse
    {
        $this->authorize('delete', $contactMessage);

        $contactMessage->delete();

        return response()->json(null, 204);
    }
}
