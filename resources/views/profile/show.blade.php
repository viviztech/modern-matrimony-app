@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @php
            $user = request()->has('user')
                ? \App\Models\User::with(['profile', 'photos', 'primaryPhoto'])->find(request('user'))
                : auth()->user()->load(['profile', 'photos', 'primaryPhoto']);

            $isOwnProfile = $user->id === auth()->id();
        @endphp

        @if(!$user)
            <div class="text-center py-12">
                <p class="text-gray-600 dark:text-gray-400">User not found</p>
                <a href="{{ route('discover') }}" class="text-primary hover:underline mt-4 inline-block">Back to Discover</a>
            </div>
        @else
            <!-- Profile Header -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden mb-6">
                <div class="relative h-64 bg-gradient-to-r from-primary to-secondary">
                    @if($user->primaryPhoto)
                        <img src="{{ $user->primaryPhoto->url }}" alt="{{ $user->name }}" class="w-full h-full object-cover opacity-40">
                    @endif
                </div>

                <div class="relative px-6 pb-6">
                    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between -mt-20">
                        <div class="flex items-end gap-4">
                            @if($user->primaryPhoto)
                                <img src="{{ $user->primaryPhoto->url }}"
                                     alt="{{ $user->name }}"
                                     class="w-32 h-32 rounded-2xl object-cover border-4 border-white dark:border-gray-800 shadow-xl">
                            @else
                                <div class="w-32 h-32 rounded-2xl bg-gradient-to-br from-primary to-secondary flex items-center justify-center text-white text-4xl font-bold border-4 border-white dark:border-gray-800 shadow-xl">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                            @endif

                            <div class="mb-2">
                                <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                    {{ $user->name }}
                                    @if($user->hasVerifiedEmail())
                                        <svg class="w-6 h-6 text-primary" fill="currentColor" viewBox="0 0 20 20" title="Email Verified">
                                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                    @endif
                                    @if($user->hasVerifiedPhone())
                                        <svg class="w-6 h-6 text-green-500" fill="currentColor" viewBox="0 0 20 20" title="Phone Verified">
                                            <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                                            <path d="M16.707 3.293a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0l-2-2a1 1 0 011.414-1.414L11 7.586l4.293-4.293a1 1 0 011.414 0z" />
                                        </svg>
                                    @endif
                                    @if($user->hasVerifiedVideo())
                                        <svg class="w-6 h-6 text-blue-500" fill="currentColor" viewBox="0 0 20 20" title="Video Verified">
                                            <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zm12.553 1.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z" />
                                            <path d="M16 3l-4 4-2-2-4 4" stroke="white" stroke-width="1.5" fill="none" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    @endif
                                    @if($user->hasLinkedInVerified())
                                        <svg class="w-6 h-6 text-blue-700" fill="currentColor" viewBox="0 0 24 24" title="LinkedIn Verified">
                                            <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                            <circle cx="19" cy="5" r="4" fill="white"/>
                                            <path d="M19 3l-2 2-1-1" stroke="#0077B5" stroke-width="1.5" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    @endif
                                    @if($user->hasInstagramVerified())
                                        <svg class="w-6 h-6 text-pink-600" fill="currentColor" viewBox="0 0 24 24" title="Instagram Verified">
                                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                            <circle cx="19" cy="5" r="4" fill="white"/>
                                            <path d="M19 3l-2 2-1-1" stroke="#E4405F" stroke-width="1.5" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    @endif
                                </h1>
                                @if($user->age)
                                    <p class="text-gray-600 dark:text-gray-400">{{ $user->age }} years old</p>
                                @endif
                            </div>
                        </div>

                        @if($isOwnProfile)
                            <div class="mt-4 sm:mt-0 flex gap-2">
                                <a href="{{ route('profile.edit') }}" class="bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                                    Edit Profile
                                </a>
                            </div>
                        @else
                            <div class="mt-4 sm:mt-0 flex gap-2">
                                <a href="{{ route('messages.create', $user) }}" class="bg-gradient-to-r from-primary to-secondary text-white px-6 py-2 rounded-lg hover:shadow-glow transition-all">
                                    Message
                                </a>
                            </div>
                        @endif
                    </div>

                    <!-- Quick Info -->
                    <div class="mt-6 grid grid-cols-2 md:grid-cols-4 gap-4">
                        @if($user->profile)
                            @if($user->profile->occupation)
                                <div class="text-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Occupation</p>
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $user->profile->occupation }}</p>
                                </div>
                            @endif
                            @if($user->profile->education)
                                <div class="text-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Education</p>
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $user->profile->education }}</p>
                                </div>
                            @endif
                            @if($user->city)
                                <div class="text-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Location</p>
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $user->city }}</p>
                                </div>
                            @endif
                            @if($user->profile->height)
                                <div class="text-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Height</p>
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $user->profile->height }} cm</p>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>

            <!-- About Section -->
            @if($user->profile && $user->profile->about)
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">About</h2>
                    <p class="text-gray-700 dark:text-gray-300 leading-relaxed">{{ $user->profile->about }}</p>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Basic Details -->
                @if($user->profile)
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Basic Details</h2>
                        <dl class="space-y-3">
                            @if($user->profile->marital_status)
                                <div class="flex justify-between">
                                    <dt class="text-gray-600 dark:text-gray-400">Marital Status</dt>
                                    <dd class="font-semibold text-gray-900 dark:text-white">{{ ucwords(str_replace('_', ' ', $user->profile->marital_status)) }}</dd>
                                </div>
                            @endif
                            @if($user->profile->religion)
                                <div class="flex justify-between">
                                    <dt class="text-gray-600 dark:text-gray-400">Religion</dt>
                                    <dd class="font-semibold text-gray-900 dark:text-white">{{ $user->profile->religion }}</dd>
                                </div>
                            @endif
                            @if($user->profile->caste)
                                <div class="flex justify-between">
                                    <dt class="text-gray-600 dark:text-gray-400">Community</dt>
                                    <dd class="font-semibold text-gray-900 dark:text-white">{{ $user->profile->caste }}</dd>
                                </div>
                            @endif
                            @if($user->profile->weight)
                                <div class="flex justify-between">
                                    <dt class="text-gray-600 dark:text-gray-400">Weight</dt>
                                    <dd class="font-semibold text-gray-900 dark:text-white">{{ $user->profile->weight }} kg</dd>
                                </div>
                            @endif
                        </dl>
                    </div>
                @endif

                <!-- Professional Details -->
                @if($user->profile)
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Professional Details</h2>
                        <dl class="space-y-3">
                            @if($user->profile->occupation)
                                <div class="flex justify-between">
                                    <dt class="text-gray-600 dark:text-gray-400">Occupation</dt>
                                    <dd class="font-semibold text-gray-900 dark:text-white">{{ $user->profile->occupation }}</dd>
                                </div>
                            @endif
                            @if($user->profile->education)
                                <div class="flex justify-between">
                                    <dt class="text-gray-600 dark:text-gray-400">Education</dt>
                                    <dd class="font-semibold text-gray-900 dark:text-white">{{ $user->profile->education }}</dd>
                                </div>
                            @endif
                            @if($user->profile->income)
                                <div class="flex justify-between">
                                    <dt class="text-gray-600 dark:text-gray-400">Annual Income</dt>
                                    <dd class="font-semibold text-gray-900 dark:text-white">₹{{ number_format($user->profile->income) }}</dd>
                                </div>
                            @endif
                        </dl>
                    </div>
                @endif
            </div>

            <!-- Interests -->
            @if($user->profile && $user->profile->interests)
                @php
                    $interests = is_array($user->profile->interests)
                        ? $user->profile->interests
                        : json_decode($user->profile->interests, true);
                @endphp
                @if($interests && count($interests) > 0)
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 mt-6">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Interests</h2>
                        <div class="flex flex-wrap gap-2">
                            @foreach($interests as $interest)
                                <span class="px-4 py-2 bg-gradient-to-r from-primary/10 to-secondary/10 text-primary dark:text-primary-light rounded-full text-sm font-medium">
                                    {{ $interest }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endif

            <!-- Photo Gallery -->
            @if($user->photos->count() > 0)
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 mt-6">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Photos</h2>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach($user->photos as $photo)
                            <div class="aspect-square rounded-xl overflow-hidden">
                                <img src="{{ $photo->url }}" alt="Photo" class="w-full h-full object-cover hover:scale-105 transition-transform duration-200">
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
