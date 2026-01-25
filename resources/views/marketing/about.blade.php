@extends('layouts.public')

@section('title', 'About Us - Aadhi Matrimony')
@section('meta_description', 'Learn about Aadhi Matrimony - our mission to help people find meaningful connections through technology and authentic dating.')

@section('content')
<!-- Hero Section -->
<section class="py-20 bg-gradient-to-br from-taxi-cream via-white to-taxi-cream-dark">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 mb-6">
            Our Mission to Transform
            <span class="bg-gradient-to-r from-taxi-yellow to-taxi-yellow-dark bg-clip-text text-transparent">Modern Dating</span>
        </h1>
        <p class="text-xl text-gray-600 max-w-3xl mx-auto mb-10">
            We're building the future of matrimony - where technology meets genuine human connections, and finding your perfect match is simple, safe, and fun.
        </p>
    </div>
</section>

<!-- Our Story Section -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <div>
                <div class="inline-flex items-center px-4 py-2 bg-taxi-yellow/10 rounded-full text-taxi-yellow-dark text-sm font-semibold mb-4">
                    <span class="w-2 h-2 bg-taxi-yellow rounded-full mr-2"></span>
                    Our Story
                </div>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    Born from a Simple Idea
                </h2>
                <p class="text-lg text-gray-600 mb-6">
                    Aadhi Matrimony was founded in 2024 with a clear mission: to make finding a life partner as simple and enjoyable as possible. We believed that technology could help people connect more authentically, not less.
                </p>
                <p class="text-lg text-gray-600 mb-6">
                    Frustrated by superficial dating apps and outdated matrimony services, our founders set out to create something different - a platform that combines the best of modern technology with the timeless values of meaningful relationships.
                </p>
                <p class="text-lg text-gray-600">
                    Today, Aadhi Matrimony is helping thousands of people find their perfect match every day. And we're just getting started.
                </p>
            </div>
            <div>
                <div class="bg-gradient-to-br from-taxi-yellow/20 to-taxi-yellow-dark/10 rounded-3xl p-12 aspect-square flex items-center justify-center">
                    <div class="text-center">
                        <div class="text-6xl mb-4">💑</div>
                        <div class="text-2xl font-bold text-gray-900">5000+</div>
                        <div class="text-gray-600">Happy Couples</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Values Section -->
<section class="py-20 bg-gradient-to-br from-gray-50 to-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                Our Core Values
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                These principles guide everything we do at Aadhi Matrimony.
            </p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="text-center p-6">
                <div class="w-16 h-16 bg-taxi-yellow/20 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-taxi-yellow-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Authenticity</h3>
                <p class="text-gray-600">We believe in real connections, not curated personas. Our verification features ensure you're meeting real people.</p>
            </div>

            <div class="text-center p-6">
                <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Safety First</h3>
                <p class="text-gray-600">Your safety is our top priority. We've built comprehensive safety features to protect you throughout your journey.</p>
            </div>

            <div class="text-center p-6">
                <div class="w-16 h-16 bg-purple-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Innovation</h3>
                <p class="text-gray-600">We continuously push boundaries to improve your dating experience with cutting-edge technology.</p>
            </div>

            <div class="text-center p-6">
                <div class="w-16 h-16 bg-green-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Community</h3>
                <p class="text-gray-600">We're building a supportive community where everyone can find their perfect match with dignity and respect.</p>
            </div>
        </div>
    </div>
</section>

<!-- Team Section -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                Meet Our Team
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                A diverse team of passionate individuals working together to transform modern dating.
            </p>
        </div>

        <div class="grid md:grid-cols-3 gap-8 max-w-4xl mx-auto">
            <div class="text-center">
                <div class="w-32 h-32 bg-gradient-to-br from-taxi-yellow to-taxi-yellow-dark rounded-full mx-auto mb-4 flex items-center justify-center text-white text-3xl font-bold">
                    S
                </div>
                <h3 class="text-xl font-bold text-gray-900">Suresh Kumar</h3>
                <p class="text-taxi-yellow-dark font-medium">Founder & CEO</p>
                <p class="text-gray-500 text-sm mt-2">Ex-IIT, 15+ years in tech</p>
            </div>

            <div class="text-center">
                <div class="w-32 h-32 bg-gradient-to-br from-purple-500 to-purple-600 rounded-full mx-auto mb-4 flex items-center justify-center text-white text-3xl font-bold">
                    P
                </div>
                <h3 class="text-xl font-bold text-gray-900">Priya Sharma</h3>
                <p class="text-purple-600 font-medium">Head of Product</p>
                <p class="text-gray-500 text-sm mt-2">Ex-Flipkart, UX expert</p>
            </div>

            <div class="text-center">
                <div class="w-32 h-32 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full mx-auto mb-4 flex items-center justify-center text-white text-3xl font-bold">
                    R
                </div>
                <h3 class="text-xl font-bold text-gray-900">Rahul Menon</h3>
                <p class="text-blue-600 font-medium">CTO</p>
                <p class="text-gray-500 text-sm mt-2">AI/ML Specialist</p>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-20 bg-gradient-to-br from-taxi-cream to-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid md:grid-cols-4 gap-8 text-center">
            <div>
                <div class="text-4xl md:text-5xl font-bold text-taxi-yellow-dark mb-2">500K+</div>
                <div class="text-gray-600">Registered Users</div>
            </div>
            <div>
                <div class="text-4xl md:text-5xl font-bold text-taxi-yellow-dark mb-2">50K+</div>
                <div class="text-gray-600">Daily Active Users</div>
            </div>
            <div>
                <div class="text-4xl md:text-5xl font-bold text-taxi-yellow-dark mb-2">5000+</div>
                <div class="text-gray-600">Success Stories</div>
            </div>
            <div>
                <div class="text-4xl md:text-5xl font-bold text-taxi-yellow-dark mb-2">95%</div>
                <div class="text-gray-600">User Satisfaction</div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-gradient-to-r from-taxi-yellow via-taxi-yellow-light to-taxi-yellow-dark">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
            Join Our Community
        </h2>
        <p class="text-lg text-gray-800 mb-8">
            Be part of the fastest-growing matrimony platform in India.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('register') }}" class="inline-flex items-center justify-center bg-gray-900 text-white px-8 py-4 rounded-full font-semibold text-lg hover:bg-gray-800 transition-all transform hover:-translate-y-1">
                Create Free Account
            </a>
            <a href="{{ route('contact') }}" class="inline-flex items-center justify-center bg-white text-gray-900 px-8 py-4 rounded-full font-semibold text-lg border-2 border-gray-200 hover:border-gray-300 transition-all">
                Contact Us
            </a>
        </div>
    </div>
</section>
@endsection
