<?php

namespace Tests\Feature\Api;

use App\Models\Media;
use App\Models\Message;
use App\Models\MessageCategory;
use App\Models\MessageSeries;
use App\Models\Permission;
use App\Models\Preacher;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AdminMessageCrudTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolePermissionSeeder::class);
    }

    public function test_message_admin_routes_require_authentication(): void
    {
        $message = Message::factory()->create();

        $this->getJson('/api/v1/admin/messages')->assertUnauthorized();
        $this->getJson("/api/v1/admin/messages/{$message->id}")->assertUnauthorized();
        $this->postJson('/api/v1/admin/messages', [])->assertUnauthorized();
        $this->patchJson("/api/v1/admin/messages/{$message->id}", [])->assertUnauthorized();
        $this->deleteJson("/api/v1/admin/messages/{$message->id}")->assertUnauthorized();
    }

    public function test_admin_message_list_filters_searches_sorts_and_includes_drafts(): void
    {
        $preacher = Preacher::factory()->create();
        $category = MessageCategory::factory()->create();
        $series = MessageSeries::factory()->create();

        $draft = Message::factory()->create([
            'preacher_id' => $preacher->id,
            'message_category_id' => $category->id,
            'message_series_id' => $series->id,
            'title' => 'Message brouillon admin',
            'slug' => 'message-brouillon-admin',
            'status' => 'draft',
            'featured' => true,
            'views' => 15,
        ]);

        Message::factory()->create([
            'title' => 'Message public',
            'status' => 'published',
            'featured' => false,
        ]);

        Sanctum::actingAs($this->userWithRole('messages_editor'));

        $this->getJson("/api/v1/admin/messages?search=brouillon&preacher_id={$preacher->id}&message_category_id={$category->id}&message_series_id={$series->id}&status=draft&featured=true&sort=views&direction=desc&per_page=100000")
            ->assertOk()
            ->assertJsonPath('meta.per_page', 100)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $draft->id)
            ->assertJsonPath('data.0.status', 'draft')
            ->assertJsonStructure([
                'data' => [
                    [
                        'id',
                        'title',
                        'slug',
                        'status',
                        'featured',
                        'views',
                        'created_at',
                        'updated_at',
                        'preacher',
                        'category',
                        'series',
                    ],
                ],
                'links',
                'meta',
            ]);
    }

    public function test_admin_message_detail_returns_admin_fields_and_media(): void
    {
        $message = Message::factory()->create([
            'status' => 'draft',
            'views' => 42,
        ]);

        $media = Media::create([
            'file_name' => 'message-admin.jpg',
            'file_path' => 'media/message-admin.jpg',
            'file_type' => 'image',
            'mime_type' => 'image/jpeg',
        ]);
        $message->media()->attach($media->id);

        Sanctum::actingAs($this->userWithRole('messages_editor'));

        $this->getJson("/api/v1/admin/messages/{$message->id}")
            ->assertOk()
            ->assertJsonPath('data.id', $message->id)
            ->assertJsonPath('data.status', 'draft')
            ->assertJsonPath('data.views', 42)
            ->assertJsonPath('data.media.0.file_name', 'message-admin.jpg');
    }

    public function test_admin_can_create_update_patch_and_delete_message(): void
    {
        $preacher = Preacher::factory()->create();
        $category = MessageCategory::factory()->create();
        $series = MessageSeries::factory()->create();

        Sanctum::actingAs($this->userWithRole('messages_editor'));

        $createResponse = $this->postJson('/api/v1/admin/messages', [
            'preacher_id' => $preacher->id,
            'message_category_id' => $category->id,
            'message_series_id' => $series->id,
            'title' => 'Nouveau message admin',
            'excerpt' => 'Court resume',
            'content' => 'Contenu complet du message.',
            'preached_at' => '2026-08-21',
            'duration' => '45:00',
            'youtube_video_id' => 'abc_123-XYZ',
            'youtube_url' => 'https://www.youtube.com/watch?v=abc_123-XYZ',
            'audio_url' => 'https://example.test/audio.mp3',
            'pdf_url' => 'https://example.test/message.pdf',
            'thumbnail' => 'https://example.test/thumb.jpg',
            'featured' => true,
            'status' => 'published',
            'views' => 999,
        ])
            ->assertCreated()
            ->assertJsonPath('data.slug', 'nouveau-message-admin')
            ->assertJsonPath('data.status', 'published')
            ->assertJsonPath('data.views', 0);

        $messageId = $createResponse->json('data.id');
        $message = Message::findOrFail($messageId);
        $this->assertSame('0', (string) $message->views);

        $this->putJson("/api/v1/admin/messages/{$messageId}", [
            'title' => 'Message modifie',
            'slug' => 'message-modifie',
            'status' => 'draft',
            'featured' => false,
        ])
            ->assertOk()
            ->assertJsonPath('data.slug', 'message-modifie')
            ->assertJsonPath('data.status', 'draft')
            ->assertJsonPath('data.featured', false);

        $this->patchJson("/api/v1/admin/messages/{$messageId}", [
            'excerpt' => 'Resume patche',
        ])
            ->assertOk()
            ->assertJsonPath('data.excerpt', 'Resume patche');

        $media = Media::create([
            'file_name' => 'detach.jpg',
            'file_path' => 'media/detach.jpg',
            'file_type' => 'image',
            'mime_type' => 'image/jpeg',
        ]);
        $message->fresh()->media()->attach($media->id);

        $this->deleteJson("/api/v1/admin/messages/{$messageId}")->assertNoContent();

        $this->assertDatabaseMissing('messages', ['id' => $messageId]);
        $this->assertDatabaseMissing('mediaables', [
            'media_id' => $media->id,
            'mediaable_id' => $messageId,
            'mediaable_type' => Message::class,
        ]);
    }

    public function test_admin_message_validation_rejects_invalid_relations_duplicate_slug_and_bad_status(): void
    {
        Message::factory()->create(['slug' => 'slug-existant']);

        Sanctum::actingAs($this->userWithRole('messages_editor'));

        $this->postJson('/api/v1/admin/messages', [
            'title' => '',
            'slug' => 'slug-existant',
            'preacher_id' => 999999,
            'message_category_id' => 999999,
            'message_series_id' => 999999,
            'youtube_video_id' => 'invalid id',
            'youtube_url' => 'not-a-url',
            'status' => 'private',
        ])
            ->assertUnprocessable()
            ->assertJsonPath('message', 'Validation failed.')
            ->assertJsonValidationErrors([
                'title',
                'slug',
                'preacher_id',
                'message_category_id',
                'message_series_id',
                'youtube_video_id',
                'youtube_url',
                'status',
            ]);
    }

    public function test_user_without_publish_permission_cannot_publish_message(): void
    {
        $user = User::factory()->create(['status' => 'active']);
        $role = Role::create([
            'name' => 'Draft Message Editor',
            'slug' => 'draft_message_editor',
            'description' => 'Can draft messages but cannot publish.',
        ]);
        $role->permissions()->sync(Permission::whereIn('slug', [
            'messages.view',
            'messages.create',
            'messages.update',
        ])->pluck('id')->all());
        $user->roles()->attach($role);

        Sanctum::actingAs($user);

        $this->postJson('/api/v1/admin/messages', [
            'title' => 'Message sans publication',
            'status' => 'published',
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['status']);

        $message = Message::factory()->create(['status' => 'draft']);

        $this->patchJson("/api/v1/admin/messages/{$message->id}", [
            'status' => 'published',
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['status']);
    }

    public function test_user_without_message_permission_is_forbidden(): void
    {
        $message = Message::factory()->create();

        Sanctum::actingAs($this->userWithRole('dosc_editor'));

        $this->getJson('/api/v1/admin/messages')->assertForbidden();
        $this->getJson("/api/v1/admin/messages/{$message->id}")->assertForbidden();
        $this->postJson('/api/v1/admin/messages', ['title' => 'Interdit'])->assertForbidden();
        $this->patchJson("/api/v1/admin/messages/{$message->id}", ['title' => 'Interdit'])->assertForbidden();
        $this->deleteJson("/api/v1/admin/messages/{$message->id}")->assertForbidden();
    }

    public function test_draft_message_is_visible_in_admin_but_hidden_from_public_api(): void
    {
        $draft = Message::factory()->create([
            'title' => 'Brouillon public cache',
            'slug' => 'brouillon-public-cache',
            'status' => 'draft',
        ]);

        Sanctum::actingAs($this->userWithRole('messages_editor'));

        $this->getJson('/api/v1/admin/messages?status=draft')
            ->assertOk()
            ->assertJsonFragment(['id' => $draft->id]);

        $this->getJson('/api/v1/messages')
            ->assertOk()
            ->assertJsonMissing(['slug' => 'brouillon-public-cache']);

        $this->getJson('/api/v1/messages/brouillon-public-cache')->assertNotFound();
    }

    private function userWithRole(string $roleSlug): User
    {
        $user = User::factory()->create(['status' => 'active']);
        $user->roles()->attach(Role::where('slug', $roleSlug)->firstOrFail());

        return $user;
    }
}
