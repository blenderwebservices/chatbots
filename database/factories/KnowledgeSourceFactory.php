<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\KnowledgeSource>
 */
class KnowledgeSourceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->sentence(),
            'type' => $type = fake()->randomElement(['pdf', 'website']),
            'path' => match ($type) {
                'pdf' => fake()->filePath(),
                'website' => fake()->url(),
            },
            'extracted_content' => fake()->paragraphs(3, true),
        ];
    }
}
