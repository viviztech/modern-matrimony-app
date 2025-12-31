<div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 sticky top-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Filters</h2>
        <button
            @click="filters = { keyword: '', age_min: '', age_max: '', height_min: '', height_max: '', city: '', state: '', country: '', religion: [], education: [], marital_status: [], diet: [], sort: 'relevance', page: 1 }; search()"
            class="text-sm text-rose-600 dark:text-rose-400 hover:underline"
        >
            Clear All
        </button>
    </div>

    <div class="space-y-6">
        <!-- Age Range -->
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Age Range</label>
            <div class="grid grid-cols-2 gap-3">
                <input
                    type="number"
                    x-model="filters.age_min"
                    @change="search()"
                    placeholder="Min"
                    min="18"
                    max="100"
                    class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                >
                <input
                    type="number"
                    x-model="filters.age_max"
                    @change="search()"
                    placeholder="Max"
                    min="18"
                    max="100"
                    class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                >
            </div>
        </div>

        <!-- Height Range -->
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Height (cm)</label>
            <div class="grid grid-cols-2 gap-3">
                <input
                    type="number"
                    x-model="filters.height_min"
                    @change="search()"
                    placeholder="Min"
                    min="100"
                    max="250"
                    class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                >
                <input
                    type="number"
                    x-model="filters.height_max"
                    @change="search()"
                    placeholder="Max"
                    min="100"
                    max="250"
                    class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                >
            </div>
        </div>

        <!-- Location -->
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Location</label>
            <input
                type="text"
                x-model="filters.city"
                @change="search()"
                placeholder="City"
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white mb-2"
            >
            <input
                type="text"
                x-model="filters.state"
                @change="search()"
                placeholder="State"
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white mb-2"
            >
            <input
                type="text"
                x-model="filters.country"
                @change="search()"
                placeholder="Country"
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
            >
        </div>

        <!-- Religion -->
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Religion</label>
            <select
                x-model="filters.religion"
                @change="search()"
                multiple
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
            >
                @foreach($filterOptions['religions'] as $religion)
                <option value="{{ $religion }}">{{ $religion }}</option>
                @endforeach
            </select>
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Hold Ctrl/Cmd to select multiple</p>
        </div>

        <!-- Education -->
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Education</label>
            <select
                x-model="filters.education"
                @change="search()"
                multiple
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
            >
                @foreach($filterOptions['educations'] as $education)
                <option value="{{ $education }}">{{ $education }}</option>
                @endforeach
            </select>
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Hold Ctrl/Cmd to select multiple</p>
        </div>

        <!-- Marital Status -->
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Marital Status</label>
            <select
                x-model="filters.marital_status"
                @change="search()"
                multiple
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
            >
                @foreach($filterOptions['marital_statuses'] as $status)
                <option value="{{ $status }}">{{ $status }}</option>
                @endforeach
            </select>
        </div>

        <!-- Diet -->
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Diet</label>
            <select
                x-model="filters.diet"
                @change="search()"
                multiple
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
            >
                @foreach($filterOptions['diets'] as $diet)
                <option value="{{ $diet }}">{{ $diet }}</option>
                @endforeach
            </select>
        </div>

        <!-- Quick Filters -->
        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
            <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Quick Filters</h3>
            <div class="space-y-2">
                <label class="flex items-center">
                    <input
                        type="checkbox"
                        x-model="filters.photo_verified"
                        @change="search()"
                        class="rounded border-gray-300 text-rose-600 focus:ring-rose-500"
                    >
                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Photo Verified</span>
                </label>
                <label class="flex items-center">
                    <input
                        type="checkbox"
                        x-model="filters.video_verified"
                        @change="search()"
                        class="rounded border-gray-300 text-rose-600 focus:ring-rose-500"
                    >
                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Video Verified</span>
                </label>
                <label class="flex items-center">
                    <input
                        type="checkbox"
                        x-model="filters.has_photos"
                        @change="search()"
                        class="rounded border-gray-300 text-rose-600 focus:ring-rose-500"
                    >
                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Has Photos</span>
                </label>
                <label class="flex items-center">
                    <input
                        type="checkbox"
                        x-model="filters.has_video_intro"
                        @change="search()"
                        class="rounded border-gray-300 text-rose-600 focus:ring-rose-500"
                    >
                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Has Video Intro</span>
                </label>
                <label class="flex items-center">
                    <input
                        type="checkbox"
                        x-model="filters.online_only"
                        @change="search()"
                        class="rounded border-gray-300 text-rose-600 focus:ring-rose-500"
                    >
                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Online Now</span>
                </label>
            </div>
        </div>

        @if($canUseAdvancedFilters)
        <!-- Advanced Filters (Premium) -->
        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
            <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3 flex items-center">
                Advanced Filters
                <span class="ml-2 text-xs bg-gradient-to-r from-yellow-400 to-orange-500 text-white px-2 py-0.5 rounded">PREMIUM</span>
            </h3>
            <div class="space-y-4">
                <label class="flex items-center">
                    <input
                        type="checkbox"
                        x-model="filters.premium_only"
                        @change="search()"
                        class="rounded border-gray-300 text-rose-600 focus:ring-rose-500"
                    >
                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Premium Members Only</span>
                </label>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Search Radius (km)</label>
                    <input
                        type="number"
                        x-model="filters.radius"
                        @change="search()"
                        placeholder="e.g., 50"
                        min="1"
                        max="500"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                    >
                </div>
            </div>
        </div>
        @else
        <!-- Upgrade Prompt -->
        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
            <div class="bg-gradient-to-r from-rose-50 to-pink-50 dark:from-rose-900/20 dark:to-pink-900/20 rounded-lg p-4">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-2">Unlock Advanced Filters</h3>
                <p class="text-xs text-gray-600 dark:text-gray-400 mb-3">
                    Get access to location radius, income filters, and more with Gold membership.
                </p>
                <a
                    href="{{ route('pricing') }}"
                    class="block w-full text-center px-4 py-2 bg-gradient-to-r from-rose-600 to-pink-600 text-white rounded-lg hover:from-rose-700 hover:to-pink-700 transition text-sm font-medium"
                >
                    Upgrade Now
                </a>
            </div>
        </div>
        @endif
    </div>
</div>
