@extends('layouts.public')

@section('title', 'Features - Aadhi Matrimony')
@section('meta_description', 'Discover the modern features of Aadhi Matrimony - AI-powered matching, video profiles, verified connections, and real-time messaging.')

@section('content')
<!-- Hero Section -->
<section class="py-20 bg-gradient-to-br from-taxi-cream via-white to-taxi-cream-dark">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 mb-6">
            Powerful Features for
            <span class="bg-gradient-to-r from-taxi-yellow to-taxi-yellow-dark bg-clip-text text-transparent">Modern Dating</span>
        </h1>
        <p class="text-xl text-gray-600 max-w-3xl mx-auto mb-10">
            Everything you need to find your perfect match, all in one place. Our innovative features make dating simpler, safer, and more fun.
        </p>
    </div>
</section>

<!-- Video Profiles Section -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <div class="order-2 lg:order-1">
                <div class="bg-gradient-to-br from-taxi-yellow/20 to-taxi-yellow-dark/10 rounded-3xl p-8 aspect-video flex items-center justify-center">
                    <div class="w-24 h-24 bg-taxi-yellow rounded-full flex items-center justify-center">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="order-1 lg:order-2">
                <div class="inline-flex items-center px-4 py-2 bg-taxi-yellow/10 rounded-full text-taxi-yellow-dark text-sm font-semibold mb-4">
                    <span class="w-2 h-2 bg-taxi-yellow rounded-full mr-2"></span>
                    Video Profiles
                </div>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    See the Real Person, Not Just Photos
                </h2>
                <p class="text-lg text-gray-600 mb-6">
                    Video profiles let you see the real personality of potential matches. A 30-second video introduction shows mannerisms, smile, and genuine character - making connections more authentic.
                </p>
                <ul class="space-y-3">
                    <li class="flex items-center text-gray-700">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        30-second video introductions
                    </li>
                    <li class="flex items-center text-gray-700">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Authentic personality showcase
                    </li>
                    <li class="flex items-center text-gray-700">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Higher quality matches
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- AI Matching Section -->
<section class="py-20 bg-gradient-to-br from-gray-50 to-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <div>
                <div class="inline-flex items-center px-4 py-2 bg-purple-100 rounded-full text-purple-600 text-sm font-semibold mb-4">
                    <span class="w-2 h-2 bg-purple-500 rounded-full mr-2"></span>
                    AI-Powered Matching
                </div>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    Smart Matching That Actually Works
                </h2>
                <p class="text-lg text-gray-600 mb-6">
                    Our advanced AI analyzes multiple dimensions of compatibility including personality traits, interests, values, and relationship goals to suggest matches you'll genuinely connect with.
                </p>
                <ul class="space-y-3">
                    <li class="flex items-center text-gray-700">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Compatibility score (1-100%)
                    </li>
                    <li class="flex items-center text-gray-700">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Interest-based matching
                    </li>
                    <li class="flex items-center text-gray-700">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Continuous learning algorithm
                    </li>
                </ul>
            </div>
            <div>
                <div class="bg-white rounded-3xl shadow-xl p-8">
                    <div class="space-y-6">
                        <div>
                            <div class="flex justify-between text-sm text-gray-600 mb-2">
                                <span>Compatibility Score</span>
                                <span class="font-semibold text-taxi-yellow-dark">92%</span>
                            </div>
                            <div class="h-3 bg-gray-200 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-taxi-yellow to-taxi-yellow-dark rounded-full" style="width: 92%"></div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-xl">
                            <div class="w-16 h-16 bg-gradient-to-br from-taxi-yellow to-taxi-yellow-dark rounded-full flex items-center justify-center text-white text-xl font-bold">A</div>
                            <div>
                                <div class="font-semibold text-gray-900">Ananya, 26</div>
                                <div class="text-sm text-gray-500">Software Engineer, Bangalore</div>
                            </div>
                        </div>
                        <div class="grid grid-cols-3 gap-4 text-center">
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <div class="text-2xl font-bold text-gray-900">89%</div>
                                <div class="text-xs text-gray-500">Interests</div>
                            </div>
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <div class="text-2xl font-bold text-gray-900">95%</div>
                                <div class="text-xs text-gray-500">Values</div>
                            </div>
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <div class="text-2xl font-bold text-gray-900">91%</div>
                                <div class="text-xs text-gray-500">Goals</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Verification Section -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <div class="inline-flex items-center px-4 py-2 bg-green-100 rounded-full text-green-600 text-sm font-semibold mb-4">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                100% Verified Profiles
            </div>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                Date with Confidence
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Our multi-step verification process ensures you're connecting with real, genuine people.
            </p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Phone Verification -->
            <div class="text-center p-6 bg-gradient-to-br from-gray-50 to-white rounded-3xl">
                <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Phone Verification</h3>
                <p class="text-gray-600">Every profile is verified via OTP to ensure a valid phone number.</p>
            </div>

            <!-- Video Verification -->
            <div class="text-center p-6 bg-gradient-to-br from-gray-50 to-white rounded-3xl">
                <div class="w-16 h-16 bg-purple-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Video Selfie</h3>
                <p class="text-gray-600">Liveness detection ensures the person matches their photos.</p>
            </div>

            <!-- ID Verification -->
            <div class="text-center p-6 bg-gradient-to-br from-gray-50 to-white rounded-3xl">
                <div class="w-16 h-16 bg-green-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">ID Verification</h3>
                <p class="text-gray-600">Optional government ID verification for premium users.</p>
            </div>

            <!-- Social Proof -->
            <div class="text-center p-6 bg-gradient-to-br from-gray-50 to-white rounded-3xl">
                <div class="w-16 h-16 bg-orange-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Social Links</h3>
                <p class="text-gray-600">Connect social accounts to increase trust score.</p>
            </div>
        </div>
    </div>
