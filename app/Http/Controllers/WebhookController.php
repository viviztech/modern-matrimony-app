<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Subscription;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function __construct(
        protected PaymentService $paymentService
    ) {
    }

    /**
     * Handle Razorpay webhook events.
     */
    public function razorpay(Request $request)
    {
        // Verify webhook signature
        if (!$this->verifyWebhookSignature($request)) {
            Log::warning('Invalid Razorpay webhook signature');
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        $event = $request->input('event');
        $payload = $request->input('payload');

        Log::info('Razorpay webhook received', ['event' => $event]);

        try {
            match ($event) {
                'payment.authorized' => $this->handlePaymentAuthorized($payload),
                'payment.captured' => $this->handlePaymentCaptured($payload),
                'payment.failed' => $this->handlePaymentFailed($payload),
                'order.paid' => $this->handleOrderPaid($payload),
                'refund.created' => $this->handleRefundCreated($payload),
                'subscription.cancelled' => $this->handleSubscriptionCancelled($payload),
                default => Log::info('Unhandled webhook event', ['event' => $event])
            };

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            Log::error('Webhook processing failed', [
                'event' => $event,
                'error' => $e->getMessage(),
            ]);

            return response()->json(['error' => 'Processing failed'], 500);
        }
    }

    /**
     * Verify webhook signature.
     */
    protected function verifyWebhookSignature(Request $request): bool
    {
        $webhookSecret = config('services.razorpay.webhook_secret');

        if (!$webhookSecret) {
            // Skip verification in development if webhook secret not configured
            return true;
        }

        $webhookSignature = $request->header('X-Razorpay-Signature');
        $webhookBody = $request->getContent();

        $expectedSignature = hash_hmac('sha256', $webhookBody, $webhookSecret);

        return hash_equals($expectedSignature, $webhookSignature);
    }

    /**
     * Handle payment.authorized event.
     */
    protected function handlePaymentAuthorized(array $payload): void
    {
        $paymentEntity = $payload['payment']['entity'];

        $payment = Payment::where('razorpay_order_id', $paymentEntity['order_id'])->first();

        if ($payment && $payment->isPending()) {
            $payment->update(['status' => 'processing']);

            Log::info('Payment authorized', [
                'payment_id' => $paymentEntity['id'],
                'order_id' => $paymentEntity['order_id'],
            ]);
        }
    }

    /**
     * Handle payment.captured event.
     */
    protected function handlePaymentCaptured(array $payload): void
    {
        $paymentEntity = $payload['payment']['entity'];

        $payment = Payment::where('razorpay_order_id', $paymentEntity['order_id'])->first();

        if ($payment && !$payment->isCompleted()) {
            // Process the payment if not already processed
            $this->paymentService->processSuccessfulPayment(
                $paymentEntity['order_id'],
                $paymentEntity['id'],
                $paymentEntity['notes']['signature'] ?? ''
            );

            Log::info('Payment captured via webhook', [
                'payment_id' => $paymentEntity['id'],
                'order_id' => $paymentEntity['order_id'],
            ]);
        }
    }

    /**
     * Handle payment.failed event.
     */
    protected function handlePaymentFailed(array $payload): void
    {
        $paymentEntity = $payload['payment']['entity'];

        $this->paymentService->processFailedPayment(
            $paymentEntity['order_id'],
            $paymentEntity['error_description'] ?? 'Payment failed'
        );

        Log::warning('Payment failed via webhook', [
            'payment_id' => $paymentEntity['id'],
            'order_id' => $paymentEntity['order_id'],
            'error' => $paymentEntity['error_description'] ?? 'Unknown error',
        ]);
    }

    /**
     * Handle order.paid event.
     */
    protected function handleOrderPaid(array $payload): void
    {
        $orderEntity = $payload['order']['entity'];

        Log::info('Order marked as paid', [
            'order_id' => $orderEntity['id'],
            'amount' => $orderEntity['amount'],
        ]);
    }

    /**
     * Handle refund.created event.
     */
    protected function handleRefundCreated(array $payload): void
    {
        $refundEntity = $payload['refund']['entity'];

        $payment = Payment::where('razorpay_payment_id', $refundEntity['payment_id'])->first();

        if ($payment && !$payment->isRefunded()) {
            $payment->markAsRefunded();

            // Cancel associated subscription
            if ($payment->subscription) {
                $payment->subscription->cancel();
                $payment->user->update([
                    'is_premium' => false,
                    'premium_until' => null,
                ]);
            }

            Log::info('Refund processed via webhook', [
                'refund_id' => $refundEntity['id'],
                'payment_id' => $refundEntity['payment_id'],
                'amount' => $refundEntity['amount'],
            ]);
        }
    }

    /**
     * Handle subscription.cancelled event (for future auto-renewal).
     */
    protected function handleSubscriptionCancelled(array $payload): void
    {
        $subscriptionEntity = $payload['subscription']['entity'];

        // Handle subscription cancellation
        // This is for future implementation when auto-renewal is enabled

        Log::info('Subscription cancelled via webhook', [
            'subscription_id' => $subscriptionEntity['id'],
        ]);
    }
}
