<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\JsonResponse;

class ApiInfoController extends ApiController
{
    public function __invoke(): JsonResponse
    {
        return response()->json([
            'name' => config('api.name', 'EMEC API'),
            'version' => config('api.version', 'v1'),
        ]);
    }
}
