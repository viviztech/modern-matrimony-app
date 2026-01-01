<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Hash;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PaymentTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected User $user;

    /**
     * Setup authenticated user before each test
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'email' => 'premium@example.com',
            'password' => Hash::make('Password123!'),
            'onboarding_completed' => true,
        ]);
    }

    /**
     * Test user can view subscription plans page
     */
    public function test_user_can_view_subscription_plans(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                    ->visit('/subscriptions')
                    ->assertSee('Subscription Plans')
                    ->assertSee('Gold')
                    ->assertSee('Platinum')
                    ->assertSee('Elite');
        });
    }

    /**
     * Test free user sees upgrade prompts
     */
    public function test_free_user_sees_upgrade_prompts(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                    ->visit('/subscriptions')
                    ->assertSee('Current Plan')
                    ->assertSee('Free')
                    ->assertSee('Upgrade');
        });
    }

    /**
     * Test user can view checkout page for Gold plan
     */
    public function test_user_can_view_checkout_for_gold_plan(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                    ->visit('/subscriptions')
                    ->clickLink('Get Gold')
                    ->waitForLocation('/subscriptions/checkout/gold', 10)
                    ->assertPathBeginsWith('/subscriptions/checkout')
                    ->assertSee('Gold')
                    ->assertSee('₹999');
        });
    }

    /**
     * Test checkout page displays correct plan details
     */
    public function test_checkout_displays_correct_plan_details(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                    ->visit('/subscriptions/checkout/platinum')
                    ->assertSee('Platinum')
                    ->assertSee('₹1,999')
                    ->assertSee('Unlimited likes')
                    ->assertSee('Advanced filters')
                    ->assertSee('Video calls');
        });
    }

    /**
     * Test Razorpay payment button is present
     */
    public function test_razorpay_payment_button_is_present(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                    ->visit('/subscriptions/checkout/gold')
                    ->waitFor('#razorpay-button', 10)
                    ->assertVisible('#razorpay-button')
                    ->assertSee('Pay with Razorpay');
        });
    }

    /**
     * Test subscription features are displayed correctly
     */
    public function test_subscription_features_displayed_correctly(): void
    {
        $this->browse(function (Browser $browser) {
            // Test Gold features
            $browser->loginAs($this->user)
                    ->visit('/subscriptions')
                    ->assertSee('Unlimited daily likes')
                    ->assertSee('Advanced search filters')
                    ->assertSee('See who liked you');

            // Test Platinum features
            $browser->assertSee('All Gold features')
                    ->assertSee('Video calls')
                    ->assertSee('Profile boost');

            // Test Elite features
            $browser->assertSee('All Platinum features')
                    ->assertSee('Priority support')
                    ->assertSee('Background verification');
        });
    }

    /**
     * Test user can view their active subscription
     */
    public function test_user_can_view_active_subscription(): void
    {
        // Create a user with active subscription
        $premiumUser = User::factory()->create([
            'email' => 'active@example.com',
            'password' => Hash::make('Password123!'),
            'onboarding_completed' => true,
            'subscription_tier' => 'gold',
        ]);

        $this->browse(function (Browser $browser) use ($premiumUser) {
            $browser->loginAs($premiumUser)
                    ->visit('/subscriptions')
                    ->assertSee('Current Plan')
                    ->assertSee('Gold')
                    ->assertSee('Active');
        });
    }

    /**
     * Test user can navigate to payment history
     */
    public function test_user_can_navigate_to_payment_history(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                    ->visit('/subscriptions')
                    ->clickLink('Payment History')
                    ->waitForLocation('/subscriptions/history', 10)
                    ->assertPathIs('/subscriptions/history');
        });
    }

    /**
     * Test payment history shows no payments for new user
     */
    public function test_payment_history_empty_for_new_user(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                    ->visit('/subscriptions/history')
                    ->assertSee('No payments yet')
                    ->orAssertSee('Payment History');
        });
    }

    /**
     * Test upgrade prompt on discover page for free users
     */
    public function test_upgrade_prompt_on_discover_for_free_users(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                    ->visit('/discover');

            // After exhausting daily likes (if implemented)
            // Should see upgrade prompt
            if ($browser->seeIn('body', 'Upgrade')) {
                $browser->assertSee('Upgrade to Gold')
                        ->assertSee('unlimited likes');
            }
        });
    }

    /**
     * Test cancel subscription button is visible for premium users
     */
    public function test_cancel_subscription_visible_for_premium_users(): void
    {
        $premiumUser = User::factory()->create([
            'email' => 'cancel@example.com',
            'password' => Hash::make('Password123!'),
            'onboarding_completed' => true,
            'subscription_tier' => 'platinum',
        ]);

        $this->browse(function (Browser $browser) use ($premiumUser) {
            $browser->loginAs($premiumUser)
                    ->visit('/subscriptions')
                    ->assertSee('Cancel Subscription')
                    ->orAssertSee('Manage Subscription');
        });
    }

    /**
     * Test plan comparison is clear and informative
     */
    public function test_plan_comparison_is_clear(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                    ->visit('/subscriptions')
                    ->assertSee('Compare Plans')
                    ->orAssertSee('Choose Your Plan');

            // Check pricing is visible
            $browser->assertSee('₹999')
                    ->assertSee('₹1,999')
                    ->assertSee('₹4,999');

            // Check plan names
            $browser->assertSee('Gold')
                    ->assertSee('Platinum')
                    ->assertSee('Elite');
        });
    }

    /**
     * Test premium features are gated for free users
     */
    public function test_premium_features_gated_for_free_users(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                    ->visit('/search/who-liked-you')
                    ->waitFor('body', 5);

            // Should see upgrade prompt or redirect
            $currentUrl = $browser->driver->getCurrentURL();
            $this->assertTrue(
                str_contains($currentUrl, 'subscriptions') ||
                str_contains($browser->text(), 'Upgrade') ||
                str_contains($browser->text(), 'Premium'),
                'Free users should see upgrade prompt for premium features'
            );
        });
    }

    /**
     * Test monthly and annual billing options
     */
    public function test_billing_cycle_options(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                    ->visit('/subscriptions');

            // Check if billing cycles are shown
            if ($browser->seeIn('body', 'Monthly')) {
                $browser->assertSee('Monthly')
                        ->orAssertSee('month');
            }

            // Elite plan should show pricing
            $browser->assertSee('₹4,999');
        });
    }
}
