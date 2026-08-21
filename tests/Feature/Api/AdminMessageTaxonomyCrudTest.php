<?php

namespace Tests\Feature\Api;

use App\Models\Media;
use App\Models\Message;
use App\Models\MessageCategory;
use App\Models\MessageSeries;
use App\Models\Preacher;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AdminMessageTaxonomyCrudTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolePermissionSeeder::class);
    }

    public function test_message_taxonomy_admin_routes_require_authentication(): void
    {
        $preacher = Preacher::factory()->create();
        $category = MessageCategory::factory()->create();
        $series = MessageSeries::factory()->create();

        foreach ([
            ['getJson', '/api/v1/admin/preachers'],
            ['getJson', "/api/v1/admin/preachers/{$preacher->id}"],
            ['postJson', '/api/v1/admin/preachers'],
            ['patchJson', "/api/v1/admin/preachers/{$preacher->id}"],
            ['deleteJson', "/api/v1/admin/preachers/{$preacher->id}"],
            ['getJson', '/api/v1/admin/message-categories'],
            ['getJson', "/api/v1/admin/message-categories/{$category->id}"],
            ['postJson', '/api/v1/admin/message-categories'],
            ['patchJson', "/api/v1/admin/message-categories/{$category->id}"],
            ['deleteJson', "/api/v1/admin/message-categories/{$category->id}"],
            ['getJson', '/api/v1/admin/message-series'],
            ['getJson', "/api/v1/admin/message-series/{$series->id}"],
            ['postJson', '/api/v1/admin/message-series'],
            ['patchJson', "/api/v1/admin/message-series/{$series->id}"],
            ['deleteJson', "/api/v1/admin/message-series/{$series->id}"],
        ] as [$method, $uri]) {
            $this->{$method}($uri, [])->assertUnauthorized();
        }
    }

    public function test_user_without_message_permissions_is_forbidden_on_message_taxonomies(): void
    {
        $preacher = Preacher::factory()->create();
        $category = MessageCategory::factory()->create();
        $series = MessageSeries::factory()->create();

        Sanctum::actingAs($this->userWithRole('dosc_editor'));

        foreach ([
            ['getJson', '/api/v1/admin/preachers'],
            ['getJson', "/api/v1/admin/preachers/{$preacher->id}"],
            ['postJson', '/api/v1/admin/preachers'],
            ['patchJson', "/api/v1/admin/preachers/{$preacher->id}"],
            ['deleteJson', "/api/v1/admin/preachers/{$preacher->id}"],
            ['getJson', '/api/v1/admin/message-categories'],
            ['getJson', "/api/v1/admin/message-categories/{$category->id}"],
            ['postJson', '/api/v1/admin/message-categories'],
            ['patchJson', "/api/v1/admin/message-categories/{$category->id}"],
            ['deleteJson', "/api/v1/admin/message-categories/{$category->id}"],
            ['getJson', '/api/v1/admin/message-series'],
            ['getJson', "/api/v1/admin/message-series/{$series->id}"],
            ['postJson', '/api/v1/admin/message-series'],
            ['patchJson', "/api/v1/admin/message-series/{$series->id}"],
            ['deleteJson', "/api/v1/admin/message-series/{$series->id}"],
        ] as [$method, $uri]) {
            $this->{$method}($uri, [])->assertForbidden();
        }
    }

    public function test_preachers_are_paginated_searchable_sortable_and_crud_ready(): void
    {
        $preacher = Preacher::factory()->create([
            'name' => 'Pasteur Alpha',
            'slug' => 'pasteur-alpha',
            'role' => 'Pasteur',
            'bio' => 'Enseignement biblique',
            'active' => true,
        ]);
        Preacher::factory()->create(['name' => 'Inactive', 'active' => false]);
        Message::factory()->create(['preacher_id' => $preacher->id]);

        Sanctum::actingAs($this->userWithRole('messages_editor'));

        $this->getJson('/api/v1/admin/preachers?search=Alpha&active=true&sort=name&direction=asc&per_page=100000')
            ->assertOk()
            ->assertJsonPath('meta.per_page', 100)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.slug', 'pasteur-alpha')
            ->assertJsonPath('data.0.messages_count', 1);

        $createdId = $this->postJson('/api/v1/admin/preachers', [
            'name' => 'Pasteur Beta',
            'role' => 'Enseignant',
            'bio' => 'Bio publique',
            'image' => 'images/beta.jpg',
            'active' => true,
        ])
            ->assertCreated()
            ->assertJsonPath('data.slug', 'pasteur-beta')
            ->json('data.id');

        $this->getJson("/api/v1/admin/preachers/{$createdId}")
            ->assertOk()
            ->assertJsonPath('data.name', 'Pasteur Beta');

        $this->putJson("/api/v1/admin/preachers/{$createdId}", [
            'name' => 'Pasteur Beta Modifie',
            'slug' => 'pasteur-beta-modifie',
            'active' => false,
        ])
            ->assertOk()
            ->assertJsonPath('data.slug', 'pasteur-beta-modifie')
            ->assertJsonPath('data.active', false);

        $this->patchJson("/api/v1/admin/preachers/{$createdId}", ['role' => 'Invite'])
            ->assertOk()
            ->assertJsonPath('data.role', 'Invite');

        $media = Media::create([
            'file_name' => 'preacher.jpg',
            'file_path' => 'media/preacher.jpg',
            'file_type' => 'image',
            'mime_type' => 'image/jpeg',
        ]);
        Preacher::findOrFail($createdId)->media()->attach($media->id);

        $this->deleteJson("/api/v1/admin/preachers/{$createdId}")->assertNoContent();
        $this->assertDatabaseMissing('preachers', ['id' => $createdId]);
        $this->assertDatabaseMissing('mediaables', [
            'media_id' => $media->id,
            'mediaable_id' => $createdId,
            'mediaable_type' => Preacher::class,
        ]);
    }

    public function test_message_categories_are_paginated_searchable_sortable_and_crud_ready(): void
    {
        $category = MessageCategory::factory()->create([
            'name' => 'Foi active',
            'slug' => 'foi-active',
            'description' => 'Messages sur la foi',
            'active' => true,
        ]);
        MessageCategory::factory()->create(['name' => 'Cachee', 'active' => false]);
        Message::factory()->create(['message_category_id' => $category->id]);

        Sanctum::actingAs($this->userWithRole('messages_editor'));

        $this->getJson('/api/v1/admin/message-categories?search=foi&active=true&sort=name&direction=asc&per_page=100000')
            ->assertOk()
            ->assertJsonPath('meta.per_page', 100)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.slug', 'foi-active')
            ->assertJsonPath('data.0.messages_count', 1);

        $createdId = $this->postJson('/api/v1/admin/message-categories', [
            'name' => 'Priere',
            'description' => 'Messages sur la priere',
            'active' => true,
        ])
            ->assertCreated()
            ->assertJsonPath('data.slug', 'priere')
            ->json('data.id');

        $this->patchJson("/api/v1/admin/message-categories/{$createdId}", [
            'name' => 'Priere et foi',
            'slug' => 'priere-et-foi',
        ])
            ->assertOk()
            ->assertJsonPath('data.slug', 'priere-et-foi');

        $this->deleteJson("/api/v1/admin/message-categories/{$createdId}")->assertNoContent();
        $this->assertDatabaseMissing('message_categories', ['id' => $createdId]);
    }

    public function test_message_series_are_paginated_searchable_sortable_and_crud_ready(): void
    {
        $series = MessageSeries::factory()->create([
            'name' => 'Fondements',
            'slug' => 'fondements',
            'description' => 'Serie de base',
            'cover_image' => 'images/fondements.jpg',
            'active' => true,
        ]);
        MessageSeries::factory()->create(['name' => 'Cachee', 'active' => false]);
        Message::factory()->create(['message_series_id' => $series->id]);

        Sanctum::actingAs($this->userWithRole('messages_editor'));

        $this->getJson('/api/v1/admin/message-series?search=fondements&active=true&sort=name&direction=asc&per_page=100000')
            ->assertOk()
            ->assertJsonPath('meta.per_page', 100)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.slug', 'fondements')
            ->assertJsonPath('data.0.messages_count', 1);

        $createdId = $this->postJson('/api/v1/admin/message-series', [
            'name' => 'Vie de priere',
            'description' => 'Serie de prieres',
            'cover_image' => 'images/priere.jpg',
            'active' => true,
        ])
            ->assertCreated()
            ->assertJsonPath('data.slug', 'vie-de-priere')
            ->json('data.id');

        $this->patchJson("/api/v1/admin/message-series/{$createdId}", [
            'slug' => 'vie-de-priere-admin',
            'cover_image' => null,
        ])
            ->assertOk()
            ->assertJsonPath('data.slug', 'vie-de-priere-admin')
            ->assertJsonPath('data.cover_image', null);

        $media = Media::create([
            'file_name' => 'series.jpg',
            'file_path' => 'media/series.jpg',
            'file_type' => 'image',
            'mime_type' => 'image/jpeg',
        ]);
        MessageSeries::findOrFail($createdId)->media()->attach($media->id);

        $this->deleteJson("/api/v1/admin/message-series/{$createdId}")->assertNoContent();
        $this->assertDatabaseMissing('message_series', ['id' => $createdId]);
        $this->assertDatabaseMissing('mediaables', [
            'media_id' => $media->id,
            'mediaable_id' => $createdId,
            'mediaable_type' => MessageSeries::class,
        ]);
    }

    public function test_message_taxonomy_validation_rejects_duplicate_slugs_and_missing_names(): void
    {
        Preacher::factory()->create(['slug' => 'duplicate-preacher']);
        MessageCategory::factory()->create(['slug' => 'duplicate-category']);
        MessageSeries::factory()->create(['slug' => 'duplicate-series']);

        Sanctum::actingAs($this->userWithRole('messages_editor'));

        $this->postJson('/api/v1/admin/preachers', [
            'name' => '',
            'slug' => 'duplicate-preacher',
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'slug']);

        $this->postJson('/api/v1/admin/message-categories', [
            'name' => '',
            'slug' => 'duplicate-category',
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'slug']);

        $this->postJson('/api/v1/admin/message-series', [
            'name' => '',
            'slug' => 'duplicate-series',
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'slug']);
    }

    public function test_deleting_message_taxonomies_preserves_messages_with_null_relations(): void
    {
        $message = Message::factory()->create();
        $preacherId = $message->preacher_id;
        $categoryId = $message->message_category_id;
        $seriesId = $message->message_series_id;

        Sanctum::actingAs($this->userWithRole('messages_editor'));

        $this->deleteJson("/api/v1/admin/preachers/{$preacherId}")->assertNoContent();
        $this->deleteJson("/api/v1/admin/message-categories/{$categoryId}")->assertNoContent();
        $this->deleteJson("/api/v1/admin/message-series/{$seriesId}")->assertNoContent();

        $message->refresh();

        $this->assertNull($message->preacher_id);
        $this->assertNull($message->message_category_id);
        $this->assertNull($message->message_series_id);
    }

    public function test_missing_message_taxonomy_resources_return_not_found(): void
    {
        Sanctum::actingAs($this->userWithRole('messages_editor'));

        $this->getJson('/api/v1/admin/preachers/999999')->assertNotFound();
        $this->getJson('/api/v1/admin/message-categories/999999')->assertNotFound();
        $this->getJson('/api/v1/admin/message-series/999999')->assertNotFound();
    }

    private function userWithRole(string $roleSlug): User
    {
        $user = User::factory()->create(['status' => 'active']);
        $user->roles()->attach(Role::where('slug', $roleSlug)->firstOrFail());

        return $user;
    }
}
