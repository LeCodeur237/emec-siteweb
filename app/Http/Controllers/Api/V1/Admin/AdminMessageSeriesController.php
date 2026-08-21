<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Api\V1\ApiController;
use App\Http\Requests\Api\V1\Admin\AdminMessageTaxonomyIndexRequest;
use App\Http\Requests\Api\V1\Admin\StoreMessageSeriesRequest;
use App\Http\Requests\Api\V1\Admin\UpdateMessageSeriesRequest;
use App\Http\Resources\Api\V1\Admin\AdminMessageSeriesResource;
use App\Models\MessageSeries;
use App\Support\Api\ApiQueryParameters;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AdminMessageSeriesController extends ApiController
{
    public function index(AdminMessageTaxonomyIndexRequest $request): AnonymousResourceCollection
    {
        $this->authorize('viewAny', MessageSeries::class);

        $query = MessageSeries::query()
            ->withCount('messages')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search')->toString();
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('active'), fn ($query) => $query->where('active', $request->boolean('active')));

        $sort = ApiQueryParameters::sort($request, ['name', 'created_at', 'updated_at'], 'name');
        $direction = $request->filled('direction') ? ApiQueryParameters::direction($request) : 'asc';

        return AdminMessageSeriesResource::collection(
            $query->orderBy($sort, $direction)->paginate(ApiQueryParameters::perPage($request))
        );
    }

    public function show(MessageSeries $messageSeries): AdminMessageSeriesResource
    {
        $this->authorize('view', $messageSeries);

        return new AdminMessageSeriesResource($messageSeries->load('media')->loadCount('messages'));
    }

    public function store(StoreMessageSeriesRequest $request): AdminMessageSeriesResource
    {
        $this->authorize('create', MessageSeries::class);

        $series = MessageSeries::create($request->validated());

        return new AdminMessageSeriesResource($series->load('media')->loadCount('messages'));
    }

    public function update(UpdateMessageSeriesRequest $request, MessageSeries $messageSeries): AdminMessageSeriesResource
    {
        $this->authorize('update', $messageSeries);

        $messageSeries->fill($request->validated());
        $messageSeries->save();

        return new AdminMessageSeriesResource($messageSeries->load('media')->loadCount('messages'));
    }

    public function destroy(MessageSeries $messageSeries): JsonResponse
    {
        $this->authorize('delete', $messageSeries);

        $messageSeries->media()->detach();
        $messageSeries->delete();

        return response()->json(null, 204);
    }
}
