<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Photo>
 */
class PhotoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Using placeholder image service
        $imageId = fake()->numberBetween(1, 1000);
        $url = "https://i.pravatar.cc/600?img={$imageId}";

        return [
            'url' => $url,
            'thumbnail_url' => "https://i.pravatar.cc/150?img={$imageId}",
            'order' => 0,
            'is_primary' => false,
            'verification_score' => fake()->numberBetween(70, 100),
            'has_face' => fake()->boolean(95),
            'is_appropriate' => fake()->boolean(98),
            'status' => fake()->randomElement(['approved', 'pending', 'rejected'], [85, 10, 5]),
            'rejection_reason' => null,
            'approved_at' => now()->subDays(fake()->numberBetween(1, 30)),
        ];
    }

    /**
     * Indicate that the photo is primary.
     */
    public function primary(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_primary' => true,
            'order' => 0,
            'status' => 'approved',
        ]);
    }

    /**
     * Indicate that the photo is approved.
     */
    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'approved',
            'approved_at' => now()->subDays(fake()->numberBetween(1, 30)),
        ]);
    }
}
