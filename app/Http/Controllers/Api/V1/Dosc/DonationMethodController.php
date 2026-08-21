<?php

namespace App\Http\Controllers\Api\V1\Dosc;

use App\Http\Controllers\Api\V1\ApiController;
use App\Http\Resources\Api\V1\Dosc\DonationMethodResource;
use App\Models\DonationMethod;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class DonationMethodController extends ApiController
{
    public function index(): AnonymousResourceCollection
    {
        return DonationMethodResource::collection(
            DonationMethod::query()
                ->where('active', true)
                ->orderBy('type')
                ->orderBy('name')
                ->get()
        );
    }
}
