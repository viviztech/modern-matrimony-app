<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $gender = fake()->randomElement(['male', 'female']);
        $cities = ['Mumbai', 'Delhi', 'Bangalore', 'Chennai', 'Hyderabad', 'Pune', 'Kolkata', 'Ahmedabad', 'Jaipur', 'Surat'];
        $city = fake()->randomElement($cities);

        // Generate realistic coordinates for Indian cities
        $coordinates = $this->getCityCoordinates($city);

        return [
            'name' => fake()->name($gender === 'male' ? 'male' : 'female'),
            'email' => fake()->unique()->safeEmail(),
            'phone' => '+91' . fake()->numerify('##########'),
            'email_verified_at' => fake()->boolean(80) ? now() : null,
            'phone_verified_at' => fake()->boolean(70) ? now() : null,
            'password' => static::$password ??= Hash::make('password'),
            'dob' => fake()->dateTimeBetween('-40 years', '-22 years'),
            'gender' => $gender,
            'city' => $city,
            'state' => $this->getCityState($city),
            'country' => 'India',
            'latitude' => $coordinates['lat'],
            'longitude' => $coordinates['lng'],
            'is_active' => fake()->boolean(95),
            'is_premium' => fake()->boolean(15),
            'premium_until' => fake()->boolean(15) ? now()->addMonths(fake()->numberBetween(1, 12)) : null,
            'profile_completion_percentage' => fake()->numberBetween(30, 100),
            'last_active_at' => fake()->dateTimeBetween('-7 days', 'now'),
            'last_login_at' => fake()->dateTimeBetween('-3 days', 'now'),
            'last_login_ip' => fake()->ipv4(),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Get city coordinates.
     */
    private function getCityCoordinates(string $city): array
    {
        $coordinates = [
            'Mumbai' => ['lat' => 19.0760, 'lng' => 72.8777],
            'Delhi' => ['lat' => 28.7041, 'lng' => 77.1025],
            'Bangalore' => ['lat' => 12.9716, 'lng' => 77.5946],
            'Chennai' => ['lat' => 13.0827, 'lng' => 80.2707],
            'Hyderabad' => ['lat' => 17.3850, 'lng' => 78.4867],
            'Pune' => ['lat' => 18.5204, 'lng' => 73.8567],
            'Kolkata' => ['lat' => 22.5726, 'lng' => 88.3639],
            'Ahmedabad' => ['lat' => 23.0225, 'lng' => 72.5714],
            'Jaipur' => ['lat' => 26.9124, 'lng' => 75.7873],
            'Surat' => ['lat' => 21.1702, 'lng' => 72.8311],
        ];

        return $coordinates[$city] ?? ['lat' => 20.5937, 'lng' => 78.9629]; // India center
    }

    /**
     * Get state for city.
     */
    private function getCityState(string $city): string
    {
        $states = [
            'Mumbai' => 'Maharashtra',
            'Delhi' => 'Delhi',
            'Bangalore' => 'Karnataka',
            'Chennai' => 'Tamil Nadu',
            'Hyderabad' => 'Telangana',
            'Pune' => 'Maharashtra',
            'Kolkata' => 'West Bengal',
            'Ahmedabad' => 'Gujarat',
            'Jaipur' => 'Rajasthan',
            'Surat' => 'Gujarat',
        ];

        return $states[$city] ?? 'Unknown';
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
