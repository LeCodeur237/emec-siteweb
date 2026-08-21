<?php

namespace Tests\Feature\Api;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AdminUserRolePermissionCrudTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolePermissionSeeder::class);
    }

    public function test_user_role_permission_admin_routes_require_authentication(): void
    {
        $user = User::factory()->create();
        $role = Role::where('slug', 'editor')->firstOrFail();
        $permission = Permission::where('slug', 'messages.view')->firstOrFail();

        foreach ([
            ['getJson', '/api/v1/admin/users'],
            ['getJson', "/api/v1/admin/users/{$user->id}"],
            ['postJson', '/api/v1/admin/users'],
            ['patchJson', "/api/v1/admin/users/{$user->id}"],
            ['deleteJson', "/api/v1/admin/users/{$user->id}"],
            ['getJson', '/api/v1/admin/roles'],
            ['getJson', "/api/v1/admin/roles/{$role->id}"],
            ['postJson', '/api/v1/admin/roles'],
            ['patchJson', "/api/v1/admin/roles/{$role->id}"],
            ['deleteJson', "/api/v1/admin/roles/{$role->id}"],
            ['getJson', '/api/v1/admin/permissions'],
            ['getJson', "/api/v1/admin/permissions/{$permission->id}"],
            ['postJson', '/api/v1/admin/permissions'],
            ['patchJson', "/api/v1/admin/permissions/{$permission->id}"],
            ['deleteJson', "/api/v1/admin/permissions/{$permission->id}"],
        ] as [$method, $uri]) {
            $this->{$method}($uri, [])->assertUnauthorized();
        }
    }

    public function test_user_without_rbac_permissions_is_forbidden(): void
    {
        $user = User::factory()->create();
        $role = Role::where('slug', 'editor')->firstOrFail();
        $permission = Permission::where('slug', 'messages.view')->firstOrFail();

        Sanctum::actingAs($this->userWithRole('messages_editor'));

        $this->getJson('/api/v1/admin/users')->assertForbidden();
        $this->postJson('/api/v1/admin/users', ['name' => 'Interdit'])->assertForbidden();
        $this->patchJson("/api/v1/admin/users/{$user->id}", ['name' => 'Interdit'])->assertForbidden();
        $this->deleteJson("/api/v1/admin/users/{$user->id}")->assertForbidden();

        $this->getJson('/api/v1/admin/roles')->assertForbidden();
        $this->postJson('/api/v1/admin/roles', ['name' => 'Interdit'])->assertForbidden();
        $this->patchJson("/api/v1/admin/roles/{$role->id}", ['name' => 'Interdit'])->assertForbidden();
        $this->deleteJson("/api/v1/admin/roles/{$role->id}")->assertForbidden();

        $this->getJson('/api/v1/admin/permissions')->assertForbidden();
        $this->postJson('/api/v1/admin/permissions', ['name' => 'Interdit'])->assertForbidden();
        $this->patchJson("/api/v1/admin/permissions/{$permission->id}", ['name' => 'Interdit'])->assertForbidden();
        $this->deleteJson("/api/v1/admin/permissions/{$permission->id}")->assertForbidden();
    }

    public function test_admin_can_crud_users_and_sync_roles_without_exposing_passwords(): void
    {
        $editor = Role::where('slug', 'editor')->firstOrFail();
        $messagesEditor = Role::where('slug', 'messages_editor')->firstOrFail();
        $existing = User::factory()->create([
            'name' => 'Alpha Admin',
            'email' => 'alpha-admin@example.test',
            'status' => 'active',
        ]);
        $existing->roles()->attach($editor);

        Sanctum::actingAs($this->userWithRole('admin'));

        $this->getJson("/api/v1/admin/users?search=Alpha&status=active&role_id={$editor->id}&sort=name&direction=asc&per_page=100000")
            ->assertOk()
            ->assertJsonPath('meta.per_page', 100)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.email', 'alpha-admin@example.test')
            ->assertJsonMissingPath('data.0.password')
            ->assertJsonMissingPath('data.0.remember_token');

        $createdId = $this->postJson('/api/v1/admin/users', [
            'name' => 'Nouvel Admin',
            'email' => 'nouvel-admin@example.test',
            'password' => 'password-secret',
            'phone' => '699000000',
            'status' => 'active',
            'role_ids' => [$editor->id],
        ])
            ->assertCreated()
            ->assertJsonPath('data.roles.0.slug', 'editor')
            ->assertJsonMissingPath('data.password')
            ->json('data.id');

        $this->patchJson("/api/v1/admin/users/{$createdId}", [
            'status' => 'inactive',
            'password' => null,
            'role_ids' => [$messagesEditor->id],
        ])
            ->assertOk()
            ->assertJsonPath('data.status', 'inactive')
            ->assertJsonPath('data.roles.0.slug', 'messages_editor')
            ->assertJsonMissingPath('data.password');

        $this->assertDatabaseHas('role_user', [
            'user_id' => $createdId,
            'role_id' => $messagesEditor->id,
        ]);

        $this->deleteJson("/api/v1/admin/users/{$createdId}")->assertNoContent();
        $this->assertDatabaseMissing('users', ['id' => $createdId]);
    }

    public function test_super_admin_can_crud_roles_and_permissions(): void
    {
        $permission = Permission::where('slug', 'messages.view')->firstOrFail();

        Sanctum::actingAs($this->userWithRole('super_admin'));

        $this->getJson('/api/v1/admin/permissions?search=messages.view&sort=slug&direction=asc')
            ->assertOk()
            ->assertJsonFragment(['slug' => 'messages.view']);

        $permissionId = $this->postJson('/api/v1/admin/permissions', [
            'name' => 'Custom Permission',
            'slug' => 'custom.permission',
            'description' => 'Permission de test.',
        ])
            ->assertCreated()
            ->assertJsonPath('data.slug', 'custom.permission')
            ->json('data.id');

        $roleId = $this->postJson('/api/v1/admin/roles', [
            'name' => 'Custom Role',
            'description' => 'Role de test.',
            'permission_ids' => [$permission->id, $permissionId],
        ])
            ->assertCreated()
            ->assertJsonPath('data.slug', 'custom-role')
            ->assertJsonCount(2, 'data.permissions')
            ->json('data.id');

        $this->getJson("/api/v1/admin/roles?search=custom&permission_id={$permissionId}")
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $roleId);

        $this->patchJson("/api/v1/admin/roles/{$roleId}", [
            'slug' => 'custom-role-updated',
            'permission_ids' => [$permissionId],
        ])
            ->assertOk()
            ->assertJsonPath('data.slug', 'custom-role-updated')
            ->assertJsonCount(1, 'data.permissions');

        $this->patchJson("/api/v1/admin/permissions/{$permissionId}", ['description' => 'Permission modifiee.'])
            ->assertOk()
            ->assertJsonPath('data.description', 'Permission modifiee.');

        $this->deleteJson("/api/v1/admin/roles/{$roleId}")->assertNoContent();
        $this->deleteJson("/api/v1/admin/permissions/{$permissionId}")->assertNoContent();
        $this->assertDatabaseMissing('roles', ['id' => $roleId]);
        $this->assertDatabaseMissing('permissions', ['id' => $permissionId]);
    }

    public function test_rbac_validation_rejects_invalid_payloads(): void
    {
        $existingUser = User::factory()->create(['email' => 'duplicate-user@example.test']);
        $existingRole = Role::where('slug', 'editor')->firstOrFail();
        $existingPermission = Permission::where('slug', 'messages.view')->firstOrFail();

        Sanctum::actingAs($this->userWithRole('admin'));

        $this->postJson('/api/v1/admin/users', [
            'name' => '',
            'email' => $existingUser->email,
            'password' => 'short',
            'status' => 'blocked',
            'role_ids' => [999999],
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'email', 'password', 'status', 'role_ids.0']);

        Sanctum::actingAs($this->userWithRole('super_admin'));

        $this->postJson('/api/v1/admin/roles', [
            'name' => '',
            'slug' => $existingRole->slug,
            'permission_ids' => [999999],
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'slug', 'permission_ids.0']);

        $this->postJson('/api/v1/admin/permissions', [
            'name' => '',
            'slug' => $existingPermission->slug,
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'slug']);
    }

    public function test_missing_rbac_resources_return_not_found(): void
    {
        Sanctum::actingAs($this->userWithRole('admin'));

        $this->getJson('/api/v1/admin/users/999999')->assertNotFound();

        Sanctum::actingAs($this->userWithRole('super_admin'));

        $this->getJson('/api/v1/admin/roles/999999')->assertNotFound();
        $this->getJson('/api/v1/admin/permissions/999999')->assertNotFound();
    }

    private function userWithRole(string $roleSlug): User
    {
        $user = User::factory()->create(['status' => 'active']);
        $user->roles()->attach(Role::where('slug', $roleSlug)->firstOrFail());

        return $user;
    }
}
