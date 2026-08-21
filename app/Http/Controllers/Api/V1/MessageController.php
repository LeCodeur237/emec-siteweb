<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\MessageIndexRequest;
use App\Http\Resources\Api\V1\MessageResource;
use App\Models\Message;
use App\Support\Api\ApiQueryParameters;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class MessageController extends ApiController
{
    public function index(MessageIndexRequest $request): AnonymousResourceCollection
    {
        $query = Message::query()
            ->where('status', 'published')
            ->with(['preacher', 'category', 'series']);

        $query
            ->where(function ($query) {
                $query->whereNull('preacher_id')
                    ->orWhereHas('preacher', fn ($query) => $query->where('active', true));
            })
            ->where(function ($query) {
                $query->whereNull('message_category_id')
                    ->orWhereHas('category', fn ($query) => $query->where('active', true));
            })
            ->where(function ($query) {
                $query->whereNull('message_series_id')
                    ->orWhereHas('series', fn ($query) => $query->where('active', true));
            })
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search')->toString();
                $query->where(function ($query) use ($search) {
                    $query->where('title', 'like', "%{$search}%")
                        ->orWhere('excerpt', 'like', "%{$search}%")
                        ->orWhere('content', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('preacher_id'), fn ($query) => $query->where('preacher_id', $request->integer('preacher_id')))
            ->when($request->filled('message_category_id'), fn ($query) => $query->where('message_category_id', $request->integer('message_category_id')))
            ->when($request->filled('message_series_id'), fn ($query) => $query->where('message_series_id', $request->integer('message_series_id')))
            ->when($request->filled('featured'), fn ($query) => $query->where('featured', $request->boolean('featured')))
            ->when($request->filled('from'), fn ($query) => $query->whereDate('preached_at', '>=', $request->date('from')->toDateString()))
            ->when($request->filled('to'), fn ($query) => $query->whereDate('preached_at', '<=', $request->date('to')->toDateString()));

        $sort = ApiQueryParameters::sort($request, ['preached_at', 'title', 'created_at', 'views'], 'preached_at');
        $direction = $request->filled('direction') ? ApiQueryParameters::direction($request) : 'desc';

        $query->when($sort === 'preached_at', fn ($query) => $query->orderByRaw('preached_at IS NULL asc'));

        return MessageResource::collection(
            $query->orderBy($sort, $direction)
                ->paginate(ApiQueryParameters::perPage($request))
        );
    }

    public function show(string $slug): MessageResource
    {
        $message = Message::query()
            ->where('slug', $slug)
            ->where('status', 'published')
            ->with(['preacher', 'category', 'series', 'media'])
            ->where(function ($query) {
                $query->whereNull('preacher_id')
                    ->orWhereHas('preacher', fn ($query) => $query->where('active', true));
            })
            ->where(function ($query) {
                $query->whereNull('message_category_id')
                    ->orWhereHas('category', fn ($query) => $query->where('active', true));
            })
            ->where(function ($query) {
                $query->whereNull('message_series_id')
                    ->orWhereHas('series', fn ($query) => $query->where('active', true));
            })
            ->firstOrFail();

        return new MessageResource($message);
    }
}
