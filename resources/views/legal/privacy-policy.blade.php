@extends('layouts.public')

@section('title', 'Privacy Policy - ' . config('app.name'))
@section('meta_description', 'Read our privacy policy to understand how we collect, use, and protect your personal information on our matrimony platform.')

@php
    $domain = parse_url(config('app.url'), PHP_URL_HOST) ?: 'matrimony.com';
    $privacyEmail = 'privacy@' . $domain;
@endphp

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Privacy Policy</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-8">Last Updated: {{ now()->format('F d, Y') }}</p>

            <div class="prose prose-blue dark:prose-invert max-w-none">
                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">1. Introduction</h2>
                    <p class="text-gray-700 dark:text-gray-300 mb-4">
                        Welcome to {{ config('app.name') }} ("we," "our," or "us"). We are committed to protecting your personal
                        information and your right to privacy. This Privacy Policy explains how we collect, use, disclose,
                        and safeguard your information when you use our matrimony platform.
                    </p>
                    <p class="text-gray-700 dark:text-gray-300">
                        Please read this privacy policy carefully. If you do not agree with the terms of this privacy policy,
                        please do not access the platform.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">2. Information We Collect</h2>

                    <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-3">2.1 Personal Information</h3>
                    <p class="text-gray-700 dark:text-gray-300 mb-3">We collect the following types of personal information:</p>
                    <ul class="list-disc pl-6 mb-4 text-gray-700 dark:text-gray-300 space-y-2">
                        <li><strong>Profile Information:</strong> Name, date of birth, gender, height, weight, religion, caste, education, occupation, income</li>
                        <li><strong>Contact Information:</strong> Email address, phone number, city, state, country</li>
                        <li><strong>Photos and Videos:</strong> Profile photos, video selfies for verification</li>
                        <li><strong>Preferences:</strong> Partner preferences, hobbies, interests, lifestyle choices</li>
                        <li><strong>Communication Data:</strong> Messages, voice notes, video call data</li>
                        <li><strong>Verification Data:</strong> ID documents, social media profiles (LinkedIn, Instagram, Facebook)</li>
                    </ul>

                    <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-3">2.2 Automatically Collected Information</h3>
                    <ul class="list-disc pl-6 mb-4 text-gray-700 dark:text-gray-300 space-y-2">
                        <li><strong>Usage Data:</strong> IP address, browser type, device information, pages visited, time spent</li>
                        <li><strong>Analytics Data:</strong> Profile views, likes, matches, messages sent/received</li>
                        <li><strong>Cookies:</strong> We use cookies and similar tracking technologies to track activity</li>
                    </ul>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">3. How We Use Your Information</h2>
                    <p class="text-gray-700 dark:text-gray-300 mb-3">We use your information for the following purposes:</p>
                    <ul class="list-disc pl-6 mb-4 text-gray-700 dark:text-gray-300 space-y-2">
                        <li>To provide and maintain our matrimony platform services</li>
                        <li>To match you with compatible partners based on preferences</li>
                        <li>To verify your identity and ensure platform safety</li>
                        <li>To process payments for premium subscriptions</li>
                        <li>To send you notifications about matches, messages, and platform updates</li>
                        <li>To improve our services through analytics and user behavior analysis</li>
                        <li>To prevent fraud and ensure security</li>
                        <li>To comply with legal obligations</li>
                    </ul>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">4. Information Sharing and Disclosure</h2>

                    <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-3">4.1 With Other Users</h3>
                    <p class="text-gray-700 dark:text-gray-300 mb-4">
                        Your profile information, photos, and preferences are visible to other users for matchmaking purposes.
                        You control the visibility of certain information through your privacy settings.
                    </p>

                    <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-3">4.2 With Service Providers</h3>
                    <p class="text-gray-700 dark:text-gray-300 mb-3">We may share your information with third-party service providers:</p>
                    <ul class="list-disc pl-6 mb-4 text-gray-700 dark:text-gray-300 space-y-2">
                        <li>Payment processors (Razorpay) for subscription payments</li>
                        <li>SMS providers (Twilio/MSG91) for OTP verification</li>
                        <li>Cloud storage providers (AWS S3) for photos and videos</li>
                        <li>Video calling services (Agora.io) for video calls</li>
                        <li>Analytics services for platform improvement</li>
                    </ul>

                    <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-3">4.3 Legal Requirements</h3>
                    <p class="text-gray-700 dark:text-gray-300 mb-4">
                        We may disclose your information if required by law or in response to valid requests by public authorities.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">5. Data Security</h2>
                    <p class="text-gray-700 dark:text-gray-300 mb-3">We implement appropriate security measures to protect your information:</p>
                    <ul class="list-disc pl-6 mb-4 text-gray-700 dark:text-gray-300 space-y-2">
                        <li>Encryption of sensitive data in transit and at rest</li>
                        <li>Secure HTTPS connections</li>
                        <li>Regular security audits and vulnerability assessments</li>
                        <li>Access controls and authentication mechanisms</li>
                        <li>Regular backups and disaster recovery procedures</li>
                    </ul>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">6. Your Privacy Rights</h2>
                    <p class="text-gray-700 dark:text-gray-300 mb-3">Under GDPR and other privacy laws, you have the following rights:</p>
                    <ul class="list-disc pl-6 mb-4 text-gray-700 dark:text-gray-300 space-y-2">
                        <li><strong>Right to Access:</strong> Request a copy of your personal data</li>
                        <li><strong>Right to Rectification:</strong> Request correction of inaccurate data</li>
                        <li><strong>Right to Erasure:</strong> Request deletion of your data</li>
                        <li><strong>Right to Restriction:</strong> Request restriction of processing</li>
                        <li><strong>Right to Data Portability:</strong> Receive your data in a portable format</li>
                        <li><strong>Right to Object:</strong> Object to certain processing activities</li>
                        <li><strong>Right to Withdraw Consent:</strong> Withdraw consent at any time</li>
                    </ul>
                    <p class="text-gray-700 dark:text-gray-300">
                        To exercise these rights, please contact us at <a href="mailto:{{ $privacyEmail }}" class="text-primary dark:text-primary-400 hover:underline">{{ $privacyEmail }}</a>
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">7. Data Retention</h2>
                    <p class="text-gray-700 dark:text-gray-300 mb-4">
                        We retain your personal information only for as long as necessary to fulfill the purposes outlined in this
                        privacy policy. When you delete your account, we provide a 30-day grace period during which you can recover
                        your account. After this period, your data will be permanently deleted.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">8. Cookies and Tracking Technologies</h2>
                    <p class="text-gray-700 dark:text-gray-300 mb-3">We use cookies and similar technologies for:</p>
                    <ul class="list-disc pl-6 mb-4 text-gray-700 dark:text-gray-300 space-y-2">
                        <li>Authentication and session management</li>
                        <li>Remembering your preferences</li>
                        <li>Analytics and performance monitoring</li>
                        <li>Advertising and marketing (with your consent)</li>
                    </ul>
                    <p class="text-gray-700 dark:text-gray-300">
                        You can control cookies through your browser settings. See our <a href="{{ route('legal.cookies') }}" class="text-primary dark:text-primary-400 hover:underline">Cookie Policy</a> for more details.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">9. Children's Privacy</h2>
                    <p class="text-gray-700 dark:text-gray-300 mb-4">
                        Our platform is intended for users aged 18 and above. We do not knowingly collect personal information
                        from individuals under 18 years of age.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">10. International Data Transfers</h2>
                    <p class="text-gray-700 dark:text-gray-300 mb-4">
                        Your information may be transferred to and processed in countries other than your country of residence.
                        We ensure appropriate safeguards are in place for such transfers.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">11. Changes to This Privacy Policy</h2>
                    <p class="text-gray-700 dark:text-gray-300 mb-4">
                        We may update this privacy policy from time to time. We will notify you of any changes by posting the
                        new privacy policy on this page and updating the "Last Updated" date.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">12. Contact Us</h2>
                    <p class="text-gray-700 dark:text-gray-300 mb-3">If you have questions about this Privacy Policy, please contact us:</p>
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <p class="text-gray-700 dark:text-gray-300"><strong>Email:</strong> <a href="mailto:{{ $privacyEmail }}" class="text-primary dark:text-primary-400 hover:underline">{{ $privacyEmail }}</a></p>
                        <p class="text-gray-700 dark:text-gray-300"><strong>Address:</strong> [Your Company Address]</p>
                    </div>
                </section>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('legal.terms') }}" class="text-primary dark:text-primary-400 hover:underline">Terms of Service</a>
                    <a href="{{ route('legal.cookies') }}" class="text-primary dark:text-primary-400 hover:underline">Cookie Policy</a>
                    <a href="{{ route('home') }}" class="text-primary dark:text-primary-400 hover:underline">Back to Home</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection