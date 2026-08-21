<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\EventCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Event>
 */
class EventFactory extends Factory
{
    public function definition(): array
    {
        $title = fake()->randomElement([
            'Culte special',
            'Seminaire biblique',
            'Rencontre de priere',
            'Journee communautaire',
            'Conference jeunesse',
        ]).' '.fake()->unique()->numberBetween(100, 999);

        $startAt = fake()->dateTimeBetween('now', '+6 months');

        return [
            'event_category_id' => EventCategory::factory(),
            'title' => $title,
            'slug' => Str::slug($title),
            'description' => fake()->paragraph(),
            'image' => null,
            'start_at' => $startAt,
            'end_at' => fake()->optional()->dateTimeBetween($startAt, '+7 months'),
            'location' => fake()->optional()->streetAddress(),
            'city' => fake()->city(),
            'featured' => fake()->boolean(20),
            'status' => 'published',
        ];
    }
}
