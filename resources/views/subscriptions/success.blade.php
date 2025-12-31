@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Success Animation -->
        <div class="text-center mb-8">
            <div class="w-24 h-24 bg-gradient-to-br from-green-400 to-emerald-500 rounded-full mx-auto mb-6 flex items-center justify-center animate-bounce">
                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-3">Payment Successful!</h1>
            <p class="text-lg text-gray-600 dark:text-gray-400">Welcome to {{ $subscription->plan->name }} Plan</p>
        </div>

        <!-- Subscription Details Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden mb-6">
            <!-- Header with Gradient -->
            <div class="h-2 {{ $subscription->plan->slug === 'gold' ? 'bg-gradient-to-r from-yellow-400 to-yellow-600' : '' }}
                              {{ $subscription->plan->slug === 'platinum' ? 'bg-gradient-to-r from-purple-500 to-pink-500' : '' }}
                              {{ $subscription->plan->slug === 'elite' ? 'bg-gradient-to-r from-indigo-600 to-purple-700' : '' }}">
            </div>

            <div class="p-8">
                <!-- Plan Icon and Name -->
                <div class="flex items-center gap-4 mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="w-16 h-16 rounded-xl flex items-center justify-center
                                {{ $subscription->plan->slug === 'gold' ? 'bg-gradient-to-br from-yellow-400/20 to-yellow-600/20' : '' }}
                                {{ $subscription->plan->slug === 'platinum' ? 'bg-gradient-to-br from-purple-500/20 to-pink-500/20' : '' }}
                                {{ $subscription->plan->slug === 'elite' ? 'bg-gradient-to-br from-indigo-600/20 to-purple-700/20' : '' }}">
                        @if($subscription->plan->slug === 'gold')
                            <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                            </svg>
                        @elseif($subscription->plan->slug === 'platinum')
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
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $subscription->plan->name }} Plan</h2>
                        <p class="text-gray-600 dark:text-gray-400">{{ ucfirst($subscription->billing_cycle) }} subscription</p>
                    </div>
                </div>

                <!-- Subscription Info Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Transaction ID</p>
                        <p class="font-mono text-sm font-semibold text-gray-900 dark:text-white">{{ $subscription->transaction_id }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Started On</p>
                        <p class="font-semibold text-gray-900 dark:text-white">{{ $subscription->started_at->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Next Billing Date</p>
                        <p class="font-semibold text-gray-900 dark:text-white">{{ $subscription->ends_at->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Auto Renewal</p>
                        <p class="font-semibold text-gray-900 dark:text-white">
                            @if($subscription->auto_renew)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300">
                                    Enabled
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300">
                                    Disabled
                                </span>
                            @endif
                        </p>
                    </div>
                </div>

                <!-- Features Unlocked -->
                <div class="bg-gradient-to-br from-blue-50 to-purple-50 dark:from-blue-900/20 dark:to-purple-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-6">
                    <h3 class="font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        Features Unlocked
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @if($subscription->plan->hasFeature('unlimited_swipes'))
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="text-sm text-gray-700 dark:text-gray-300">Unlimited swipes</span>
                            </div>
                        @endif
                        @if($subscription->plan->hasFeature('unlimited_likes'))
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="text-sm text-gray-700 dark:text-gray-300">Unlimited likes</span>
                            </div>
                        @endif
                        @if($subscription->plan->hasFeature('see_who_liked'))
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="text-sm text-gray-700 dark:text-gray-300">See who liked you</span>
                            </div>
                        @endif
                        @if($subscription->plan->hasFeature('advanced_filters'))
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="text-sm text-gray-700 dark:text-gray-300">Advanced filters</span>
                            </div>
                        @endif
                        @if($subscription->plan->hasFeature('read_receipts'))
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="text-sm text-gray-700 dark:text-gray-300">Read receipts</span>
                            </div>
                        @endif
                        @if($subscription->plan->hasFeature('rewind_feature'))
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="text-sm text-gray-700 dark:text-gray-300">Rewind feature</span>
                            </div>
                        @endif
                        @if($subscription->plan->hasFeature('priority_placement'))
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="text-sm text-gray-700 dark:text-gray-300">Priority placement</span>
                            </div>
                        @endif
                        @if($subscription->plan->hasFeature('incognito_mode'))
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="text-sm text-gray-700 dark:text-gray-300">Incognito mode</span>
                            </div>
                        @endif
                        @if($subscription->plan->hasFeature('background_verification'))
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="text-sm text-gray-700 dark:text-gray-300">Background verification</span>
                            </div>
                        @endif
                        @if($subscription->plan->hasFeature('video_call_recording'))
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="text-sm text-gray-700 dark:text-gray-300">Video call recording</span>
                            </div>
                        @endif
                        @if($subscription->plan->hasFeature('relationship_manager'))
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="text-sm text-gray-700 dark:text-gray-300">Relationship manager</span>
                            </div>
                        @endif
                        @if($subscription->plan->hasFeature('coaching_session'))
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="text-sm text-gray-700 dark:text-gray-300">Monthly coaching</span>
                            </div>
                        @endif
                        @if($subscription->plan->hasFeature('private_events'))
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="text-sm text-gray-700 dark:text-gray-300">Private events access</span>
                            </div>
                        @endif
                        @if($subscription->plan->getFeature('profile_boost_per_month', 0) > 0)
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="text-sm text-gray-700 dark:text-gray-300">{{ $subscription->plan->getFeature('profile_boost_per_month') }} Profile boost/month</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 mb-8">
            <a
                href="{{ route('discover.index') }}"
                class="flex-1 bg-gradient-to-r from-primary to-secondary text-white text-center px-8 py-4 rounded-xl font-semibold hover:shadow-glow transition-all duration-200"
            >
                Start Discovering Matches
            </a>
            <a
                href="{{ route('subscriptions.show') }}"
                class="flex-1 bg-white dark:bg-gray-800 border-2 border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-center px-8 py-4 rounded-xl font-semibold hover:border-primary dark:hover:border-primary transition-all duration-200"
            >
                View Subscription Details
            </a>
        </div>

        <!-- Email Confirmation Notice -->
        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-6 mb-6">
            <div class="flex items-start gap-3">
                <svg class="w-6 h-6 text-blue-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                <div>
                    <h3 class="font-semibold text-gray-900 dark:text-white mb-1">Receipt Sent</h3>
                    <p class="text-sm text-gray-700 dark:text-gray-300">A confirmation email with your receipt has been sent to <span class="font-semibold">{{ auth()->user()->email }}</span></p>
                </div>
            </div>
        </div>

        <!-- Support -->
        <div class="text-center text-sm text-gray-600 dark:text-gray-400">
            <p class="mb-2">Questions about your subscription?</p>
            <div class="flex items-center justify-center gap-6">
                <a href="{{ route('subscriptions.show') }}" class="text-primary hover:text-secondary font-semibold">View Details</a>
                <span class="text-gray-400">•</span>
                <a href="mailto:support@matrimony.com" class="text-primary hover:text-secondary font-semibold">Contact Support</a>
            </div>
        </div>
    </div>
</div>
@endsection
