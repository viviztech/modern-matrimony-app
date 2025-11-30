<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Profile;
use App\Models\Photo;
use App\Models\Preference;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🌱 Seeding matrimony database...');

        // Create test user (for development/testing)
        $this->command->info('Creating test user...');
        $testUser = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '+919876543210',
            'gender' => 'male',
            'email_verified_at' => now(),
            'phone_verified_at' => now(),
            'is_active' => true,
            'is_admin' => false,
            'profile_completion_percentage' => 100,
        ]);

        // Create admin user
        $this->command->info('Creating admin user...');
        $adminUser = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@matrimony.app',
            'phone' => '+919999999999',
            'gender' => 'male',
            'email_verified_at' => now(),
            'phone_verified_at' => now(),
            'video_verified_at' => now(),
            'is_active' => true,
            'is_admin' => true,
            'is_premium' => true,
            'premium_until' => now()->addYears(10),
            'profile_completion_percentage' => 100,
        ]);

        // Create profile for admin user
        $adminUser->profile()->create(
            Profile::factory()->make()->toArray()
        );

        // Create photos for admin user
        Photo::factory()->primary()->create(['user_id' => $adminUser->id]);
        Photo::factory()->approved()->count(3)->create([
            'user_id' => $adminUser->id,
            'order' => fn () => Photo::where('user_id', $adminUser->id)->count(),
        ]);

        // Create preferences for admin user
        $adminUser->preference()->create(
            Preference::factory()->make()->toArray()
        );

        // Create profile for test user
        $testUser->profile()->create(
            Profile::factory()->make()->toArray()
        );

        // Create photos for test user
        Photo::factory()->primary()->create(['user_id' => $testUser->id]);
        Photo::factory()->approved()->count(3)->create([
            'user_id' => $testUser->id,
            'order' => fn () => Photo::where('user_id', $testUser->id)->count(),
        ]);

        // Create preferences for test user
        $testUser->preference()->create(
            Preference::factory()->make()->toArray()
        );

        $this->command->info('✅ Test user created: test@example.com / password');
        $this->command->info('✅ Admin user created: admin@matrimony.app / password');

        // Create 50 random users with complete profiles
        $this->command->info('Creating 50 users with profiles...');
        $this->command->getOutput()->progressStart(50);

        for ($i = 0; $i < 50; $i++) {
            // Create user
            $user = User::factory()->create();

            // Create profile
            $user->profile()->create(
                Profile::factory()->make()->toArray()
            );

            // Create 3-6 photos for each user
            $photoCount = rand(3, 6);

            // First photo is primary
            Photo::factory()->primary()->create(['user_id' => $user->id]);

            // Rest are regular photos
            Photo::factory()->approved()->count($photoCount - 1)->create([
                'user_id' => $user->id,
                'order' => fn () => Photo::where('user_id', $user->id)->count(),
            ]);

            // Create preferences
            $user->preference()->create(
                Preference::factory()->make()->toArray()
            );

            // Update profile completion
            $completion = $user->profile->calculateCompletion();
            if ($user->photos()->approved()->count() >= 3) {
                $completion += 10; // Add 10 for having photos
            }
            $user->update(['profile_completion_percentage' => min($completion, 100)]);

            $this->command->getOutput()->progressAdvance();
        }

        $this->command->getOutput()->progressFinish();

        // Show summary
        $this->command->info('');
        $this->command->info('✅ Database seeding completed!');
        $this->command->info('');
        $this->command->table(
            ['Model', 'Count'],
            [
                ['Users', User::count()],
                ['Profiles', Profile::count()],
                ['Photos', Photo::count()],
                ['Preferences', Preference::count()],
                ['Verified Users', User::whereNotNull('email_verified_at')->count()],
                ['Premium Users', User::where('is_premium', true)->count()],
                ['Complete Profiles', User::where('profile_completion_percentage', '>=', 80)->count()],
            ]
        );

        $this->command->info('');
        $this->command->info('🎉 Ready to go!');
        $this->command->info('');
        $this->command->info('📧 User Login:');
        $this->command->info('   Email: test@example.com');
        $this->command->info('   Password: password');
        $this->command->info('');
        $this->command->info('👑 Admin Login:');
        $this->command->info('   Email: admin@matrimony.app');
        $this->command->info('   Password: password');
        $this->command->info('   Access: /admin/moderation');
        $this->command->info('');
    }
}
