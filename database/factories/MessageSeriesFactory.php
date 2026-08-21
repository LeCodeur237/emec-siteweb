<?php

namespace Database\Factories;

use App\Models\MessageSeries;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<MessageSeries>
 */
class MessageSeriesFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->randomElement([
            'Marcher avec Dieu',
            'Fondements de la foi',
            'Vie de priere',
            'Servir avec fidelite',
        ]).' '.fake()->unique()->numberBetween(10, 99);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => fake()->optional()->paragraph(),
            'cover_image' => null,
            'active' => true,
        ];
    }
}
