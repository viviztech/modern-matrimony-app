<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Profile>
 */
class ProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $educationLevels = ['High School', "Bachelor's Degree", "Master's Degree", 'PhD', 'Diploma'];
        $occupations = ['Software Engineer', 'Doctor', 'Teacher', 'Business Owner', 'Consultant', 'Marketing Manager', 'Data Analyst', 'Designer', 'Lawyer', 'Architect'];
        $companies = ['TCS', 'Infosys', 'Wipro', 'Google India', 'Microsoft', 'Amazon', 'Flipkart', 'Swiggy', 'HDFC Bank', 'Self-Employed'];
        $religions = ['Hindu', 'Muslim', 'Christian', 'Sikh', 'Buddhist', 'Jain'];
        $languages = ['Hindi', 'English', 'Tamil', 'Telugu', 'Kannada', 'Malayalam', 'Marathi', 'Bengali', 'Gujarati'];

        $interests = ['Travel', 'Reading', 'Cooking', 'Fitness', 'Music', 'Movies', 'Photography', 'Gaming', 'Yoga', 'Dancing', 'Art', 'Sports', 'Technology', 'Food', 'Fashion'];

        $prompts = [
            ['question' => "I'll know I found the one when...", 'answer' => fake()->sentence(), 'order' => 1],
            ['question' => 'My ideal Sunday looks like...', 'answer' => fake()->sentence(), 'order' => 2],
            ['question' => 'I geek out on...', 'answer' => fake()->words(5, true), 'order' => 3],
        ];

        $education = fake()->randomElement($educationLevels);
        $religion = fake()->randomElement($religions);

        return [
            'bio' => fake()->paragraph(3),
            'looking_for' => fake()->paragraph(2),
            'height' => fake()->numberBetween(150, 190), // cm
            'body_type' => fake()->randomElement(['slim', 'average', 'athletic', 'heavy']),
            'complexion' => fake()->randomElement(['very_fair', 'fair', 'wheatish', 'dark']),
            'education' => $education,
            'field_of_study' => $this->getFieldOfStudy($education),
            'occupation' => fake()->randomElement($occupations),
            'company' => fake()->randomElement($companies),
            'annual_income_range' => fake()->randomElement(['3-5 LPA', '5-7 LPA', '7-10 LPA', '10-15 LPA', '15-20 LPA', '20+ LPA']),
            'diet' => fake()->randomElement(['vegetarian', 'non_vegetarian', 'vegan']),
            'drinking' => fake()->randomElement(['never', 'socially', 'regularly']),
            'smoking' => fake()->randomElement(['never', 'socially', 'regularly']),
            'religion' => $religion,
            'religion_importance' => fake()->numberBetween(1, 10),
            'caste' => fake()->boolean(70) ? fake()->word() : null,
            'show_caste' => fake()->boolean(30),
            'mother_tongue' => fake()->randomElement($languages),
            'languages_known' => fake()->randomElements($languages, fake()->numberBetween(2, 4)),
            'family_type' => fake()->randomElement(['nuclear', 'joint']),
            'family_values' => fake()->sentence(),
            'family_location' => fake()->city(),
            'fathers_occupation' => fake()->randomElement(['Retired', 'Business', 'Government Job', 'Private Job']),
            'mothers_occupation' => fake()->randomElement(['Homemaker', 'Teacher', 'Business', 'Retired']),
            'siblings_count' => fake()->numberBetween(0, 3),
            'marital_status' => fake()->randomElement(['never_married', 'divorced', 'widowed']),
            'have_children' => fake()->boolean(10),
            'children_count' => fake()->boolean(10) ? fake()->numberBetween(0, 2) : 0,
            'interests' => fake()->randomElements($interests, fake()->numberBetween(3, 7)),
            'hobbies' => fake()->randomElements(['Reading', 'Traveling', 'Cooking', 'Sports', 'Music'], fake()->numberBetween(2, 4)),
            'personality_type' => fake()->randomElement(['INTJ', 'ENTJ', 'INFP', 'ENFP', 'ISTJ', 'ESTJ', 'ISFJ', 'ESFJ']),
            'personality_traits' => ['trait1' => 'Outgoing', 'trait2' => 'Creative'],
            'dealbreakers' => fake()->randomElements(['Smoking', 'Drinking', 'Non-vegetarian', 'Different religion'], fake()->numberBetween(0, 2)),
            'prompts' => $prompts,
            'is_visible' => fake()->boolean(95),
            'show_online_status' => fake()->boolean(80),
            'allow_messages_from_non_matches' => fake()->boolean(30),
        ];
    }

    /**
     * Get field of study based on education.
     */
    private function getFieldOfStudy(string $education): ?string
    {
        if ($education === 'High School') {
            return null;
        }

        $fields = [
            'Computer Science',
            'Engineering',
            'Medicine',
            'Business Administration',
            'Arts',
            'Commerce',
            'Law',
            'Architecture',
            'Education',
            'Science',
        ];

        return fake()->randomElement($fields);
    }
}
