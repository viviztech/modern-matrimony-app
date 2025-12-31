@extends('layouts.app')

@section('content')
<div class="py-8" x-data="checkoutApp()">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('subscriptions.index') }}" class="inline-flex items-center text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white mb-4">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Plans
            </a>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Complete Your Purchase</h1>
            <p class="text-gray-600 dark:text-gray-400">Secure checkout powered by Razorpay</p>
        </div>

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="mb-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl p-4 flex items-start gap-3">
                <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-green-800 dark:text-green-200">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl p-4 flex items-start gap-3">
                <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-red-800 dark:text-red-200">{{ session('error') }}</p>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Order Summary -->
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 mb-6">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Order Summary</h2>

                    <!-- Plan Details -->
                    <div class="flex items-start justify-between pb-6 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-start gap-4">
                            <!-- Plan Icon -->
                            <div class="w-16 h-16 rounded-xl flex items-center justify-center
                                        {{ $plan->slug === 'gold' ? 'bg-gradient-to-br from-yellow-400/20 to-yellow-600/20' : '' }}
                                        {{ $plan->slug === 'platinum' ? 'bg-gradient-to-br from-purple-500/20 to-pink-500/20' : '' }}
                                        {{ $plan->slug === 'elite' ? 'bg-gradient-to-br from-indigo-600/20 to-purple-700/20' : '' }}">
                                @if($plan->slug === 'gold')
                                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                    </svg>
                                @elseif($plan->slug === 'platinum')
                                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11.5l4.5-4.5m0 0l-4.5-4.5M16.5 7H3m18 10.5L12 12m0 0l4.5 4.5M3 17.5h9.5M12 12c0-4.5 4.5-9 9-9M12 21c7.5 0 9-4.5 9-9" />
                                    </svg>
                                @else
                                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                    </svg>
                                @endif
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $plan->name }} Plan</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $plan->description }}</p>
                                <div class="mt-2">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                                {{ $billingCycle === 'yearly' ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300' : 'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300' }}">
                                        {{ ucfirst($billingCycle) }} Billing
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Key Features -->
                    <div class="py-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="font-semibold text-gray-900 dark:text-white mb-4">What you'll get:</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @if($plan->hasFeature('unlimited_swipes'))
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-sm text-gray-700 dark:text-gray-300">Unlimited swipes</span>
                                </div>
                            @endif
                            @if($plan->hasFeature('unlimited_likes'))
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-sm text-gray-700 dark:text-gray-300">Unlimited likes</span>
                                </div>
                            @endif
                            @if($plan->hasFeature('see_who_liked'))
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-sm text-gray-700 dark:text-gray-300">See who liked you</span>
                                </div>
                            @endif
                            @if($plan->hasFeature('advanced_filters'))
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-sm text-gray-700 dark:text-gray-300">Advanced filters</span>
                                </div>
                            @endif
                            @if($plan->hasFeature('read_receipts'))
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-sm text-gray-700 dark:text-gray-300">Read receipts</span>
                                </div>
                            @endif
                            @if($plan->hasFeature('priority_placement'))
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-sm text-gray-700 dark:text-gray-300">Priority placement</span>
                                </div>
                            @endif
                            @if($plan->hasFeature('background_verification'))
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-sm text-gray-700 dark:text-gray-300">Background verification</span>
                                </div>
                            @endif
                            @if($plan->hasFeature('relationship_manager'))
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-sm text-gray-700 dark:text-gray-300">Dedicated relationship manager</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Pricing Breakdown -->
                    <div class="pt-6">
                        <div class="space-y-3">
                            <div class="flex justify-between text-gray-700 dark:text-gray-300">
                                <span>{{ $plan->name }} ({{ ucfirst($billingCycle) }})</span>
                                <span>₹{{ number_format($amount, 2) }}</span>
                            </div>
                            @if($billingCycle === 'yearly' && $plan->yearly_discount_percentage > 0)
                                <div class="flex justify-between text-green-600 dark:text-green-400 text-sm">
                                    <span>Yearly discount ({{ $plan->yearly_discount_percentage }}%)</span>
                                    <span>-₹{{ number_format($plan->yearly_savings, 2) }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between text-gray-600 dark:text-gray-400 text-sm">
                                <span>Tax (GST 18%)</span>
                                <span>₹{{ number_format($amount * 0.18, 2) }}</span>
                            </div>
                            <div class="border-t border-gray-200 dark:border-gray-700 pt-3 flex justify-between text-lg font-bold text-gray-900 dark:text-white">
                                <span>Total</span>
                                <span>₹{{ number_format($amount * 1.18, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Button -->
                <button
                    @click="initiatePayment()"
                    :disabled="processing"
                    class="w-full bg-gradient-to-r from-primary to-secondary text-white py-4 rounded-xl font-semibold hover:shadow-glow transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
                >
                    <span x-show="!processing">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        Proceed to Payment
                    </span>
                    <span x-show="processing" class="flex items-center gap-2">
                        <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Processing...
                    </span>
                </button>

                <!-- Trust Badges -->
                <div class="mt-6 flex items-center justify-center gap-6 text-sm text-gray-600 dark:text-gray-400">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        <span>Secured by Razorpay</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        <span>SSL Encrypted</span>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <!-- Money Back Guarantee -->
                <div class="bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 border border-green-200 dark:border-green-800 rounded-2xl p-6 mb-6">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 dark:text-white mb-1">7-Day Money Back</h3>
                            <p class="text-sm text-gray-700 dark:text-gray-300">Not satisfied? Get a full refund within 7 days, no questions asked.</p>
                        </div>
                    </div>
                </div>

                <!-- What Happens Next -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
                    <h3 class="font-bold text-gray-900 dark:text-white mb-4">What happens next?</h3>
                    <div class="space-y-4">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 bg-primary/10 text-primary rounded-full flex items-center justify-center flex-shrink-0 font-bold text-sm">1</div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">Secure Payment</p>
                                <p class="text-xs text-gray-600 dark:text-gray-400">Complete payment via Razorpay</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 bg-primary/10 text-primary rounded-full flex items-center justify-center flex-shrink-0 font-bold text-sm">2</div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">Instant Activation</p>
                                <p class="text-xs text-gray-600 dark:text-gray-400">Your plan activates immediately</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 bg-primary/10 text-primary rounded-full flex items-center justify-center flex-shrink-0 font-bold text-sm">3</div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">Start Connecting</p>
                                <p class="text-xs text-gray-600 dark:text-gray-400">Enjoy all premium features</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Support -->
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Need help?</p>
                    <a href="mailto:support@matrimony.com" class="text-primary hover:text-secondary font-semibold text-sm">Contact Support</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Razorpay Checkout Script -->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script>
function checkoutApp() {
    return {
        processing: false,
        razorpayKey: '{{ config("services.razorpay.key") }}',
        planSlug: '{{ $plan->slug }}',
        billingCycle: '{{ $billingCycle }}',
        amount: {{ $amount * 1.18 * 100 }}, // Convert to paise and include tax

        init() {
            console.log('Checkout initialized for', this.planSlug, 'plan');
        },

        async initiatePayment() {
            this.processing = true;

            try {
                // Create order on backend
                const response = await fetch('{{ route("subscriptions.create-order") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        plan_slug: this.planSlug,
                        billing_cycle: this.billingCycle
                    })
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message || 'Failed to create order');
                }

                // Open Razorpay checkout
                this.openRazorpayCheckout(data);

            } catch (error) {
                console.error('Payment initiation error:', error);
                alert(error.message || 'Failed to initiate payment. Please try again.');
                this.processing = false;
            }
        },

        openRazorpayCheckout(orderData) {
            const options = {
                key: this.razorpayKey,
                amount: this.amount,
                currency: 'INR',
                name: '{{ config("app.name") }}',
                description: '{{ $plan->name }} Plan - {{ ucfirst($billingCycle) }}',
                order_id: orderData.order_id,
                prefill: {
                    name: '{{ auth()->user()->name }}',
                    email: '{{ auth()->user()->email }}',
                    contact: '{{ auth()->user()->phone ?? "" }}'
                },
                theme: {
                    color: '#F97316'
                },
                modal: {
                    ondismiss: () => {
                        this.processing = false;
                    }
                },
                handler: (response) => {
                    this.verifyPayment(response);
                }
            };

            const razorpay = new Razorpay(options);
            razorpay.on('payment.failed', (response) => {
                this.handlePaymentFailure(response);
            });

            razorpay.open();
        },

        async verifyPayment(response) {
            try {
                const verifyResponse = await fetch('{{ route("subscriptions.verify-payment") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        razorpay_order_id: response.razorpay_order_id,
                        razorpay_payment_id: response.razorpay_payment_id,
                        razorpay_signature: response.razorpay_signature,
                        plan_slug: this.planSlug,
                        billing_cycle: this.billingCycle
                    })
                });

                const data = await verifyResponse.json();

                if (verifyResponse.ok && data.success) {
                    window.location.href = data.redirect_url;
                } else {
                    throw new Error(data.message || 'Payment verification failed');
                }

            } catch (error) {
                console.error('Payment verification error:', error);
                alert(error.message || 'Payment verification failed. Please contact support.');
                this.processing = false;
            }
        },

        handlePaymentFailure(response) {
            console.error('Payment failed:', response);

            let errorMessage = 'Payment failed. ';
            if (response.error) {
                errorMessage += response.error.description || response.error.reason || 'Please try again.';
            }

            alert(errorMessage);
            this.processing = false;
        }
    }
}
</script>
@endsection
