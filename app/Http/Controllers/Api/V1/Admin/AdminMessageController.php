<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Api\V1\ApiController;
use App\Http\Requests\Api\V1\Admin\AdminMessageIndexRequest;
use App\Http\Requests\Api\V1\Admin\StoreMessageRequest;
use App\Http\Requests\Api\V1\Admin\UpdateMessageRequest;
use App\Http\Resources\Api\V1\Admin\AdminMessageResource;
use App\Models\Message;
use App\Support\Api\ApiQueryParameters;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

class AdminMessageController extends ApiController
{
    public function index(AdminMessageIndexRequest $request): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Message::class);

        $query = Message::query()
            ->with(['preacher', 'category', 'series'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search')->toString();
                $query->where(function ($query) use ($search) {
                    $query->where('title', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%")
                        ->orWhere('excerpt', 'like', "%{$search}%")
                        ->orWhere('content', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('preacher_id'), fn ($query) => $query->where('preacher_id', $request->integer('preacher_id')))
            ->when($request->filled('message_category_id'), fn ($query) => $query->where('message_category_id', $request->integer('message_category_id')))
            ->when($request->filled('message_series_id'), fn ($query) => $query->where('message_series_id', $request->integer('message_series_id')))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')->toString()))
            ->when($request->filled('featured'), fn ($query) => $query->where('featured', $request->boolean('featured')));

        $sort = ApiQueryParameters::sort($request, ['preached_at', 'title', 'created_at', 'updated_at', 'views'], 'created_at');
        $direction = $request->filled('direction') ? ApiQueryParameters::direction($request) : 'desc';

        return AdminMessageResource::collection(
            $query->orderBy($sort, $direction)->paginate(ApiQueryParameters::perPage($request))
        );
    }

    public function show(Message $message): AdminMessageResource
    {
        $this->authorize('view', $message);

        return new AdminMessageResource($message->load(['preacher', 'category', 'series', 'media']));
    }

    public function store(StoreMessageRequest $request): AdminMessageResource
    {
        $this->authorize('create', Message::class);

        $data = $this->validatedMessageData($request->validated(), $request->user());
        $message = Message::create($data);
        $message->refresh();

        return new AdminMessageResource($message->load(['preacher', 'category', 'series', 'media']));
    }

    public function update(UpdateMessageRequest $request, Message $message): AdminMessageResource
    {
        $this->authorize('update', $message);

        $data = $this->validatedMessageData($request->validated(), $request->user());
        $message->fill($data);
        $message->save();

        return new AdminMessageResource($message->load(['preacher', 'category', 'series', 'media']));
    }

    public function destroy(Message $message): JsonResponse
    {
        $this->authorize('delete', $message);

        $message->media()->detach();
        $message->delete();

        return response()->json(null, 204);
    }

    private function validatedMessageData(array $data, $user): array
    {
        unset($data['id'], $data['views'], $data['created_at'], $data['updated_at']);

        if (($data['status'] ?? null) === 'published' && Gate::forUser($user)->denies('publish', Message::class)) {
            throw ValidationException::withMessages([
                'status' => ['You are not allowed to publish messages.'],
            ]);
        }

        if (array_key_exists('featured', $data)) {
            $data['featured'] = (bool) $data['featured'];
        }

        return $data;
    }
}
