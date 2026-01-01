<x-app-layout>
    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg p-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Cookie Policy</h1>
                <p class="text-sm text-gray-500 mb-8">Last Updated: {{ now()->format('F d, Y') }}</p>

                <div class="prose prose-blue max-w-none">
                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">1. What Are Cookies?</h2>
                        <p class="text-gray-700 mb-4">
                            Cookies are small text files that are placed on your device when you visit our website. They help us
                            provide you with a better experience by remembering your preferences and understanding how you use our platform.
                        </p>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">2. Types of Cookies We Use</h2>

                        <div class="space-y-6">
                            <div class="bg-blue-50 border-l-4 border-blue-400 p-4">
                                <h3 class="text-xl font-semibold text-gray-800 mb-2">2.1 Essential Cookies</h3>
                                <p class="text-gray-700 mb-3">
                                    These cookies are necessary for the platform to function and cannot be disabled.
                                </p>
                                <ul class="list-disc pl-6 text-gray-700 space-y-1">
                                    <li><strong>Session Cookies:</strong> Keep you logged in during your session</li>
                                    <li><strong>Security Cookies:</strong> Prevent CSRF attacks and ensure security</li>
                                    <li><strong>Load Balancing:</strong> Distribute traffic across servers</li>
                                </ul>
                            </div>

                            <div class="bg-green-50 border-l-4 border-green-400 p-4">
                                <h3 class="text-xl font-semibold text-gray-800 mb-2">2.2 Functional Cookies</h3>
                                <p class="text-gray-700 mb-3">
                                    These cookies enable enhanced functionality and personalization.
                                </p>
                                <ul class="list-disc pl-6 text-gray-700 space-y-1">
                                    <li><strong>Preference Cookies:</strong> Remember your settings and preferences</li>
                                    <li><strong>Language Cookies:</strong> Remember your language preference</li>
                                    <li><strong>UI Cookies:</strong> Remember your interface customizations</li>
                                </ul>
                            </div>

                            <div class="bg-purple-50 border-l-4 border-purple-400 p-4">
                                <h3 class="text-xl font-semibold text-gray-800 mb-2">2.3 Analytics Cookies</h3>
                                <p class="text-gray-700 mb-3">
                                    These cookies help us understand how visitors use our platform.
                                </p>
                                <ul class="list-disc pl-6 text-gray-700 space-y-1">
                                    <li><strong>Usage Analytics:</strong> Track page views, clicks, and navigation</li>
                                    <li><strong>Performance Monitoring:</strong> Measure page load times and errors</li>
                                    <li><strong>User Behavior:</strong> Understand feature usage and engagement</li>
                                </ul>
                            </div>

                            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                                <h3 class="text-xl font-semibold text-gray-800 mb-2">2.4 Marketing Cookies</h3>
                                <p class="text-gray-700 mb-3">
                                    These cookies are used to deliver relevant advertisements (requires consent).
                                </p>
                                <ul class="list-disc pl-6 text-gray-700 space-y-1">
                                    <li><strong>Advertising:</strong> Show you relevant ads based on your interests</li>
                                    <li><strong>Remarketing:</strong> Show ads to users who visited our platform</li>
                                    <li><strong>Conversion Tracking:</strong> Measure ad campaign effectiveness</li>
                                </ul>
                            </div>
                        </div>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">3. Specific Cookies We Use</h2>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 border border-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cookie Name</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Purpose</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duration</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">laravel_session</td>
                                        <td class="px-6 py-4 text-sm text-gray-700">Maintains your login session</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">2 hours</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">Essential</td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">XSRF-TOKEN</td>
                                        <td class="px-6 py-4 text-sm text-gray-700">Security protection against CSRF attacks</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">2 hours</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">Essential</td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">remember_web</td>
                                        <td class="px-6 py-4 text-sm text-gray-700">Remembers your login for persistent sessions</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">5 years</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">Functional</td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">cookie_consent</td>
                                        <td class="px-6 py-4 text-sm text-gray-700">Stores your cookie preferences</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">1 year</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">Essential</td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">_analytics</td>
                                        <td class="px-6 py-4 text-sm text-gray-700">Tracks user behavior and engagement</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">2 years</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">Analytics</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">4. Third-Party Cookies</h2>
                        <p class="text-gray-700 mb-3">We use services from third parties that may set their own cookies:</p>
                        <ul class="list-disc pl-6 mb-4 text-gray-700 space-y-2">
                            <li><strong>Payment Processors (Razorpay):</strong> For secure payment processing</li>
                            <li><strong>Video Services (Agora.io):</strong> For video calling functionality</li>
                            <li><strong>Analytics Services:</strong> To understand platform usage</li>
                            <li><strong>CDN Providers:</strong> For faster content delivery</li>
                        </ul>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">5. Managing Your Cookie Preferences</h2>

                        <h3 class="text-xl font-semibold text-gray-800 mb-3">5.1 On Our Platform</h3>
                        <p class="text-gray-700 mb-4">
                            You can manage your cookie preferences through our cookie consent banner when you first visit the platform,
                            or by clicking the "Cookie Settings" link in the footer.
                        </p>

                        <h3 class="text-xl font-semibold text-gray-800 mb-3">5.2 Browser Settings</h3>
                        <p class="text-gray-700 mb-3">You can also control cookies through your browser settings:</p>
                        <ul class="list-disc pl-6 mb-4 text-gray-700 space-y-2">
                            <li><strong>Chrome:</strong> Settings > Privacy and Security > Cookies</li>
                            <li><strong>Firefox:</strong> Settings > Privacy & Security > Cookies and Site Data</li>
                            <li><strong>Safari:</strong> Preferences > Privacy > Manage Website Data</li>
                            <li><strong>Edge:</strong> Settings > Cookies and Site Permissions</li>
                        </ul>

                        <div class="bg-orange-50 border-l-4 border-orange-400 p-4">
                            <p class="text-gray-800 font-semibold mb-2">⚠️ Important Note:</p>
                            <p class="text-gray-700">
                                Blocking essential cookies may prevent you from using certain features of the platform,
                                including logging in and maintaining your session.
                            </p>
                        </div>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">6. Do Not Track Signals</h2>
                        <p class="text-gray-700 mb-4">
                            We honor Do Not Track (DNT) browser signals. When DNT is enabled, we will not set non-essential cookies
                            and will limit data collection to what is necessary for platform functionality.
                        </p>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">7. Cookie Consent for EU Users</h2>
                        <p class="text-gray-700 mb-4">
                            In compliance with the EU Cookie Directive and GDPR, we will ask for your consent before setting
                            non-essential cookies. You can withdraw your consent at any time through your cookie preferences.
                        </p>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">8. Updates to This Policy</h2>
                        <p class="text-gray-700 mb-4">
                            We may update this Cookie Policy from time to time. We will notify you of any significant changes
                            by posting a notice on our platform or sending you an email.
                        </p>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">9. Contact Us</h2>
                        <p class="text-gray-700 mb-3">If you have questions about our use of cookies, contact us:</p>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-gray-700"><strong>Email:</strong> <a href="mailto:privacy@{{ parse_url(config('app.url'), PHP_URL_HOST) }}" class="text-blue-600 hover:underline">privacy@{{ parse_url(config('app.url'), PHP_URL_HOST) }}</a></p>
                            <p class="text-gray-700"><strong>Cookie Settings:</strong> <a href="#" onclick="event.preventDefault(); showCookieSettings();" class="text-blue-600 hover:underline">Manage Cookie Preferences</a></p>
                        </div>
                    </section>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-200">
                    <div class="flex space-x-4">
                        <a href="{{ route('legal.privacy') }}" class="text-blue-600 hover:underline">Privacy Policy</a>
                        <a href="{{ route('legal.terms') }}" class="text-blue-600 hover:underline">Terms of Service</a>
                        <a href="{{ route('home') }}" class="text-blue-600 hover:underline">Back to Home</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function showCookieSettings() {
            // This will be implemented with the cookie consent banner
            alert('Cookie settings panel will open here');
        }
    </script>
    @endpush
</x-app-layout>
