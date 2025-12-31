@extends('layouts.app')

@section('content')
<div class="py-8" x-data="subscriptionPlans()">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">Choose Your Perfect Plan</h1>
            <p class="text-lg text-gray-600 dark:text-gray-400 mb-8">Find your soulmate with the right subscription</p>

            <!-- Billing Cycle Toggle -->
            <div class="inline-flex items-center bg-white dark:bg-gray-800 rounded-full p-1 shadow-md">
                <button
                    @click="billingCycle = 'monthly'"
                    :class="billingCycle === 'monthly' ? 'bg-gradient-to-r from-primary to-secondary text-white shadow-md' : 'text-gray-600 dark:text-gray-400'"
                    class="px-6 py-2.5 rounded-full font-semibold transition-all duration-200"
                >
                    Monthly
                </button>
                <button
                    @click="billingCycle = 'yearly'"
                    :class="billingCycle === 'yearly' ? 'bg-gradient-to-r from-primary to-secondary text-white shadow-md' : 'text-gray-600 dark:text-gray-400'"
                    class="px-6 py-2.5 rounded-full font-semibold transition-all duration-200 relative"
                >
                    Yearly
                    <span class="absolute -top-2 -right-2 bg-green-500 text-white text-xs px-2 py-0.5 rounded-full">Save 17%</span>
                </button>
            </div>
        </div>

        <!-- Current Plan Status -->
        @if(auth()->user()->activeSubscription)
            <div class="mb-8 bg-gradient-to-r from-blue-50 to-purple-50 dark:from-blue-900/20 dark:to-purple-900/20 border border-blue-200 dark:border-blue-800 rounded-2xl p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-primary to-secondary rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Current Plan</p>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ auth()->user()->activeSubscription->plan->name }}</h3>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-600 dark:text-gray-400">Valid until</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ auth()->user()->activeSubscription->ends_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Plans Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
            @foreach($plans as $plan)
                <div
                    class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden group {{ $plan->slug === 'platinum' ? 'ring-2 ring-primary transform scale-105' : '' }}"
                    :class="{ 'opacity-75': currentPlan === '{{ $plan->slug }}' }"
                >
                    <!-- Gradient Header -->
                    <div class="h-2 {{ $plan->slug === 'free' ? 'bg-gradient-to-r from-gray-400 to-gray-500' : '' }}
                                      {{ $plan->slug === 'gold' ? 'bg-gradient-to-r from-yellow-400 to-yellow-600' : '' }}
                                      {{ $plan->slug === 'platinum' ? 'bg-gradient-to-r from-purple-500 to-pink-500' : '' }}
                                      {{ $plan->slug === 'elite' ? 'bg-gradient-to-r from-indigo-600 to-purple-700' : '' }}">
                    </div>

                    <!-- Popular Badge -->
                    @if($plan->slug === 'platinum')
                        <div class="absolute top-6 -right-10 bg-gradient-to-r from-primary to-secondary text-white text-xs font-bold px-10 py-1 rotate-45 shadow-md">
                            POPULAR
                        </div>
                    @endif

                    <!-- Plan Content -->
                    <div class="p-6">
                        <!-- Plan Icon -->
                        <div class="w-16 h-16 mb-4 rounded-xl flex items-center justify-center
                                    {{ $plan->slug === 'free' ? 'bg-gradient-to-br from-gray-400/20 to-gray-500/20' : '' }}
                                    {{ $plan->slug === 'gold' ? 'bg-gradient-to-br from-yellow-400/20 to-yellow-600/20' : '' }}
                                    {{ $plan->slug === 'platinum' ? 'bg-gradient-to-br from-purple-500/20 to-pink-500/20' : '' }}
                                    {{ $plan->slug === 'elite' ? 'bg-gradient-to-br from-indigo-600/20 to-purple-700/20' : '' }}">
                            @if($plan->slug === 'free')
                                <svg class="w-8 h-8 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            @elseif($plan->slug === 'gold')
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

                        <!-- Plan Name -->
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">{{ $plan->name }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">{{ $plan->description }}</p>

                        <!-- Price -->
                        <div class="mb-6">
                            <div class="flex items-baseline gap-2">
                                <span class="text-4xl font-bold text-gray-900 dark:text-white">
                                    <span x-show="billingCycle === 'monthly'">₹{{ number_format($plan->price_monthly, 0) }}</span>
                                    <span x-show="billingCycle === 'yearly'">₹{{ number_format($plan->price_yearly / 12, 0) }}</span>
                                </span>
                                <span class="text-gray-600 dark:text-gray-400">/month</span>
                            </div>
                            @if($plan->price_yearly > 0)
                                <p class="text-sm text-gray-500 dark:text-gray-500 mt-1" x-show="billingCycle === 'yearly'">
                                    Billed ₹{{ number_format($plan->price_yearly, 0) }} yearly
                                </p>
                            @endif
                        </div>

                        <!-- CTA Button -->
                        @if(auth()->user()->activeSubscription && auth()->user()->activeSubscription->plan_id === $plan->id)
                            <button disabled class="w-full px-6 py-3 rounded-xl bg-gray-200 dark:bg-gray-700 text-gray-500 dark:text-gray-400 font-semibold mb-6 cursor-not-allowed">
                                Current Plan
                            </button>
                        @elseif($plan->isFree())
                            <button disabled class="w-full px-6 py-3 rounded-xl bg-gray-200 dark:bg-gray-700 text-gray-500 dark:text-gray-400 font-semibold mb-6 cursor-not-allowed">
                                Always Free
                            </button>
                        @else
                            <a
                                href="{{ route('subscriptions.checkout', ['plan' => $plan->slug]) }}?billing_cycle={{ $plan->price_yearly > 0 ? 'yearly' : 'monthly' }}"
                                @click.prevent="handleUpgrade('{{ $plan->slug }}')"
                                class="block w-full text-center px-6 py-3 rounded-xl font-semibold mb-6 transition-all duration-200
                                       {{ $plan->slug === 'platinum' ? 'bg-gradient-to-r from-primary to-secondary text-white hover:shadow-glow' : 'bg-gray-900 dark:bg-white text-white dark:text-gray-900 hover:bg-gray-800 dark:hover:bg-gray-100' }}"
                            >
                                {{ auth()->user()->activeSubscription ? 'Upgrade Now' : 'Get Started' }}
                            </a>
                        @endif

                        <!-- Features List -->
                        <ul class="space-y-3">
                            @if($plan->isFree())
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">{{ $plan->getFeature('swipes_per_day', 0) }} swipes per day</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">{{ $plan->getFeature('likes_per_day', 0) }} likes per day</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">{{ $plan->getFeature('video_calls_per_week', 0) }} video calls/week</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Basic filters</span>
                                </li>
                            @elseif($plan->isGold())
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Unlimited swipes & likes</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">See who liked you</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Advanced filters</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Read receipts</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Rewind last swipe</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">5 Super Likes/week</span>
                                </li>
                            @elseif($plan->isPlatinum())
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Everything in Gold</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Priority placement (2x visibility)</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Incognito mode</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Background verification</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">1 Profile boost/month</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Video call recording</span>
                                </li>
                            @else
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Everything in Platinum</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Dedicated relationship manager</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Monthly coaching session</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Access to private events</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">2 Professional photo shoots/year</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Priority support 24/7</span>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- FAQ Section -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 text-center">Frequently Asked Questions</h2>
            <div class="space-y-4 max-w-3xl mx-auto">
                <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                    <h3 class="font-semibold text-gray-900 dark:text-white mb-2">Can I cancel anytime?</h3>
                    <p class="text-gray-600 dark:text-gray-400">Yes, you can cancel your subscription at any time. You'll continue to have access until the end of your billing period.</p>
                </div>
                <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                    <h3 class="font-semibold text-gray-900 dark:text-white mb-2">Can I upgrade or downgrade my plan?</h3>
                    <p class="text-gray-600 dark:text-gray-400">Absolutely! You can upgrade or downgrade your plan at any time, and changes will be reflected in your next billing cycle.</p>
                </div>
                <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                    <h3 class="font-semibold text-gray-900 dark:text-white mb-2">Is my payment information secure?</h3>
                    <p class="text-gray-600 dark:text-gray-400">Yes, we use Razorpay for secure payment processing. We never store your payment information on our servers.</p>
                </div>
                <div class="pb-4">
                    <h3 class="font-semibold text-gray-900 dark:text-white mb-2">Do you offer refunds?</h3>
                    <p class="text-gray-600 dark:text-gray-400">We offer a 7-day money-back guarantee if you're not satisfied with your subscription.</p>
                </div>
            </div>
        </div>

        <!-- Trust Badges -->
        <div class="flex items-center justify-center gap-8 text-sm text-gray-600 dark:text-gray-400">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                <span>Secure Payment</span>
            </div>
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
                <span>SSL Encrypted</span>
            </div>
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>7-Day Guarantee</span>
            </div>
        </div>
    </div>
</div>

<script>
function subscriptionPlans() {
    return {
        billingCycle: 'yearly',
        currentPlan: '{{ auth()->user()->activeSubscription?->plan->slug ?? "free" }}',

        init() {
            console.log('Subscription plans initialized');
        },

        handleUpgrade(planSlug) {
            const url = `/subscriptions/checkout/${planSlug}?billing_cycle=${this.billingCycle}`;
            window.location.href = url;
        }
    }
}
</script>
@endsection
