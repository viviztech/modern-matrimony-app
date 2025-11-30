@extends('layouts.app')

@section('title', 'Find Your Perfect Match')

@section('content')
<div class="relative">
    <!-- Hero Section -->
    <div class="relative overflow-hidden bg-gradient-to-br from-primary-50 via-white to-secondary-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
            <div class="text-center">
                <h1 class="text-5xl md:text-6xl font-bold mb-6 animate-fade-in">
                    <span class="text-gradient">Find Your Perfect</span>
                    <br>
                    <span class="text-gray-900 dark:text-white">Life Partner</span>
                </h1>
                <p class="text-xl text-gray-600 dark:text-gray-300 mb-8 max-w-2xl mx-auto">
                    Modern matchmaking for Gen Z. Connect with like-minded individuals through AI-powered compatibility matching.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('register') }}" class="bg-gradient-to-r from-primary to-secondary text-white px-8 py-4 rounded-xl hover:shadow-glow transition-all duration-200 font-semibold text-lg">
                        Get Started Free
                    </a>
                    <a href="{{ route('login') }}" class="bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-8 py-4 rounded-xl border-2 border-gray-200 dark:border-gray-700 hover:border-primary dark:hover:border-primary transition-all duration-200 font-semibold text-lg">
                        Sign In
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="py-24 bg-white dark:bg-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">Why Choose Us?</h2>
                <p class="text-xl text-gray-600 dark:text-gray-300">Modern features for modern relationships</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="p-8 rounded-2xl bg-gradient-to-br from-primary-50 to-white dark:from-gray-700 dark:to-gray-800 hover:shadow-xl transition-shadow">
                    <div class="w-16 h-16 bg-gradient-to-br from-primary to-secondary rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Video Profiles</h3>
                    <p class="text-gray-600 dark:text-gray-300">See real people with 30-second video introductions. No more studio photos - just authentic you!</p>
                </div>

                <!-- Feature 2 -->
                <div class="p-8 rounded-2xl bg-gradient-to-br from-secondary-50 to-white dark:from-gray-700 dark:to-gray-800 hover:shadow-xl transition-shadow">
                    <div class="w-16 h-16 bg-gradient-to-br from-secondary to-accent rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">AI Matching</h3>
                    <p class="text-gray-600 dark:text-gray-300">Smart compatibility scores based on personality, interests, and values. Find matches that truly click.</p>
                </div>

                <!-- Feature 3 -->
                <div class="p-8 rounded-2xl bg-gradient-to-br from-accent-50 to-white dark:from-gray-700 dark:to-gray-800 hover:shadow-xl transition-shadow">
                    <div class="w-16 h-16 bg-gradient-to-br from-accent to-primary rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Safe & Verified</h3>
                    <p class="text-gray-600 dark:text-gray-300">Video verification, phone OTP, and social proof. Connect with confidence on our secure platform.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="py-24 bg-gradient-to-br from-primary to-secondary">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl font-bold text-white mb-6">Ready to Find Your Match?</h2>
            <p class="text-xl text-white/90 mb-8">Join thousands of verified users finding meaningful connections every day</p>
            <a href="{{ route('register') }}" class="inline-block bg-white text-primary px-8 py-4 rounded-xl hover:shadow-2xl transition-all duration-200 font-semibold text-lg">
                Create Free Account
            </a>
        </div>
    </div>
</div>
@endsection
