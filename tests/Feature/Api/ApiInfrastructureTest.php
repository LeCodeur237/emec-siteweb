<?php

namespace Tests\Feature\Api;

use App\Support\Api\ApiQueryParameters;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class ApiInfrastructureTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        RateLimiter::clear('127.0.0.1');
    }

    public function test_api_v1_info_endpoint_returns_json_identity(): void
    {
        $response = $this->getJson('/api/v1');

        $response
            ->assertOk()
            ->assertHeader('content-type', 'application/json')
            ->assertExactJson([
                'name' => 'EMEC API',
                'version' => 'v1',
            ]);
    }

    public function test_api_v1_health_endpoint_returns_json_status(): void
    {
        $response = $this->getJson('/api/v1/health');

        $response
            ->assertOk()
            ->assertHeader('content-type', 'application/json')
            ->assertHeader('X-Content-Type-Options', 'nosniff')
            ->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin')
            ->assertHeader('Permissions-Policy', 'camera=(), microphone=(), geolocation=()')
            ->assertExactJson([
                'status' => 'ok',
            ]);
    }

    public function test_api_not_found_errors_are_json(): void
    {
        $response = $this->getJson('/api/v1/missing-route');

        $response
            ->assertNotFound()
            ->assertHeader('content-type', 'application/json')
            ->assertExactJson([
                'message' => 'Resource not found.',
            ]);
    }

    public function test_api_rate_limiter_returns_too_many_requests(): void
    {
        Config::set('api.rate_limit.per_minute', 1);

        $this->getJson('/api/v1/health')->assertOk();

        $this->getJson('/api/v1/health')
            ->assertStatus(429)
            ->assertHeader('content-type', 'application/json')
            ->assertJson([
                'message' => 'Too Many Attempts.',
            ]);
    }

    public function test_api_query_parameters_limit_pagination_and_secure_sort(): void
    {
        Config::set('api.pagination.default_per_page', 20);
        Config::set('api.pagination.max_per_page', 100);

        $request = Request::create('/api/v1/example', 'GET', [
            'per_page' => 100000,
            'sort' => 'unsafe_column',
            'direction' => 'asc',
        ]);

        $this->assertSame(100, ApiQueryParameters::perPage($request));
        $this->assertSame('created_at', ApiQueryParameters::sort($request, ['created_at', 'title']));
        $this->assertSame('asc', ApiQueryParameters::direction($request));
    }
}
