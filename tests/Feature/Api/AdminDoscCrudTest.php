<?php

namespace Tests\Feature\Api;

use App\Models\ImpactStat;
use App\Models\Media;
use App\Models\Role;
use App\Models\SocialAction;
use App\Models\SocialActionStat;
use App\Models\SocialProject;
use App\Models\Testimonial;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AdminDoscCrudTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolePermissionSeeder::class);
    }

    public function test_dosc_admin_routes_require_authentication(): void
    {
        $project = SocialProject::factory()->create();
        $action = SocialAction::factory()->create(['social_project_id' => $project->id]);
        $stat = SocialActionStat::create([
            'social_action_id' => $action->id,
            'label' => 'Beneficiaires',
            'value' => '120',
        ]);
        $testimonial = Testimonial::create([
            'social_action_id' => $action->id,
            'quote' => 'Un accompagnement utile.',
        ]);
        $impact = ImpactStat::create([
            'label' => 'Familles accompagnees',
            'value' => '250',
        ]);

        foreach ([
            ['getJson', '/api/v1/admin/dosc/projects'],
            ['getJson', "/api/v1/admin/dosc/projects/{$project->id}"],
            ['postJson', '/api/v1/admin/dosc/projects'],
            ['patchJson', "/api/v1/admin/dosc/projects/{$project->id}"],
            ['deleteJson', "/api/v1/admin/dosc/projects/{$project->id}"],
            ['getJson', '/api/v1/admin/dosc/actions'],
            ['getJson', "/api/v1/admin/dosc/actions/{$action->id}"],
            ['postJson', '/api/v1/admin/dosc/actions'],
            ['patchJson', "/api/v1/admin/dosc/actions/{$action->id}"],
            ['deleteJson', "/api/v1/admin/dosc/actions/{$action->id}"],
            ['getJson', '/api/v1/admin/dosc/action-stats'],
            ['getJson', "/api/v1/admin/dosc/action-stats/{$stat->id}"],
            ['postJson', '/api/v1/admin/dosc/action-stats'],
            ['patchJson', "/api/v1/admin/dosc/action-stats/{$stat->id}"],
            ['deleteJson', "/api/v1/admin/dosc/action-stats/{$stat->id}"],
            ['getJson', '/api/v1/admin/dosc/testimonials'],
            ['getJson', "/api/v1/admin/dosc/testimonials/{$testimonial->id}"],
            ['postJson', '/api/v1/admin/dosc/testimonials'],
            ['patchJson', "/api/v1/admin/dosc/testimonials/{$testimonial->id}"],
            ['deleteJson', "/api/v1/admin/dosc/testimonials/{$testimonial->id}"],
            ['getJson', '/api/v1/admin/dosc/impact-stats'],
            ['getJson', "/api/v1/admin/dosc/impact-stats/{$impact->id}"],
            ['postJson', '/api/v1/admin/dosc/impact-stats'],
            ['patchJson', "/api/v1/admin/dosc/impact-stats/{$impact->id}"],
            ['deleteJson', "/api/v1/admin/dosc/impact-stats/{$impact->id}"],
        ] as [$method, $uri]) {
            $this->{$method}($uri, [])->assertUnauthorized();
        }
    }

    public function test_user_without_dosc_permissions_is_forbidden(): void
    {
        $project = SocialProject::factory()->create();
        $action = SocialAction::factory()->create(['social_project_id' => $project->id]);

        Sanctum::actingAs($this->userWithRole('messages_editor'));

        $this->getJson('/api/v1/admin/dosc/projects')->assertForbidden();
        $this->postJson('/api/v1/admin/dosc/projects', ['title' => 'Interdit'])->assertForbidden();
        $this->patchJson("/api/v1/admin/dosc/projects/{$project->id}", ['title' => 'Interdit'])->assertForbidden();
        $this->deleteJson("/api/v1/admin/dosc/projects/{$project->id}")->assertForbidden();

        $this->getJson('/api/v1/admin/dosc/actions')->assertForbidden();
        $this->postJson('/api/v1/admin/dosc/actions', ['title' => 'Interdit'])->assertForbidden();
        $this->patchJson("/api/v1/admin/dosc/actions/{$action->id}", ['title' => 'Interdit'])->assertForbidden();
        $this->deleteJson("/api/v1/admin/dosc/actions/{$action->id}")->assertForbidden();
    }

    public function test_social_projects_are_paginated_searchable_sortable_and_crud_ready(): void
    {
        $project = SocialProject::factory()->create([
            'title' => 'Projet Alpha DOSC',
            'slug' => 'projet-alpha-dosc',
            'status' => 'active',
            'featured' => true,
        ]);
        SocialProject::factory()->create(['title' => 'Archive DOSC', 'status' => 'archived']);
        SocialAction::factory()->create(['social_project_id' => $project->id]);

        Sanctum::actingAs($this->userWithRole('dosc_editor'));

        $this->getJson('/api/v1/admin/dosc/projects?search=Alpha&status=active&featured=true&sort=title&direction=asc&per_page=100000')
            ->assertOk()
            ->assertJsonPath('meta.per_page', 100)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.slug', 'projet-alpha-dosc')
            ->assertJsonPath('data.0.actions_count', 1);

        $createdId = $this->postJson('/api/v1/admin/dosc/projects', [
            'title' => 'Projet Beta DOSC',
            'short_description' => 'Soutien social',
            'goal_amount' => 500000,
            'raised_amount' => 25000,
            'beneficiaries_count' => 40,
            'deadline' => '2026-12-31',
            'status' => 'active',
            'featured' => true,
        ])
            ->assertCreated()
            ->assertJsonPath('data.slug', 'projet-beta-dosc')
            ->json('data.id');

        $this->patchJson("/api/v1/admin/dosc/projects/{$createdId}", [
            'title' => 'Projet Beta ajuste',
            'slug' => 'projet-beta-ajuste',
            'featured' => false,
        ])
            ->assertOk()
            ->assertJsonPath('data.slug', 'projet-beta-ajuste')
            ->assertJsonPath('data.featured', false);

        $media = Media::create([
            'file_name' => 'project.jpg',
            'file_path' => 'media/project.jpg',
            'file_type' => 'image',
            'mime_type' => 'image/jpeg',
        ]);
        SocialProject::findOrFail($createdId)->media()->attach($media->id);

        $this->deleteJson("/api/v1/admin/dosc/projects/{$createdId}")->assertNoContent();
        $this->assertDatabaseMissing('social_projects', ['id' => $createdId]);
        $this->assertDatabaseMissing('mediaables', [
            'media_id' => $media->id,
            'mediaable_id' => $createdId,
            'mediaable_type' => SocialProject::class,
        ]);
    }

    public function test_social_actions_stats_and_testimonials_are_crud_ready(): void
    {
        $project = SocialProject::factory()->create(['status' => 'active']);
        $action = SocialAction::factory()->create([
            'social_project_id' => $project->id,
            'title' => 'Action Alpha DOSC',
            'slug' => 'action-alpha-dosc',
            'category' => 'education',
            'location' => 'Yaounde',
            'status' => 'published',
            'action_date' => '2026-09-10',
        ]);
        SocialActionStat::create([
            'social_action_id' => $action->id,
            'label' => 'Kits distribues',
            'value' => '80',
        ]);
        Testimonial::create([
            'social_action_id' => $action->id,
            'name' => 'Beneficiaire',
            'quote' => 'Merci pour le soutien.',
            'published' => true,
        ]);

        Sanctum::actingAs($this->userWithRole('dosc_editor'));

        $this->getJson("/api/v1/admin/dosc/actions?search=Alpha&social_project_id={$project->id}&category=education&location=Yaounde&status=published&from=2026-09-01&to=2026-09-30")
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.stats_count', 1)
            ->assertJsonPath('data.0.testimonials_count', 1);

        $createdActionId = $this->postJson('/api/v1/admin/dosc/actions', [
            'social_project_id' => $project->id,
            'title' => 'Action Beta DOSC',
            'category' => 'health',
            'description' => 'Action sanitaire',
            'location' => 'Douala',
            'action_date' => '2026-10-01',
            'youtube_video_id' => 'abc_123-XYZ',
            'beneficiaries_count' => 25,
            'status' => 'published',
        ])
            ->assertCreated()
            ->assertJsonPath('data.slug', 'action-beta-dosc')
            ->assertJsonPath('data.project.id', $project->id)
            ->json('data.id');

        $statId = $this->postJson('/api/v1/admin/dosc/action-stats', [
            'social_action_id' => $createdActionId,
            'label' => 'Personnes suivies',
            'value' => '25',
        ])
            ->assertCreated()
            ->assertJsonPath('data.action.id', $createdActionId)
            ->json('data.id');

        $testimonialId = $this->postJson('/api/v1/admin/dosc/testimonials', [
            'social_action_id' => $createdActionId,
            'name' => null,
            'location' => 'Douala',
            'quote' => 'Le DOSC nous a accompagnes.',
            'published' => true,
        ])
            ->assertCreated()
            ->assertJsonPath('data.published', true)
            ->json('data.id');

        $this->patchJson("/api/v1/admin/dosc/action-stats/{$statId}", ['value' => '30'])
            ->assertOk()
            ->assertJsonPath('data.value', '30');

        $this->patchJson("/api/v1/admin/dosc/testimonials/{$testimonialId}", ['published' => false])
            ->assertOk()
            ->assertJsonPath('data.published', false);

        $this->deleteJson("/api/v1/admin/dosc/actions/{$createdActionId}")->assertNoContent();
        $this->assertDatabaseMissing('social_actions', ['id' => $createdActionId]);
        $this->assertDatabaseMissing('social_action_stats', ['id' => $statId]);
        $this->assertDatabaseHas('testimonials', [
            'id' => $testimonialId,
            'social_action_id' => null,
        ]);
    }

    public function test_impact_stats_are_crud_ready(): void
    {
        ImpactStat::create([
            'label' => 'Familles accompagnees',
            'value' => '250',
            'suffix' => '+',
            'sort_order' => 1,
            'active' => true,
        ]);
        ImpactStat::create([
            'label' => 'Archive',
            'value' => '10',
            'active' => false,
        ]);

        Sanctum::actingAs($this->userWithRole('dosc_editor'));

        $this->getJson('/api/v1/admin/dosc/impact-stats?search=Familles&active=true&sort=sort_order&direction=asc')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.label', 'Familles accompagnees');

        $createdId = $this->postJson('/api/v1/admin/dosc/impact-stats', [
            'label' => 'Repas servis',
            'value' => '1200',
            'suffix' => '+',
            'icon' => 'heart',
            'sort_order' => 2,
            'active' => true,
        ])
            ->assertCreated()
            ->assertJsonPath('data.sort_order', 2)
            ->json('data.id');

        $this->patchJson("/api/v1/admin/dosc/impact-stats/{$createdId}", ['active' => false])
            ->assertOk()
            ->assertJsonPath('data.active', false);

        $this->deleteJson("/api/v1/admin/dosc/impact-stats/{$createdId}")->assertNoContent();
        $this->assertDatabaseMissing('impact_stats', ['id' => $createdId]);
    }

    public function test_dosc_admin_validation_rejects_invalid_payloads(): void
    {
        SocialProject::factory()->create(['slug' => 'duplicate-project']);
        SocialAction::factory()->create(['slug' => 'duplicate-action']);

        Sanctum::actingAs($this->userWithRole('dosc_editor'));

        $this->postJson('/api/v1/admin/dosc/projects', [
            'title' => '',
            'slug' => 'duplicate-project',
            'goal_amount' => -1,
            'status' => 'private',
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['title', 'slug', 'goal_amount', 'status']);

        $this->postJson('/api/v1/admin/dosc/actions', [
            'social_project_id' => 999999,
            'title' => '',
            'slug' => 'duplicate-action',
            'youtube_video_id' => 'bad id',
            'beneficiaries_count' => -1,
            'status' => 'private',
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['social_project_id', 'title', 'slug', 'youtube_video_id', 'beneficiaries_count', 'status']);

        $this->postJson('/api/v1/admin/dosc/action-stats', [
            'social_action_id' => 999999,
            'label' => '',
            'value' => '',
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['social_action_id', 'label', 'value']);

        $this->postJson('/api/v1/admin/dosc/testimonials', [
            'social_action_id' => 999999,
            'quote' => '',
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['social_action_id', 'quote']);

        $this->postJson('/api/v1/admin/dosc/impact-stats', [
            'label' => '',
            'value' => '',
            'sort_order' => -1,
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['label', 'value', 'sort_order']);
    }

    public function test_missing_dosc_admin_resources_return_not_found(): void
    {
        Sanctum::actingAs($this->userWithRole('dosc_editor'));

        $this->getJson('/api/v1/admin/dosc/projects/999999')->assertNotFound();
        $this->getJson('/api/v1/admin/dosc/actions/999999')->assertNotFound();
        $this->getJson('/api/v1/admin/dosc/action-stats/999999')->assertNotFound();
        $this->getJson('/api/v1/admin/dosc/testimonials/999999')->assertNotFound();
        $this->getJson('/api/v1/admin/dosc/impact-stats/999999')->assertNotFound();
    }

    private function userWithRole(string $roleSlug): User
    {
        $user = User::factory()->create(['status' => 'active']);
        $user->roles()->attach(Role::where('slug', $roleSlug)->firstOrFail());

        return $user;
    }
}
