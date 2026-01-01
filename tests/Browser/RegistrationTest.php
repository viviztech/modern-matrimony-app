<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class RegistrationTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * Test user can view registration page
     */
    public function test_user_can_view_registration_page(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register')
                    ->assertSee('Register')
                    ->assertSee('Name')
                    ->assertSee('Email')
                    ->assertSee('Password');
        });
    }

    /**
     * Test user can register with valid credentials
     */
    public function test_user_can_register_with_valid_credentials(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register')
                    ->type('name', 'John Doe')
                    ->type('email', 'john@example.com')
                    ->type('password', 'Password123!')
                    ->type('password_confirmation', 'Password123!')
                    ->press('Register')
                    ->waitForLocation('/onboarding/step1', 10)
                    ->assertPathIs('/onboarding/step1')
                    ->assertAuthenticated();
        });

        // Verify user was created in database
        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
            'name' => 'John Doe',
        ]);
    }

    /**
     * Test registration fails with invalid email
     */
    public function test_registration_fails_with_invalid_email(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register')
                    ->type('name', 'John Doe')
                    ->type('email', 'invalid-email')
                    ->type('password', 'Password123!')
                    ->type('password_confirmation', 'Password123!')
                    ->press('Register')
                    ->waitForText('email')
                    ->assertSee('email');
        });
    }

    /**
     * Test registration fails with short password
     */
    public function test_registration_fails_with_short_password(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register')
                    ->type('name', 'John Doe')
                    ->type('email', 'john@example.com')
                    ->type('password', '12345')
                    ->type('password_confirmation', '12345')
                    ->press('Register')
                    ->waitForText('password')
                    ->assertSee('password');
        });
    }

    /**
     * Test registration fails with mismatched passwords
     */
    public function test_registration_fails_with_mismatched_passwords(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register')
                    ->type('name', 'John Doe')
                    ->type('email', 'john@example.com')
                    ->type('password', 'Password123!')
                    ->type('password_confirmation', 'DifferentPassword123!')
                    ->press('Register')
                    ->waitForText('password')
                    ->assertSee('password');
        });
    }

    /**
     * Test registration fails with duplicate email
     */
    public function test_registration_fails_with_duplicate_email(): void
    {
        // Create existing user
        User::factory()->create([
            'email' => 'existing@example.com',
        ]);

        $this->browse(function (Browser $browser) {
            $browser->visit('/register')
                    ->type('name', 'John Doe')
                    ->type('email', 'existing@example.com')
                    ->type('password', 'Password123!')
                    ->type('password_confirmation', 'Password123!')
                    ->press('Register')
                    ->waitForText('email')
                    ->assertSee('email');
        });
    }

    /**
     * Test user can navigate to login from registration page
     */
    public function test_user_can_navigate_to_login_from_registration(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register')
                    ->clickLink('login')
                    ->waitForLocation('/login', 5)
                    ->assertPathIs('/login');
        });
    }

    /**
     * Test complete registration and onboarding flow
     */
    public function test_complete_registration_and_onboarding_flow(): void
    {
        $this->browse(function (Browser $browser) {
            // Register
            $browser->visit('/register')
                    ->type('name', 'Jane Smith')
                    ->type('email', 'jane@example.com')
                    ->type('password', 'Password123!')
                    ->type('password_confirmation', 'Password123!')
                    ->press('Register')
                    ->waitForLocation('/onboarding/step1', 10)
                    ->assertPathIs('/onboarding/step1');

            // Step 1: Basic Info
            if ($browser->seeIn('body', 'Gender')) {
                $browser->select('gender', 'female')
                        ->type('date_of_birth', '1995-01-15')
                        ->type('height', '165')
                        ->press('Next')
                        ->waitFor('@step2', 10)
                        ->assertPathIs('/onboarding/step2');
            }

            // Verify user is authenticated throughout
            $browser->assertAuthenticated();
        });
    }
}
