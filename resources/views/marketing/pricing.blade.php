@extends('layouts.public')

@section('title', 'Pricing - Aadhi Matrimony')
@section('meta_description', 'Simple, transparent pricing for Aadhi Matrimony. Choose the plan that works best for you and start finding your perfect match today.')

@section('content')
<!-- Hero Section -->
<section class="py-20 bg-gradient-to-br from-taxi-cream via-white to-taxi-cream-dark">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 mb-6">
            Simple, Transparent
            <span class="bg-gradient-to-r from-taxi-yellow to-taxi-yellow-dark bg-clip-text text-transparent">Pricing</span>
        </h1>
        <p class="text-xl text-gray-600 max-w-3xl mx-auto mb-10">
            Choose the plan that fits your needs. No hidden fees, no surprises. Upgrade or downgrade anytime.
        </p>
        
        <!-- Billing Toggle -->
        <div class="inline-flex items-center bg-gray-100 rounded-full p-1 mb-8">
            <button class="px-6 py-2 rounded-full bg-taxi-yellow text-gray-900 font-medium transition-all">Monthly</button>
            <button class="px-6 py-2 rounded-full text-gray-600 font-medium transition-all hover:text-gray-900">
                Yearly <span class="text-xs text-green-600 ml-1">Save 20%</span>
            </button>
        </div>
    </div>
</section>

