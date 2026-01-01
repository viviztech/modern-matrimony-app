<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\PaymentService;
use App\Models\User;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Razorpay\Api\Api;

class PaymentServiceTest extends TestCase
{
    use RefreshDatabase;

    protected PaymentService $service;
    protected $mockRazorpayApi;

    protected function setUp(): void
    {
        parent::setUp();

        // Mock Razorpay API
        $this->mockRazorpayApi = Mockery::mock(Api::class);
        $this->app->instance(Api::class, $this->mockRazorpayApi);

        // Create service with mocked API
        $this->service = Mockery::mock(PaymentService::class)->makePartial();
        $this->service->shouldAllowMockingProtectedMethods();
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function it_creates_order_with_monthly_billing()
    {
        $user = User::factory()->create();
        $plan = SubscriptionPlan::factory()->create([
            'price_monthly' => 999,
            'price_yearly' => 9999,
        ]);

        // Mock Razorpay order creation
        $mockOrder = Mockery::mock();
        $mockOrder->id = 'order_test123';

        $mockOrderApi = Mockery::mock();
        $mockOrderApi->shouldReceive('create')
            ->once()
            ->with(Mockery::on(function ($args) {
                return $args['amount'] === 99900 && // 999 * 100
                       $args['currency'] === 'INR';
            }))
            ->andReturn($mockOrder);

        $this->service->api = Mockery::mock(Api::class);
        $this->service->api->order = $mockOrderApi;

        $payment = $this->service->createOrder($user, $plan, 'monthly');

        $this->assertInstanceOf(Payment::class, $payment);
        $this->assertEquals($user->id, $payment->user_id);
        $this->assertEquals('order_test123', $payment->razorpay_order_id);
        $this->assertEquals(999, $payment->amount);
        $this->assertEquals('pending', $payment->status);
        $this->assertEquals($plan->id, $payment->metadata['plan_id']);
        $this->assertEquals('monthly', $payment->metadata['billing_cycle']);
    }

    /** @test */
    public function it_creates_order_with_yearly_billing()
    {
        $user = User::factory()->create();
        $plan = SubscriptionPlan::factory()->create([
            'price_monthly' => 999,
            'price_yearly' => 9999,
        ]);

        $mockOrder = Mockery::mock();
        $mockOrder->id = 'order_test456';

        $mockOrderApi = Mockery::mock();
        $mockOrderApi->shouldReceive('create')
            ->once()
            ->with(Mockery::on(function ($args) {
                return $args['amount'] === 999900; // 9999 * 100
            }))
            ->andReturn($mockOrder);

        $this->service->api = Mockery::mock(Api::class);
        $this->service->api->order = $mockOrderApi;

        $payment = $this->service->createOrder($user, $plan, 'yearly');

        $this->assertEquals(9999, $payment->amount);
        $this->assertEquals('yearly', $payment->metadata['billing_cycle']);
    }

    /** @test */
    public function it_verifies_valid_payment_signature()
    {
        $mockUtility = Mockery::mock();
        $mockUtility->shouldReceive('verifyPaymentSignature')
            ->once()
            ->with(Mockery::on(function ($attrs) {
                return isset($attrs['razorpay_order_id']) &&
                       isset($attrs['razorpay_payment_id']) &&
                       isset($attrs['razorpay_signature']);
            }))
            ->andReturn(true);

        $this->service->api = Mockery::mock(Api::class);
        $this->service->api->utility = $mockUtility;

        $result = $this->service->verifyPayment(
            'order_123',
            'pay_123',
            'signature_123'
        );

        $this->assertTrue($result);
    }

    /** @test */
    public function it_rejects_invalid_payment_signature()
    {
        $mockUtility = Mockery::mock();
        $mockUtility->shouldReceive('verifyPaymentSignature')
            ->once()
            ->andThrow(new \Exception('Invalid signature'));

        $this->service->api = Mockery::mock(Api::class);
        $this->service->api->utility = $mockUtility;

        $result = $this->service->verifyPayment(
            'order_123',
            'pay_123',
            'invalid_signature'
        );

        $this->assertFalse($result);
    }

    /** @test */
    public function it_processes_successful_payment_and_creates_subscription()
    {
        $user = User::factory()->create(['is_premium' => false]);
        $plan = SubscriptionPlan::factory()->create([
            'name' => 'Premium',
            'price_monthly' => 999,
        ]);

        $payment = Payment::factory()->create([
            'user_id' => $user->id,
            'razorpay_order_id' => 'order_123',
            'status' => 'pending',
            'metadata' => [
                'plan_id' => $plan->id,
                'plan_name' => $plan->name,
                'billing_cycle' => 'monthly',
            ],
        ]);

        // Mock payment verification
        $this->service->shouldReceive('verifyPayment')
            ->once()
            ->andReturn(true);

        // Mock Razorpay payment fetch
        $mockPayment = Mockery::mock();
        $mockPayment->method = 'card';

        $mockPaymentApi = Mockery::mock();
        $mockPaymentApi->shouldReceive('fetch')
            ->once()
            ->with('pay_123')
            ->andReturn($mockPayment);

        $this->service->api = Mockery::mock(Api::class);
        $this->service->api->payment = $mockPaymentApi;

        $subscription = $this->service->processSuccessfulPayment(
            'order_123',
            'pay_123',
            'signature_123'
        );

        $this->assertInstanceOf(Subscription::class, $subscription);
        $this->assertEquals($user->id, $subscription->user_id);
        $this->assertEquals($plan->id, $subscription->plan_id);
        $this->assertEquals('active', $subscription->status);
        $this->assertEquals('monthly', $subscription->billing_cycle);

        // Verify payment was marked as completed
        $payment->refresh();
        $this->assertEquals('completed', $payment->status);
        $this->assertEquals('pay_123', $payment->razorpay_payment_id);

        // Verify user was upgraded to premium
        $user->refresh();
        $this->assertTrue($user->is_premium);
        $this->assertNotNull($user->premium_until);
    }

    /** @test */
    public function it_fails_payment_with_invalid_signature()
    {
        $user = User::factory()->create();
        $plan = SubscriptionPlan::factory()->create();

        $payment = Payment::factory()->create([
            'user_id' => $user->id,
            'razorpay_order_id' => 'order_123',
            'status' => 'pending',
            'metadata' => [
                'plan_id' => $plan->id,
                'billing_cycle' => 'monthly',
            ],
        ]);

        // Mock payment verification failure
        $this->service->shouldReceive('verifyPayment')
            ->once()
            ->andReturn(false);

        $subscription = $this->service->processSuccessfulPayment(
            'order_123',
            'pay_123',
            'invalid_signature'
        );

        $this->assertNull($subscription);

        $payment->refresh();
        $this->assertEquals('failed', $payment->status);
    }

    /** @test */
    public function it_cancels_existing_subscription_when_creating_new_one()
    {
        $user = User::factory()->create(['is_premium' => true]);
        $oldPlan = SubscriptionPlan::factory()->create();
        $newPlan = SubscriptionPlan::factory()->create();

        // Create existing active subscription
        $existingSubscription = Subscription::factory()->create([
            'user_id' => $user->id,
            'plan_id' => $oldPlan->id,
            'status' => 'active',
        ]);

        $payment = Payment::factory()->create([
            'user_id' => $user->id,
            'razorpay_order_id' => 'order_123',
            'status' => 'pending',
            'metadata' => [
                'plan_id' => $newPlan->id,
                'billing_cycle' => 'monthly',
            ],
        ]);

        $this->service->shouldReceive('verifyPayment')->andReturn(true);

        $mockPayment = Mockery::mock();
        $mockPayment->method = 'upi';

        $mockPaymentApi = Mockery::mock();
        $mockPaymentApi->shouldReceive('fetch')->andReturn($mockPayment);

        $this->service->api = Mockery::mock(Api::class);
        $this->service->api->payment = $mockPaymentApi;

        $newSubscription = $this->service->processSuccessfulPayment(
            'order_123',
            'pay_123',
            'signature_123'
        );

        // Verify old subscription was cancelled
        $existingSubscription->refresh();
        $this->assertEquals('cancelled', $existingSubscription->status);

        // Verify new subscription was created
        $this->assertEquals('active', $newSubscription->status);
        $this->assertEquals($newPlan->id, $newSubscription->plan_id);
    }

    /** @test */
    public function it_processes_failed_payment()
    {
        $user = User::factory()->create();
        $payment = Payment::factory()->create([
            'user_id' => $user->id,
            'razorpay_order_id' => 'order_123',
            'status' => 'pending',
        ]);

        $this->service->processFailedPayment('order_123', 'Payment declined');

        $payment->refresh();
        $this->assertEquals('failed', $payment->status);
    }

    /** @test */
    public function it_processes_refund_successfully()
    {
        $user = User::factory()->create(['is_premium' => true]);
        $subscription = Subscription::factory()->create([
            'user_id' => $user->id,
            'status' => 'active',
        ]);

        $payment = Payment::factory()->create([
            'user_id' => $user->id,
            'status' => 'completed',
            'razorpay_payment_id' => 'pay_123',
            'amount' => 999,
            'subscription_id' => $subscription->id,
        ]);

        // Mock refund
        $mockRefund = Mockery::mock();
        $mockRefund->id = 'rfnd_123';

        $mockRazorpayPayment = Mockery::mock();
        $mockRazorpayPayment->shouldReceive('refund')
            ->once()
            ->with(['amount' => 99900])
            ->andReturn($mockRefund);

        $mockPaymentApi = Mockery::mock();
        $mockPaymentApi->shouldReceive('fetch')
            ->with('pay_123')
            ->andReturn($mockRazorpayPayment);

        $this->service->api = Mockery::mock(Api::class);
        $this->service->api->payment = $mockPaymentApi;

        $result = $this->service->processRefund($payment);

        $this->assertTrue($result);

        $payment->refresh();
        $this->assertEquals('refunded', $payment->status);

        $subscription->refresh();
        $this->assertEquals('cancelled', $subscription->status);

        $user->refresh();
        $this->assertFalse($user->is_premium);
        $this->assertNull($user->premium_until);
    }

    /** @test */
    public function it_fails_refund_for_non_completed_payment()
    {
        $payment = Payment::factory()->create([
            'status' => 'pending',
        ]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Only completed payments can be refunded');

        $this->service->processRefund($payment);
    }

    /** @test */
    public function it_fetches_payment_details_from_razorpay()
    {
        $mockPayment = Mockery::mock();
        $mockPayment->id = 'pay_123';
        $mockPayment->amount = 99900;

        $mockPaymentApi = Mockery::mock();
        $mockPaymentApi->shouldReceive('fetch')
            ->with('pay_123')
            ->andReturn($mockPayment);

        $this->service->api = Mockery::mock(Api::class);
        $this->service->api->payment = $mockPaymentApi;

        $result = $this->service->fetchPaymentDetails('pay_123');

        $this->assertEquals('pay_123', $result->id);
        $this->assertEquals(99900, $result->amount);
    }

    /** @test */
    public function it_handles_webhook_payment_captured()
    {
        $user = User::factory()->create();
        $plan = SubscriptionPlan::factory()->create();

        $payment = Payment::factory()->create([
            'user_id' => $user->id,
            'razorpay_order_id' => 'order_123',
            'status' => 'pending',
            'metadata' => [
                'plan_id' => $plan->id,
                'billing_cycle' => 'monthly',
            ],
        ]);

        $this->service->shouldReceive('verifyPayment')->andReturn(true);

        $mockPayment = Mockery::mock();
        $mockPayment->method = 'netbanking';

        $mockPaymentApi = Mockery::mock();
        $mockPaymentApi->shouldReceive('fetch')->andReturn($mockPayment);

        $this->service->api = Mockery::mock(Api::class);
        $this->service->api->payment = $mockPaymentApi;

        $subscription = $this->service->processSuccessfulPayment(
            'order_123',
            'pay_webhook_123',
            'signature_webhook_123'
        );

        $this->assertNotNull($subscription);
        $this->assertEquals('active', $subscription->status);
    }

    /** @test */
    public function it_calculates_correct_subscription_duration()
    {
        $user = User::factory()->create();
        $plan = SubscriptionPlan::factory()->create();

        // Test monthly subscription
        $monthlyPayment = Payment::factory()->create([
            'user_id' => $user->id,
            'razorpay_order_id' => 'order_monthly',
            'status' => 'pending',
            'metadata' => [
                'plan_id' => $plan->id,
                'billing_cycle' => 'monthly',
            ],
        ]);

        $this->service->shouldReceive('verifyPayment')->andReturn(true);

        $mockPayment = Mockery::mock();
        $mockPayment->method = 'card';

        $mockPaymentApi = Mockery::mock();
        $mockPaymentApi->shouldReceive('fetch')->andReturn($mockPayment);

        $this->service->api = Mockery::mock(Api::class);
        $this->service->api->payment = $mockPaymentApi;

        $subscription = $this->service->processSuccessfulPayment(
            'order_monthly',
            'pay_123',
            'sig_123'
        );

        // Monthly = 30 days
        $expectedEndsAt = now()->addDays(30);
        $this->assertEquals(
            $expectedEndsAt->format('Y-m-d'),
            $subscription->ends_at->format('Y-m-d')
        );
    }
}
