<?php

namespace Database\Factories;

use App\Models\Media;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Media>
 */
class MediaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'file_name' => fake()->word().'.jpg',
            'file_path' => 'media/2026/08/'.fake()->uuid().'.jpg',
            'file_type' => 'image',
            'mime_type' => 'image/jpeg',
            'alt_text' => fake()->optional()->sentence(4),
            'title' => fake()->optional()->sentence(3),
            'description' => fake()->optional()->sentence(),
            'size' => fake()->numberBetween(1000, 500000),
            'uploaded_by' => null,
        ];
    }
}
