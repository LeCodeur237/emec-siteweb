<?php

namespace Tests\Feature\Api;

use App\Models\Role;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AdminAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolePermissionSeeder::class);
    }

    public function test_active_user_can_login_and_receives_sanctum_token(): void
    {
        $user = User::factory()->create([
            'email' => 'admin@example.test',
            'password' => Hash::make('secret-password'),
            'status' => 'active',
        ]);
        $user->roles()->attach(Role::where('slug', 'messages_editor')->firstOrFail());

        $this->postJson('/api/v1/auth/login', [
            'email' => 'admin@example.test',
            'password' => 'secret-password',
        ])
            ->assertOk()
            ->assertJsonPath('message', 'Authenticated.')
            ->assertJsonPath('token_type', 'Bearer')
            ->assertJsonPath('user.email', 'admin@example.test')
            ->assertJsonPath('user.roles.0.slug', 'messages_editor')
            ->assertJsonMissingPath('user.password')
            ->assertJsonMissingPath('user.remember_token')
            ->assertJsonStructure([
                'access_token',
                'user' => [
                    'id',
                    'name',
                    'email',
                    'roles',
                    'permissions',
                ],
            ]);

        $this->assertSame(1, $user->tokens()->count());
    }

    public function test_login_rejects_bad_password_missing_user_and_inactive_user_generically(): void
    {
        User::factory()->create([
            'email' => 'known@example.test',
            'password' => Hash::make('secret-password'),
            'status' => 'active',
        ]);

        User::factory()->create([
            'email' => 'inactive@example.test',
            'password' => Hash::make('secret-password'),
            'status' => 'inactive',
        ]);

        foreach ([
            ['email' => 'known@example.test', 'password' => 'bad-password'],
            ['email' => 'missing@example.test', 'password' => 'secret-password'],
            ['email' => 'inactive@example.test', 'password' => 'secret-password'],
        ] as $payload) {
            $this->postJson('/api/v1/auth/login', $payload)
                ->assertUnprocessable()
                ->assertJsonPath('message', 'Validation failed.')
                ->assertJsonPath('errors.email.0', 'The provided credentials are invalid.');
        }
    }

    public function test_auth_me_requires_authentication_and_returns_current_user(): void
    {
        $this->getJson('/api/v1/auth/me')->assertUnauthorized();

        $user = User::factory()->create(['status' => 'active']);
        $user->roles()->attach(Role::where('slug', 'messages_editor')->firstOrFail());

        Sanctum::actingAs($user);

        $this->getJson('/api/v1/auth/me')
            ->assertOk()
            ->assertJsonPath('data.id', $user->id)
            ->assertJsonPath('data.roles.0.slug', 'messages_editor')
            ->assertJsonMissingPath('data.password')
            ->assertJsonMissingPath('data.tokens');
    }

    public function test_logout_revokes_current_token(): void
    {
        $user = User::factory()->create(['status' => 'active']);
        $token = $user->createToken('admin-api')->plainTextToken;

        $this->withToken($token)
            ->postJson('/api/v1/auth/logout')
            ->assertOk()
            ->assertJsonPath('message', 'Logged out.');

        $this->assertSame(0, $user->tokens()->count());

        $this->app['auth']->forgetGuards();

        $this->withToken($token)
            ->getJson('/api/v1/auth/me')
            ->assertUnauthorized();
    }
}
