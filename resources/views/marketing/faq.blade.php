@extends('layouts.public')

@section('title', 'FAQ - Aadhi Matrimony')
@section('meta_description', 'Frequently asked questions about Aadhi Matrimony - get answers about features, pricing, safety, and how to use our platform.')

@section('content')
<!-- Hero Section -->
<section class="py-20 bg-gradient-to-br from-taxi-cream via-white to-taxi-cream-dark">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 mb-6">
            Frequently Asked
            <span class="bg-gradient-to-r from-taxi-yellow to-taxi-yellow-dark bg-clip-text text-transparent">Questions</span>
        </h1>
        <p class="text-xl text-gray-600 max-w-3xl mx-auto">
            Find answers to common questions about Aadhi Matrimony. Can't find what you're looking for? Contact us!
        </p>
    </div>
</section>

<!-- FAQ Sections -->
<section class="py-20 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- General Questions -->
        <div class="mb-16">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-8 flex items-center">
                <span class="w-10 h-10 bg-taxi-yellow/20 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-5 h-5 text-taxi-yellow-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </span>
                General Questions
            </h2>

            <div class="space-y-4" x-data="{ active: null }">
                <div class="bg-gray-50 rounded-2xl overflow-hidden">
                    <button @click="active = active === 1 ? null : 1" class="w-full px-6 py-5 text-left flex items-center justify-between">
                        <span class="font-semibold text-gray-900">What is Aadhi Matrimony?</span>
                        <svg class="w-5 h-5 text-gray-500 transition-transform" :class="{ 'rotate-180': active === 1 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="active === 1" class="px-6 pb-5">
                        <p class="text-gray-600">Aadhi Matrimony is a modern dating and matrimony platform designed to help people find meaningful relationships. We combine advanced AI technology with comprehensive verification features to create a safe and effective dating experience.</p>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-2xl overflow-hidden">
                    <button @click="active = active === 2 ? null : 2" class="w-full px-6 py-5 text-left flex items-center justify-between">
                        <span class="font-semibold text-gray-900">Is Aadhi Matrimony free to use?</span>
                        <svg class="w-5 h-5 text-gray-500 transition-transform" :class="{ 'rotate-180': active === 2 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="active === 2" class="px-6 pb-5">
                        <p class="text-gray-600">Yes! Aadhi Matrimony offers a free plan with essential features. You can create a profile, browse matches, and send limited likes for free. Premium plans offer additional features like unlimited likes, advanced matching, and video calls.</p>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-2xl overflow-hidden">
                    <button @click="active = active === 3 ? null : 3" class="w-full px-6 py-5 text-left flex items-center justify-between">
                        <span class="font-semibold text-gray-900">How is Aadhi Matrimony different from other dating apps?</span>
                        <svg class="w-5 h-5 text-gray-500 transition-transform" :class="{ 'rotate-180': active === 3 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="active === 3" class="px-6 pb-5">
                        <p class="text-gray-600">Unlike other dating apps, Aadhi Matrimony focuses on meaningful connections. Our AI-powered matching goes beyond surface-level preferences to find compatible partners. We also have robust verification features to ensure you're connecting with real people.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Matching & Features -->
        <div class="mb-16">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-8 flex items-center">
                <span class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </span>
                Matching & Features
            </h2>

            <div class="space-y-4" x-data="{ active: null }">
                <div class="bg-gray-50 rounded-2xl overflow-hidden">
                    <button @click="active = active === 4 ? null : 4" class="w-full px-6 py-5 text-left flex items-center justify-between">
                        <span class="font-semibold text-gray-900">How does the AI matching work?</span>
                        <svg class="w-5 h-5 text-gray-500 transition-transform" :class="{ 'rotate-180': active === 4 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="active === 4" class="px-6 pb-5">
                        <p class="text-gray-600">Our AI analyzes multiple dimensions including personality traits, interests, values, lifestyle preferences, and relationship goals. It then matches you with people who complement your profile, providing a compatibility score for each match.</p>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-2xl overflow-hidden">
                    <button @click="active = active === 5 ? null : 5" class="w-full px-6 py-5 text-left flex items-center justify-between">
                        <span class="font-semibold text-gray-900">What are video profiles?</span>
                        <svg class="w-5 h-5 text-gray-500 transition-transform" :class="{ 'rotate-180': active === 5 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="active === 5" class="px-6 pb-5">
                        <p class="text-gray-600">Video profiles allow users to upload a 30-second video introduction. This helps you see the real personality of potential matches - their smile, mannerisms, and energy - making connections more authentic than photos alone.</p>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-2xl overflow-hidden">
                    <button @click="active = active === 6 ? null : 6" class="w-full px-6 py-5 text-left flex items-center justify-between">
                        <span class="font-semibold text-gray-900">What are icebreaker questions?</span>
                        <svg class="w-5 h-5 text-gray-500 transition-transform" :class="{ 'rotate-180': active === 6 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="active === 6" class="px-6 pb-5">
                        <p class="text-gray-600">Icebreaker questions are fun, thought-provoking prompts that help start conversations. We have 135+ questions across categories like "Would You Rather", "Deep Thoughts", and "Fun Facts" to help you break the ice with your matches.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Safety & Privacy -->
        <div class="mb-16">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-8 flex items-center">
                <span class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </span>
                Safety & Privacy
            </h2>

            <div class="space-y-4" x-data="{ active: null }">
                <div class="bg-gray-50 rounded-2xl overflow-hidden">
                    <button @click="active = active === 7 ? null : 7" class="w-full px-6 py-5 text-left flex items-center justify-between">
                        <span class="font-semibold text-gray-900">How do you verify user profiles?</span>
                        <svg class="w-5 h-5 text-gray-500 transition-transform" :class="{ 'rotate-180': active === 7 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="active === 7" class="px-6 pb-5">
                        <p class="text-gray-600">We use a multi-step verification process: 1) Phone verification via OTP, 2) Video selfie verification with liveness detection, 3) Optional government ID verification for premium users.</p>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-2xl overflow-hidden">
                    <button @click="active = active === 8 ? null : 8" class="w-full px-6 py-5 text-left flex items-center justify-between">
                        <span class="font-semibold text-gray-900">Is my data safe on Aadhi Matrimony?</span>
                        <svg class="w-5 h-5 text-gray-500 transition-transform" :class="{ 'rotate-180': active === 8 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="active === 8" class="px-6 pb-5">
                        <p class="text-gray-600">Absolutely. We use industry-standard encryption to protect your data and never sell your personal information. You control what information is visible on your profile and can delete your account anytime.</p>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-2xl overflow-hidden">
                    <button @click="active = active === 9 ? null : 9" class="w-full px-6 py-5 text-left flex items-center justify-between">
                        <span class="font-semibold text-gray-900">How do I report inappropriate behavior?</span>
                        <svg class="w-5 h-5 text-gray-500 transition-transform" :class="{ 'rotate-180': active === 9 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="active === 9" class="px-6 pb-5">
                        <p class="text-gray-600">If you encounter inappropriate behavior, you can report the user directly from their profile or chat. Our moderation team reviews all reports within 24 hours and takes appropriate action.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Billing -->
        <div>
            <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-8 flex items-center">
                <span class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                </span>
                Billing & Subscriptions
            </h2>

            <div class="space-y-4" x-data="{ active: null }">
                <div class="bg-gray-50 rounded-2xl overflow-hidden">
                    <button @click="active = active === 10 ? null : 10" class="w-full px-6 py-5 text-left flex items-center justify-between">
                        <span class="font-semibold text-gray-900">What payment methods do you accept?</span>
                        <svg class="w-5 h-5 text-gray-500 transition-transform" :class="{ 'rotate-180': active === 10 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="active === 10" class="px-6 pb-5">
                        <p class="text-gray-600">We accept all major credit/debit cards (Visa, Mastercard, American Express), UPI, net banking, and digital wallets like Paytm and PhonePe.</p>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-2xl overflow-hidden">
                    <button @click="active = active === 11 ? null : 11" class="w-full px-6 py-5 text-left flex items-center justify-between">
                        <span class="font-semibold text-gray-900">Can I cancel my subscription?</span>
                        <svg class="w-5 h-5 text-gray-500 transition-transform" :class="{ 'rotate-180': active === 11 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="active === 11" class="px-6 pb-5">
                        <p class="text-gray-600">Yes, you can cancel anytime from your account settings. Your premium features will remain active until the end of your billing period.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Still Have Questions -->
<section class="py-20 bg-gradient-to-br from-gray-50 to-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="bg-white rounded-3xl shadow-lg p-10">
            <div class="w-20 h-20 bg-taxi-yellow/20 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-taxi-yellow-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
            </div>
            <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4">
                Still Have Questions?
            </h2>
            <p class="text-gray-600 mb-8">
                Can't find the answer you're looking for? Please contact our support team.
            </p>
            <a href="{{ route('contact') }}" class="inline-flex items-center justify-center bg-gradient-to-r from-taxi-yellow to-taxi-yellow-dark text-gray-900 px-8 py-4 rounded-full font-semibold text-lg hover:shadow-lg transition-all">
                Contact Support
            </a>
        </div>
    </div>
</section>
@endsection
