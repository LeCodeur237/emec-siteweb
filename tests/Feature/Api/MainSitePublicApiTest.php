<?php

namespace Tests\Feature\Api;

use App\Models\AdministrativeLeader;
use App\Models\Church;
use App\Models\ChurchLeader;
use App\Models\Event;
use App\Models\EventCategory;
use App\Models\Group;
use App\Models\GroupLeader;
use App\Models\WeeklyProgram;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MainSitePublicApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_churches_are_paginated_filterable_and_public_only(): void
    {
        $published = Church::factory()->create([
            'name' => 'Assemblee EMEC Yaounde',
            'city' => 'Yaounde',
            'region' => 'Centre',
            'status' => 'published',
            'active' => true,
        ]);

        Church::factory()->create([
            'name' => 'Brouillon EMEC',
            'status' => 'draft',
            'active' => true,
        ]);

        Church::factory()->create([
            'name' => 'Inactive EMEC',
            'status' => 'published',
            'active' => false,
        ]);

        $response = $this->getJson('/api/v1/churches?search=Yaounde&city=Yaounde&region=Centre&per_page=100000');

        $response
            ->assertOk()
            ->assertJsonPath('meta.per_page', 100)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.slug', $published->slug)
            ->assertJsonStructure([
                'data' => [
                    [
                        'id',
                        'name',
                        'slug',
                        'city',
                        'region',
                        'status',
                        'active',
                    ],
                ],
                'links',
                'meta',
            ]);
    }

    public function test_church_detail_includes_only_active_leaders(): void
    {
        $church = Church::factory()->create(['status' => 'published', 'active' => true]);
        ChurchLeader::create([
            'church_id' => $church->id,
            'name' => 'Pasteur actif',
            'responsibility' => 'Pasteur',
            'active' => true,
        ]);
        ChurchLeader::create([
            'church_id' => $church->id,
            'name' => 'Ancien inactif',
            'responsibility' => 'Ancien',
            'active' => false,
        ]);

        $this->getJson("/api/v1/churches/{$church->slug}")
            ->assertOk()
            ->assertJsonPath('data.slug', $church->slug)
            ->assertJsonCount(1, 'data.leaders')
            ->assertJsonPath('data.leaders.0.name', 'Pasteur actif');

        Church::factory()->create(['slug' => 'draft-church', 'status' => 'draft', 'active' => true]);

        $this->getJson('/api/v1/churches/draft-church')->assertNotFound();
        $this->getJson('/api/v1/churches/missing-church')->assertNotFound();
    }

    public function test_church_leaders_and_administrative_leaders_are_active_by_default(): void
    {
        $church = Church::factory()->create(['status' => 'published', 'active' => true]);

        $activeLeader = ChurchLeader::create([
            'church_id' => $church->id,
            'name' => 'Leader actif',
            'responsibility' => 'Responsable',
            'active' => true,
        ]);

        ChurchLeader::create([
            'church_id' => $church->id,
            'name' => 'Leader inactif',
            'responsibility' => 'Responsable',
            'active' => false,
        ]);

        $adminLeader = AdministrativeLeader::create([
            'name' => 'Administrateur actif',
            'responsibility' => 'Coordination',
            'active' => true,
        ]);

        AdministrativeLeader::create([
            'name' => 'Administrateur inactif',
            'responsibility' => 'Coordination',
            'active' => false,
        ]);

        $this->getJson("/api/v1/church-leaders?church_id={$church->id}")
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $activeLeader->id);

        $this->getJson("/api/v1/church-leaders/{$activeLeader->id}")->assertOk();
        $this->getJson('/api/v1/administrative-leaders')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $adminLeader->id);
    }

    public function test_groups_are_paginated_searchable_and_detail_loads_active_leaders(): void
    {
        $group = Group::factory()->create([
            'name' => 'Jeunesse EMEC',
            'active' => true,
        ]);

        Group::factory()->create([
            'name' => 'Groupe inactif',
            'active' => false,
        ]);

        GroupLeader::create([
            'group_id' => $group->id,
            'name' => 'Leader groupe actif',
            'responsibility' => 'Coordination',
            'active' => true,
        ]);

        GroupLeader::create([
            'group_id' => $group->id,
            'name' => 'Leader groupe inactif',
            'responsibility' => 'Coordination',
            'active' => false,
        ]);

        $this->getJson('/api/v1/groups?search=Jeunesse')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.slug', $group->slug);

        $this->getJson("/api/v1/groups/{$group->slug}")
            ->assertOk()
            ->assertJsonCount(1, 'data.leaders')
            ->assertJsonPath('data.leaders.0.name', 'Leader groupe actif');
    }

    public function test_event_categories_events_and_weekly_programs_are_publicly_readable(): void
    {
        $category = EventCategory::factory()->create(['active' => true]);
        EventCategory::factory()->create(['active' => false]);

        $featured = Event::factory()->create([
            'event_category_id' => $category->id,
            'title' => 'Conference publique',
            'slug' => 'conference-publique',
            'city' => 'Yaounde',
            'featured' => true,
            'status' => 'published',
            'start_at' => '2026-09-10 09:00:00',
        ]);

        Event::factory()->create([
            'event_category_id' => $category->id,
            'title' => 'Brouillon',
            'status' => 'draft',
            'start_at' => '2026-09-11 09:00:00',
        ]);

        $program = WeeklyProgram::create([
            'title' => 'Culte dominical',
            'day_of_week' => 7,
            'start_time' => '09:00:00',
            'active' => true,
        ]);

        WeeklyProgram::create([
            'title' => 'Programme inactif',
            'day_of_week' => 1,
            'start_time' => '18:00:00',
            'active' => false,
        ]);

        $this->getJson('/api/v1/event-categories')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.slug', $category->slug);

        $this->getJson("/api/v1/event-categories/{$category->slug}")->assertOk();

        $this->getJson("/api/v1/events?featured=true&event_category_id={$category->id}&from=2026-09-01&to=2026-09-30&per_page=2")
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.slug', $featured->slug)
            ->assertJsonPath('meta.per_page', 2);

        $this->getJson("/api/v1/events/{$featured->slug}")
            ->assertOk()
            ->assertJsonPath('data.category.slug', $category->slug);

        $this->getJson('/api/v1/events?from=2026-10-01&to=2026-09-01')
            ->assertUnprocessable();

        $this->getJson('/api/v1/weekly-programs?day_of_week=7')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $program->id)
            ->assertJsonPath('data.0.day_of_week', 7);

        $this->getJson("/api/v1/weekly-programs/{$program->id}")->assertOk();
    }
}
