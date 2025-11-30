<?php

namespace Database\Seeders;

use App\Models\Icebreaker;
use Illuminate\Database\Seeder;

class IcebreakerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $icebreakers = [
            // Getting to know you (25 questions)
            ['category' => 'getting_to_know', 'question' => 'If you could travel anywhere right now, where would you go and why?'],
            ['category' => 'getting_to_know', 'question' => 'What\'s your idea of a perfect weekend?'],
            ['category' => 'getting_to_know', 'question' => 'What\'s the best advice you\'ve ever received?'],
            ['category' => 'getting_to_know', 'question' => 'If you could have dinner with anyone (living or dead), who would it be?'],
            ['category' => 'getting_to_know', 'question' => 'What\'s something you\'re really proud of?'],
            ['category' => 'getting_to_know', 'question' => 'What makes you feel most alive?'],
            ['category' => 'getting_to_know', 'question' => 'What\'s your favorite way to spend a rainy day?'],
            ['category' => 'getting_to_know', 'question' => 'If you could master any skill instantly, what would it be?'],
            ['category' => 'getting_to_know', 'question' => 'What\'s the most spontaneous thing you\'ve ever done?'],
            ['category' => 'getting_to_know', 'question' => 'What\'s your go-to karaoke song?'],
            ['category' => 'getting_to_know', 'question' => 'What\'s something people often misunderstand about you?'],
            ['category' => 'getting_to_know', 'question' => 'If you could relive any day of your life, which would it be?'],
            ['category' => 'getting_to_know', 'question' => 'What\'s your favorite childhood memory?'],
            ['category' => 'getting_to_know', 'question' => 'What makes you laugh the most?'],
            ['category' => 'getting_to_know', 'question' => 'What\'s the best compliment you\'ve ever received?'],
            ['category' => 'getting_to_know', 'question' => 'What\'s your hidden talent?'],
            ['category' => 'getting_to_know', 'question' => 'What\'s the last book that made an impact on you?'],
            ['category' => 'getting_to_know', 'question' => 'What\'s your favorite way to unwind after a long day?'],
            ['category' => 'getting_to_know', 'question' => 'If your life had a theme song, what would it be?'],
            ['category' => 'getting_to_know', 'question' => 'What\'s something you\'ve always wanted to learn?'],
            ['category' => 'getting_to_know', 'question' => 'What\'s your favorite season and why?'],
            ['category' => 'getting_to_know', 'question' => 'What\'s the most interesting place you\'ve ever visited?'],
            ['category' => 'getting_to_know', 'question' => 'What do you value most in a friendship?'],
            ['category' => 'getting_to_know', 'question' => 'If you could change one thing about the world, what would it be?'],
            ['category' => 'getting_to_know', 'question' => 'What\'s your favorite tradition or ritual?'],

            // Hobbies & interests (20 questions)
            ['category' => 'hobbies_interests', 'question' => 'What hobby would you pick up if you had unlimited time?'],
            ['category' => 'hobbies_interests', 'question' => 'What\'s the last concert or live event you attended?'],
            ['category' => 'hobbies_interests', 'question' => 'Are you more of a bookworm or a movie buff?'],
            ['category' => 'hobbies_interests', 'question' => 'What\'s your favorite way to stay active?'],
            ['category' => 'hobbies_interests', 'question' => 'Do you have any creative outlets? Tell me about them!'],
            ['category' => 'hobbies_interests', 'question' => 'What\'s a skill you\'ve been working on recently?'],
            ['category' => 'hobbies_interests', 'question' => 'What\'s your favorite type of music to listen to?'],
            ['category' => 'hobbies_interests', 'question' => 'Are you into any sports? Playing or watching?'],
            ['category' => 'hobbies_interests', 'question' => 'What\'s the most interesting hobby you\'ve tried?'],
            ['category' => 'hobbies_interests', 'question' => 'Do you prefer indoor or outdoor activities?'],
            ['category' => 'hobbies_interests', 'question' => 'What\'s your favorite podcast or YouTube channel?'],
            ['category' => 'hobbies_interests', 'question' => 'Are you a morning person or a night owl?'],
            ['category' => 'hobbies_interests', 'question' => 'What\'s your go-to activity on a Sunday afternoon?'],
            ['category' => 'hobbies_interests', 'question' => 'Do you enjoy cooking? What\'s your signature dish?'],
            ['category' => 'hobbies_interests', 'question' => 'What\'s the last thing you binge-watched?'],
            ['category' => 'hobbies_interests', 'question' => 'Do you collect anything?'],
            ['category' => 'hobbies_interests', 'question' => 'What\'s your favorite board game or video game?'],
            ['category' => 'hobbies_interests', 'question' => 'Are you more of a planner or go-with-the-flow type?'],
            ['category' => 'hobbies_interests', 'question' => 'What\'s your favorite way to explore a new city?'],
            ['category' => 'hobbies_interests', 'question' => 'Do you prefer sunrise or sunset?'],

            // Life goals & dreams (20 questions)
            ['category' => 'life_goals', 'question' => 'What\'s on your bucket list?'],
            ['category' => 'life_goals', 'question' => 'Where do you see yourself in 5 years?'],
            ['category' => 'life_goals', 'question' => 'What\'s your biggest dream?'],
            ['category' => 'life_goals', 'question' => 'If money wasn\'t an issue, what would you do with your life?'],
            ['category' => 'life_goals', 'question' => 'What legacy do you want to leave behind?'],
            ['category' => 'life_goals', 'question' => 'What\'s something you want to accomplish in the next year?'],
            ['category' => 'life_goals', 'question' => 'What motivates you to get out of bed every morning?'],
            ['category' => 'life_goals', 'question' => 'What\'s your definition of success?'],
            ['category' => 'life_goals', 'question' => 'If you could start a business, what would it be?'],
            ['category' => 'life_goals', 'question' => 'What cause are you most passionate about?'],
            ['category' => 'life_goals', 'question' => 'What\'s the biggest risk you\'ve ever taken?'],
            ['category' => 'life_goals', 'question' => 'What\'s something you want to be known for?'],
            ['category' => 'life_goals', 'question' => 'If you could live anywhere in the world, where would it be?'],
            ['category' => 'life_goals', 'question' => 'What adventure is next on your list?'],
            ['category' => 'life_goals', 'question' => 'What\'s your idea of retirement?'],
            ['category' => 'life_goals', 'question' => 'What would you do if you had a whole year off?'],
            ['category' => 'life_goals', 'question' => 'What\'s the most important lesson life has taught you?'],
            ['category' => 'life_goals', 'question' => 'What would your ideal day look like, start to finish?'],
            ['category' => 'life_goals', 'question' => 'What\'s something you want to learn before you\'re 50?'],
            ['category' => 'life_goals', 'question' => 'If you could write a book, what would it be about?'],

            // Food & travel (20 questions)
            ['category' => 'food_travel', 'question' => 'What\'s your go-to comfort food?'],
            ['category' => 'food_travel', 'question' => 'Coffee or tea? Defend your answer!'],
            ['category' => 'food_travel', 'question' => 'What\'s the best meal you\'ve ever had?'],
            ['category' => 'food_travel', 'question' => 'If you could only eat one cuisine for the rest of your life, what would it be?'],
            ['category' => 'food_travel', 'question' => 'What\'s your favorite restaurant in your city?'],
            ['category' => 'food_travel', 'question' => 'Sweet or savory? What\'s your preference?'],
            ['category' => 'food_travel', 'question' => 'What\'s the most exotic food you\'ve tried?'],
            ['category' => 'food_travel', 'question' => 'Beach vacation or mountain retreat?'],
            ['category' => 'food_travel', 'question' => 'What\'s your favorite travel destination?'],
            ['category' => 'food_travel', 'question' => 'Do you prefer to travel solo or with company?'],
            ['category' => 'food_travel', 'question' => 'What\'s the best travel experience you\'ve had?'],
            ['category' => 'food_travel', 'question' => 'What\'s next on your travel wishlist?'],
            ['category' => 'food_travel', 'question' => 'Do you prefer trying local street food or fancy restaurants?'],
            ['category' => 'food_travel', 'question' => 'What\'s your favorite late-night snack?'],
            ['category' => 'food_travel', 'question' => 'All-inclusive resort or backpacking adventure?'],
            ['category' => 'food_travel', 'question' => 'What\'s the weirdest food combination you actually enjoy?'],
            ['category' => 'food_travel', 'question' => 'If you could teleport anywhere for dinner tonight, where would you go?'],
            ['category' => 'food_travel', 'question' => 'Road trip or flight? Which do you prefer?'],
            ['category' => 'food_travel', 'question' => 'What\'s your favorite breakfast food?'],
            ['category' => 'food_travel', 'question' => 'Would you rather explore a new city or relax by the pool?'],

            // Fun & quirky (20 questions)
            ['category' => 'fun_quirky', 'question' => 'What\'s your most unpopular opinion?'],
            ['category' => 'fun_quirky', 'question' => 'If you were an animal, what would you be?'],
            ['category' => 'fun_quirky', 'question' => 'What\'s the weirdest thing you\'ve ever Googled?'],
            ['category' => 'fun_quirky', 'question' => 'If you had a superpower, what would it be?'],
            ['category' => 'fun_quirky', 'question' => 'What\'s your guilty pleasure TV show or movie?'],
            ['category' => 'fun_quirky', 'question' => 'If you could be a character from any book/movie, who would you be?'],
            ['category' => 'fun_quirky', 'question' => 'What\'s the most embarrassing thing that\'s happened to you?'],
            ['category' => 'fun_quirky', 'question' => 'What would your wrestling name be?'],
            ['category' => 'fun_quirky', 'question' => 'If you could have any fictional character as a best friend, who would it be?'],
            ['category' => 'fun_quirky', 'question' => 'What\'s your spirit animal?'],
            ['category' => 'fun_quirky', 'question' => 'If you won the lottery tomorrow, what\'s the first thing you\'d buy?'],
            ['category' => 'fun_quirky', 'question' => 'What\'s your biggest pet peeve?'],
            ['category' => 'fun_quirky', 'question' => 'If you could swap lives with someone for a day, who would it be?'],
            ['category' => 'fun_quirky', 'question' => 'What\'s the most useless talent you have?'],
            ['category' => 'fun_quirky', 'question' => 'Would you rather fight 100 duck-sized horses or 1 horse-sized duck?'],
            ['category' => 'fun_quirky', 'question' => 'What\'s the strangest compliment you\'ve received?'],
            ['category' => 'fun_quirky', 'question' => 'If you could time travel, past or future?'],
            ['category' => 'fun_quirky', 'question' => 'What\'s your favorite meme right now?'],
            ['category' => 'fun_quirky', 'question' => 'If you were a pizza topping, what would you be?'],
            ['category' => 'fun_quirky', 'question' => 'What\'s the most random fact you know?'],

            // Values & beliefs (15 questions)
            ['category' => 'values_beliefs', 'question' => 'What do you value most in a relationship?'],
            ['category' => 'values_beliefs', 'question' => 'How do you define happiness?'],
            ['category' => 'values_beliefs', 'question' => 'What\'s your love language?'],
            ['category' => 'values_beliefs', 'question' => 'What qualities do you admire most in people?'],
            ['category' => 'values_beliefs', 'question' => 'How do you handle disagreements?'],
            ['category' => 'values_beliefs', 'question' => 'What role does family play in your life?'],
            ['category' => 'values_beliefs', 'question' => 'What\'s something you\'ll never compromise on?'],
            ['category' => 'values_beliefs', 'question' => 'How important is work-life balance to you?'],
            ['category' => 'values_beliefs', 'question' => 'What makes a house feel like home?'],
            ['category' => 'values_beliefs', 'question' => 'How do you show someone you care?'],
            ['category' => 'values_beliefs', 'question' => 'What\'s your perspective on personal growth?'],
            ['category' => 'values_beliefs', 'question' => 'What does trust mean to you?'],
            ['category' => 'values_beliefs', 'question' => 'How do you celebrate your wins?'],
            ['category' => 'values_beliefs', 'question' => 'What\'s your approach to handling tough times?'],
            ['category' => 'values_beliefs', 'question' => 'What makes you feel most connected to someone?'],

            // This or that (15 questions)
            ['category' => 'this_or_that', 'question' => 'Netflix binge or reading a book?'],
            ['category' => 'this_or_that', 'question' => 'City life or countryside?'],
            ['category' => 'this_or_that', 'question' => 'Dogs or cats?'],
            ['category' => 'this_or_that', 'question' => 'Text or call?'],
            ['category' => 'this_or_that', 'question' => 'Spontaneous trip or planned vacation?'],
            ['category' => 'this_or_that', 'question' => 'Stay in or go out?'],
            ['category' => 'this_or_that', 'question' => 'Summer or winter?'],
            ['category' => 'this_or_that', 'question' => 'Early riser or night owl?'],
            ['category' => 'this_or_that', 'question' => 'Big party or intimate gathering?'],
            ['category' => 'this_or_that', 'question' => 'Pizza or burgers?'],
            ['category' => 'this_or_that', 'question' => 'Adventure or relaxation?'],
            ['category' => 'this_or_that', 'question' => 'Save money or spend on experiences?'],
            ['category' => 'this_or_that', 'question' => 'Work from home or office?'],
            ['category' => 'this_or_that', 'question' => 'Concert or comedy show?'],
            ['category' => 'this_or_that', 'question' => 'Gym or yoga?'],
        ];

        foreach ($icebreakers as $index => $icebreaker) {
            Icebreaker::create([
                'category' => $icebreaker['category'],
                'question' => $icebreaker['question'],
                'is_active' => true,
                'usage_count' => 0,
                'success_rate' => 50, // Start with 50% baseline
                'display_order' => $index,
            ]);
        }

        $this->command->info('Created ' . count($icebreakers) . ' icebreaker questions!');
    }
}
