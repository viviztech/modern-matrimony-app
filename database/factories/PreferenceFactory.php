<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Preference>
 */
class PreferenceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $cities = ['Mumbai', 'Delhi', 'Bangalore', 'Chennai', 'Hyderabad', 'Pune'];
        $religions = ['Hindu', 'Muslim', 'Christian', 'Sikh', 'Buddhist', 'Jain'];
        $educationLevels = ["Bachelor's Degree", "Master's Degree", 'PhD'];

        return [
            'age_min' => fake()->numberBetween(22, 28),
            'age_max' => fake()->numberBetween(30, 40),
            'height_min' => fake()->numberBetween(150, 165),
            'height_max' => fake()->numberBetween(170, 190),
            'body_type_preferences' => fake()->randomElements(['slim', 'average', 'athletic'], fake()->numberBetween(1, 3)),
            'city_preferences' => fake()->randomElements($cities, fake()->numberBetween(2, 4)),
            'state_preferences' => null,
            'distance_radius' => fake()->randomElement([25, 50, 100, 200]),
            'willing_to_relocate' => fake()->boolean(60),
            'education_levels' => fake()->randomElements($educationLevels, fake()->numberBetween(1, 3)),
            'occupation_types' => fake()->randomElements(['Software Engineer', 'Doctor', 'Teacher', 'Business Owner'], fake()->numberBetween(1, 3)),
            'income_min' => fake()->randomElement(['3 LPA', '5 LPA', '7 LPA']),
            'income_max' => fake()->randomElement(['10 LPA', '15 LPA', '20+ LPA']),
            'religion_preferences' => fake()->randomElements($religions, fake()->numberBetween(1, 2)),
            'caste_preferences' => fake()->boolean(30) ? [fake()->word()] : null,
            'mother_tongue_preferences' => null,
            'diet_preferences' => fake()->randomElements(['vegetarian', 'non_vegetarian'], fake()->numberBetween(1, 2)),
            'drinking_preferences' => ['never', 'socially'],
            'smoking_preferences' => ['never'],
            'marital_status_preferences' => ['never_married'],
            'accept_children' => fake()->boolean(40),
            'dealbreakers' => fake()->randomElements(['Smoking', 'Drinking'], fake()->numberBetween(0, 2)),
            'min_compatibility_score' => fake()->randomElement([60, 70, 80]),
            'verified_profiles_only' => fake()->boolean(50),
            'with_photos_only' => fake()->boolean(80),
            'with_video_only' => fake()->boolean(30),
        ];
    }
}
