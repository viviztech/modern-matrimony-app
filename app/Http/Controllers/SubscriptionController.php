<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPlan;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    public function __construct(
        protected PaymentService $paymentService
    ) {
    }

    /**
     * Display subscription plans.
     */
    public function index()
    {
        $plans = SubscriptionPlan::active()->ordered()->get();
        $currentPlan = Auth::user()->activeSubscription()?->plan;

        return view('subscriptions.index', compact('plans', 'currentPlan'));
    }

    /**
     * Show subscription checkout page.
     */
    public function checkout(Request $request, SubscriptionPlan $plan)
    {
        $billingCycle = $request->get('billing_cycle', 'monthly');

        if (!in_array($billingCycle, ['monthly', 'yearly'])) {
            return redirect()->back()->with('error', 'Invalid billing cycle');
        }

        $amount = $billingCycle === 'yearly' ? $plan->price_yearly : $plan->price_monthly;

        return view('subscriptions.checkout', compact('plan', 'billingCycle', 'amount'));
    }

    /**
     * Create payment order.
     */
    public function createOrder(Request $request, SubscriptionPlan $plan)
    {
        $request->validate([
            'billing_cycle' => 'required|in:monthly,yearly',
        ]);

        try {
            $payment = $this->paymentService->createOrder(
                Auth::user(),
                $plan,
                $request->billing_cycle
            );

            return response()->json([
                'success' => true,
                'order_id' => $payment->razorpay_order_id,
                'amount' => $payment->amount * 100, // In paise
                'currency' => $payment->currency,
                'payment_id' => $payment->id,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create order. Please try again.',
            ], 500);
        }
    }

    /**
     * Verify payment and activate subscription.
     */
    public function verifyPayment(Request $request)
    {
        $request->validate([
            'razorpay_order_id' => 'required|string',
            'razorpay_payment_id' => 'required|string',
            'razorpay_signature' => 'required|string',
        ]);

        try {
            $subscription = $this->paymentService->processSuccessfulPayment(
                $request->razorpay_order_id,
                $request->razorpay_payment_id,
                $request->razorpay_signature
            );

            if ($subscription) {
                return response()->json([
                    'success' => true,
                    'message' => 'Payment successful! Your subscription has been activated.',
                    'redirect' => route('subscriptions.success', $subscription),
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Payment verification failed. Please contact support.',
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing your payment.',
            ], 500);
        }
    }

    /**
     * Handle payment failure.
     */
    public function paymentFailed(Request $request)
    {
        $request->validate([
            'razorpay_order_id' => 'required|string',
            'error' => 'nullable|string',
        ]);

        $this->paymentService->processFailedPayment(
            $request->razorpay_order_id,
            $request->error
        );

        return redirect()->route('subscriptions.index')
            ->with('error', 'Payment failed. Please try again.');
    }

    /**
     * Show subscription success page.
     */
    public function success($subscriptionId)
    {
        $subscription = Auth::user()->subscriptions()
            ->with('plan')
            ->findOrFail($subscriptionId);

        return view('subscriptions.success', compact('subscription'));
    }

    /**
     * Show user's current subscription.
     */
    public function show()
    {
        $subscription = Auth::user()->activeSubscription();
        $paymentHistory = $this->paymentService->getUserPaymentHistory(Auth::user());

        return view('subscriptions.show', compact('subscription', 'paymentHistory'));
    }

    /**
     * Cancel user's subscription.
     */
    public function cancel(Request $request)
    {
        $subscription = Auth::user()->activeSubscription();

        if (!$subscription) {
            return redirect()->back()->with('error', 'No active subscription found.');
        }

        $subscription->cancel();

        return redirect()->route('subscriptions.show')
            ->with('success', 'Your subscription has been cancelled. You can continue using premium features until ' . $subscription->ends_at->format('M d, Y'));
    }

    /**
     * Resume a paused subscription.
     */
    public function resume()
    {
        $subscription = Auth::user()->subscriptions()
            ->where('status', 'paused')
            ->latest()
            ->first();

        if (!$subscription) {
            return redirect()->back()->with('error', 'No paused subscription found.');
        }

        $subscription->resume();

        return redirect()->route('subscriptions.show')
            ->with('success', 'Your subscription has been resumed.');
    }
}
