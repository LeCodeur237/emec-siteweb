<?php

namespace Tests\Feature\Api;

use App\Models\Role;
use App\Models\SiteSetting;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AdminSiteSettingCrudTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolePermissionSeeder::class);
    }

    public function test_site_setting_admin_routes_require_authentication(): void
    {
        $setting = SiteSetting::create([
            'key' => 'site.name',
            'value' => 'EMEC',
            'type' => 'string',
            'group' => 'general',
        ]);

        foreach ([
            ['getJson', '/api/v1/admin/site-settings'],
            ['getJson', "/api/v1/admin/site-settings/{$setting->id}"],
            ['postJson', '/api/v1/admin/site-settings'],
            ['patchJson', "/api/v1/admin/site-settings/{$setting->id}"],
            ['deleteJson', "/api/v1/admin/site-settings/{$setting->id}"],
        ] as [$method, $uri]) {
            $this->{$method}($uri, [])->assertUnauthorized();
        }
    }

    public function test_user_without_settings_permission_is_forbidden(): void
    {
        $setting = SiteSetting::create([
            'key' => 'site.name',
            'value' => 'EMEC',
            'type' => 'string',
            'group' => 'general',
        ]);

        Sanctum::actingAs($this->userWithRole('messages_editor'));

        $this->getJson('/api/v1/admin/site-settings')->assertForbidden();
        $this->postJson('/api/v1/admin/site-settings', ['key' => 'site.email'])->assertForbidden();
        $this->patchJson("/api/v1/admin/site-settings/{$setting->id}", ['value' => 'Non'])->assertForbidden();
        $this->deleteJson("/api/v1/admin/site-settings/{$setting->id}")->assertForbidden();
    }

    public function test_admin_can_crud_site_settings(): void
    {
        SiteSetting::create([
            'key' => 'site.name',
            'value' => 'EMEC',
            'type' => 'string',
            'group' => 'general',
        ]);
        SiteSetting::create([
            'key' => 'seo.description',
            'value' => 'Archive',
            'type' => 'text',
            'group' => 'seo',
        ]);

        Sanctum::actingAs($this->userWithRole('admin'));

        $this->getJson('/api/v1/admin/site-settings?search=site&type=string&group=general&sort=key&direction=asc&per_page=100000')
            ->assertOk()
            ->assertJsonPath('meta.per_page', 100)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.key', 'site.name');

        $createdId = $this->postJson('/api/v1/admin/site-settings', [
            'key' => 'site.contact_email',
            'value' => 'contact@example.test',
            'type' => 'email',
            'group' => 'contact',
        ])
            ->assertCreated()
            ->assertJsonPath('data.key', 'site.contact_email')
            ->assertJsonPath('data.type', 'email')
            ->json('data.id');

        $this->patchJson("/api/v1/admin/site-settings/{$createdId}", [
            'key' => 'site.contact.url',
            'value' => 'https://example.test/contact',
            'type' => 'url',
        ])
            ->assertOk()
            ->assertJsonPath('data.key', 'site.contact.url')
            ->assertJsonPath('data.type', 'url');

        $this->deleteJson("/api/v1/admin/site-settings/{$createdId}")->assertNoContent();
        $this->assertDatabaseMissing('site_settings', ['id' => $createdId]);
    }

    public function test_site_setting_validation_rejects_invalid_payloads(): void
    {
        SiteSetting::create([
            'key' => 'duplicate.key',
            'value' => '1',
            'type' => 'string',
        ]);

        Sanctum::actingAs($this->userWithRole('admin'));

        $this->postJson('/api/v1/admin/site-settings', [
            'key' => 'Duplicate Key',
            'type' => 'secret',
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['key', 'type']);

        $this->postJson('/api/v1/admin/site-settings', [
            'key' => 'duplicate.key',
            'value' => '2',
            'type' => 'string',
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['key']);
    }

    public function test_missing_site_setting_returns_not_found(): void
    {
        Sanctum::actingAs($this->userWithRole('admin'));

        $this->getJson('/api/v1/admin/site-settings/999999')->assertNotFound();
    }

    private function userWithRole(string $roleSlug): User
    {
        $user = User::factory()->create(['status' => 'active']);
        $user->roles()->attach(Role::where('slug', $roleSlug)->firstOrFail());

        return $user;
    }
}
