<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Razorpay\Api\Api;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    protected Api $api;

    public function __construct()
    {
        $this->api = new Api(
            config('services.razorpay.key'),
            config('services.razorpay.secret')
        );
    }

    /**
     * Create a Razorpay order for subscription purchase.
     */
    public function createOrder(User $user, SubscriptionPlan $plan, string $billingCycle): Payment
    {
        $amount = $billingCycle === 'yearly' ? $plan->price_yearly : $plan->price_monthly;
        $amountInPaise = $amount * 100; // Convert to paise

        try {
            // Create Razorpay order
            $order = $this->api->order->create([
                'amount' => $amountInPaise,
                'currency' => 'INR',
                'receipt' => 'order_' . uniqid(),
                'notes' => [
                    'user_id' => $user->id,
                    'plan_id' => $plan->id,
                    'billing_cycle' => $billingCycle,
                ]
            ]);

            // Create payment record
            $payment = Payment::create([
                'user_id' => $user->id,
                'razorpay_order_id' => $order->id,
                'amount' => $amount,
                'currency' => 'INR',
                'status' => 'pending',
                'description' => "Subscription: {$plan->name} ({$billingCycle})",
                'metadata' => [
                    'plan_id' => $plan->id,
                    'plan_name' => $plan->name,
                    'billing_cycle' => $billingCycle,
                ],
            ]);

            Log::info('Razorpay order created', [
                'order_id' => $order->id,
                'user_id' => $user->id,
                'amount' => $amount,
            ]);

            return $payment;
        } catch (\Exception $e) {
            Log::error('Failed to create Razorpay order', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Verify payment signature and complete the payment.
     */
    public function verifyPayment(string $orderId, string $paymentId, string $signature): bool
    {
        try {
            $attributes = [
                'razorpay_order_id' => $orderId,
                'razorpay_payment_id' => $paymentId,
                'razorpay_signature' => $signature,
            ];

            $this->api->utility->verifyPaymentSignature($attributes);
            return true;
        } catch (\Exception $e) {
            Log::error('Payment signature verification failed', [
                'order_id' => $orderId,
                'payment_id' => $paymentId,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Process successful payment and activate subscription.
     */
    public function processSuccessfulPayment(
        string $orderId,
        string $paymentId,
        string $signature
    ): ?Subscription {
        return DB::transaction(function () use ($orderId, $paymentId, $signature) {
            // Find payment record
            $payment = Payment::where('razorpay_order_id', $orderId)->firstOrFail();

            // Verify signature
            if (!$this->verifyPayment($orderId, $paymentId, $signature)) {
                $payment->markAsFailed('Signature verification failed');
                return null;
            }

            // Fetch payment details from Razorpay
            $razorpayPayment = $this->api->payment->fetch($paymentId);

            // Mark payment as completed
            $payment->markAsCompleted(
                $paymentId,
                $signature,
                $razorpayPayment->method ?? null
            );

            // Get plan details from metadata
            $metadata = $payment->metadata;
            $plan = SubscriptionPlan::findOrFail($metadata['plan_id']);
            $billingCycle = $metadata['billing_cycle'];

            // Calculate subscription duration
            $duration = $billingCycle === 'yearly' ? 365 : 30;

            // Cancel existing active subscription
            $existingSubscription = $payment->user->activeSubscription();
            if ($existingSubscription) {
                $existingSubscription->cancel();
            }

            // Create new subscription
            $subscription = Subscription::create([
                'user_id' => $payment->user_id,
                'plan_id' => $plan->id,
                'status' => 'active',
                'billing_cycle' => $billingCycle,
                'started_at' => now(),
                'ends_at' => now()->addDays($duration),
                'payment_method' => $razorpayPayment->method ?? 'razorpay',
                'transaction_id' => $paymentId,
                'auto_renew' => false, // Can be updated to true for auto-renewal
            ]);

            // Link payment to subscription
            $payment->update(['subscription_id' => $subscription->id]);

            // Update user's premium status
            $payment->user->update([
                'is_premium' => true,
                'premium_until' => $subscription->ends_at,
            ]);

            Log::info('Subscription activated successfully', [
                'user_id' => $payment->user_id,
                'subscription_id' => $subscription->id,
                'plan' => $plan->name,
            ]);

            return $subscription;
        });
    }

    /**
     * Handle failed payment.
     */
    public function processFailedPayment(string $orderId, string $errorMessage = null): void
    {
        $payment = Payment::where('razorpay_order_id', $orderId)->first();

        if ($payment) {
            $payment->markAsFailed($errorMessage);

            Log::warning('Payment failed', [
                'order_id' => $orderId,
                'user_id' => $payment->user_id,
                'error' => $errorMessage,
            ]);
        }
    }

    /**
     * Get payment history for user.
     */
    public function getUserPaymentHistory(User $user, int $limit = 10)
    {
        return Payment::forUser($user->id)
            ->with(['subscription.plan'])
            ->orderByDesc('created_at')
            ->paginate($limit);
    }

    /**
     * Fetch payment details from Razorpay.
     */
    public function fetchPaymentDetails(string $paymentId)
    {
        try {
            return $this->api->payment->fetch($paymentId);
        } catch (\Exception $e) {
            Log::error('Failed to fetch payment details', [
                'payment_id' => $paymentId,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Process refund for a payment.
     */
    public function processRefund(Payment $payment, float $amount = null): bool
    {
        try {
            if (!$payment->isCompleted()) {
                throw new \Exception('Only completed payments can be refunded');
            }

            $refundAmount = $amount ?? $payment->amount;
            $refundAmountInPaise = $refundAmount * 100;

            $refund = $this->api->payment->fetch($payment->razorpay_payment_id)
                ->refund(['amount' => $refundAmountInPaise]);

            $payment->markAsRefunded();

            // Cancel associated subscription if full refund
            if ($amount === null && $payment->subscription) {
                $payment->subscription->cancel();
                $payment->user->update([
                    'is_premium' => false,
                    'premium_until' => null,
                ]);
            }

            Log::info('Refund processed successfully', [
                'payment_id' => $payment->id,
                'refund_id' => $refund->id,
                'amount' => $refundAmount,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Refund processing failed', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }
}
