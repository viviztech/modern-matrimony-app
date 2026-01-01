@extends('layouts.public')

@section('title', 'Find Your Perfect Match - Modern Matrimony Platform')
@section('meta_description', 'Join 50,000+ verified users finding love with AI-powered matching. Video profiles, real-time chat, interactive games. Free to join!')
@section('meta_keywords', 'matrimony, matchmaking, AI matching, video profiles, dating, marriage, life partner, Indian matrimony')

@section('content')
<div class="relative">
    <!-- Hero Section -->
    <div class="relative overflow-hidden bg-gradient-to-br from-primary-50 via-white to-secondary-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
        <!-- Animated Background Elements -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-primary/10 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-secondary/10 rounded-full blur-3xl animate-pulse" style="animation-delay: 1s;"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 relative">
            <div class="text-center">
                <div class="inline-flex items-center px-4 py-2 rounded-full bg-primary/10 dark:bg-primary/20 text-primary dark:text-primary-400 text-sm font-semibold mb-6 animate-fade-in">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    Trusted by 50,000+ Users
                </div>

                <h1 class="text-5xl md:text-7xl font-bold mb-6 animate-fade-in">
                    <span class="text-gradient bg-gradient-to-r from-primary via-secondary to-accent bg-clip-text text-transparent">Find Your Perfect</span>
                    <br>
                    <span class="text-gray-900 dark:text-white">Life Partner</span>
                </h1>
                <p class="text-xl md:text-2xl text-gray-600 dark:text-gray-300 mb-8 max-w-3xl mx-auto leading-relaxed">
                    Modern matchmaking for Gen Z and Millennials. Connect with like-minded individuals through <span class="font-semibold text-primary">AI-powered compatibility</span> matching and authentic video profiles.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center mb-12">
                    <a href="{{ route('register') }}" class="group bg-gradient-to-r from-primary to-secondary text-white px-10 py-5 rounded-2xl hover:shadow-glow transition-all duration-300 font-semibold text-lg transform hover:scale-105">
                        Get Started Free
                        <svg class="w-5 h-5 inline-block ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </a>
                    <a href="#how-it-works" class="bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-10 py-5 rounded-2xl border-2 border-gray-200 dark:border-gray-700 hover:border-primary dark:hover:border-primary transition-all duration-300 font-semibold text-lg transform hover:scale-105">
                        Learn More
                    </a>
                </div>

                <!-- Trust Indicators -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 max-w-4xl mx-auto mt-16">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-primary dark:text-primary-400">50K+</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">Verified Users</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-secondary dark:text-secondary-400">10K+</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">Success Stories</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-accent dark:text-accent-400">98%</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">Match Rate</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-primary dark:text-primary-400">4.9★</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">User Rating</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="py-24 bg-white dark:bg-gray-800" id="features">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-4">Why Choose Us?</h2>
                <p class="text-xl text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">Modern features for modern relationships. Everything you need to find your perfect match.</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="group p-8 rounded-2xl bg-gradient-to-br from-primary-50 to-white dark:from-gray-700 dark:to-gray-800 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="w-16 h-16 bg-gradient-to-br from-primary to-secondary rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">Video Profiles</h3>
                    <p class="text-gray-600 dark:text-gray-300 leading-relaxed">See real people with 30-second video introductions. No more studio photos - just authentic you!</p>
                </div>

                <!-- Feature 2 -->
                <div class="group p-8 rounded-2xl bg-gradient-to-br from-secondary-50 to-white dark:from-gray-700 dark:to-gray-800 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="w-16 h-16 bg-gradient-to-br from-secondary to-accent rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">AI Matching</h3>
                    <p class="text-gray-600 dark:text-gray-300 leading-relaxed">Smart compatibility scores based on personality, interests, and values. Find matches that truly click.</p>
                </div>

                <!-- Feature 3 -->
                <div class="group p-8 rounded-2xl bg-gradient-to-br from-accent-50 to-white dark:from-gray-700 dark:to-gray-800 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="w-16 h-16 bg-gradient-to-br from-accent to-primary rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">Safe & Verified</h3>
                    <p class="text-gray-600 dark:text-gray-300 leading-relaxed">Video verification, phone OTP, and social proof. Connect with confidence on our secure platform.</p>
                </div>

                <!-- Feature 4 -->
                <div class="group p-8 rounded-2xl bg-gradient-to-br from-primary-50 to-white dark:from-gray-700 dark:to-gray-800 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="w-16 h-16 bg-gradient-to-br from-primary to-secondary rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">Real-time Chat</h3>
                    <p class="text-gray-600 dark:text-gray-300 leading-relaxed">Instant messaging with voice notes, icebreakers, and video calls. Connect meaningfully with matches.</p>
                </div>

                <!-- Feature 5 -->
                <div class="group p-8 rounded-2xl bg-gradient-to-br from-secondary-50 to-white dark:from-gray-700 dark:to-gray-800 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="w-16 h-16 bg-gradient-to-br from-secondary to-accent rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">Interactive Games</h3>
                    <p class="text-gray-600 dark:text-gray-300 leading-relaxed">Break the ice with fun compatibility games and quizzes. Discover shared interests naturally.</p>
                </div>

                <!-- Feature 6 -->
                <div class="group p-8 rounded-2xl bg-gradient-to-br from-accent-50 to-white dark:from-gray-700 dark:to-gray-800 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="w-16 h-16 bg-gradient-to-br from-accent to-primary rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">Profile Analytics</h3>
                    <p class="text-gray-600 dark:text-gray-300 leading-relaxed">Track who viewed your profile, engagement metrics, and optimize your presence with insights.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- How It Works Section -->
    <div class="py-24 bg-gradient-to-br from-gray-50 to-white dark:from-gray-900 dark:to-gray-800" id="how-it-works">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-4">How It Works</h2>
                <p class="text-xl text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">Your journey to finding the perfect match in 4 simple steps</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Step 1 -->
                <div class="relative text-center">
                    <div class="w-20 h-20 bg-gradient-to-br from-primary to-secondary rounded-full flex items-center justify-center mx-auto mb-6 text-white text-3xl font-bold shadow-xl">1</div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Create Profile</h3>
                    <p class="text-gray-600 dark:text-gray-300">Sign up and complete your profile with photos and video introduction</p>
                    <div class="hidden lg:block absolute top-10 left-full w-full h-0.5 bg-gradient-to-r from-primary to-secondary opacity-20 -translate-x-1/2"></div>
                </div>

                <!-- Step 2 -->
                <div class="relative text-center">
                    <div class="w-20 h-20 bg-gradient-to-br from-secondary to-accent rounded-full flex items-center justify-center mx-auto mb-6 text-white text-3xl font-bold shadow-xl">2</div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Get Verified</h3>
                    <p class="text-gray-600 dark:text-gray-300">Complete phone and video verification for trustworthy connections</p>
                    <div class="hidden lg:block absolute top-10 left-full w-full h-0.5 bg-gradient-to-r from-secondary to-accent opacity-20 -translate-x-1/2"></div>
                </div>

                <!-- Step 3 -->
                <div class="relative text-center">
                    <div class="w-20 h-20 bg-gradient-to-br from-accent to-primary rounded-full flex items-center justify-center mx-auto mb-6 text-white text-3xl font-bold shadow-xl">3</div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Discover Matches</h3>
                    <p class="text-gray-600 dark:text-gray-300">Browse AI-matched profiles and connect with compatible partners</p>
                    <div class="hidden lg:block absolute top-10 left-full w-full h-0.5 bg-gradient-to-r from-accent to-primary opacity-20 -translate-x-1/2"></div>
                </div>

                <!-- Step 4 -->
                <div class="text-center">
                    <div class="w-20 h-20 bg-gradient-to-br from-primary to-secondary rounded-full flex items-center justify-center mx-auto mb-6 text-white text-3xl font-bold shadow-xl">4</div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Start Dating</h3>
                    <p class="text-gray-600 dark:text-gray-300">Chat, video call, and plan your first date with your perfect match</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Testimonials Section -->
    <div class="py-24 bg-white dark:bg-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-4">Success Stories</h2>
                <p class="text-xl text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">Join thousands of couples who found their perfect match</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Testimonial 1 -->
                <div class="bg-gradient-to-br from-primary-50 to-white dark:from-gray-700 dark:to-gray-800 p-8 rounded-2xl shadow-lg">
                    <div class="flex items-center mb-4">
                        <div class="flex text-yellow-400">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        </div>
                    </div>
                    <p class="text-gray-700 dark:text-gray-300 mb-6 italic">"Found my soulmate within 2 months! The video profiles helped me connect with genuine people. The AI matching is incredibly accurate. Highly recommended!"</p>
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-br from-primary to-secondary rounded-full flex items-center justify-center text-white font-bold">RS</div>
                        <div class="ml-4">
                            <div class="font-semibold text-gray-900 dark:text-white">Rahul & Sneha</div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">Mumbai, India</div>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 2 -->
                <div class="bg-gradient-to-br from-secondary-50 to-white dark:from-gray-700 dark:to-gray-800 p-8 rounded-2xl shadow-lg">
                    <div class="flex items-center mb-4">
                        <div class="flex text-yellow-400">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        </div>
                    </div>
                    <p class="text-gray-700 dark:text-gray-300 mb-6 italic">"Best matrimony app I've used! The verification process made me feel safe. Met my partner through this platform and we're getting married next month!"</p>
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-br from-secondary to-accent rounded-full flex items-center justify-center text-white font-bold">AP</div>
                        <div class="ml-4">
                            <div class="font-semibold text-gray-900 dark:text-white">Amit & Priya</div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">Bangalore, India</div>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 3 -->
                <div class="bg-gradient-to-br from-accent-50 to-white dark:from-gray-700 dark:to-gray-800 p-8 rounded-2xl shadow-lg">
                    <div class="flex items-center mb-4">
                        <div class="flex text-yellow-400">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        </div>
                    </div>
                    <p class="text-gray-700 dark:text-gray-300 mb-6 italic">"The compatibility games were so fun! Helped us break the ice and discover we had so much in common. Now happily married for 6 months!"</p>
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-br from-accent to-primary rounded-full flex items-center justify-center text-white font-bold">VK</div>
                        <div class="ml-4">
                            <div class="font-semibold text-gray-900 dark:text-white">Vikram & Kavya</div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">Delhi, India</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pricing Section -->
    <div class="py-24 bg-gradient-to-br from-gray-50 to-white dark:from-gray-900 dark:to-gray-800" id="pricing">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-4">Simple, Transparent Pricing</h2>
                <p class="text-xl text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">Choose the plan that works best for you</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8 max-w-6xl mx-auto">
                <!-- Free Plan -->
                <div class="bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-lg border-2 border-gray-200 dark:border-gray-700">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Free</h3>
                    <div class="mb-6">
                        <span class="text-4xl font-bold text-gray-900 dark:text-white">₹0</span>
                        <span class="text-gray-600 dark:text-gray-400">/month</span>
                    </div>
                    <ul class="space-y-4 mb-8">
                        <li class="flex items-start">
                            <svg class="w-6 h-6 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-gray-600 dark:text-gray-400">5 likes per day</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-6 h-6 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-gray-600 dark:text-gray-400">Basic matching</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-6 h-6 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-gray-600 dark:text-gray-400">Chat with matches</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-6 h-6 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-gray-600 dark:text-gray-400">Profile verification</span>
                        </li>
                    </ul>
                    <a href="{{ route('register') }}" class="block text-center bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white px-6 py-3 rounded-xl hover:bg-gray-300 dark:hover:bg-gray-600 transition-all font-semibold">
                        Get Started
                    </a>
                </div>

                <!-- Silver Plan -->
                <div class="bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-xl border-2 border-primary transform scale-105">
                    <div class="inline-block bg-gradient-to-r from-primary to-secondary text-white text-xs font-semibold px-3 py-1 rounded-full mb-4">MOST POPULAR</div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Silver</h3>
                    <div class="mb-6">
                        <span class="text-4xl font-bold text-primary dark:text-primary-400">₹499</span>
                        <span class="text-gray-600 dark:text-gray-400">/month</span>
                    </div>
                    <ul class="space-y-4 mb-8">
                        <li class="flex items-start">
                            <svg class="w-6 h-6 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-gray-600 dark:text-gray-400">Unlimited likes</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-6 h-6 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-gray-600 dark:text-gray-400">Advanced AI matching</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-6 h-6 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-gray-600 dark:text-gray-400">See who liked you</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-6 h-6 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-gray-600 dark:text-gray-400">Video calls</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-6 h-6 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-gray-600 dark:text-gray-400">Profile analytics</span>
                        </li>
                    </ul>
                    <a href="{{ route('register') }}" class="block text-center bg-gradient-to-r from-primary to-secondary text-white px-6 py-3 rounded-xl hover:shadow-glow transition-all font-semibold">
                        Start Free Trial
                    </a>
                </div>

                <!-- Gold Plan -->
                <div class="bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-lg border-2 border-gray-200 dark:border-gray-700">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Gold</h3>
                    <div class="mb-6">
                        <span class="text-4xl font-bold text-gray-900 dark:text-white">₹999</span>
                        <span class="text-gray-600 dark:text-gray-400">/month</span>
                    </div>
                    <ul class="space-y-4 mb-8">
                        <li class="flex items-start">
                            <svg class="w-6 h-6 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-gray-600 dark:text-gray-400">Everything in Silver</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-6 h-6 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-gray-600 dark:text-gray-400">Profile boost (5x visibility)</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-6 h-6 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-gray-600 dark:text-gray-400">Priority support</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-6 h-6 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-gray-600 dark:text-gray-400">Advanced filters</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-6 h-6 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-gray-600 dark:text-gray-400">Incognito mode</span>
                        </li>
                    </ul>
                    <a href="{{ route('register') }}" class="block text-center bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white px-6 py-3 rounded-xl hover:bg-gray-300 dark:hover:bg-gray-600 transition-all font-semibold">
                        Get Gold
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- FAQ Section -->
    <div class="py-24 bg-white dark:bg-gray-800" id="faq">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-4">Frequently Asked Questions</h2>
                <p class="text-xl text-gray-600 dark:text-gray-300">Everything you need to know</p>
            </div>

            <div class="space-y-6" x-data="{ openFaq: null }">
                <!-- FAQ 1 -->
                <div class="border-2 border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden">
                    <button @click="openFaq = openFaq === 1 ? null : 1" class="w-full px-6 py-5 text-left flex items-center justify-between bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors">
                        <span class="font-semibold text-gray-900 dark:text-white text-lg">How does the AI matching work?</span>
                        <svg class="w-6 h-6 text-gray-500 transform transition-transform" :class="{ 'rotate-180': openFaq === 1 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="openFaq === 1" x-collapse class="px-6 py-4 bg-gray-50 dark:bg-gray-750">
                        <p class="text-gray-600 dark:text-gray-400">Our AI analyzes multiple factors including personality traits, interests, values, education, location, and lifestyle preferences to calculate compatibility scores. The more you interact with the app, the better it gets at suggesting compatible matches.</p>
                    </div>
                </div>

                <!-- FAQ 2 -->
                <div class="border-2 border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden">
                    <button @click="openFaq = openFaq === 2 ? null : 2" class="w-full px-6 py-5 text-left flex items-center justify-between bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors">
                        <span class="font-semibold text-gray-900 dark:text-white text-lg">Is verification mandatory?</span>
                        <svg class="w-6 h-6 text-gray-500 transform transition-transform" :class="{ 'rotate-180': openFaq === 2 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="openFaq === 2" x-collapse class="px-6 py-4 bg-gray-50 dark:bg-gray-750">
                        <p class="text-gray-600 dark:text-gray-400">Phone verification is optional but highly recommended. Video verification gives you a verified badge that increases trust and match rates by 3x. You can skip verification but verified users are prioritized in search results.</p>
                    </div>
                </div>

                <!-- FAQ 3 -->
                <div class="border-2 border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden">
                    <button @click="openFaq = openFaq === 3 ? null : 3" class="w-full px-6 py-5 text-left flex items-center justify-between bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors">
                        <span class="font-semibold text-gray-900 dark:text-white text-lg">Can I use the app for free?</span>
                        <svg class="w-6 h-6 text-gray-500 transform transition-transform" :class="{ 'rotate-180': openFaq === 3 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="openFaq === 3" x-collapse class="px-6 py-4 bg-gray-50 dark:bg-gray-750">
                        <p class="text-gray-600 dark:text-gray-400">Yes! Our free plan includes 5 likes per day, basic matching, and chat with matches. You can upgrade to Silver or Gold for unlimited likes, video calls, and advanced features anytime.</p>
                    </div>
                </div>

                <!-- FAQ 4 -->
                <div class="border-2 border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden">
                    <button @click="openFaq = openFaq === 4 ? null : 4" class="w-full px-6 py-5 text-left flex items-center justify-between bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors">
                        <span class="font-semibold text-gray-900 dark:text-white text-lg">How is my data protected?</span>
                        <svg class="w-6 h-6 text-gray-500 transform transition-transform" :class="{ 'rotate-180': openFaq === 4 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="openFaq === 4" x-collapse class="px-6 py-4 bg-gray-50 dark:bg-gray-750">
                        <p class="text-gray-600 dark:text-gray-400">We use bank-grade encryption and are fully GDPR compliant. Your data is never sold to third parties. You can export or delete your data anytime. We also have security features like blocking, reporting, and profile privacy controls.</p>
                    </div>
                </div>

                <!-- FAQ 5 -->
                <div class="border-2 border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden">
                    <button @click="openFaq = openFaq === 5 ? null : 5" class="w-full px-6 py-5 text-left flex items-center justify-between bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors">
                        <span class="font-semibold text-gray-900 dark:text-white text-lg">What makes this different from other apps?</span>
                        <svg class="w-6 h-6 text-gray-500 transform transition-transform" :class="{ 'rotate-180': openFaq === 5 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="openFaq === 5" x-collapse class="px-6 py-4 bg-gray-50 dark:bg-gray-750">
                        <p class="text-gray-600 dark:text-gray-400">We focus on authenticity with video profiles, have the most accurate AI matching, interactive games to break the ice, real-time features like stories and video calls, and a strong verification system. We're built for Gen Z and millennials who value genuine connections.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="py-24 bg-gradient-to-br from-primary via-secondary to-accent relative overflow-hidden">
        <!-- Decorative Elements -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 left-0 w-96 h-96 bg-white rounded-full filter blur-3xl"></div>
            <div class="absolute bottom-0 right-0 w-96 h-96 bg-white rounded-full filter blur-3xl"></div>
        </div>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">Ready to Find Your Match?</h2>
            <p class="text-xl text-white/90 mb-8 max-w-2xl mx-auto">Join thousands of verified users finding meaningful connections every day. Your perfect match is waiting!</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register') }}" class="inline-block bg-white text-primary px-10 py-5 rounded-2xl hover:shadow-2xl transition-all duration-300 font-semibold text-lg transform hover:scale-105">
                    Create Free Account
                </a>
                <a href="{{ route('login') }}" class="inline-block bg-transparent border-2 border-white text-white px-10 py-5 rounded-2xl hover:bg-white hover:text-primary transition-all duration-300 font-semibold text-lg transform hover:scale-105">
                    Sign In
                </a>
            </div>
            <p class="text-white/80 text-sm mt-6">No credit card required • Free forever plan available</p>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-900 dark:bg-black text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8 mb-8">
                <!-- Company Info -->
                <div class="col-span-2 md:col-span-1">
                    <h3 class="text-2xl font-bold mb-4 text-gradient bg-gradient-to-r from-primary via-secondary to-accent bg-clip-text text-transparent">{{ config('app.name') }}</h3>
                    <p class="text-gray-400 mb-4">Modern matchmaking for the next generation. Find your perfect match today.</p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126.667.666 1.336 1.079 2.126 1.384.766.296 1.636.499 2.913.558C8.333 23.988 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384.666-.667 1.079-1.335 1.384-2.126.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913-.306-.789-.718-1.459-1.384-2.126C21.319 1.347 20.651.935 19.86.63c-.765-.297-1.636-.499-2.913-.558C15.667.012 15.26 0 12 0zm0 2.16c3.203 0 3.585.016 4.85.071 1.17.055 1.805.249 2.227.415.562.217.96.477 1.382.896.419.42.679.819.896 1.381.164.422.36 1.057.413 2.227.057 1.266.07 1.646.07 4.85s-.015 3.585-.074 4.85c-.061 1.17-.256 1.805-.421 2.227-.224.562-.479.96-.899 1.382-.419.419-.824.679-1.38.896-.42.164-1.065.36-2.235.413-1.274.057-1.649.07-4.859.07-3.211 0-3.586-.015-4.859-.074-1.171-.061-1.816-.256-2.236-.421-.569-.224-.96-.479-1.379-.899-.421-.419-.69-.824-.9-1.38-.165-.42-.359-1.065-.42-2.235-.045-1.26-.061-1.649-.061-4.844 0-3.196.016-3.586.061-4.861.061-1.17.255-1.814.42-2.234.21-.57.479-.96.9-1.381.419-.419.81-.689 1.379-.898.42-.166 1.051-.361 2.221-.421 1.275-.045 1.65-.06 4.859-.06l.045.03zm0 3.678c-3.405 0-6.162 2.76-6.162 6.162 0 3.405 2.76 6.162 6.162 6.162 3.405 0 6.162-2.76 6.162-6.162 0-3.405-2.76-6.162-6.162-6.162zM12 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm7.846-10.405c0 .795-.646 1.44-1.44 1.44-.795 0-1.44-.646-1.44-1.44 0-.794.646-1.439 1.44-1.439.793-.001 1.44.645 1.44 1.439z"/></svg>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h4 class="font-semibold text-lg mb-4">Company</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">About Us</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">How It Works</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Success Stories</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Blog</a></li>
                    </ul>
                </div>

                <!-- Support -->
                <div>
                    <h4 class="font-semibold text-lg mb-4">Support</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Help Center</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Safety Tips</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Contact Us</a></li>
                        <li><a href="#faq" class="text-gray-400 hover:text-white transition-colors">FAQs</a></li>
                    </ul>
                </div>

                <!-- Legal -->
                <div>
                    <h4 class="font-semibold text-lg mb-4">Legal</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('legal.privacy') }}" class="text-gray-400 hover:text-white transition-colors">Privacy Policy</a></li>
                        <li><a href="{{ route('legal.terms') }}" class="text-gray-400 hover:text-white transition-colors">Terms of Service</a></li>
                        <li><a href="{{ route('legal.cookies') }}" class="text-gray-400 hover:text-white transition-colors">Cookie Policy</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">GDPR</a></li>
                    </ul>
                </div>
            </div>

            <!-- Bottom Bar -->
            <div class="border-t border-gray-800 pt-8 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            </div>
        </div>
    </footer>
</div>
@endsection
