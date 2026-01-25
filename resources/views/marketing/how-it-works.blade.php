@extends('layouts.public')

@section('title', 'How It Works - Aadhi Matrimony')
@section('meta_description', 'Learn how Aadhi Matrimony works. Our simple 4-step process helps you find your perfect life partner with AI-powered matching.')
@section('meta_keywords', 'how it works, matrimony process, matchmaking steps, find partner')

@section('content')
<!-- Hero Section -->
<section class="relative py-20 overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-taxi-cream via-white to-taxi-yellow/20 dark:from-gray-900 dark:via-gray-900 dark:to-gray-800"></div>
    <div class="absolute top-20 left-10 w-72 h-72 bg-taxi-yellow/20 rounded-full blur-3xl"></div>
    <div class="absolute bottom-20 right-10 w-96 h-96 bg-taxi-yellow/10 rounded-full blur-3xl"></div>
    
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <span class="inline-block px-4 py-1.5 bg-taxi-yellow/20 text-taxi-yellow-dark rounded-full text-sm font-semibold mb-6">
                Simple Process
            </span>
            <h1 class="text-4xl md:text-6xl font-bold text-gray-900 dark:text-white mb-6">
                Find Your Perfect Match in
                <span class="bg-gradient-to-r from-taxi-yellow to-taxi-yellow-dark bg-clip-text text-transparent">4 Simple Steps</span>
            </h1>
            <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto mb-10">
                Our AI-powered platform makes finding your life partner simple, safe, and effective. 
                Join thousands of happy couples who found their perfect match on Aadhi Matrimony.
            </p>
        </div>
    </div>
</section>