</section>

<!-- Real-time Chat Section -->
<section class="py-20 bg-gradient-to-br from-taxi-cream to-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <div class="order-2 lg:order-1">
                <div class="bg-white rounded-3xl shadow-xl p-6 max-w-md mx-auto">
                    <div class="space-y-4">
                        <div class="flex items-start space-x-3">
                            <div class="w-10 h-10 bg-taxi-yellow rounded-full flex items-center justify-center text-white font-bold text-sm">A</div>
                            <div class="bg-gray-100 rounded-2xl rounded-tl-none p-3 max-w-xs">
                                <p class="text-gray-700 text-sm">Hey! I saw your profile and we have so many common interests! 🎉</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3 flex-row-reverse">
                            <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center text-gray-600 font-bold text-sm">Y</div>
                            <div class="bg-taxi-yellow/20 rounded-2xl rounded-tr-none p-3 max-w-xs">
                                <p class="text-gray-700 text-sm">Hi! Yes, I noticed we both love hiking and photography! 📸</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-center py-2">
                            <span class="text-xs text-gray-400 bg-gray-100 px-3 py-1 rounded-full">Use icebreakers to start conversations!</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="order-1 lg:order-2">
                <div class="inline-flex items-center px-4 py-2 bg-blue-100 rounded-full text-blue-600 text-sm font-semibold mb-4">
                    <span class="w-2 h-2 bg-blue-500 rounded-full mr-2"></span>
                    Real-time Chat
                </div>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    Conversations That Flow Naturally
                </h2>
                <p class="text-lg text-gray-600 mb-6">
                    Our real-time messaging makes it easy to connect instantly. Send text, voice messages, and even use fun icebreaker questions to break the silence.
                </p>
                <ul class="space-y-3">
                    <li class="flex items-center text-gray-700">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Instant messaging with read receipts
                    </li>
                    <li class="flex items-center text-gray-700">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Voice messages up to 60 seconds
                    </li>
                    <li class="flex items-center text-gray-700">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        135+ icebreaker questions
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Video Calls Section -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <div class="inline-flex items-center px-4 py-2 bg-pink-100 rounded-full text-pink-600 text-sm font-semibold mb-4">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                </svg>
                Video & Voice Calls
            </div>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                Take Your Connection to the Next Level
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Video and voice calls let you hear the voice and see the smile of your potential match before meeting in person.
            </p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            <div class="bg-gradient-to-br from-gray-50 to-white rounded-3xl p-8 text-center">
                <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">High-Quality Video</h3>
                <p class="text-gray-600">Crystal clear video calls for an immersive experience.</p>
            </div>
            <div class="bg-gradient-to-br from-gray-50 to-white rounded-3xl p-8 text-center">
                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Voice Calls</h3>
                <p class="text-gray-600">Quick voice calls when video isn't convenient.</p>
            </div>
            <div class="bg-gradient-to-br from-gray-50 to-white rounded-3xl p-8 text-center">
                <div class="w-20 h-20 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Call History</h3>
                <p class="text-gray-600">Track all your calls and never miss a connection.</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-gradient-to-r from-taxi-yellow via-taxi-yellow-light to-taxi-yellow-dark">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
            Ready to Experience These Features?
        </h2>
        <p class="text-lg text-gray-800 mb-8">
            Join thousands of happy couples who found their perfect match on Aadhi Matrimony.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('register') }}" class="inline-flex items-center justify-center bg-gray-900 text-white px-8 py-4 rounded-full font-semibold text-lg hover:bg-gray-800 transition-all transform hover:-translate-y-1">
                Create Free Account
            </a>
            <a href="{{ route('pricing') }}" class="inline-flex items-center justify-center bg-white text-gray-900 px-8 py-4 rounded-full font-semibold text-lg border-2 border-gray-200 hover:border-gray-300 transition-all">
                View Pricing
            </a>
        </div>
    </div>
</section>
@endsection
