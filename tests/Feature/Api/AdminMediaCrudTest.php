<?php

namespace Tests\Feature\Api;

use App\Models\Church;
use App\Models\Media;
use App\Models\Message;
use App\Models\Permission;
use App\Models\Preacher;
use App\Models\Role;
use App\Models\SocialAction;
use App\Models\SocialProject;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AdminMediaCrudTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolePermissionSeeder::class);
        config(['media.disk' => 'public']);
        Storage::fake('public');
    }

    public function test_media_admin_routes_require_authentication(): void
    {
        $media = Media::factory()->create();

        $this->getJson('/api/v1/admin/media')->assertUnauthorized();
        $this->getJson("/api/v1/admin/media/{$media->id}")->assertUnauthorized();
        $this->postJson('/api/v1/admin/media')->assertUnauthorized();
        $this->patchJson("/api/v1/admin/media/{$media->id}")->assertUnauthorized();
        $this->deleteJson("/api/v1/admin/media/{$media->id}")->assertUnauthorized();
    }

    public function test_user_without_media_permission_is_forbidden(): void
    {
        $media = Media::factory()->create();

        Sanctum::actingAs($this->userWithRole('finance_manager'));

        $this->getJson('/api/v1/admin/media')->assertForbidden();
        $this->postJson('/api/v1/admin/media', [])->assertForbidden();
        $this->patchJson("/api/v1/admin/media/{$media->id}", ['title' => 'Interdit'])->assertForbidden();
        $this->deleteJson("/api/v1/admin/media/{$media->id}")->assertForbidden();
    }

    public function test_media_upload_stores_file_generates_url_and_attaches_to_model(): void
    {
        $message = Message::factory()->create();

        Sanctum::actingAs($this->userWithRole('media_manager'));

        $mediaId = $this->post('/api/v1/admin/media', [
            'file' => UploadedFile::fake()->create('cover.jpg', 512, 'image/jpeg'),
            'alt_text' => 'Couverture du message',
            'title' => 'Image message',
            'description' => 'Image de couverture.',
            'mediaable_type' => 'message',
            'mediaable_id' => $message->id,
        ])
            ->assertCreated()
            ->assertJsonPath('data.file_name', 'cover.jpg')
            ->assertJsonPath('data.file_type', 'image')
            ->assertJsonPath('data.mime_type', 'image/jpeg')
            ->assertJsonPath('data.uploaded_by', auth()->id())
            ->assertJsonPath('data.alt_text', 'Couverture du message')
            ->assertJsonStructure(['data' => ['id', 'file_path', 'url']])
            ->json('data.id');

        $media = Media::findOrFail($mediaId);

        $this->assertStringStartsWith('media/'.now()->format('Y/m').'/', $media->file_path);
        $this->assertNotSame('cover.jpg', basename($media->file_path));
        Storage::disk('public')->assertExists($media->file_path);
        $this->assertSame(1, $message->media()->count());
        $this->assertStringContainsString($media->file_path, $this->getJson("/api/v1/admin/media/{$mediaId}")->json('data.url'));
    }

    public function test_media_validation_rejects_invalid_files_sizes_and_metadata(): void
    {
        Sanctum::actingAs($this->userWithRole('media_manager'));

        $this->post('/api/v1/admin/media', [
            'file' => UploadedFile::fake()->create('script.php', 1, 'application/x-php'),
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['file']);

        $this->post('/api/v1/admin/media', [
            'file' => UploadedFile::fake()->create('shell.php.jpg', 1, 'image/jpeg'),
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['file']);

        $this->post('/api/v1/admin/media', [
            'file' => UploadedFile::fake()->create('page.html', 1, 'text/html'),
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['file']);

        $this->post('/api/v1/admin/media', [
            'file' => UploadedFile::fake()->create('large.jpg', ((int) config('media.max_size_kb.image')) + 1, 'image/jpeg'),
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['file']);

        $this->post('/api/v1/admin/media', [
            'file' => UploadedFile::fake()->create('valid.jpg', 1, 'image/jpeg'),
            'alt_text' => str_repeat('a', 256),
            'mediaable_type' => 'message',
            'mediaable_id' => 999999,
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['alt_text', 'mediaable_id']);
    }

    public function test_media_list_supports_pagination_filters_sort_and_orphaned(): void
    {
        $uploader = $this->userWithRole('media_manager');
        $attached = Media::factory()->create([
            'file_name' => 'attached.jpg',
            'file_type' => 'image',
            'mime_type' => 'image/jpeg',
            'uploaded_by' => $uploader->id,
            'title' => 'Attache',
            'size' => 1000,
        ]);
        $orphan = Media::factory()->create([
            'file_name' => 'orphan.pdf',
            'file_path' => 'media/2026/08/orphan.pdf',
            'file_type' => 'document',
            'mime_type' => 'application/pdf',
            'uploaded_by' => $uploader->id,
            'title' => 'Rapport orphelin',
            'size' => 2000,
        ]);
        Message::factory()->create()->media()->attach($attached->id);

        Sanctum::actingAs($uploader);

        $this->getJson("/api/v1/admin/media?search=orphelin&mime_type=application/pdf&file_type=document&uploaded_by={$uploader->id}&orphaned=1&sort=size&direction=desc&per_page=100000")
            ->assertOk()
            ->assertJsonPath('meta.per_page', 100)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $orphan->id);
    }

    public function test_media_metadata_can_be_updated_without_replacing_file_and_can_attach_multiple_models(): void
    {
        $media = Media::factory()->create(['file_path' => 'media/2026/08/shared.jpg']);
        Storage::disk('public')->put($media->file_path, 'image-bytes');
        $church = Church::factory()->create();
        $preacher = Preacher::factory()->create();
        $project = SocialProject::factory()->create();
        $action = SocialAction::factory()->create();
        $user = User::factory()->create();

        Sanctum::actingAs($this->userWithRole('media_manager'));

        $this->patchJson("/api/v1/admin/media/{$media->id}", [
            'alt_text' => 'Image partagee',
            'title' => 'Media partage',
            'description' => 'Metadonnees uniquement.',
            'mediaable_type' => 'church',
            'mediaable_id' => $church->id,
        ])
            ->assertOk()
            ->assertJsonPath('data.title', 'Media partage');

        $church->media()->syncWithoutDetaching([$media->id]);
        $preacher->media()->syncWithoutDetaching([$media->id]);
        $project->media()->syncWithoutDetaching([$media->id]);
        $action->media()->syncWithoutDetaching([$media->id]);
        $user->media()->syncWithoutDetaching([$media->id]);

        $this->assertSame(1, $media->churches()->count());
        $this->assertSame(1, $media->preachers()->count());
        $this->assertSame(1, $media->socialProjects()->count());
        $this->assertSame(1, $media->socialActions()->count());
        $this->assertSame(1, $media->users()->count());
        Storage::disk('public')->assertExists($media->file_path);
    }

    public function test_media_delete_detaches_relations_and_removes_file_when_unused(): void
    {
        $media = Media::factory()->create(['file_path' => 'media/2026/08/delete-me.jpg']);
        Storage::disk('public')->put($media->file_path, 'image-bytes');
        $message = Message::factory()->create();
        $preacher = Preacher::factory()->create();
        $message->media()->attach($media->id);
        $preacher->media()->attach($media->id);

        Sanctum::actingAs($this->userWithRole('media_manager'));

        $this->deleteJson("/api/v1/admin/media/{$media->id}")->assertNoContent();

        $this->assertDatabaseMissing('media', ['id' => $media->id]);
        $this->assertDatabaseMissing('mediaables', ['media_id' => $media->id]);
        Storage::disk('public')->assertMissing('media/2026/08/delete-me.jpg');
    }

    public function test_view_only_media_permission_cannot_upload_update_or_delete(): void
    {
        $media = Media::factory()->create();
        $user = User::factory()->create(['status' => 'active']);
        $role = Role::create([
            'name' => 'Media Viewer',
            'slug' => 'media_viewer',
            'description' => 'Read only media access.',
        ]);
        $role->permissions()->sync(
            Permission::where('slug', 'media.view')->pluck('id')->all()
        );
        $user->roles()->attach($role);

        Sanctum::actingAs($user);

        $this->getJson('/api/v1/admin/media')->assertOk();
        $this->post('/api/v1/admin/media', [
            'file' => UploadedFile::fake()->create('denied.jpg', 1, 'image/jpeg'),
        ])->assertForbidden();
        $this->patchJson("/api/v1/admin/media/{$media->id}", ['title' => 'Denied'])->assertForbidden();
        $this->deleteJson("/api/v1/admin/media/{$media->id}")->assertForbidden();
    }

    private function userWithRole(string $roleSlug): User
    {
        $user = User::factory()->create(['status' => 'active']);
        $user->roles()->attach(Role::where('slug', $roleSlug)->firstOrFail());

        return $user;
    }
}
