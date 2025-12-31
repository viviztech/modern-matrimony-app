@extends('layouts.app')

@section('content')
<div class="py-8" x-data="subscriptionManagement()">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">My Subscription</h1>
            <p class="text-gray-600 dark:text-gray-400">Manage your plan and billing</p>
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

        <!-- Current Plan Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden mb-6">
            <!-- Header Gradient -->
            <div class="h-2 {{ $subscription->plan->slug === 'free' ? 'bg-gradient-to-r from-gray-400 to-gray-500' : '' }}
                              {{ $subscription->plan->slug === 'gold' ? 'bg-gradient-to-r from-yellow-400 to-yellow-600' : '' }}
                              {{ $subscription->plan->slug === 'platinum' ? 'bg-gradient-to-r from-purple-500 to-pink-500' : '' }}
                              {{ $subscription->plan->slug === 'elite' ? 'bg-gradient-to-r from-indigo-600 to-purple-700' : '' }}">
            </div>

            <div class="p-8">
                <!-- Plan Info -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-xl flex items-center justify-center
                                    {{ $subscription->plan->slug === 'free' ? 'bg-gradient-to-br from-gray-400/20 to-gray-500/20' : '' }}
                                    {{ $subscription->plan->slug === 'gold' ? 'bg-gradient-to-br from-yellow-400/20 to-yellow-600/20' : '' }}
                                    {{ $subscription->plan->slug === 'platinum' ? 'bg-gradient-to-br from-purple-500/20 to-pink-500/20' : '' }}
                                    {{ $subscription->plan->slug === 'elite' ? 'bg-gradient-to-br from-indigo-600/20 to-purple-700/20' : '' }}">
                            @if($subscription->plan->slug === 'free')
                                <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            @elseif($subscription->plan->slug === 'gold')
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
                            <p class="text-gray-600 dark:text-gray-400">
                                @if($subscription->plan->isFree())
                                    Always free
                                @else
                                    {{ ucfirst($subscription->billing_cycle) }} billing
                                @endif
                            </p>
                        </div>
                    </div>

                    <!-- Status Badge -->
                    <div>
                        @if($subscription->isActive())
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Active
                            </span>
                        @elseif($subscription->isCancelled())
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Cancelled
                            </span>
                        @elseif($subscription->isExpired())
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Expired
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Subscription Details Grid -->
                @if(!$subscription->plan->isFree())
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-6">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Started On</p>
                            <p class="font-semibold text-gray-900 dark:text-white">{{ $subscription->started_at->format('M d, Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Next Billing</p>
                            <p class="font-semibold text-gray-900 dark:text-white">{{ $subscription->ends_at->format('M d, Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Days Remaining</p>
                            <p class="font-semibold text-gray-900 dark:text-white">{{ $subscription->daysRemaining() }} days</p>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    @php
                        $totalDays = $subscription->started_at->diffInDays($subscription->ends_at);
                        $remainingDays = $subscription->daysRemaining();
                        $progress = $totalDays > 0 ? (($totalDays - $remainingDays) / $totalDays) * 100 : 0;
                    @endphp
                    <div class="mb-6">
                        <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400 mb-2">
                            <span>Billing Period Progress</span>
                            <span>{{ number_format($progress, 0) }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                            <div class="bg-gradient-to-r from-primary to-secondary h-2 rounded-full transition-all duration-500" style="width: {{ $progress }}%"></div>
                        </div>
                    </div>
                @endif

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-3">
                    @if(!$subscription->plan->isFree())
                        <a
                            href="{{ route('subscriptions.index') }}"
                            class="flex-1 text-center px-6 py-3 rounded-xl bg-gradient-to-r from-primary to-secondary text-white font-semibold hover:shadow-glow transition-all duration-200"
                        >
                            Upgrade Plan
                        </a>

                        @if($subscription->isActive() && !$subscription->isCancelled())
                            <button
                                @click="showCancelModal = true"
                                class="flex-1 px-6 py-3 rounded-xl border-2 border-red-300 dark:border-red-700 text-red-600 dark:text-red-400 font-semibold hover:bg-red-50 dark:hover:bg-red-900/20 transition-all duration-200"
                            >
                                Cancel Subscription
                            </button>
                        @elseif($subscription->isCancelled())
                            <form action="{{ route('subscriptions.resume') }}" method="POST" class="flex-1">
                                @csrf
                                <button
                                    type="submit"
                                    class="w-full px-6 py-3 rounded-xl border-2 border-green-300 dark:border-green-700 text-green-600 dark:text-green-400 font-semibold hover:bg-green-50 dark:hover:bg-green-900/20 transition-all duration-200"
                                >
                                    Resume Subscription
                                </button>
                            </form>
                        @endif
                    @else
                        <a
                            href="{{ route('subscriptions.index') }}"
                            class="flex-1 text-center px-6 py-3 rounded-xl bg-gradient-to-r from-primary to-secondary text-white font-semibold hover:shadow-glow transition-all duration-200"
                        >
                            Upgrade to Premium
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Usage Statistics -->
        @if(!$subscription->plan->isFree())
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 mb-6">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Current Month Usage</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Swipes -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Swipes</span>
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                            </svg>
                        </div>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">
                            @if($subscription->plan->hasFeature('unlimited_swipes'))
                                Unlimited
                            @else
                                {{ $usage['swipes'] ?? 0 }} / {{ $subscription->plan->getFeature('swipes_per_day', 0) * 30 }}
                            @endif
                        </p>
                    </div>

                    <!-- Likes -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Likes</span>
                            <svg class="w-5 h-5 text-primary" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">
                            @if($subscription->plan->hasFeature('unlimited_likes'))
                                Unlimited
                            @else
                                {{ $usage['likes'] ?? 0 }} / {{ $subscription->plan->getFeature('likes_per_day', 0) * 30 }}
                            @endif
                        </p>
                    </div>

                    <!-- Video Calls -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Video Calls</span>
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">
                            @if($subscription->plan->hasFeature('unlimited_video_calls'))
                                Unlimited
                            @else
                                {{ $usage['video_calls'] ?? 0 }} / {{ $subscription->plan->getFeature('video_calls_per_week', 0) * 4 }}
                            @endif
                        </p>
                    </div>

                    <!-- Profile Boosts -->
                    @if($subscription->plan->getFeature('profile_boost_per_month', 0) > 0)
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Profile Boosts</span>
                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">
                                {{ $usage['boosts'] ?? 0 }} / {{ $subscription->plan->getFeature('profile_boost_per_month', 0) }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Payment History -->
        @if($payments->count() > 0)
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Payment History</h2>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200 dark:border-gray-700">
                                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600 dark:text-gray-400">Date</th>
                                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600 dark:text-gray-400">Plan</th>
                                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600 dark:text-gray-400">Amount</th>
                                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600 dark:text-gray-400">Transaction ID</th>
                                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600 dark:text-gray-400">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payments as $payment)
                                <tr class="border-b border-gray-100 dark:border-gray-700/50 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                    <td class="py-4 px-4 text-sm text-gray-900 dark:text-white">
                                        {{ $payment->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="py-4 px-4 text-sm text-gray-900 dark:text-white">
                                        {{ $payment->plan->name }}
                                    </td>
                                    <td class="py-4 px-4 text-sm font-semibold text-gray-900 dark:text-white">
                                        ₹{{ number_format($payment->billing_cycle === 'yearly' ? $payment->plan->price_yearly : $payment->plan->price_monthly, 2) }}
                                    </td>
                                    <td class="py-4 px-4 text-sm font-mono text-gray-600 dark:text-gray-400">
                                        {{ Str::limit($payment->transaction_id, 20) }}
                                    </td>
                                    <td class="py-4 px-4 text-sm">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300">
                                            Paid
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($payments->hasPages())
                    <div class="mt-6">
                        {{ $payments->links() }}
                    </div>
                @endif
            </div>
        @endif
    </div>

    <!-- Cancel Subscription Modal -->
    <div
        x-show="showCancelModal"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50"
        @click.self="showCancelModal = false"
        style="display: none;"
    >
        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-8 max-w-md w-full"
            x-transition:enter="transition ease-out duration-300 delay-100"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
        >
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-red-100 dark:bg-red-900/30 rounded-full mx-auto mb-4 flex items-center justify-center">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Cancel Subscription?</h3>
                <p class="text-gray-600 dark:text-gray-400">Are you sure you want to cancel your {{ $subscription->plan->name }} plan?</p>
            </div>

            <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-xl p-4 mb-6">
                <p class="text-sm text-yellow-800 dark:text-yellow-300">
                    You'll continue to have access until {{ $subscription->ends_at->format('M d, Y') }}. After that, you'll be downgraded to the Free plan.
                </p>
            </div>

            <form action="{{ route('subscriptions.cancel') }}" method="POST">
                @csrf
                <div class="flex gap-3">
                    <button
                        type="button"
                        @click="showCancelModal = false"
                        class="flex-1 px-6 py-3 rounded-xl border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-semibold hover:border-gray-400 dark:hover:border-gray-500 transition-all duration-200"
                    >
                        Keep Subscription
                    </button>
                    <button
                        type="submit"
                        class="flex-1 px-6 py-3 rounded-xl bg-red-600 hover:bg-red-700 text-white font-semibold transition-all duration-200"
                    >
                        Yes, Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function subscriptionManagement() {
    return {
        showCancelModal: false,

        init() {
            console.log('Subscription management initialized');
        }
    }
}
</script>
@endsection
