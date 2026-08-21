<?php

namespace Tests\Feature\Api;

use App\Models\AdministrativeLeader;
use App\Models\Church;
use App\Models\ChurchLeader;
use App\Models\Event;
use App\Models\EventCategory;
use App\Models\Group;
use App\Models\GroupLeader;
use App\Models\Media;
use App\Models\Role;
use App\Models\User;
use App\Models\WeeklyProgram;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AdminEmecCrudTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolePermissionSeeder::class);
    }

    public function test_emec_admin_routes_require_authentication(): void
    {
        $church = Church::factory()->create();
        $churchLeader = ChurchLeader::create([
            'church_id' => $church->id,
            'name' => 'Responsable local',
            'responsibility' => 'Pasteur',
        ]);
        $administrativeLeader = AdministrativeLeader::create([
            'name' => 'Responsable administratif',
            'responsibility' => 'Secretaire',
        ]);
        $group = Group::factory()->create();
        $groupLeader = GroupLeader::create([
            'group_id' => $group->id,
            'name' => 'Responsable groupe',
            'responsibility' => 'Coordonnateur',
        ]);
        $eventCategory = EventCategory::factory()->create();
        $event = Event::factory()->create(['event_category_id' => $eventCategory->id]);
        $weeklyProgram = WeeklyProgram::create([
            'title' => 'Culte du dimanche',
            'day_of_week' => 7,
            'start_time' => '09:00',
        ]);

        foreach ([
            ['getJson', '/api/v1/admin/churches'],
            ['getJson', "/api/v1/admin/churches/{$church->id}"],
            ['postJson', '/api/v1/admin/churches'],
            ['patchJson', "/api/v1/admin/churches/{$church->id}"],
            ['deleteJson', "/api/v1/admin/churches/{$church->id}"],
            ['getJson', '/api/v1/admin/church-leaders'],
            ['getJson', "/api/v1/admin/church-leaders/{$churchLeader->id}"],
            ['postJson', '/api/v1/admin/church-leaders'],
            ['patchJson', "/api/v1/admin/church-leaders/{$churchLeader->id}"],
            ['deleteJson', "/api/v1/admin/church-leaders/{$churchLeader->id}"],
            ['getJson', '/api/v1/admin/administrative-leaders'],
            ['getJson', "/api/v1/admin/administrative-leaders/{$administrativeLeader->id}"],
            ['postJson', '/api/v1/admin/administrative-leaders'],
            ['patchJson', "/api/v1/admin/administrative-leaders/{$administrativeLeader->id}"],
            ['deleteJson', "/api/v1/admin/administrative-leaders/{$administrativeLeader->id}"],
            ['getJson', '/api/v1/admin/groups'],
            ['getJson', "/api/v1/admin/groups/{$group->id}"],
            ['postJson', '/api/v1/admin/groups'],
            ['patchJson', "/api/v1/admin/groups/{$group->id}"],
            ['deleteJson', "/api/v1/admin/groups/{$group->id}"],
            ['getJson', '/api/v1/admin/group-leaders'],
            ['getJson', "/api/v1/admin/group-leaders/{$groupLeader->id}"],
            ['postJson', '/api/v1/admin/group-leaders'],
            ['patchJson', "/api/v1/admin/group-leaders/{$groupLeader->id}"],
            ['deleteJson', "/api/v1/admin/group-leaders/{$groupLeader->id}"],
            ['getJson', '/api/v1/admin/event-categories'],
            ['getJson', "/api/v1/admin/event-categories/{$eventCategory->id}"],
            ['postJson', '/api/v1/admin/event-categories'],
            ['patchJson', "/api/v1/admin/event-categories/{$eventCategory->id}"],
            ['deleteJson', "/api/v1/admin/event-categories/{$eventCategory->id}"],
            ['getJson', '/api/v1/admin/events'],
            ['getJson', "/api/v1/admin/events/{$event->id}"],
            ['postJson', '/api/v1/admin/events'],
            ['patchJson', "/api/v1/admin/events/{$event->id}"],
            ['deleteJson', "/api/v1/admin/events/{$event->id}"],
            ['getJson', '/api/v1/admin/weekly-programs'],
            ['getJson', "/api/v1/admin/weekly-programs/{$weeklyProgram->id}"],
            ['postJson', '/api/v1/admin/weekly-programs'],
            ['patchJson', "/api/v1/admin/weekly-programs/{$weeklyProgram->id}"],
            ['deleteJson', "/api/v1/admin/weekly-programs/{$weeklyProgram->id}"],
        ] as [$method, $uri]) {
            $this->{$method}($uri, [])->assertUnauthorized();
        }
    }

    public function test_user_without_emec_permissions_is_forbidden(): void
    {
        $church = Church::factory()->create();
        $group = Group::factory()->create();
        $event = Event::factory()->create();

        Sanctum::actingAs($this->userWithRole('dosc_editor'));

        $this->getJson('/api/v1/admin/churches')->assertForbidden();
        $this->postJson('/api/v1/admin/churches', ['name' => 'Interdit'])->assertForbidden();
        $this->patchJson("/api/v1/admin/churches/{$church->id}", ['name' => 'Interdit'])->assertForbidden();
        $this->deleteJson("/api/v1/admin/churches/{$church->id}")->assertForbidden();

        $this->getJson('/api/v1/admin/groups')->assertForbidden();
        $this->postJson('/api/v1/admin/groups', ['name' => 'Interdit'])->assertForbidden();
        $this->patchJson("/api/v1/admin/groups/{$group->id}", ['name' => 'Interdit'])->assertForbidden();
        $this->deleteJson("/api/v1/admin/groups/{$group->id}")->assertForbidden();

        $this->getJson('/api/v1/admin/events')->assertForbidden();
        $this->postJson('/api/v1/admin/events', ['title' => 'Interdit'])->assertForbidden();
        $this->patchJson("/api/v1/admin/events/{$event->id}", ['title' => 'Interdit'])->assertForbidden();
        $this->deleteJson("/api/v1/admin/events/{$event->id}")->assertForbidden();
    }

    public function test_churches_and_church_leaders_are_crud_ready(): void
    {
        $church = Church::factory()->create([
            'name' => 'EMEC Alpha',
            'slug' => 'emec-alpha',
            'city' => 'Yaounde',
            'region' => 'Centre',
            'active' => true,
        ]);
        Church::factory()->create(['name' => 'Archivee', 'active' => false]);
        ChurchLeader::create([
            'church_id' => $church->id,
            'name' => 'Pasteur Alpha',
            'responsibility' => 'Pasteur titulaire',
        ]);

        Sanctum::actingAs($this->userWithRole('editor'));

        $this->getJson('/api/v1/admin/churches?search=Alpha&city=Yaounde&active=true&sort=name&direction=asc&per_page=100000')
            ->assertOk()
            ->assertJsonPath('meta.per_page', 100)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.slug', 'emec-alpha')
            ->assertJsonPath('data.0.leaders_count', 1);

        $createdId = $this->postJson('/api/v1/admin/churches', [
            'name' => 'EMEC Beta',
            'city' => 'Douala',
            'region' => 'Littoral',
            'status' => 'published',
            'active' => true,
        ])
            ->assertCreated()
            ->assertJsonPath('data.slug', 'emec-beta')
            ->json('data.id');

        $leaderId = $this->postJson('/api/v1/admin/church-leaders', [
            'church_id' => $createdId,
            'name' => 'Pasteur Beta',
            'responsibility' => 'Pasteur',
            'start_date' => '2026-01-01',
            'active' => true,
        ])
            ->assertCreated()
            ->assertJsonPath('data.church.id', $createdId)
            ->json('data.id');

        $this->patchJson("/api/v1/admin/church-leaders/{$leaderId}", ['responsibility' => 'Pasteur principal'])
            ->assertOk()
            ->assertJsonPath('data.responsibility', 'Pasteur principal');

        $media = Media::create([
            'file_name' => 'church.jpg',
            'file_path' => 'media/church.jpg',
            'file_type' => 'image',
            'mime_type' => 'image/jpeg',
        ]);
        Church::findOrFail($createdId)->media()->attach($media->id);

        $this->deleteJson("/api/v1/admin/churches/{$createdId}")->assertNoContent();
        $this->assertDatabaseMissing('churches', ['id' => $createdId]);
        $this->assertDatabaseMissing('church_leaders', ['id' => $leaderId]);
        $this->assertDatabaseMissing('mediaables', [
            'media_id' => $media->id,
            'mediaable_id' => $createdId,
            'mediaable_type' => Church::class,
        ]);
    }

    public function test_administrative_leaders_groups_and_group_leaders_are_crud_ready(): void
    {
        $group = Group::factory()->create([
            'name' => 'Jeunesse EMEC',
            'slug' => 'jeunesse-emec',
            'active' => true,
        ]);
        Group::factory()->create(['name' => 'Cache', 'active' => false]);
        GroupLeader::create([
            'group_id' => $group->id,
            'name' => 'Leader jeunesse',
            'responsibility' => 'Coordonnateur',
        ]);

        Sanctum::actingAs($this->userWithRole('editor'));

        $leaderAdminId = $this->postJson('/api/v1/admin/administrative-leaders', [
            'name' => 'Secretaire general',
            'responsibility' => 'Administration',
            'description' => 'Coordination administrative',
            'active' => true,
        ])
            ->assertCreated()
            ->assertJsonPath('data.name', 'Secretaire general')
            ->json('data.id');

        $this->patchJson("/api/v1/admin/administrative-leaders/{$leaderAdminId}", ['responsibility' => 'Secretariat'])
            ->assertOk()
            ->assertJsonPath('data.responsibility', 'Secretariat');

        $this->getJson('/api/v1/admin/groups?search=Jeunesse&active=true&sort=name&direction=asc')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.leaders_count', 1);

        $createdGroupId = $this->postJson('/api/v1/admin/groups', [
            'name' => 'Intercession EMEC',
            'short_description' => 'Groupe de priere',
            'email' => 'intercession@example.test',
            'active' => true,
        ])
            ->assertCreated()
            ->assertJsonPath('data.slug', 'intercession-emec')
            ->json('data.id');

        $groupLeaderId = $this->postJson('/api/v1/admin/group-leaders', [
            'group_id' => $createdGroupId,
            'name' => 'Responsable intercession',
            'responsibility' => 'Responsable',
        ])
            ->assertCreated()
            ->assertJsonPath('data.group.id', $createdGroupId)
            ->json('data.id');

        $this->deleteJson("/api/v1/admin/groups/{$createdGroupId}")->assertNoContent();
        $this->assertDatabaseMissing('groups', ['id' => $createdGroupId]);
        $this->assertDatabaseMissing('group_leaders', ['id' => $groupLeaderId]);
    }

    public function test_event_categories_events_and_weekly_programs_are_crud_ready(): void
    {
        $category = EventCategory::factory()->create([
            'name' => 'Formation',
            'slug' => 'formation',
            'active' => true,
        ]);
        Event::factory()->create([
            'event_category_id' => $category->id,
            'title' => 'Formation biblique',
            'slug' => 'formation-biblique',
            'city' => 'Yaounde',
            'featured' => true,
            'status' => 'published',
            'start_at' => '2026-09-01 09:00:00',
        ]);

        Sanctum::actingAs($this->userWithRole('editor'));

        $this->getJson('/api/v1/admin/event-categories?search=Formation&active=true')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.events_count', 1);

        $eventCategoryId = $this->postJson('/api/v1/admin/event-categories', [
            'name' => 'Jeunesse',
            'description' => 'Evenements jeunesse',
            'active' => true,
        ])
            ->assertCreated()
            ->assertJsonPath('data.slug', 'jeunesse')
            ->json('data.id');

        $eventId = $this->postJson('/api/v1/admin/events', [
            'event_category_id' => $eventCategoryId,
            'title' => 'Conference jeunesse',
            'description' => 'Rencontre jeunesse',
            'start_at' => '2026-10-10 10:00:00',
            'end_at' => '2026-10-10 13:00:00',
            'city' => 'Douala',
            'featured' => true,
            'status' => 'published',
        ])
            ->assertCreated()
            ->assertJsonPath('data.slug', 'conference-jeunesse')
            ->assertJsonPath('data.category.id', $eventCategoryId)
            ->json('data.id');

        $this->getJson("/api/v1/admin/events?event_category_id={$eventCategoryId}&city=Douala&featured=true&status=published&from=2026-10-01&to=2026-10-31")
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $eventId);

        $programId = $this->postJson('/api/v1/admin/weekly-programs', [
            'title' => 'Culte de celebration',
            'day_of_week' => 7,
            'start_time' => '09:00',
            'end_time' => '11:00',
            'location' => 'Temple EMEC',
            'active' => true,
        ])
            ->assertCreated()
            ->assertJsonPath('data.day_of_week', 7)
            ->json('data.id');

        $this->patchJson("/api/v1/admin/weekly-programs/{$programId}", ['start_time' => '08:30'])
            ->assertOk()
            ->assertJsonPath('data.start_time', '08:30');

        $this->deleteJson("/api/v1/admin/events/{$eventId}")->assertNoContent();
        $this->deleteJson("/api/v1/admin/event-categories/{$eventCategoryId}")->assertNoContent();
        $this->deleteJson("/api/v1/admin/weekly-programs/{$programId}")->assertNoContent();
        $this->assertDatabaseMissing('events', ['id' => $eventId]);
        $this->assertDatabaseMissing('event_categories', ['id' => $eventCategoryId]);
        $this->assertDatabaseMissing('weekly_programs', ['id' => $programId]);
    }

    public function test_emec_admin_validation_rejects_invalid_payloads(): void
    {
        Church::factory()->create(['slug' => 'duplicate-church']);
        Group::factory()->create(['slug' => 'duplicate-group']);
        EventCategory::factory()->create(['slug' => 'duplicate-event-category']);
        Event::factory()->create(['slug' => 'duplicate-event']);

        Sanctum::actingAs($this->userWithRole('editor'));

        $this->postJson('/api/v1/admin/churches', [
            'name' => '',
            'slug' => 'duplicate-church',
            'status' => 'private',
            'map_url' => 'bad-url',
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'slug', 'status', 'map_url']);

        $this->postJson('/api/v1/admin/groups', [
            'name' => '',
            'slug' => 'duplicate-group',
            'email' => 'bad-email',
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'slug', 'email']);

        $this->postJson('/api/v1/admin/church-leaders', [
            'church_id' => 999999,
            'name' => '',
            'responsibility' => '',
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['church_id', 'name', 'responsibility']);

        $this->postJson('/api/v1/admin/event-categories', [
            'name' => '',
            'slug' => 'duplicate-event-category',
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'slug']);

        $this->postJson('/api/v1/admin/events', [
            'event_category_id' => 999999,
            'title' => '',
            'slug' => 'duplicate-event',
            'start_at' => '',
            'end_at' => '2026-01-01 10:00:00',
            'status' => 'private',
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['event_category_id', 'title', 'slug', 'start_at', 'status']);

        $this->postJson('/api/v1/admin/weekly-programs', [
            'title' => '',
            'day_of_week' => 8,
            'start_time' => 'bad-time',
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['title', 'day_of_week', 'start_time']);
    }

    public function test_missing_emec_resources_return_not_found(): void
    {
        Sanctum::actingAs($this->userWithRole('editor'));

        $this->getJson('/api/v1/admin/churches/999999')->assertNotFound();
        $this->getJson('/api/v1/admin/church-leaders/999999')->assertNotFound();
        $this->getJson('/api/v1/admin/administrative-leaders/999999')->assertNotFound();
        $this->getJson('/api/v1/admin/groups/999999')->assertNotFound();
        $this->getJson('/api/v1/admin/group-leaders/999999')->assertNotFound();
        $this->getJson('/api/v1/admin/event-categories/999999')->assertNotFound();
        $this->getJson('/api/v1/admin/events/999999')->assertNotFound();
        $this->getJson('/api/v1/admin/weekly-programs/999999')->assertNotFound();
    }

    private function userWithRole(string $roleSlug): User
    {
        $user = User::factory()->create(['status' => 'active']);
        $user->roles()->attach(Role::where('slug', $roleSlug)->firstOrFail());

        return $user;
    }
}
