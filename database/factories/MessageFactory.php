<?php

namespace Database\Factories;

use App\Models\Message;
use App\Models\MessageCategory;
use App\Models\MessageSeries;
use App\Models\Preacher;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Message>
 */
class MessageFactory extends Factory
{
    public function definition(): array
    {
        $title = fake()->randomElement([
            'Vivre par la foi',
            'La puissance de la priere',
            'Grandir dans la parole',
            'Servir avec amour',
            'Esperer en Dieu',
        ]).' '.fake()->unique()->numberBetween(100, 999);

        return [
            'preacher_id' => Preacher::factory(),
            'message_category_id' => MessageCategory::factory(),
            'message_series_id' => MessageSeries::factory(),
            'title' => $title,
            'slug' => Str::slug($title),
            'excerpt' => fake()->sentence(18),
            'content' => fake()->paragraphs(4, true),
            'preached_at' => fake()->optional()->dateTimeBetween('-1 year', 'now'),
            'duration' => fake()->optional()->randomElement(['32:15', '45:00', '58:30']),
            'youtube_video_id' => null,
            'youtube_url' => null,
            'audio_url' => null,
            'pdf_url' => null,
            'thumbnail' => null,
            'featured' => fake()->boolean(20),
            'status' => 'published',
            'views' => fake()->numberBetween(0, 5000),
        ];
    }
}
