<?php

namespace Tests\Feature\Api;

use App\Models\Message;
use App\Models\Role;
use App\Models\SocialAction;
use App\Models\SocialProject;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AdminAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolePermissionSeeder::class);
    }

    public function test_admin_profile_and_dashboard_require_authentication(): void
    {
        $this->getJson('/api/v1/admin/me')->assertUnauthorized();
        $this->getJson('/api/v1/admin/dashboard')->assertUnauthorized();
    }

    public function test_admin_me_returns_roles_and_permissions(): void
    {
        $user = $this->userWithRole('messages_editor');

        Sanctum::actingAs($user);

        $this->getJson('/api/v1/admin/me')
            ->assertOk()
            ->assertJsonPath('data.id', $user->id)
            ->assertJsonPath('data.roles.0.slug', 'messages_editor')
            ->assertJsonFragment(['slug' => 'messages.view'])
            ->assertJsonMissingPath('data.password')
            ->assertJsonMissingPath('data.remember_token');
    }

    public function test_dashboard_only_exposes_counts_allowed_by_permissions(): void
    {
        Message::factory()->count(2)->create();
        SocialProject::factory()->count(3)->create();
        SocialAction::factory()->count(4)->create(['social_project_id' => null]);
        User::factory()->count(2)->create();

        Sanctum::actingAs($this->userWithRole('messages_editor'));

        $this->getJson('/api/v1/admin/dashboard')
            ->assertOk()
            ->assertJsonPath('data.messages_count', 2)
            ->assertJsonMissingPath('data.social_projects_count')
            ->assertJsonMissingPath('data.users_count');

        Sanctum::actingAs($this->userWithRole('dosc_editor'));

        $this->getJson('/api/v1/admin/dashboard')
            ->assertOk()
            ->assertJsonPath('data.social_projects_count', 3)
            ->assertJsonPath('data.social_actions_count', 4)
            ->assertJsonMissingPath('data.messages_count')
            ->assertJsonMissingPath('data.users_count');
    }

    public function test_roles_gate_message_admin_access_correctly(): void
    {
        Message::factory()->create(['status' => 'draft']);

        Sanctum::actingAs($this->userWithRole('messages_editor'));
        $this->getJson('/api/v1/admin/messages')->assertOk();

        Sanctum::actingAs($this->userWithRole('super_admin'));
        $this->getJson('/api/v1/admin/messages')->assertOk();

        Sanctum::actingAs($this->userWithRole('dosc_editor'));
        $this->getJson('/api/v1/admin/messages')->assertForbidden();
    }

    private function userWithRole(string $roleSlug): User
    {
        $user = User::factory()->create(['status' => 'active']);
        $user->roles()->attach(Role::where('slug', $roleSlug)->firstOrFail());

        return $user;
    }
}
