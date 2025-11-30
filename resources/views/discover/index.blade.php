@extends('layouts.app')

@section('content')
<div class="py-8" x-data="discoverApp()">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Discover</h1>
            <p class="text-gray-600 dark:text-gray-400">Find your perfect match</p>
        </div>

        <!-- Swipe Cards Container -->
        <div class="relative">
            @if($potentialMatches->isEmpty())
                <!-- No More Matches -->
                <div class="text-center py-16">
                    <div class="w-32 h-32 mx-auto mb-6 bg-gradient-to-br from-primary/10 to-secondary/10 rounded-full flex items-center justify-center">
                        <svg class="w-16 h-16 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">No More Profiles</h2>
                    <p class="text-gray-600 dark:text-gray-400 mb-6">Check back later for new matches</p>
                    <button @click="loadMore()" class="bg-gradient-to-r from-primary to-secondary text-white px-6 py-3 rounded-xl hover:shadow-glow transition-all duration-200 font-semibold">
                        Refresh
                    </button>
                </div>
            @else
                <!-- Cards Stack -->
                <div class="relative h-[600px] flex items-center justify-center">
                    <template x-for="(match, index) in matches" :key="match.id">
                        <div
                            x-show="index === currentIndex"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 scale-95"
                            x-transition:enter-end="opacity-100 scale-100"
                            class="absolute w-full max-w-md"
                        >
                            <!-- Profile Card -->
                            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl overflow-hidden">
                                <!-- Image Section -->
                                <div class="relative h-96">
                                    <template x-if="match.photos && match.photos.length > 0">
                                        <img
                                            :src="match.photos[0].url || '/images/default-avatar.jpg'"
                                            :alt="match.name"
                                            class="w-full h-full object-cover"
                                        >
                                    </template>
                                    <template x-if="!match.photos || match.photos.length === 0">
                                        <div class="w-full h-full bg-gradient-to-br from-primary/20 to-secondary/20 flex items-center justify-center">
                                            <svg class="w-32 h-32 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </div>
                                    </template>

                                    <!-- Gradient Overlay -->
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>

                                    <!-- Verified Badge -->
                                    <template x-if="match.email_verified_at">
                                        <div class="absolute top-4 right-4 bg-blue-500 text-white px-3 py-1 rounded-full text-sm font-semibold flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                            Verified
                                        </div>
                                    </template>

                                    <!-- Basic Info Overlay -->
                                    <div class="absolute bottom-4 left-4 right-4 text-white">
                                        <h2 class="text-3xl font-bold mb-1" x-text="match.name"></h2>
                                        <p class="text-lg mb-2">
                                            <span x-text="calculateAge(match.dob)"></span> •
                                            <span x-text="match.city || 'Location hidden'"></span>
                                        </p>
                                        <template x-if="match.profile && match.profile.occupation">
                                            <p class="text-sm opacity-90" x-text="match.profile.occupation"></p>
                                        </template>
                                    </div>
                                </div>

                                <!-- Profile Details -->
                                <div class="p-6 space-y-4">
                                    <!-- Bio -->
                                    <template x-if="match.profile && match.profile.bio">
                                        <div>
                                            <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 mb-2">About</h3>
                                            <p class="text-gray-900 dark:text-white" x-text="match.profile.bio"></p>
                                        </div>
                                    </template>

                                    <!-- Quick Stats Grid -->
                                    <div class="grid grid-cols-2 gap-4">
                                        <template x-if="match.profile && match.profile.height">
                                            <div class="flex items-center gap-2">
                                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                                </svg>
                                                <span class="text-sm text-gray-600 dark:text-gray-400" x-text="match.profile.height + ' cm'"></span>
                                            </div>
                                        </template>

                                        <template x-if="match.profile && match.profile.education">
                                            <div class="flex items-center gap-2">
                                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path d="M12 14l9-5-9-5-9 5 9 5z" />
                                                    <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                                                </svg>
                                                <span class="text-sm text-gray-600 dark:text-gray-400" x-text="match.profile.education"></span>
                                            </div>
                                        </template>

                                        <template x-if="match.profile && match.profile.religion">
                                            <div class="flex items-center gap-2">
                                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                                </svg>
                                                <span class="text-sm text-gray-600 dark:text-gray-400" x-text="match.profile.religion"></span>
                                            </div>
                                        </template>

                                        <template x-if="match.profile && match.profile.marital_status">
                                            <div class="flex items-center gap-2">
                                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                                </svg>
                                                <span class="text-sm text-gray-600 dark:text-gray-400" x-text="formatMaritalStatus(match.profile.marital_status)"></span>
                                            </div>
                                        </template>
                                    </div>

                                    <!-- Interests Tags -->
                                    <template x-if="match.profile && match.profile.interests">
                                        <div>
                                            <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 mb-2">Interests</h3>
                                            <div class="flex flex-wrap gap-2">
                                                <template x-for="interest in parseInterests(match.profile.interests)" :key="interest">
                                                    <span class="px-3 py-1 bg-primary/10 text-primary rounded-full text-sm" x-text="interest"></span>
                                                </template>
                                            </div>
                                        </div>
                                    </template>
                                </div>

                                <!-- Action Buttons -->
                                <div class="p-6 pt-0 flex items-center justify-center gap-4">
                                    <!-- Pass Button -->
                                    <button
                                        @click="handlePass(match)"
                                        class="w-16 h-16 rounded-full bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 hover:border-red-500 dark:hover:border-red-500 shadow-lg hover:shadow-xl transition-all duration-200 flex items-center justify-center group"
                                    >
                                        <svg class="w-8 h-8 text-gray-600 dark:text-gray-400 group-hover:text-red-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>

                                    <!-- Super Like Button (Premium Feature) -->
                                    <button
                                        @click="handleSuperLike(match)"
                                        class="w-14 h-14 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 shadow-lg hover:shadow-xl transition-all duration-200 flex items-center justify-center"
                                    >
                                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    </button>

                                    <!-- Like Button -->
                                    <button
                                        @click="handleLike(match)"
                                        class="w-16 h-16 rounded-full bg-gradient-to-r from-primary to-secondary hover:shadow-glow shadow-lg transition-all duration-200 flex items-center justify-center"
                                    >
                                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            @endif
        </div>
    </div>

    <!-- Match Modal -->
    <div
        x-show="showMatchModal"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50"
        @click.self="showMatchModal = false"
    >
        <div
            class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl p-8 max-w-md w-full text-center"
            x-transition:enter="transition ease-out duration-300 delay-100"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
        >
            <!-- Match Icon -->
            <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-br from-primary to-secondary rounded-full flex items-center justify-center animate-pulse">
                <svg class="w-12 h-12 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                </svg>
            </div>

            <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">It's a Match!</h2>
            <p class="text-gray-600 dark:text-gray-400 mb-8">You and <span x-text="matchedUser?.name" class="font-semibold text-primary"></span> liked each other</p>

            <div class="flex gap-4">
                <button
                    @click="showMatchModal = false"
                    class="flex-1 px-6 py-3 rounded-xl border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:border-primary dark:hover:border-primary transition-all duration-200 font-semibold"
                >
                    Keep Swiping
                </button>
                <a
                    :href="'/messages/' + matchedUser?.id"
                    class="flex-1 bg-gradient-to-r from-primary to-secondary text-white px-6 py-3 rounded-xl hover:shadow-glow transition-all duration-200 font-semibold"
                >
                    Send Message
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function discoverApp() {
    return {
        matches: @json($potentialMatches),
        currentIndex: 0,
        showMatchModal: false,
        matchedUser: null,

        init() {
            console.log('Discover app initialized with', this.matches.length, 'matches');
        },

        calculateAge(dob) {
            if (!dob) return 'Age hidden';
            const birthDate = new Date(dob);
            const today = new Date();
            let age = today.getFullYear() - birthDate.getFullYear();
            const monthDiff = today.getMonth() - birthDate.getMonth();
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }
            return age;
        },

        formatMaritalStatus(status) {
            return status.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
        },

        parseInterests(interests) {
            if (typeof interests === 'string') {
                try {
                    return JSON.parse(interests);
                } catch (e) {
                    return interests.split(',').map(i => i.trim());
                }
            }
            return interests || [];
        },

        async handleLike(match) {
            try {
                const response = await fetch(`/discover/like/${match.id}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ type: 'like' })
                });

                const data = await response.json();

                if (data.matched) {
                    this.matchedUser = match;
                    this.showMatchModal = true;
                }

                this.nextCard();
            } catch (error) {
                console.error('Error liking profile:', error);
            }
        },

        async handleSuperLike(match) {
            try {
                const response = await fetch(`/discover/like/${match.id}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ type: 'super_like' })
                });

                const data = await response.json();

                if (data.matched) {
                    this.matchedUser = match;
                    this.showMatchModal = true;
                }

                this.nextCard();
            } catch (error) {
                console.error('Error super liking profile:', error);
            }
        },

        async handlePass(match) {
            try {
                await fetch(`/discover/pass/${match.id}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                this.nextCard();
            } catch (error) {
                console.error('Error passing profile:', error);
            }
        },

        nextCard() {
            this.currentIndex++;

            // Load more when running low
            if (this.currentIndex >= this.matches.length - 2) {
                this.loadMore();
            }
        },

        async loadMore() {
            try {
                const response = await fetch('/discover/next-batch');
                const data = await response.json();

                if (data.matches && data.matches.length > 0) {
                    this.matches = [...this.matches, ...data.matches];
                }
            } catch (error) {
                console.error('Error loading more profiles:', error);
            }
        }
    }
}
</script>
@endsection
