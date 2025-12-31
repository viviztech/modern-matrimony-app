@extends('layouts.app')

@section('content')
<div x-data="searchApp()" x-init="init()" class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Find Your Match</h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">Search through thousands of verified profiles</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <!-- Filters Sidebar -->
            <div class="lg:col-span-1">
                @include('search.partials.filters')
            </div>

            <!-- Results Area -->
            <div class="lg:col-span-3">
                <!-- Search Bar -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 mb-6">
                    <div class="flex items-center gap-4">
                        <div class="flex-1">
                            <div class="relative">
                                <input
                                    type="text"
                                    x-model="filters.keyword"
                                    @input.debounce.500ms="search()"
                                    placeholder="Search by name, occupation, interests..."
                                    class="w-full pl-10 pr-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-rose-500 dark:bg-gray-700 dark:text-white"
                                >
                                <svg class="absolute left-3 top-3.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                        </div>
                        <button
                            @click="showSaveSearchModal = true"
                            class="px-4 py-3 bg-rose-600 text-white rounded-lg hover:bg-rose-700 transition whitespace-nowrap"
                        >
                            Save Search
                        </button>
                    </div>
                </div>

                <!-- Saved Searches Quick Access -->
                @if($savedSearches->count() > 0)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 mb-6">
                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Quick Access</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($savedSearches as $savedSearch)
                        <a
                            href="{{ route('search.saved.run', $savedSearch) }}"
                            class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-full text-sm hover:bg-rose-100 dark:hover:bg-rose-900 transition"
                        >
                            {{ $savedSearch->name }}
                        </a>
                        @endforeach
                        <a href="{{ route('search.saved') }}" class="px-4 py-2 text-rose-600 dark:text-rose-400 text-sm hover:underline">
                            View all →
                        </a>
                    </div>
                </div>
                @endif

                <!-- Suggestions -->
                @if(count($suggestions) > 0)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Suggested Searches</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($suggestions as $suggestion)
                        <button
                            @click="applySuggestion(@js($suggestion['filters']))"
                            class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg hover:border-rose-500 dark:hover:border-rose-500 transition text-left"
                        >
                            <h4 class="font-medium text-gray-900 dark:text-white mb-1">{{ $suggestion['title'] }}</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ implode(', ', array_filter(array_values($suggestion['filters']))) }}
                            </p>
                        </button>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Results Container -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                    <!-- Loading State -->
                    <div x-show="loading" class="space-y-4">
                        @for($i = 0; $i < 6; $i++)
                        <div class="animate-pulse flex gap-4">
                            <div class="bg-gray-200 dark:bg-gray-700 rounded-lg h-32 w-32"></div>
                            <div class="flex-1 space-y-3">
                                <div class="bg-gray-200 dark:bg-gray-700 h-4 rounded w-3/4"></div>
                                <div class="bg-gray-200 dark:bg-gray-700 h-4 rounded w-1/2"></div>
                                <div class="bg-gray-200 dark:bg-gray-700 h-4 rounded w-2/3"></div>
                            </div>
                        </div>
                        @endfor
                    </div>

                    <!-- Empty State -->
                    <div x-show="!loading && results.length === 0" class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">No profiles found</h3>
                        <p class="mt-2 text-gray-500 dark:text-gray-400">Try adjusting your search filters</p>
                    </div>

                    <!-- Results Grid -->
                    <div x-show="!loading && results.length > 0" class="space-y-6">
                        <div class="flex justify-between items-center">
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                Found <span x-text="total" class="font-semibold"></span> profiles
                            </p>
                            <select
                                x-model="filters.sort"
                                @change="search()"
                                class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                            >
                                @foreach($filterOptions['sort_options'] as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div id="results-grid" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <template x-for="user in results" :key="user.id">
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg overflow-hidden hover:shadow-lg transition">
                                    <a :href="`/profile/${user.id}`" class="block">
                                        <div class="aspect-w-16 aspect-h-9 bg-gray-200 dark:bg-gray-600">
                                            <img
                                                :src="user.primary_photo?.url || '/images/default-avatar.png'"
                                                :alt="user.name"
                                                class="w-full h-48 object-cover"
                                                loading="lazy"
                                            >
                                        </div>
                                        <div class="p-4">
                                            <div class="flex items-start justify-between">
                                                <div class="flex-1">
                                                    <h3 class="font-semibold text-lg text-gray-900 dark:text-white" x-text="user.name"></h3>
                                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                                        <span x-text="user.age"></span> years • <span x-text="user.profile?.height_in_feet"></span>
                                                    </p>
                                                </div>
                                                <span x-show="user.is_online" class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Online
                                                </span>
                                            </div>
                                            <div class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                                                <p x-text="user.profile?.occupation"></p>
                                                <p x-text="user.city + ', ' + user.state"></p>
                                            </div>
                                            <div class="mt-3 flex items-center gap-2">
                                                <span x-show="user.video_verified" class="inline-flex items-center text-xs text-blue-600 dark:text-blue-400">
                                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                    </svg>
                                                    Verified
                                                </span>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </template>
                        </div>

                        <!-- Load More Button -->
                        <div x-show="hasMore" class="text-center mt-6">
                            <button
                                @click="loadMore()"
                                :disabled="loading"
                                class="px-6 py-3 bg-rose-600 text-white rounded-lg hover:bg-rose-700 transition disabled:opacity-50"
                            >
                                <span x-show="!loading">Load More</span>
                                <span x-show="loading">Loading...</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Save Search Modal -->
    <div x-show="showSaveSearchModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div @click="showSaveSearchModal = false" class="fixed inset-0 bg-black opacity-50"></div>
            <div class="relative bg-white dark:bg-gray-800 rounded-lg max-w-md w-full p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Save This Search</h3>
                <form @submit.prevent="saveSearch()">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Search Name</label>
                        <input
                            type="text"
                            x-model="saveSearchForm.name"
                            required
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                            placeholder="e.g., Engineers in Mumbai"
                        >
                    </div>
                    <div class="mb-4">
                        <label class="flex items-center">
                            <input
                                type="checkbox"
                                x-model="saveSearchForm.notify_on_match"
                                class="rounded border-gray-300 text-rose-600 focus:ring-rose-500"
                            >
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Notify me when new matches are found</span>
                        </label>
                    </div>
                    <div class="flex gap-3">
                        <button
                            type="button"
                            @click="showSaveSearchModal = false"
                            class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            :disabled="saving"
                            class="flex-1 px-4 py-2 bg-rose-600 text-white rounded-lg hover:bg-rose-700 disabled:opacity-50"
                        >
                            <span x-show="!saving">Save</span>
                            <span x-show="saving">Saving...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function searchApp() {
    return {
        filters: {
            keyword: '',
            age_min: '',
            age_max: '',
            height_min: '',
            height_max: '',
            city: '',
            state: '',
            country: '',
            religion: [],
            education: [],
            marital_status: [],
            diet: [],
            sort: 'relevance',
            page: 1,
        },
        results: [],
        total: 0,
        hasMore: false,
        loading: false,
        showSaveSearchModal: false,
        saving: false,
        saveSearchForm: {
            name: '',
            notify_on_match: false,
        },

        init() {
            this.loadFiltersFromUrl();
            this.search();
        },

        loadFiltersFromUrl() {
            const params = new URLSearchParams(window.location.search);
            for (let [key, value] of params) {
                if (this.filters.hasOwnProperty(key)) {
                    this.filters[key] = value;
                }
            }
        },

        async search() {
            this.loading = true;
            this.filters.page = 1;

            try {
                const response = await fetch('/search', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(this.filters),
                });

                const data = await response.json();

                if (data.success) {
                    this.results = data.data.users;
                    this.total = data.data.total;
                    this.hasMore = data.data.hasMore;
                } else if (data.upgrade_required) {
                    alert(data.message);
                }
            } catch (error) {
                console.error('Search failed:', error);
            } finally {
                this.loading = false;
            }
        },

        async loadMore() {
            this.filters.page++;
            this.loading = true;

            try {
                const response = await fetch('/search', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(this.filters),
                });

                const data = await response.json();

                if (data.success) {
                    this.results = [...this.results, ...data.data.users];
                    this.hasMore = data.data.hasMore;
                }
            } catch (error) {
                console.error('Load more failed:', error);
            } finally {
                this.loading = false;
            }
        },

        applySuggestion(suggestionFilters) {
            Object.assign(this.filters, suggestionFilters);
            this.search();
        },

        async saveSearch() {
            this.saving = true;

            try {
                const response = await fetch('/search/save', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        name: this.saveSearchForm.name,
                        filters: this.filters,
                        notify_on_match: this.saveSearchForm.notify_on_match,
                    }),
                });

                const data = await response.json();

                if (data.success) {
                    alert('Search saved successfully!');
                    this.showSaveSearchModal = false;
                    this.saveSearchForm = { name: '', notify_on_match: false };
                } else {
                    alert(data.message || 'Failed to save search');
                }
            } catch (error) {
                console.error('Save search failed:', error);
                alert('Failed to save search');
            } finally {
                this.saving = false;
            }
        },
    };
}
</script>
@endsection
