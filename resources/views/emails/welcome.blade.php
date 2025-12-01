<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to {{ config('app.name') }}</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #f3f4f6;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 20px;
            text-align: center;
        }
        .header h1 {
            color: #ffffff;
            font-size: 28px;
            margin: 0;
            font-weight: 600;
        }
        .content {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 20px;
            color: #1f2937;
            margin-bottom: 20px;
            font-weight: 600;
        }
        .text {
            color: #4b5563;
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 20px;
        }
        .button {
            display: inline-block;
            padding: 14px 32px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            margin: 20px 0;
        }
        .button:hover {
            opacity: 0.9;
        }
        .features {
            background-color: #f9fafb;
            padding: 30px;
            border-radius: 8px;
            margin: 30px 0;
        }
        .feature {
            display: flex;
            align-items: flex-start;
            margin-bottom: 20px;
        }
        .feature:last-child {
            margin-bottom: 0;
        }
        .feature-icon {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            flex-shrink: 0;
            font-size: 20px;
        }
        .feature-content h3 {
            margin: 0 0 5px 0;
            color: #1f2937;
            font-size: 16px;
            font-weight: 600;
        }
        .feature-content p {
            margin: 0;
            color: #6b7280;
            font-size: 14px;
        }
        .footer {
            background-color: #f9fafb;
            padding: 30px;
            text-align: center;
            color: #6b7280;
            font-size: 14px;
        }
        .footer a {
            color: #667eea;
            text-decoration: none;
        }
        @media only screen and (max-width: 600px) {
            .content {
                padding: 30px 20px;
            }
            .features {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Welcome to {{ config('app.name') }}!</h1>
        </div>

        <div class="content">
            <p class="greeting">Hello {{ $user->name }},</p>

            <p class="text">
                We're thrilled to have you join our community! {{ config('app.name') }} is the perfect place to find your life partner with confidence and ease.
            </p>

            <p class="text">
                Your profile has been created successfully. Now it's time to complete your profile and start discovering amazing people who share your values and aspirations.
            </p>

            <div style="text-align: center;">
                <a href="{{ route('discover') }}" class="button">Start Discovering Matches</a>
            </div>

            <div class="features">
                <div class="feature">
                    <div class="feature-icon">✓</div>
                    <div class="feature-content">
                        <h3>Complete Your Profile</h3>
                        <p>Add photos and details to help others get to know the real you</p>
                    </div>
                </div>

                <div class="feature">
                    <div class="feature-icon">♥</div>
                    <div class="feature-content">
                        <h3>Find Your Match</h3>
                        <p>Browse verified profiles and connect with compatible partners</p>
                    </div>
                </div>

                <div class="feature">
                    <div class="feature-icon">✉</div>
                    <div class="feature-content">
                        <h3>Start Conversations</h3>
                        <p>When you both like each other, start chatting and get to know them better</p>
                    </div>
                </div>

                <div class="feature">
                    <div class="feature-icon">🎥</div>
                    <div class="feature-content">
                        <h3>Video Verification</h3>
                        <p>Complete video verification to build trust and stand out from the crowd</p>
                    </div>
                </div>
            </div>

            <p class="text">
                If you have any questions or need assistance, our support team is always here to help. Simply reply to this email or visit our help center.
            </p>

            <p class="text">
                Here's to finding your perfect match!
            </p>

            <p class="text" style="margin-bottom: 0;">
                Best regards,<br>
                <strong>The {{ config('app.name') }} Team</strong>
            </p>
        </div>

        <div class="footer">
            <p>
                &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </p>
            <p>
                <a href="{{ route('profile.edit') }}">Manage Email Preferences</a> |
                <a href="{{ route('settings') }}">Settings</a>
            </p>
        </div>
    </div>
</body>
</html>
