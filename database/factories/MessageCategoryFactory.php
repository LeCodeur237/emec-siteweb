<?php

namespace Database\Factories;

use App\Models\MessageCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<MessageCategory>
 */
class MessageCategoryFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->randomElement([
            'Foi',
            'Priere',
            'Famille',
            'Discipolat',
            'Evangelisation',
        ]).' '.fake()->unique()->numberBetween(10, 99);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => fake()->optional()->sentence(),
            'active' => true,
        ];
    }
}
