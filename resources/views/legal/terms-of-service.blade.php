@extends('layouts.public')

@section('title', 'Terms of Service - ' . config('app.name'))
@section('meta_description', 'Read our Terms of Service to understand the rules and guidelines for using our matrimony platform.')

@php
    $domain = parse_url(config('app.url'), PHP_URL_HOST) ?: 'matrimony.com';
    $supportEmail = 'support@' . $domain;
    $legalEmail = 'legal@' . $domain;
@endphp

@section('content')
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-8">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Terms of Service</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-8">Last Updated: {{ now()->format('F d, Y') }}</p>

                <div class="prose prose-blue dark:prose-invert max-w-none">
                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">1. Acceptance of Terms</h2>
                        <p class="text-gray-700 dark:text-gray-300 mb-4">
                            By accessing or using {{ config('app.name') }}, you agree to be bound by these Terms of Service
                            ("Terms"). If you do not agree to these Terms, please do not use our platform.
                        </p>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">2. Eligibility</h2>
                        <p class="text-gray-700 dark:text-gray-300 mb-3">To use our platform, you must:</p>
                        <ul class="list-disc pl-6 mb-4 text-gray-700 dark:text-gray-300 space-y-2">
                            <li>Be at least 18 years old</li>
                            <li>Be legally eligible to enter into a marriage contract</li>
                            <li>Not be currently married (unless seeking a second marriage in accordance with your beliefs)
                            </li>
                            <li>Provide accurate and truthful information</li>
                            <li>Comply with all applicable laws and regulations</li>
                        </ul>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">3. Account Registration</h2>
                        <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-3">3.1 Account Creation</h3>
                        <p class="text-gray-700 dark:text-gray-300 mb-4">
                            You must create an account to use our services. You agree to provide accurate, current, and
                            complete
                            information during registration and keep your account information updated.
                        </p>

                        <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-3">3.2 Account Security</h3>
                        <p class="text-gray-700 dark:text-gray-300 mb-3">You are responsible for:</p>
                        <ul class="list-disc pl-6 mb-4 text-gray-700 dark:text-gray-300 space-y-2">
                            <li>Maintaining the confidentiality of your password</li>
                            <li>All activities that occur under your account</li>
                            <li>Notifying us immediately of any unauthorized use</li>
                        </ul>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">4. User Conduct</h2>
                        <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-3">4.1 Prohibited Activities
                        </h3>
                        <p class="text-gray-700 dark:text-gray-300 mb-3">You agree NOT to:</p>
                        <ul class="list-disc pl-6 mb-4 text-gray-700 dark:text-gray-300 space-y-2">
                            <li>Provide false or misleading information</li>
                            <li>Impersonate any person or entity</li>
                            <li>Harass, abuse, or harm other users</li>
                            <li>Post inappropriate, offensive, or illegal content</li>
                            <li>Spam or send unsolicited messages</li>
                            <li>Use automated systems (bots) to access the platform</li>
                            <li>Attempt to hack or compromise platform security</li>
                            <li>Collect user information without consent</li>
                            <li>Use the platform for commercial purposes without authorization</li>
                            <li>Violate any applicable laws or regulations</li>
                        </ul>

                        <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-3">4.2 Content Guidelines</h3>
                        <p class="text-gray-700 dark:text-gray-300 mb-3">All user-generated content must:</p>
                        <ul class="list-disc pl-6 mb-4 text-gray-700 dark:text-gray-300 space-y-2">
                            <li>Be respectful and appropriate</li>
                            <li>Not contain nudity or sexually explicit material</li>
                            <li>Not promote violence, hatred, or discrimination</li>
                            <li>Not infringe on intellectual property rights</li>
                            <li>Comply with our community guidelines</li>
                        </ul>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">5. Subscription and Payments
                        </h2>
                        <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-3">5.1 Subscription Plans</h3>
                        <p class="text-gray-700 dark:text-gray-300 mb-4">
                            We offer free and paid subscription plans. Premium features are available only to paid
                            subscribers.
                            Subscription fees are non-refundable except as required by law.
                        </p>

                        <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-3">5.2 Billing</h3>
                        <ul class="list-disc pl-6 mb-4 text-gray-700 dark:text-gray-300 space-y-2">
                            <li>Subscriptions renew automatically unless cancelled</li>
                            <li>You authorize us to charge your payment method on each renewal</li>
                            <li>Prices may change with 30 days' notice</li>
                            <li>You can cancel your subscription at any time</li>
                        </ul>

                        <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-3">5.3 Refund Policy</h3>
                        <p class="text-gray-700 dark:text-gray-300 mb-4">
                            Refunds are generally not provided except in cases of technical errors or as required by law.
                            Contact us at <a href="mailto:{{ $supportEmail }}"
                                class="text-primary dark:text-primary-400 hover:underline">{{ $supportEmail }}</a> for
                            refund requests.
                        </p>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">6. Verification</h2>
                        <p class="text-gray-700 dark:text-gray-300 mb-3">We offer various verification services:</p>
                        <ul class="list-disc pl-6 mb-4 text-gray-700 dark:text-gray-300 space-y-2">
                            <li>Phone number verification (OTP)</li>
                            <li>Video selfie verification</li>
                            <li>Photo verification</li>
                            <li>Social media verification</li>
                        </ul>
                        <p class="text-gray-700 dark:text-gray-300 mb-4">
                            While we strive to verify users, we cannot guarantee the authenticity of all profiles.
                            Users are responsible for their own safety and due diligence.
                        </p>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">7. Privacy and Data Protection
                        </h2>
                        <p class="text-gray-700 dark:text-gray-300 mb-4">
                            Your privacy is important to us. Please review our <a href="{{ route('legal.privacy') }}"
                                class="text-primary dark:text-primary-400 hover:underline">Privacy Policy</a>
                            to understand how we collect, use, and protect your personal information.
                        </p>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">8. Intellectual Property</h2>
                        <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-3">8.1 Our Rights</h3>
                        <p class="text-gray-700 dark:text-gray-300 mb-4">
                            All content, features, and functionality on {{ config('app.name') }} are owned by us and
                            protected
                            by copyright, trademark, and other intellectual property laws.
                        </p>

                        <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-3">8.2 User Content</h3>
                        <p class="text-gray-700 dark:text-gray-300 mb-4">
                            You retain ownership of content you post. By posting content, you grant us a worldwide,
                            non-exclusive,
                            royalty-free license to use, reproduce, and display your content for platform operations.
                        </p>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">9. Disclaimers</h2>
                        <div class="bg-yellow-50 dark:bg-yellow-900/30 border-l-4 border-yellow-400 p-4 mb-4">
                            <p class="text-gray-800 dark:text-gray-200 font-semibold mb-2">IMPORTANT DISCLAIMERS:</p>
                            <ul class="list-disc pl-6 text-gray-700 dark:text-gray-300 space-y-2">
                                <li>We do not guarantee that you will find a suitable match</li>
                                <li>We are not responsible for user conduct or interactions</li>
                                <li>We do not conduct background checks (unless explicitly stated)</li>
                                <li>Users are solely responsible for their own safety</li>
                                <li>The platform is provided "AS IS" without warranties of any kind</li>
                            </ul>
                        </div>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">10. Limitation of Liability
                        </h2>
                        <p class="text-gray-700 dark:text-gray-300 mb-4">
                            To the maximum extent permitted by law, {{ config('app.name') }} shall not be liable for any
                            indirect, incidental, special, consequential, or punitive damages arising from your use of the
                            platform.
                        </p>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">11. Termination</h2>
                        <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-3">11.1 By You</h3>
                        <p class="text-gray-700 dark:text-gray-300 mb-4">
                            You may delete your account at any time. There is a 30-day grace period during which you can
                            recover your account.
                        </p>

                        <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-3">11.2 By Us</h3>
                        <p class="text-gray-700 dark:text-gray-300 mb-3">We may suspend or terminate your account if:</p>
                        <ul class="list-disc pl-6 mb-4 text-gray-700 dark:text-gray-300 space-y-2">
                            <li>You violate these Terms of Service</li>
                            <li>You engage in fraudulent activity</li>
                            <li>Your account is reported for misconduct</li>
                            <li>Required by law or legal process</li>
                        </ul>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">12. Dispute Resolution</h2>
                        <p class="text-gray-700 dark:text-gray-300 mb-4">
                            Any disputes arising from these Terms shall be resolved through arbitration in accordance with
                            the laws of [Your Jurisdiction]. You agree to waive your right to a jury trial.
                        </p>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">13. Changes to Terms</h2>
                        <p class="text-gray-700 dark:text-gray-300 mb-4">
                            We may modify these Terms at any time. We will notify you of material changes via email or
                            platform notification. Continued use of the platform after changes constitutes acceptance.
                        </p>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">14. Contact Information</h2>
                        <p class="text-gray-700 dark:text-gray-300 mb-3">For questions about these Terms, contact us:</p>
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <p class="text-gray-700 dark:text-gray-300"><strong>Email:</strong> <a
                                    href="mailto:{{ $legalEmail }}"
                                    class="text-primary dark:text-primary-400 hover:underline">{{ $legalEmail }}</a></p>
                            <p class="text-gray-700 dark:text-gray-300"><strong>Support:</strong> <a
                                    href="mailto:{{ $supportEmail }}"
                                    class="text-primary dark:text-primary-400 hover:underline">{{ $supportEmail }}</a></p>
                            <p class="text-gray-700 dark:text-gray-300"><strong>Address:</strong> [Your Company Address]</p>
                        </div>
                    </section>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex flex-wrap gap-4">
                        <a href="{{ route('legal.privacy') }}"
                            class="text-primary dark:text-primary-400 hover:underline">Privacy Policy</a>
                        <a href="{{ route('legal.cookies') }}"
                            class="text-primary dark:text-primary-400 hover:underline">Cookie Policy</a>
                        <a href="{{ route('home') }}" class="text-primary dark:text-primary-400 hover:underline">Back to
                            Home</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection