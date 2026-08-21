<?php

namespace App\Support\Api;

use Illuminate\Http\Request;

class ApiQueryParameters
{
    public static function perPage(Request $request): int
    {
        $default = max(1, (int) config('api.pagination.default_per_page', 20));
        $max = max($default, (int) config('api.pagination.max_per_page', 100));
        $requested = (int) $request->integer('per_page', $default);

        return min(max(1, $requested), $max);
    }

    /**
     * @param  array<int, string>  $allowedSorts
     */
    public static function sort(Request $request, array $allowedSorts, string $default = 'created_at'): string
    {
        $requested = (string) $request->query('sort', $default);

        if (! in_array($requested, $allowedSorts, true)) {
            return $default;
        }

        return $requested;
    }

    public static function direction(Request $request): string
    {
        return strtolower((string) $request->query('direction')) === 'asc' ? 'asc' : 'desc';
    }
}
