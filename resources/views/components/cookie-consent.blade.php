<!-- Cookie Consent Banner -->
<div
    x-data="cookieConsent()"
    x-show="!hasConsent"
    x-cloak
    class="fixed bottom-0 inset-x-0 z-50 pb-2 sm:pb-5"
    style="display: none;"
>
    <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
        <div class="p-4 rounded-lg bg-gradient-to-r from-blue-600 to-blue-700 shadow-2xl sm:p-6">
            <div class="flex items-start flex-wrap md:flex-nowrap gap-4">
                <!-- Icon -->
                <div class="flex-shrink-0 hidden sm:block">
                    <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                    </svg>
                </div>

                <!-- Content -->
                <div class="flex-1 text-white">
                    <h3 class="text-lg font-semibold mb-1">
                        🍪 We use cookies
                    </h3>
                    <p class="text-sm text-blue-100 mb-3">
                        We use cookies to enhance your experience, analyze site traffic, and personalize content.
                        By clicking "Accept All", you consent to our use of cookies.
                        <a href="{{ route('legal.cookies') }}" class="underline hover:text-white font-medium" target="_blank">Learn more</a>
                    </p>

                    <!-- Expandable Settings -->
                    <div x-show="showSettings" class="mb-4 space-y-3 transition-all duration-300">
                        <div class="bg-white/10 backdrop-blur rounded-lg p-4 space-y-3">
                            <!-- Essential Cookies -->
                            <label class="flex items-start cursor-not-allowed opacity-75">
                                <input type="checkbox" checked disabled class="mt-1 rounded border-gray-300 text-blue-600 shadow-sm">
                                <div class="ml-3">
                                    <span class="text-sm font-medium">Essential Cookies</span>
                                    <p class="text-xs text-blue-100">Required for the website to function. Cannot be disabled.</p>
                                </div>
                            </label>

                            <!-- Functional Cookies -->
                            <label class="flex items-start cursor-pointer">
                                <input type="checkbox" x-model="preferences.functional" class="mt-1 rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <div class="ml-3">
                                    <span class="text-sm font-medium">Functional Cookies</span>
                                    <p class="text-xs text-blue-100">Remember your preferences and settings.</p>
                                </div>
                            </label>

                            <!-- Analytics Cookies -->
                            <label class="flex items-start cursor-pointer">
                                <input type="checkbox" x-model="preferences.analytics" class="mt-1 rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <div class="ml-3">
                                    <span class="text-sm font-medium">Analytics Cookies</span>
                                    <p class="text-xs text-blue-100">Help us understand how you use our platform.</p>
                                </div>
                            </label>

                            <!-- Marketing Cookies -->
                            <label class="flex items-start cursor-pointer">
                                <input type="checkbox" x-model="preferences.marketing" class="mt-1 rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <div class="ml-3">
                                    <span class="text-sm font-medium">Marketing Cookies</span>
                                    <p class="text-xs text-blue-100">Used to show you relevant advertisements.</p>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex-shrink-0 flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                    <button
                        @click="toggleSettings()"
                        x-show="!showSettings"
                        class="w-full sm:w-auto px-4 py-2 bg-white/10 backdrop-blur hover:bg-white/20 text-white rounded-lg text-sm font-medium transition-colors duration-200"
                    >
                        Customize
                    </button>

                    <button
                        @click="acceptSelected()"
                        x-show="showSettings"
                        class="w-full sm:w-auto px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg text-sm font-medium transition-colors duration-200"
                    >
                        Save Preferences
                    </button>

                    <button
                        @click="rejectAll()"
                        class="w-full sm:w-auto px-4 py-2 bg-white/10 backdrop-blur hover:bg-white/20 text-white rounded-lg text-sm font-medium transition-colors duration-200"
                    >
                        Reject All
                    </button>

                    <button
                        @click="acceptAll()"
                        class="w-full sm:w-auto px-4 py-2 bg-white hover:bg-gray-100 text-blue-600 rounded-lg text-sm font-medium transition-colors duration-200 shadow-lg"
                    >
                        Accept All
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Cookie consent functionality is now loaded via Alpine.data in app.js
</script>
@endpush

<style>
    [x-cloak] { display: none !important; }
</style>
