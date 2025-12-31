@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Search Results</h1>
                @if(isset($savedSearch))
                <p class="mt-2 text-gray-600 dark:text-gray-400">
                    Saved Search: <span class="font-semibold">{{ $savedSearch->name }}</span>
                </p>
                @endif
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Found {{ number_format($total) }} profiles
                </p>
            </div>
            <a
                href="{{ route('search.index') }}"
                class="px-4 py-2 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700"
            >
                ← Back to Search
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <!-- Filters Sidebar -->
            <div class="lg:col-span-1">
                @include('search.partials.filters')
            </div>

            <!-- Results Area -->
            <div class="lg:col-span-3">
                @if($users->count() > 0)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                    <div class="flex justify-between items-center mb-6">
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Showing {{ $users->count() }} of {{ number_format($total) }} results
                        </p>
                        <select
                            name="sort"
                            onchange="window.location.href = updateQueryParam('sort', this.value)"
                            class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                        >
                            @foreach($filterOptions['sort_options'] as $value => $label)
                            <option value="{{ $value }}" {{ ($filters['sort'] ?? 'relevance') == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($users as $user)
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg overflow-hidden hover:shadow-lg transition">
                            <a href="{{ route('profile.show', $user) }}" class="block">
                                <div class="aspect-w-16 aspect-h-9 bg-gray-200 dark:bg-gray-600">
                                    @if($user->primaryPhoto)
                                    <img
                                        src="{{ $user->primaryPhoto->url }}"
                                        alt="{{ $user->name }}"
                                        class="w-full h-48 object-cover"
                                        loading="lazy"
                                    >
                                    @else
                                    <div class="w-full h-48 flex items-center justify-center bg-gradient-to-br from-rose-400 to-pink-500">
                                        <span class="text-4xl font-bold text-white">{{ substr($user->name, 0, 1) }}</span>
                                    </div>
                                    @endif
                                </div>
                                <div class="p-4">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <h3 class="font-semibold text-lg text-gray-900 dark:text-white">{{ $user->name }}</h3>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                                {{ $user->age }} years
                                                @if($user->profile?->height)
                                                • {{ $user->profile->height_in_feet }}
                                                @endif
                                            </p>
                                        </div>
                                        <div class="flex flex-col items-end gap-1">
                                            @if($user->isOnline())
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                                Online
                                            </span>
                                            @endif
                                            @if($user->hasActiveBoost())
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">
                                                Boosted
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="mt-2 text-sm text-gray-700 dark:text-gray-300 space-y-1">
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
                                        @if($user->profile?->education)
                                        <p class="flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 14l9-5-9-5-9 5 9 5z"/>
                                                <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                                            </svg>
                                            {{ $user->profile->education }}
                                        </p>
                                        @endif
                                    </div>
                                    <div class="mt-3 flex items-center gap-2 flex-wrap">
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
                                </div>
                            </a>
                        </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if($total > $perPage)
                    <div class="mt-8">
                        {{ $users->links() }}
                    </div>
                    @endif
                </div>
                @else
                <!-- Empty State -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">No profiles found</h3>
                    <p class="mt-2 text-gray-500 dark:text-gray-400">Try adjusting your search filters to see more results</p>
                    <div class="mt-6">
                        <a
                            href="{{ route('search.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-rose-600 text-white rounded-lg hover:bg-rose-700"
                        >
                            Modify Search
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function updateQueryParam(key, value) {
    const url = new URL(window.location);
    url.searchParams.set(key, value);
    return url.toString();
}
</script>
@endsection
