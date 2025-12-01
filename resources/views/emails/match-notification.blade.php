<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>You have a new match!</title>
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
        .header p {
            color: rgba(255, 255, 255, 0.9);
            font-size: 16px;
            margin: 10px 0 0 0;
        }
        .content {
            padding: 40px 30px;
        }
        .match-card {
            background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
            border-radius: 12px;
            padding: 30px;
            text-align: center;
            margin: 20px 0;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        .profile-image {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 4px solid #ffffff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            object-fit: cover;
            margin: 0 auto 20px auto;
            display: block;
        }
        .match-name {
            font-size: 24px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 10px;
        }
        .match-info {
            color: #6b7280;
            font-size: 16px;
            margin-bottom: 20px;
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
            margin: 10px 0;
        }
        .button:hover {
            opacity: 0.9;
        }
        .button-secondary {
            display: inline-block;
            padding: 14px 32px;
            background: #ffffff;
            color: #667eea;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            margin: 10px 0;
            border: 2px solid #667eea;
        }
        .celebration {
            text-align: center;
            font-size: 48px;
            margin: 20px 0;
        }
        .tips {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 20px;
            margin: 30px 0;
            border-radius: 4px;
        }
        .tips h3 {
            color: #92400e;
            font-size: 16px;
            margin: 0 0 10px 0;
            font-weight: 600;
        }
        .tips ul {
            margin: 0;
            padding-left: 20px;
            color: #78350f;
        }
        .tips li {
            margin-bottom: 8px;
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
            .match-card {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>It's a Match!</h1>
            <p>You and {{ $matchedUser->name }} like each other</p>
        </div>

        <div class="content">
            <div class="celebration">🎉 ✨ 💕</div>

            <div class="match-card">
                @if($matchedUser->primaryPhoto)
                    <img src="{{ $matchedUser->primaryPhoto->url }}" alt="{{ $matchedUser->name }}" class="profile-image">
                @else
                    <div class="profile-image" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 48px; font-weight: 600;">
                        {{ substr($matchedUser->name, 0, 1) }}
                    </div>
                @endif

                <div class="match-name">{{ $matchedUser->name }}</div>
                <div class="match-info">
                    @if($matchedUser->profile)
                        {{ $matchedUser->profile->age ?? '' }}
                        @if($matchedUser->profile->age && $matchedUser->profile->city) • @endif
                        {{ $matchedUser->profile->city ?? '' }}
                    @endif
                </div>
            </div>

            <p class="text">
                Congratulations! {{ $matchedUser->name }} has also shown interest in your profile. This is your chance to start a meaningful conversation and get to know each other better.
            </p>

            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ route('messages.create', $matchedUser) }}" class="button">Send a Message</a>
                <br>
                <a href="{{ route('profile.show', $matchedUser) }}" class="button-secondary">View Full Profile</a>
            </div>

            <div class="tips">
                <h3>💡 Tips for Starting a Great Conversation</h3>
                <ul>
                    <li>Read their profile carefully and mention something specific you noticed</li>
                    <li>Ask open-ended questions to encourage deeper conversations</li>
                    <li>Be genuine and authentic - let your personality shine through</li>
                    <li>Respond thoughtfully and keep the conversation flowing</li>
                    <li>Be respectful and considerate in all your interactions</li>
                </ul>
            </div>

            <p class="text">
                Don't keep them waiting! Start chatting now and see where this connection takes you.
            </p>

            <p class="text" style="margin-bottom: 0;">
                Best of luck,<br>
                <strong>The {{ config('app.name') }} Team</strong>
            </p>
        </div>

        <div class="footer">
            <p>
                &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </p>
            <p>
                <a href="{{ route('matches') }}">View All Matches</a> |
                <a href="{{ route('settings') }}">Notification Settings</a>
            </p>
        </div>
    </div>
</body>
</html>
