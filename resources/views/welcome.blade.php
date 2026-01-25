@extends('layouts.public')

@section('title', 'Find Your Perfect Match - Aadhi Matrimony')
@section('meta_description', 'The new way to find meaningful connections. Join the waitlist for Aadhi Matrimony - AI-powered matchmaking with video profiles. Launching in 2025!')
@section('meta_keywords', 'matrimony, matchmaking, AI matching, video profiles, dating, marriage, life partner, Indian matrimony, Aadhi Matrimony')

@section('content')
<div class="relative min-h-screen">
    <!-- Hero Section -->
    <section class="relative pt-20 pb-20 overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 bg-gradient-to-br from-taxi-cream via-white to-taxi-cream-dark">
            <div class="absolute inset-0 opacity-40">
                <div class="absolute top-20 left-10 w-72 h-72 bg-taxi-yellow/20 rounded-full blur-3xl animate-pulse"></div>
                <div class="absolute bottom-20 right-10 w-96 h-96 bg-taxi-yellow-dark/10 rounded-full blur-3xl animate-pulse" style="animation-delay: 1s;"></div>
            </div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-12 items-center pt-10">
                <!-- Text Content -->
                <div class="text-center lg:text-left">
                    <div class="inline-flex items-center px-4 py-2 bg-taxi-yellow/10 rounded-full text-taxi-yellow-dark text-sm font-semibold mb-6">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        🎉 Launching Soon - Join the Waitlist!
                    </div>

                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 mb-6 leading-tight">
                        Find Your
                        <span class="bg-gradient-to-r from-taxi-yellow to-taxi-yellow-dark bg-clip-text text-transparent">Perfect</span>
                        <br>Life Partner
                    </h1>

                    <p class="text-lg text-gray-600 mb-8 max-w-xl mx-auto lg:mx-0">
                        The new way to find meaningful connections. Join thousands of singles discovering love through AI-powered matching and authentic video profiles.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center bg-gradient-to-r from-taxi-yellow to-taxi-yellow-dark text-gray-900 px-8 py-4 rounded-full font-semibold text-lg hover:shadow-xl hover:shadow-taxi-yellow/30 transition-all duration-300 transform hover:-translate-y-1">
                            Start Free Trial
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                            </svg>
                        </a>
                        <a href="{{ route('how-it-works') }}" class="inline-flex items-center justify-center bg-white text-gray-700 px-8 py-4 rounded-full font-semibold text-lg border-2 border-gray-200 hover:border-taxi-yellow transition-all duration-300">
                            Learn More
                        </a>
                    </div>

                    <div class="flex flex-wrap justify-center lg:justify-start gap-8 mt-10 pt-10 border-t border-gray-200">
                        <div class="text-center lg:text-left">
                            <div class="text-2xl font-bold text-gray-900">Free</div>
                            <div class="text-sm text-gray-500">To Join</div>
                        </div>
                        <div class="text-center lg:text-left">
                            <div class="text-2xl font-bold text-gray-900">AI</div>
                            <div class="text-sm text-gray-500">Matching</div>
                        </div>
                        <div class="text-center lg:text-left">
                            <div class="text-2xl font-bold text-gray-900">100%</div>
                            <div class="text-sm text-gray-500">Verified</div>
                        </div>
                        <div class="text-center lg:text-left">
                            <div class="text-2xl font-bold text-gray-900">New!</div>
                            <div class="text-sm text-gray-500">Video Profiles</div>
                        </div>
                    </div>
                </div>

                <!-- Hero Image/Visual -->
                <div class="relative">
                    <div class="relative w-full max-w-md mx-auto">
                        <!-- Main Card -->
                        <div class="bg-white rounded-3xl shadow-2xl p-6 transform rotate-2 hover:rotate-0 transition-transform duration-500">
                            <div class="flex items-center space-x-4 mb-4">
                                <div class="w-16 h-16 bg-gradient-to-br from-taxi-yellow to-taxi-yellow-dark rounded-2xl flex items-center justify-center text-white text-2xl font-bold">RS</div>
                                <div>
                                    <div class="font-semibold text-gray-900">Rahul & Sneha</div>
                                    <div class="text-sm text-gray-500">Mumbai</div>
                                    <div class="flex items-center mt-1">
                                        <div class="flex text-yellow-400">
                                            @for($i = 0; $i < 5; $i++)
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <p class="text-gray-600 italic text-sm">"Found my soulmate within 2 months! The video profiles helped me connect with genuine people."</p>
                        </div>

                        <!-- Floating Badge -->
                        <div class="absolute -top-4 -right-4 bg-gradient-to-r from-green-500 to-green-600 text-white px-4 py-2 rounded-full text-sm font-semibold shadow-lg animate-bounce">
                            ★ Newly Matched
                        </div>

                        <!-- Floating Elements -->
                        <div class="absolute -left-8 top-1/2 -translate-y-1/2 bg-white rounded-2xl shadow-lg p-4 hidden lg:block">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-sm font-semibold text-gray-900">Video Call</div>
                                    <div class="text-xs text-gray-500">Active now</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Why Choose Us?</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">Modern features for modern relationships. Everything you need to find your perfect match.</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="group p-8 bg-gradient-to-br from-taxi-cream to-white rounded-3xl hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="w-16 h-16 bg-gradient-to-br from-taxi-yellow to-taxi-yellow-dark rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Video Profiles</h3>
                    <p class="text-gray-600">See real people with 30-second video introductions. No more studio photos - just authentic you!</p>
                </div>

                <!-- Feature 2 -->
                <div class="group p-8 bg-gradient-to-br from-gray-50 to-white rounded-3xl hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">AI Matching</h3>
                    <p class="text-gray-600">Smart compatibility scores based on personality, interests, and values. Find matches that truly click.</p>
                </div>

                <!-- Feature 3 -->
                <div class="group p-8 bg-gradient-to-br from-gray-50 to-white rounded-3xl hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Safe & Verified</h3>
                    <p class="text-gray-600">Video verification, phone OTP, and social proof. Connect with confidence on our secure platform.</p>
                </div>

                <!-- Feature 4 -->
                <div class="group p-8 bg-gradient-to-br from-gray-50 to-white rounded-3xl hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Real-time Chat</h3>
                    <p class="text-gray-600">Instant messaging with voice notes, icebreakers, and video calls. Connect meaningfully with matches.</p>
                </div>

                <!-- Feature 5 -->
                <div class="group p-8 bg-gradient-to-br from-gray-50 to-white rounded-3xl hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Interactive Games</h3>
                    <p class="text-gray-600">Break the ice with fun compatibility games and quizzes. Discover shared interests naturally.</p>
                </div>

                <!-- Feature 6 -->
                <div class="group p-8 bg-gradient-to-br from-gray-50 to-white rounded-3xl hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="w-16 h-16 bg-gradient-to-br from-pink-500 to-pink-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Profile Analytics</h3>
                    <p class="text-gray-600">Track who viewed your profile, engagement metrics, and optimize your presence with insights.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section id="how-it-works" class="py-20 bg-gradient-to-br from-taxi-cream to-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">How It Works</h2>
                <p class="text-lg text-gray-600">Your journey to finding the perfect match in 4 simple steps</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Step 1 -->
                <div class="relative text-center">
                    <div class="w-20 h-20 bg-gradient-to-br from-taxi-yellow to-taxi-yellow-dark rounded-full flex items-center justify-center mx-auto mb-6 text-white text-3xl font-bold shadow-xl">1</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Create Profile</h3>
                    <p class="text-gray-600">Sign up and complete your profile with photos and video introduction</p>
                    <div class="hidden lg:block absolute top-10 left-full w-full h-0.5 bg-gradient-to-r from-taxi-yellow to-taxi-yellow-dark opacity-20 -translate-x-1/2"></div>
                </div>

                <!-- Step 2 -->
                <div class="relative text-center">
                    <div class="w-20 h-20 bg-gradient-to-br from-purple-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-6 text-white text-3xl font-bold shadow-xl">2</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Get Verified</h3>
                    <p class="text-gray-600">Complete phone and video verification for trustworthy connections</p>
                    <div class="hidden lg:block absolute top-10 left-full w-full h-0.5 bg-gradient-to-r from-purple-500 to-purple-600 opacity-20 -translate-x-1/2"></div>
                </div>

                <!-- Step 3 -->
                <div class="relative text-center">
                    <div class="w-20 h-20 bg-gradient-to-br from-green-500 to-green-600 rounded-full flex items-center justify-center mx-auto mb-6 text-white text-3xl font-bold shadow-xl">3</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Discover Matches</h3>
                    <p class="text-gray-600">Browse AI-matched profiles and connect with compatible partners</p>
                    <div class="hidden lg:block absolute top-10 left-full w-full h-0.5 bg-gradient-to-r from-green-500 to-green-600 opacity-20 -translate-x-1/2"></div>
                </div>

                <!-- Step 4 -->
                <div class="text-center">
                    <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center mx-auto mb-6 text-white text-3xl font-bold shadow-xl">4</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Start Dating</h3>
                    <p class="text-gray-600">Chat, video call, and plan your first date with your perfect match</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Success Stories Section -->
    <section id="stories" class="py-20 bg-gradient-to-br from-taxi-cream to-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Success Stories</h2>
                <p class="text-lg text-gray-600">Join thousands of couples who found their perfect match</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Story 1 -->
                <div class="bg-white p-8 rounded-3xl shadow-lg border border-taxi-border">
                    <div class="flex items-center mb-4">
                        <div class="flex text-yellow-400">
                            @for($i = 0; $i < 5; $i++)
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            @endfor
                        </div>
                    </div>
                    <p class="text-gray-700 mb-6 italic">"Can't wait for this app to launch! The video profile concept is exactly what modern dating needs."</p>
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-br from-taxi-yellow to-taxi-yellow-dark rounded-full flex items-center justify-center text-white font-bold">A</div>
                        <div class="ml-4">
                            <div class="font-semibold text-gray-900">Priya S.</div>
                            <div class="text-sm text-gray-500">Waiting since Dec 2024</div>
                        </div>
                    </div>
                </div>

                <!-- Story 2 -->
                <div class="bg-white p-8 rounded-3xl shadow-lg border border-taxi-border">
                    <div class="flex items-center mb-4">
                        <div class="flex text-yellow-400">
                            @for($i = 0; $i < 5; $i++)
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            @endfor
                        </div>
                    </div>
                    <p class="text-gray-700 mb-6 italic">"Finally, a matrimony app that understands Gen Z! The verification process gives me confidence."</p>
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold">R</div>
                        <div class="ml-4">
                            <div class="font-semibold text-gray-900">Rahul K.</div>
                            <div class="text-sm text-gray-500">Waiting since Jan 2025</div>
                        </div>
                    </div>
                </div>

                <!-- Story 3 -->
                <div class="bg-white p-8 rounded-3xl shadow-lg border border-taxi-border">
                    <div class="flex items-center mb-4">
                        <div class="flex text-yellow-400">
                            @for($i = 0; $i < 5; $i++)
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            @endfor
                        </div>
                    </div>
                    <p class="text-gray-700 mb-6 italic">"The AI matching sounds promising. Excited to meet like-minded people when it launches!"</p>
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-full flex items-center justify-center text-white font-bold">M</div>
                        <div class="ml-4">
                            <div class="font-semibold text-gray-900">Maya T.</div>
                            <div class="text-sm text-gray-500">Waiting since Jan 2025</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="pricing" class="py-20 bg-gradient-to-br from-gray-50 to-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Launch Special - Coming Soon!</h2>
                <p class="text-lg text-gray-600">Sign up now and get exclusive launch benefits</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8 max-w-5xl mx-auto">
                <!-- Free Plan -->
                <div class="bg-white p-8 rounded-3xl shadow-lg border-2 border-taxi-yellow">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Free</h3>
                    <div class="mb-6">
                        <span class="text-4xl font-bold text-gray-900">₹0</span>
                        <span class="text-gray-500">/forever</span>
                    </div>
                    <p class="text-sm text-gray-500 mb-6">Launch Special - Limited Time!</p>
                    <ul class="space-y-4 mb-8">
                        <li class="flex items-center text-gray-600">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Create your profile
                        </li>
                        <li class="flex items-center text-gray-600">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Add video introduction
                        </li>
                        <li class="flex items-center text-gray-600">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Browse matches
                        </li>
                        <li class="flex items-center text-gray-600">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            10 likes per day
                        </li>
                    </ul>
                    <a href="{{ route('register') }}" class="block text-center bg-taxi-yellow text-gray-900 py-3 rounded-xl font-semibold hover:shadow-lg transition-all">Join Waitlist</a>
                </div>

                <!-- Silver Plan -->
                <div class="bg-white p-8 rounded-3xl shadow-xl border-2 border-taxi-yellow md:transform md:scale-105 md:relative z-10">
                    <div class="absolute -top-4 left-1/2 -translate-x-1/2 bg-gradient-to-r from-taxi-yellow to-taxi-yellow-dark text-white text-xs font-semibold px-4 py-1 rounded-full">EARLY BIRD</div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Premium</h3>
                    <div class="mb-6">
                        <span class="text-4xl font-bold text-taxi-yellow-dark">₹799</span>
                        <span class="text-gray-500">/month</span>
                    </div>
                    <p class="text-sm text-gray-500 mb-6">50% off for early subscribers!</p>
                    <ul class="space-y-4 mb-8">
                        <li class="flex items-center text-gray-600">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Unlimited likes
                        </li>
                        <li class="flex items-center text-gray-600">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Advanced AI matching
                        </li>
                        <li class="flex items-center text-gray-600">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            See who liked you
                        </li>
                        <li class="flex items-center text-gray-600">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Video calls included
                        </li>
                        <li class="flex items-center text-gray-600">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Profile boost (launch)
                        </li>
                    </ul>
                    <a href="{{ route('register') }}" class="block text-center bg-gradient-to-r from-taxi-yellow to-taxi-yellow-dark text-gray-900 py-3 rounded-xl font-semibold hover:shadow-lg transition-all">Get Early Access</a>
                </div>

                <!-- Gold Plan -->
                <div class="bg-white p-8 rounded-3xl shadow-lg border-2 border-taxi-border">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Family</h3>
                    <div class="mb-6">
                        <span class="text-4xl font-bold text-gray-900">₹1,499</span>
                        <span class="text-gray-500">/month</span>
                    </div>
                    <p class="text-sm text-gray-500 mb-6">For serious match seekers</p>
                    <ul class="space-y-4 mb-8">
                        <li class="flex items-center text-gray-600">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Everything in Premium
                        </li>
                        <li class="flex items-center text-gray-600">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Priority support
                        </li>
                        <li class="flex items-center text-gray-600">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Profile boost (5x visibility)
                        </li>
                        <li class="flex items-center text-gray-600">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Advanced filters
                        </li>
                        <li class="flex items-center text-gray-600">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Family verification
                        </li>
                    </ul>
                    <a href="{{ route('register') }}" class="block text-center bg-gray-100 text-gray-900 py-3 rounded-xl font-semibold hover:bg-gray-200 transition-all">Join Waitlist</a>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-r from-taxi-yellow via-taxi-yellow-light to-taxi-yellow-dark">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Ready to Find Your Match?</h2>
            <p class="text-lg text-gray-800 mb-8">Be among the first to experience modern matchmaking. Sign up now for early access!</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register') }}" class="inline-flex items-center justify-center bg-gray-900 text-white px-8 py-4 rounded-full font-semibold text-lg hover:bg-gray-800 transition-all transform hover:-translate-y-1">
                    Create Free Account
                </a>
                <a href="{{ route('login') }}" class="inline-flex items-center justify-center bg-white text-gray-900 px-8 py-4 rounded-full font-semibold text-lg border-2 border-gray-200 hover:border-gray-300 transition-all">
                    Sign In
                </a>
            </div>
             <p class="text-gray-700 text-sm mt-6">Launching soon in your city • Limited spots available</p>
        </div>
    </section>
</div>
@endsection
