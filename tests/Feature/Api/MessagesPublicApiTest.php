<?php

namespace Tests\Feature\Api;

use App\Models\Media;
use App\Models\Message;
use App\Models\MessageCategory;
use App\Models\MessageSeries;
use App\Models\Preacher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MessagesPublicApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_preachers_categories_and_series_are_publicly_readable(): void
    {
        $preacher = Preacher::factory()->create(['name' => 'Pasteur Actif', 'active' => true]);
        Preacher::factory()->create(['name' => 'Pasteur Inactif', 'active' => false]);

        $category = MessageCategory::factory()->create(['active' => true]);
        MessageCategory::factory()->create(['active' => false]);

        $series = MessageSeries::factory()->create(['active' => true]);
        MessageSeries::factory()->create(['active' => false]);

        $this->getJson('/api/v1/preachers?search=Actif')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.slug', $preacher->slug);

        $this->getJson("/api/v1/preachers/{$preacher->slug}")
            ->assertOk()
            ->assertJsonPath('data.slug', $preacher->slug);

        $this->getJson('/api/v1/message-categories')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.slug', $category->slug);

        $this->getJson("/api/v1/message-categories/{$category->slug}")->assertOk();

        $this->getJson('/api/v1/message-series')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.slug', $series->slug);

        $this->getJson("/api/v1/message-series/{$series->slug}")->assertOk();
    }

    public function test_messages_are_paginated_searchable_filterable_and_sorted(): void
    {
        $preacher = Preacher::factory()->create(['active' => true]);
        $category = MessageCategory::factory()->create(['active' => true]);
        $series = MessageSeries::factory()->create(['active' => true]);

        $message = Message::factory()->create([
            'preacher_id' => $preacher->id,
            'message_category_id' => $category->id,
            'message_series_id' => $series->id,
            'title' => 'La foi active',
            'slug' => 'la-foi-active',
            'excerpt' => 'Une exhortation sur la foi',
            'content' => 'La foi agit par amour dans la communaute.',
            'featured' => true,
            'status' => 'published',
            'preached_at' => '2026-08-10',
            'views' => 50,
        ]);

        Message::factory()->create([
            'title' => 'Autre message',
            'featured' => false,
            'status' => 'published',
            'preached_at' => '2026-07-01',
        ]);

        $this->getJson("/api/v1/messages?search=foi&preacher_id={$preacher->id}&message_category_id={$category->id}&message_series_id={$series->id}&featured=true&from=2026-08-01&to=2026-08-31&sort=views&direction=desc&per_page=100000")
            ->assertOk()
            ->assertJsonPath('meta.per_page', 100)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.slug', $message->slug)
            ->assertJsonStructure([
                'data' => [
                    [
                        'id',
                        'title',
                        'slug',
                        'excerpt',
                        'preached_at',
                        'youtube_video_id',
                        'youtube_url',
                        'audio_url',
                        'pdf_url',
                        'thumbnail',
                        'featured',
                        'status',
                        'views',
                        'preacher',
                        'category',
                        'series',
                    ],
                ],
                'links',
                'meta',
            ]);
    }

    public function test_message_detail_exposes_public_relations_and_media_without_incrementing_views(): void
    {
        $message = Message::factory()->create([
            'slug' => 'message-detail',
            'status' => 'published',
            'views' => 12,
            'youtube_video_id' => 'abc123',
            'youtube_url' => 'https://youtube.com/watch?v=abc123',
            'audio_url' => 'https://example.test/audio.mp3',
            'pdf_url' => 'https://example.test/message.pdf',
            'thumbnail' => 'https://example.test/thumb.jpg',
        ]);

        $media = Media::create([
            'file_name' => 'message.jpg',
            'file_path' => 'media/message.jpg',
            'file_type' => 'image',
            'mime_type' => 'image/jpeg',
        ]);

        $message->media()->attach($media->id);

        $this->getJson('/api/v1/messages/message-detail')
            ->assertOk()
            ->assertJsonPath('data.slug', 'message-detail')
            ->assertJsonPath('data.views', 12)
            ->assertJsonPath('data.youtube_video_id', 'abc123')
            ->assertJsonPath('data.media.0.file_name', 'message.jpg')
            ->assertJsonStructure([
                'data' => [
                    'preacher',
                    'category',
                    'series',
                    'media',
                ],
            ]);

        $this->assertSame(12, $message->fresh()->views);
    }

    public function test_non_public_messages_and_inactive_relations_are_hidden(): void
    {
        Message::factory()->create([
            'slug' => 'draft-message',
            'status' => 'draft',
        ]);

        $inactivePreacher = Preacher::factory()->create(['active' => false]);
        Message::factory()->create([
            'slug' => 'inactive-preacher-message',
            'preacher_id' => $inactivePreacher->id,
            'status' => 'published',
        ]);

        $this->getJson('/api/v1/messages')
            ->assertOk()
            ->assertJsonCount(0, 'data');

        $this->getJson('/api/v1/messages/draft-message')->assertNotFound();
        $this->getJson('/api/v1/messages/inactive-preacher-message')->assertNotFound();
        $this->getJson('/api/v1/messages/missing-message')->assertNotFound();
    }

    public function test_invalid_message_filters_return_validation_errors(): void
    {
        $this->getJson('/api/v1/messages?sort=unsafe_column')
            ->assertUnprocessable()
            ->assertJsonPath('message', 'Validation failed.');

        $this->getJson('/api/v1/messages?from=2026-09-30&to=2026-09-01')
            ->assertUnprocessable()
            ->assertJsonPath('message', 'Validation failed.');
    }
}
