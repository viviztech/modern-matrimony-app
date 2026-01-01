<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Hash;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LoginTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * Test user can view login page
     */
    public function test_user_can_view_login_page(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                    ->assertSee('Log in')
                    ->assertSee('Email')
                    ->assertSee('Password');
        });
    }

    /**
     * Test user can login with valid credentials
     */
    public function test_user_can_login_with_valid_credentials(): void
    {
        // Create a test user
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('Password123!'),
            'onboarding_completed' => true,
        ]);

        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                    ->type('email', 'test@example.com')
                    ->type('password', 'Password123!')
                    ->press('Log in')
                    ->waitForLocation('/dashboard', 10)
                    ->assertPathIs('/dashboard')
                    ->assertAuthenticated();
        });
    }

    /**
     * Test login fails with invalid email
     */
    public function test_login_fails_with_invalid_email(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                    ->type('email', 'nonexistent@example.com')
                    ->type('password', 'Password123!')
                    ->press('Log in')
                    ->waitForText('credentials', 5)
                    ->assertSee('credentials');
        });
    }

    /**
     * Test login fails with invalid password
     */
    public function test_login_fails_with_invalid_password(): void
    {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('CorrectPassword123!'),
        ]);

        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                    ->type('email', 'test@example.com')
                    ->type('password', 'WrongPassword123!')
                    ->press('Log in')
                    ->waitForText('credentials', 5)
                    ->assertSee('credentials');
        });
    }

    /**
     * Test remember me functionality
     */
    public function test_remember_me_functionality(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('Password123!'),
            'onboarding_completed' => true,
        ]);

        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                    ->type('email', 'test@example.com')
                    ->type('password', 'Password123!')
                    ->check('remember')
                    ->press('Log in')
                    ->waitForLocation('/dashboard', 10)
                    ->assertAuthenticated();

            // Check for remember cookie
            $cookies = $browser->driver->manage()->getCookies();
            $rememberCookie = collect($cookies)->firstWhere('name', 'remember_web');

            $this->assertNotNull($rememberCookie, 'Remember cookie should be set');
        });
    }

    /**
     * Test user can navigate to registration from login page
     */
    public function test_user_can_navigate_to_registration_from_login(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                    ->clickLink('register')
                    ->waitForLocation('/register', 5)
                    ->assertPathIs('/register');
        });
    }

    /**
     * Test user can navigate to password reset from login page
     */
    public function test_user_can_navigate_to_password_reset_from_login(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                    ->clickLink('Forgot your password?')
                    ->waitForLocation('/forgot-password', 5)
                    ->assertPathIs('/forgot-password');
        });
    }

    /**
     * Test incomplete onboarding redirect
     */
    public function test_incomplete_onboarding_redirect(): void
    {
        // Create user who hasn't completed onboarding
        $user = User::factory()->create([
            'email' => 'incomplete@example.com',
            'password' => Hash::make('Password123!'),
            'onboarding_completed' => false,
        ]);

        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                    ->type('email', 'incomplete@example.com')
                    ->type('password', 'Password123!')
                    ->press('Log in')
                    ->waitFor('body', 10)
                    ->assertAuthenticated();

            // Should redirect to onboarding
            $path = $browser->driver->getCurrentURL();
            $this->assertTrue(
                str_contains($path, '/onboarding'),
                'User should be redirected to onboarding'
            );
        });
    }

    /**
     * Test logout functionality
     */
    public function test_logout_functionality(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('Password123!'),
            'onboarding_completed' => true,
        ]);

        $this->browse(function (Browser $browser) {
            // Login
            $browser->visit('/login')
                    ->type('email', 'test@example.com')
                    ->type('password', 'Password123!')
                    ->press('Log in')
                    ->waitForLocation('/dashboard', 10)
                    ->assertAuthenticated();

            // Logout
            $browser->press('Logout')
                    ->waitForLocation('/login', 10)
                    ->assertGuest();
        });
    }

    /**
     * Test rate limiting on login attempts
     */
    public function test_rate_limiting_on_login_attempts(): void
    {
        $this->browse(function (Browser $browser) {
            // Make multiple failed login attempts
            for ($i = 0; $i < 6; $i++) {
                $browser->visit('/login')
                        ->type('email', 'test@example.com')
                        ->type('password', 'WrongPassword!')
                        ->press('Log in')
                        ->pause(500);
            }

            // Should see rate limit message
            $browser->waitForText('Too many', 5)
                    ->assertSee('Too many');
        });
    }
}
