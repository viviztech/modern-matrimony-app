@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center gap-3">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Who Liked You</h1>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gradient-to-r from-yellow-400 to-orange-500 text-white">
                    PREMIUM
                </span>
            </div>
            <p class="mt-2 text-gray-600 dark:text-gray-400">
                {{ $likedBy->total() }} {{ $likedBy->total() == 1 ? 'person' : 'people' }} liked your profile
            </p>
        </div>

        @if($likedBy->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($likedBy as $like)
            @php
                $user = $like->user;
            @endphp
            <div class="bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-sm hover:shadow-lg transition">
                <a href="{{ route('profile.show', $user) }}" class="block">
                    <div class="relative">
                        @if($user->primaryPhoto)
                        <img
                            src="{{ $user->primaryPhoto->url }}"
                            alt="{{ $user->name }}"
                            class="w-full h-64 object-cover"
                            loading="lazy"
                        >
                        @else
                        <div class="w-full h-64 flex items-center justify-center bg-gradient-to-br from-rose-400 to-pink-500">
                            <span class="text-6xl font-bold text-white">{{ substr($user->name, 0, 1) }}</span>
                        </div>
                        @endif

                        <!-- Like Badge -->
                        <div class="absolute top-3 right-3">
                            <div class="bg-rose-500 text-white rounded-full p-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>

                        <!-- Online Status -->
                        @if($user->isOnline())
                        <div class="absolute top-3 left-3">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-500 text-white">
                                Online
                            </span>
                        </div>
                        @endif
                    </div>

                    <div class="p-4">
                        <div class="flex items-start justify-between mb-2">
                            <div>
                                <h3 class="font-semibold text-lg text-gray-900 dark:text-white">{{ $user->name }}</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ $user->age }} years
                                    @if($user->profile?->height)
                                    • {{ $user->profile->height_in_feet }}
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="space-y-1 text-sm text-gray-700 dark:text-gray-300 mb-3">
                            @if($user->profile?->occupation)
                            <p class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                {{ $user->profile->occupation }}
                            </p>
                            @endif
                            @if($user->city)
                            <p class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                {{ $user->city }}{{ $user->state ? ', ' . $user->state : '' }}
                            </p>
                            @endif
                        </div>

                        <div class="flex items-center gap-2 mb-3">
                            @if($user->video_verified)
                            <span class="inline-flex items-center text-xs text-blue-600 dark:text-blue-400">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Verified
                            </span>
                            @endif
                            @if($user->is_premium)
                            <span class="inline-flex items-center text-xs text-yellow-600 dark:text-yellow-400">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                Premium
                            </span>
                            @endif
                        </div>

                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Liked {{ $like->created_at->diffForHumans() }}
                        </p>
                    </div>
                </a>

                <div class="px-4 pb-4 flex gap-2">
                    <button
                        onclick="likeUser({{ $user->id }})"
                        class="flex-1 px-4 py-2 bg-rose-600 text-white rounded-lg hover:bg-rose-700 transition"
                    >
                        Like Back
                    </button>
                    <a
                        href="{{ route('messages.show', $user) }}"
                        class="flex-1 px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition text-center"
                    >
                        Message
                    </a>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($likedBy->hasPages())
        <div class="mt-8">
            {{ $likedBy->links() }}
        </div>
        @endif
        @else
        <!-- Empty State -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-12 text-center">
            <div class="mx-auto w-24 h-24 bg-rose-100 dark:bg-rose-900/30 rounded-full flex items-center justify-center mb-4">
                <svg class="w-12 h-12 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">No likes yet</h3>
            <p class="text-gray-500 dark:text-gray-400 mb-6">
                When someone likes your profile, you'll see them here.
            </p>
            <div class="flex justify-center gap-4">
                <a
                    href="{{ route('search.index') }}"
                    class="px-6 py-3 bg-rose-600 text-white rounded-lg hover:bg-rose-700 transition"
                >
                    Start Browsing
                </a>
                <a
                    href="{{ route('profile.edit') }}"
                    class="px-6 py-3 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition"
                >
                    Improve Profile
                </a>
            </div>
        </div>
        @endif
    </div>
</div>

<script>
async function likeUser(userId) {
    try {
        const response = await fetch(`/profile/${userId}/like`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
        });

        const data = await response.json();

        if (data.success) {
            alert(data.message || 'Profile liked!');
        } else {
            alert(data.message || 'Failed to like profile');
        }
    } catch (error) {
        console.error('Like failed:', error);
        alert('Failed to like profile');
    }
}
</script>
@endsection
