<?php

namespace Database\Factories;

use App\Models\EventCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<EventCategory>
 */
class EventCategoryFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->randomElement([
            'Culte',
            'Formation',
            'Conference',
            'Jeunesse',
            'Communautaire',
        ]).' '.fake()->unique()->numberBetween(10, 99);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => fake()->optional()->sentence(),
            'active' => true,
        ];
    }
}