<!-- Steps Section -->
<section class="py-20 bg-white dark:bg-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Step 1 -->
        <div class="flex flex-col lg:flex-row items-center gap-12 mb-24">
            <div class="lg:w-1/2">
                <div class="relative">
                    <div class="absolute -top-4 -left-4 w-20 h-20 bg-gradient-to-br from-taxi-yellow to-taxi-yellow-dark rounded-2xl flex items-center justify-center text-3xl font-bold text-white shadow-lg">
                        1
                    </div>
                    <div class="bg-gradient-to-br from-taxi-cream to-white dark:from-gray-800 dark:to-gray-700 rounded-3xl p-8 shadow-xl">
                        <div class="w-full h-64 bg-gradient-to-br from-taxi-yellow/20 to-taxi-yellow-dark/20 rounded-2xl flex items-center justify-center">
                            <svg class="w-32 h-32 text-taxi-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            <div class="lg:w-1/2">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">Create Your Profile</h2>
                <p class="text-lg text-gray-600 dark:text-gray-300 mb-6">
                    Sign up and create your detailed profile. Add photos, share your interests, 
                    preferences, and what you're looking for in a life partner. The more details 
                    you provide, the better your matches will be.
                </p>
                <ul class="space-y-3">
                    <li class="flex items-center text-gray-700 dark:text-gray-300">
                        <svg class="w-5 h-5 text-taxi-yellow mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Add up to 10 photos
                    </li>
                    <li class="flex items-center text-gray-700 dark:text-gray-300">
                        <svg class="w-5 h-5 text-taxi-yellow mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Share your interests and hobbies
                    </li>
                    <li class="flex items-center text-gray-700 dark:text-gray-300">
                        <svg class="w-5 h-5 text-taxi-yellow mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Set your partner preferences
                    </li>
                </ul>
            </div>
        </div>

        <!-- Step 2 -->
        <div class="flex flex-col lg:flex-row-reverse items-center gap-12 mb-24">
            <div class="lg:w-1/2">
                <div class="relative">
                    <div class="absolute -top-4 -right-4 w-20 h-20 bg-gradient-to-br from-taxi-yellow to-taxi-yellow-dark rounded-2xl flex items-center justify-center text-3xl font-bold text-white shadow-lg">
                        2
                    </div>
                    <div class="bg-gradient-to-br from-taxi-cream to-white dark:from-gray-800 dark:to-gray-700 rounded-3xl p-8 shadow-xl">
                        <div class="w-full h-64 bg-gradient-to-br from-blue-500/20 to-blue-600/20 rounded-2xl flex items-center justify-center">
                            <svg class="w-32 h-32 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            <div class="lg:w-1/2">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">AI-Powered Matching</h2>
                <p class="text-lg text-gray-600 dark:text-gray-300 mb-6">
                    Our advanced AI algorithm analyzes your preferences, interests, and values to 
                    suggest compatible matches. Our machine learning continuously improves 
                    recommendations based on your interactions.
                </p>
                <ul class="space-y-3">
                    <li class="flex items-center text-gray-700 dark:text-gray-300">
                        <svg class="w-5 h-5 text-taxi-yellow mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Smart compatibility scoring
                    </li>
                    <li class="flex items-center text-gray-700 dark:text-gray-300">
                        <svg class="w-5 h-5 text-taxi-yellow mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Personalized match recommendations
                    </li>
                    <li class="flex items-center text-gray-700 dark:text-gray-300">
                        <svg class="w-5 h-5 text-taxi-yellow mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Learns from your preferences
                    </li>
                </ul>
            </div>
        </div>

        <!-- Step 3 -->
        <div class="flex flex-col lg:flex-row items-center gap-12 mb-24">
            <div class="lg:w-1/2">
                <div class="relative">
                    <div class="absolute -top-4 -left-4 w-20 h-20 bg-gradient-to-br from-taxi-yellow to-taxi-yellow-dark rounded-2xl flex items-center justify-center text-3xl font-bold text-white shadow-lg">
                        3
                    </div>
                    <div class="bg-gradient-to-br from-taxi-cream to-white dark:from-gray-800 dark:to-gray-700 rounded-3xl p-8 shadow-xl">
                        <div class="w-full h-64 bg-gradient-to-br from-green-500/20 to-green-600/20 rounded-2xl flex items-center justify-center">
                            <svg class="w-32 h-32 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            <div class="lg:w-1/2">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">Connect & Chat</h2>
                <p class="text-lg text-gray-600 dark:text-gray-300 mb-6">
                    When you find someone interesting, send a like or message to start a conversation. 
                    Use our icebreaker questions to break the ice and get to know each other better 
                    in a fun, engaging way.
                </p>
                <ul class="space-y-3">
                    <li class="flex items-center text-gray-700 dark:text-gray-300">
                        <svg class="w-5 h-5 text-taxi-yellow mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Instant messaging with read receipts
                    </li>
                    <li class="flex items-center text-gray-700 dark:text-gray-300">
                        <svg class="w-5 h-5 text-taxi-yellow mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Fun icebreaker games
                    </li>
                    <li class="flex items-center text-gray-700 dark:text-gray-300">
                        <svg class="w-5 h-5 text-taxi-yellow mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Voice and video calls
                    </li>
                </ul>
            </div>
        </div>

        <!-- Step 4 -->
        <div class="flex flex-col lg:flex-row-reverse items-center gap-12">
            <div class="lg:w-1/2">
                <div class="relative">
                    <div class="absolute -top-4 -right-4 w-20 h-20 bg-gradient-to-br from-taxi-yellow to-taxi-yellow-dark rounded-2xl flex items-center justify-center text-3xl font-bold text-white shadow-lg">
                        4
                    </div>
                    <div class="bg-gradient-to-br from-taxi-cream to-white dark:from-gray-800 dark:to-gray-700 rounded-3xl p-8 shadow-xl">
                        <div class="w-full h-64 bg-gradient-to-br from-purple-500/20 to-purple-600/20 rounded-2xl flex items-center justify-center">
                            <svg class="w-32 h-32 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            <div class="lg:w-1/2">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">Build Your Relationship</h2>
                <p class="text-lg text-gray-600 dark:text-gray-300 mb-6">
                    When both parties express interest, it's a match! Take your time to build a 
                    genuine connection through meaningful conversations. When you're ready, 
                    take the next step towards forever.
                </p>
                <ul class="space-y-3">
                    <li class="flex items-center text-gray-700 dark:text-gray-300">
                        <svg class="w-5 h-5 text-taxi-yellow mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Mutual match system
                    </li>
                    <li class="flex items-center text-gray-700 dark:text-gray-300">
                        <svg class="w-5 h-5 text-taxi-yellow mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Profile verification badges
                    </li>
                    <li class="flex items-center text-gray-700 dark:text-gray-300">
                        <svg class="w-5 h-5 text-taxi-yellow mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Success stories & support
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-20 bg-gradient-to-r from-taxi-yellow to-taxi-yellow-dark">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            <div>
                <div class="text-4xl md:text-5xl font-bold text-white mb-2">50K+</div>
                <div class="text-white/80">Active Users</div>
            </div>
            <div>
                <div class="text-4xl md:text-5xl font-bold text-white mb-2">10K+</div>
                <div class="text-white/80">Happy Couples</div>
            </div>
            <div>
                <div class="text-4xl md:text-5xl font-bold text-white mb-2">95%</div>
                <div class="text-white/80">Match Rate</div>
            </div>
            <div>
                <div class="text-4xl md:text-5xl font-bold text-white mb-2">4.9★</div>
                <div class="text-white/80">User Rating</div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-white dark:bg-gray-900">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-6">
            Ready to Find Your Perfect Match?
        </h2>
        <p class="text-xl text-gray-600 dark:text-gray-300 mb-10">
            Join Aadhi Matrimony today and start your journey to finding your life partner.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('register') }}" class="bg-gradient-to-r from-taxi-yellow to-taxi-yellow-dark text-gray-900 px-8 py-4 rounded-xl hover:shadow-glow transition-all duration-200 font-semibold text-lg">
                Get Started Free
            </a>
            <a href="{{ route('features') }}" class="bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-white px-8 py-4 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors font-semibold text-lg">
                Learn More
            </a>
        </div>
    </div>
</section>
@endsection