<!-- Pricing Cards -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid md:grid-cols-3 gap-8 max-w-6xl mx-auto">
            <!-- Free Plan -->
            <div class="bg-white rounded-3xl shadow-lg border-2 border-taxi-border hover:border-taxi-yellow/50 transition-all p-8">
                <div class="text-center mb-6">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Free</h3>
                    <div class="flex items-baseline justify-center">
                        <span class="text-5xl font-bold text-gray-900">₹0</span>
                        <span class="text-gray-500 ml-2">/forever</span>
                    </div>
                    <p class="text-gray-500 mt-4">Perfect for getting started</p>
                </div>
                
                <ul class="space-y-4 mb-8">
                    <li class="flex items-center text-gray-700">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Create your profile
                    </li>
                    <li class="flex items-center text-gray-700">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Add video introduction
                    </li>
                    <li class="flex items-center text-gray-700">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Browse all profiles
                    </li>
                    <li class="flex items-center text-gray-700">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        10 likes per day
                    </li>
                    <li class="flex items-center text-gray-700">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Basic matching
                    </li>
                </ul>
                
                <a href="{{ route('register') }}" class="block text-center bg-gray-100 text-gray-900 py-4 rounded-xl font-semibold hover:bg-gray-200 transition-all">
                    Get Started Free
                </a>
            </div>

            <!-- Premium Plan -->
            <div class="bg-white rounded-3xl shadow-xl border-2 border-taxi-yellow md:transform md:scale-105 md:relative z-10">
                <div class="absolute -top-4 left-1/2 -translate-x-1/2 bg-gradient-to-r from-taxi-yellow to-taxi-yellow-dark text-white text-sm font-semibold px-4 py-1 rounded-full">
                    MOST POPULAR
                </div>
                <div class="text-center mb-6 pt-4">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Premium</h3>
                    <div class="flex items-baseline justify-center">
                        <span class="text-5xl font-bold text-taxi-yellow-dark">₹799</span>
                        <span class="text-gray-500 ml-2">/month</span>
                    </div>
                    <p class="text-gray-500 mt-4">Best value for serious seekers</p>
                </div>
                
                <ul class="space-y-4 mb-8">
                    <li class="flex items-center text-gray-700">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Everything in Free
                    </li>
                    <li class="flex items-center text-gray-700">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="font-semibold">Unlimited likes</span>
                    </li>
                    <li class="flex items-center text-gray-700">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Advanced AI matching
                    </li>
                    <li class="flex items-center text-gray-700">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        See who liked you
                    </li>
                    <li class="flex items-center text-gray-700">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Unlimited video calls
                    </li>
                    <li class="flex items-center text-gray-700">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Profile boost (weekly)
                    </li>
                    <li class="flex items-center text-gray-700">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Advanced filters
                    </li>
                </ul>
                
                <a href="{{ route('register') }}" class="block text-center bg-gradient-to-r from-taxi-yellow to-taxi-yellow-dark text-gray-900 py-4 rounded-xl font-semibold hover:shadow-lg transition-all">
                    Get Premium
                </a>
            </div>

            <!-- Family Plan -->
            <div class="bg-white rounded-3xl shadow-lg border-2 border-taxi-border hover:border-taxi-yellow/50 transition-all p-8">
                <div class="text-center mb-6">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Family</h3>
                    <div class="flex items-baseline justify-center">
                        <span class="text-5xl font-bold text-gray-900">₹1,499</span>
                        <span class="text-gray-500 ml-2">/month</span>
                    </div>
                    <p class="text-gray-500 mt-4">For families helping find matches</p>
                </div>
                
                <ul class="space-y-4 mb-8">
                    <li class="flex items-center text-gray-700">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Everything in Premium
                    </li>
                    <li class="flex items-center text-gray-700">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Multiple family accounts
                    </li>
                    <li class="flex items-center text-gray-700">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Family verification badge
                    </li>
                    <li class="flex items-center text-gray-700">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Priority support
                    </li>
                    <li class="flex items-center text-gray-700">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Dedicated relationship manager
                    </li>
                    <li class="flex items-center text-gray-700">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Family profile highlighting
                    </li>
                </ul>
                
                <a href="{{ route('register') }}" class="block text-center bg-gray-100 text-gray-900 py-4 rounded-xl font-semibold hover:bg-gray-200 transition-all">
                    Get Family Plan
                </a>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="py-20 bg-gradient-to-br from-gray-50 to-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                Frequently Asked Questions
            </h2>
            <p class="text-xl text-gray-600">
                Have questions about pricing? We've got answers.
            </p>
        </div>

        <div class="space-y-4" x-data="{ active: null }">
            <!-- FAQ 1 -->
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                <button @click="active = active === 1 ? null : 1" class="w-full px-6 py-5 text-left flex items-center justify-between">
                    <span class="font-semibold text-gray-900">Can I cancel my subscription anytime?</span>
                    <svg class="w-5 h-5 text-gray-500 transition-transform" :class="{ 'rotate-180': active === 1 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="active === 1" class="px-6 pb-5">
                    <p class="text-gray-600">Yes, you can cancel your subscription at any time. Your premium features will remain active until the end of your billing period. No refunds are provided for partial months.</p>
                </div>
            </div>

            <!-- FAQ 2 -->
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                <button @click="active = active === 2 ? null : 2" class="w-full px-6 py-5 text-left flex items-center justify-between">
                    <span class="font-semibold text-gray-900">What payment methods do you accept?</span>
                    <svg class="w-5 h-5 text-gray-500 transition-transform" :class="{ 'rotate-180': active === 2 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="active === 2" class="px-6 pb-5">
                    <p class="text-gray-600">We accept all major credit/debit cards (Visa, Mastercard, American Express), UPI, net banking, and digital wallets like Paytm and PhonePe.</p>
                </div>
            </div>

            <!-- FAQ 3 -->
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                <button @click="active = active === 3 ? null : 3" class="w-full px-6 py-5 text-left flex items-center justify-between">
                    <span class="font-semibold text-gray-900">Is there a free trial for Premium?</span>
                    <svg class="w-5 h-5 text-gray-500 transition-transform" :class="{ 'rotate-180': active === 3 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="active === 3" class="px-6 pb-5">
                    <p class="text-gray-600">Yes! New users get a 7-day free trial of Premium features. No credit card required to start the trial.</p>
                </div>
            </div>

            <!-- FAQ 4 -->
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                <button @click="active = active === 4 ? null : 4" class="w-full px-6 py-5 text-left flex items-center justify-between">
                    <span class="font-semibold text-gray-900">What happens to my data if I downgrade?</span>
                    <svg class="w-5 h-5 text-gray-500 transition-transform" :class="{ 'rotate-180': active === 4 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="active === 4" class="px-6 pb-5">
                    <p class="text-gray-600">Your profile, matches, and conversations are never lost. You'll still have access to everything, just with the limitations of the Free plan (like 10 likes/day).</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Money Back Guarantee -->
<section class="py-20 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-3xl p-10">
            <div class="w-20 h-20 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
            </div>
            <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4">
                100% Money Back Guarantee
            </h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                If you're not satisfied with your Premium experience within the first 30 days, we'll give you a full refund. No questions asked.
            </p>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-gradient-to-r from-taxi-yellow via-taxi-yellow-light to-taxi-yellow-dark">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
            Start Your Journey Today
        </h2>
        <p class="text-lg text-gray-800 mb-8">
            Join thousands of happy couples who found love on Aadhi Matrimony.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('register') }}" class="inline-flex items-center justify-center bg-gray-900 text-white px-8 py-4 rounded-full font-semibold text-lg hover:bg-gray-800 transition-all transform hover:-translate-y-1">
                Create Free Account
            </a>
            <a href="{{ route('features') }}" class="inline-flex items-center justify-center bg-white text-gray-900 px-8 py-4 rounded-full font-semibold text-lg border-2 border-gray-200 hover:border-gray-300 transition-all">
                Explore Features
            </a>
        </div>
    </div>
</section>
@endsection
