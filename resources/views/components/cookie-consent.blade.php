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
                    <div x-show="showSettings" x-collapse class="mb-4 space-y-3">
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
function cookieConsent() {
    return {
        hasConsent: false,
        showSettings: false,
        preferences: {
            essential: true, // Always true
            functional: true,
            analytics: true,
            marketing: false
        },

        init() {
            // Check if user has already given consent
            const consent = this.getCookie('cookie_consent');
            this.hasConsent = consent !== null;

            // Load saved preferences if they exist
            if (consent) {
                try {
                    const saved = JSON.parse(consent);
                    this.preferences = { ...this.preferences, ...saved };
                } catch (e) {
                    console.error('Error parsing cookie preferences:', e);
                }
            }

            // Apply saved preferences
            if (this.hasConsent) {
                this.applyPreferences();
            }
        },

        toggleSettings() {
            this.showSettings = !this.showSettings;
        },

        acceptAll() {
            this.preferences = {
                essential: true,
                functional: true,
                analytics: true,
                marketing: true
            };
            this.saveConsent();
        },

        rejectAll() {
            this.preferences = {
                essential: true,
                functional: false,
                analytics: false,
                marketing: false
            };
            this.saveConsent();
        },

        acceptSelected() {
            this.saveConsent();
        },

        saveConsent() {
            // Save preferences to cookie (1 year expiry)
            this.setCookie('cookie_consent', JSON.stringify(this.preferences), 365);
            this.hasConsent = true;
            this.showSettings = false;

            // Apply the preferences
            this.applyPreferences();

            // Optional: Send consent to backend
            this.sendConsentToBackend();
        },

        applyPreferences() {
            // Apply analytics if enabled
            if (this.preferences.analytics) {
                this.enableAnalytics();
            }

            // Apply marketing if enabled
            if (this.preferences.marketing) {
                this.enableMarketing();
            }
        },

        enableAnalytics() {
            // Initialize analytics services (e.g., Google Analytics, custom analytics)
            console.log('Analytics enabled');
            // Example: gtag('config', 'GA_MEASUREMENT_ID');
        },

        enableMarketing() {
            // Initialize marketing/advertising services
            console.log('Marketing cookies enabled');
        },

        sendConsentToBackend() {
            // Optional: Send consent preferences to backend for logging
            fetch('/api/cookie-consent', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(this.preferences)
            }).catch(err => console.error('Error saving consent:', err));
        },

        setCookie(name, value, days) {
            const expires = new Date();
            expires.setTime(expires.getTime() + (days * 24 * 60 * 60 * 1000));
            document.cookie = `${name}=${value};expires=${expires.toUTCString()};path=/;SameSite=Lax`;
        },

        getCookie(name) {
            const value = `; ${document.cookie}`;
            const parts = value.split(`; ${name}=`);
            if (parts.length === 2) {
                return parts.pop().split(';').shift();
            }
            return null;
        }
    }
}
</script>
@endpush

<style>
    [x-cloak] { display: none !important; }
</style>
