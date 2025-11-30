@extends('layouts.app')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Matches</h1>
            <p class="text-gray-600 dark:text-gray-400">People who liked you back</p>
        </div>

        @if($matches->isEmpty())
            <!-- No Matches Yet -->
            <div class="text-center py-16">
                <div class="w-32 h-32 mx-auto mb-6 bg-gradient-to-br from-primary/10 to-secondary/10 rounded-full flex items-center justify-center">
                    <svg class="w-16 h-16 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">No Matches Yet</h2>
                <p class="text-gray-600 dark:text-gray-400 mb-6">Keep swiping to find your perfect match!</p>
                <a href="{{ route('discover') }}" class="inline-block bg-gradient-to-r from-primary to-secondary text-white px-6 py-3 rounded-xl hover:shadow-glow transition-all duration-200 font-semibold">
                    Start Discovering
                </a>
            </div>
        @else
            <!-- Matches Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($matches as $match)
                    @php
                        $matchedUser = $match->matchedUser;
                        $primaryPhoto = $matchedUser->photos->first();
                        $age = $matchedUser->dob ? \Carbon\Carbon::parse($matchedUser->dob)->age : null;
                    @endphp

                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-200">
                        <!-- Image -->
                        <div class="relative h-80">
                            @if($primaryPhoto)
                                <img
                                    src="{{ $primaryPhoto->url }}"
                                    alt="{{ $matchedUser->name }}"
                                    class="w-full h-full object-cover"
                                >
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-primary/20 to-secondary/20 flex items-center justify-center">
                                    <svg class="w-24 h-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                            @endif

                            <!-- Gradient Overlay -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>

                            <!-- Match Badge -->
                            <div class="absolute top-4 left-4 bg-gradient-to-r from-primary to-secondary text-white px-3 py-1 rounded-full text-sm font-semibold flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                                </svg>
                                Match
                            </div>

                            <!-- Verified Badge -->
                            @if($matchedUser->email_verified_at)
                                <div class="absolute top-4 right-4 bg-blue-500 text-white px-3 py-1 rounded-full text-sm font-semibold flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            @endif

                            <!-- Basic Info Overlay -->
                            <div class="absolute bottom-4 left-4 right-4 text-white">
                                <h3 class="text-2xl font-bold mb-1">{{ $matchedUser->name }}</h3>
                                <p class="text-sm">
                                    @if($age)
                                        {{ $age }} •
                                    @endif
                                    {{ $matchedUser->city ?? 'Location hidden' }}
                                </p>
                                @if($matchedUser->profile && $matchedUser->profile->occupation)
                                    <p class="text-sm opacity-90 mt-1">{{ $matchedUser->profile->occupation }}</p>
                                @endif
                            </div>
                        </div>

                        <!-- Card Footer -->
                        <div class="p-4 flex items-center justify-between">
                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                Matched {{ $match->matched_at->diffForHumans() }}
                            </div>

                            <div class="flex items-center gap-2">
                                <a
                                    href="{{ route('messages.create', $matchedUser) }}"
                                    class="bg-gradient-to-r from-primary to-secondary text-white px-4 py-2 rounded-lg hover:shadow-glow transition-all duration-200 font-semibold text-sm"
                                >
                                    Message
                                </a>

                                <button
                                    onclick="unmatchUser({{ $match->id }})"
                                    class="text-gray-400 hover:text-red-500 transition-colors p-2"
                                    title="Unmatch"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Stats -->
            <div class="mt-12 bg-gradient-to-br from-primary/10 to-secondary/10 dark:from-primary/5 dark:to-secondary/5 rounded-2xl p-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                    <div>
                        <div class="text-4xl font-bold text-gradient mb-2">{{ $matches->count() }}</div>
                        <div class="text-gray-600 dark:text-gray-400">Total Matches</div>
                    </div>
                    <div>
                        <div class="text-4xl font-bold text-gradient mb-2">
                            {{ $matches->where('matched_at', '>', now()->subDays(7))->count() }}
                        </div>
                        <div class="text-gray-600 dark:text-gray-400">This Week</div>
                    </div>
                    <div>
                        <div class="text-4xl font-bold text-gradient mb-2">
                            {{ $matches->where('matched_at', '>', now()->subDay())->count() }}
                        </div>
                        <div class="text-gray-600 dark:text-gray-400">Today</div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
async function unmatchUser(matchId) {
    if (!confirm('Are you sure you want to unmatch? This action cannot be undone.')) {
        return;
    }

    try {
        const response = await fetch(`/matches/${matchId}/unmatch`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        if (response.ok) {
            // Reload the page to reflect changes
            window.location.reload();
        } else {
            alert('Failed to unmatch. Please try again.');
        }
    } catch (error) {
        console.error('Error unmatching:', error);
        alert('An error occurred. Please try again.');
    }
}
</script>
@endsection
