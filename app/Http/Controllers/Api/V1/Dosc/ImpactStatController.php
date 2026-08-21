<?php

namespace App\Http\Controllers\Api\V1\Dosc;

use App\Http\Controllers\Api\V1\ApiController;
use App\Http\Resources\Api\V1\Dosc\ImpactStatResource;
use App\Models\ImpactStat;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ImpactStatController extends ApiController
{
    public function index(): AnonymousResourceCollection
    {
        return ImpactStatResource::collection(
            ImpactStat::query()
                ->where('active', true)
                ->orderBy('sort_order')
                ->get()
        );
    }
}
