<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Daily Activity on {{ config('app.name') }}</title>
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
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin: 30px 0;
        }
        .stat-card {
            background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
            border-radius: 8px;
            padding: 20px;
            text-align: center;
        }
        .stat-number {
            font-size: 32px;
            font-weight: 700;
            color: #667eea;
            margin-bottom: 5px;
        }
        .stat-label {
            font-size: 12px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .section {
            margin: 40px 0;
        }
        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e5e7eb;
        }
        .profile-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }
        .profile-card {
            background: #f9fafb;
            border-radius: 8px;
            overflow: hidden;
            text-decoration: none;
            color: inherit;
            transition: transform 0.2s;
        }
        .profile-card:hover {
            transform: translateY(-2px);
        }
        .profile-image {
            width: 100%;
            height: 150px;
            object-fit: cover;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .profile-info {
            padding: 15px;
        }
        .profile-name {
            font-size: 16px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 5px;
        }
        .profile-details {
            font-size: 14px;
            color: #6b7280;
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
            text-align: center;
        }
        .button:hover {
            opacity: 0.9;
        }
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #6b7280;
        }
        .empty-state-icon {
            font-size: 48px;
            margin-bottom: 10px;
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
            .stats-grid {
                grid-template-columns: 1fr;
            }
            .profile-grid {
                grid-template-columns: 1fr;
            }
            .content {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Your Daily Digest</h1>
            <p>{{ now()->format('l, F j, Y') }}</p>
        </div>

        <div class="content">
            <p class="greeting">Hello {{ $user->name }},</p>

            <p class="text">
                Here's what happened on {{ config('app.name') }} in the last 24 hours.
            </p>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number">{{ $newMatches->count() }}</div>
                    <div class="stat-label">New Matches</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ $newLikes->count() }}</div>
                    <div class="stat-label">New Likes</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ $unreadMessages }}</div>
                    <div class="stat-label">Unread Messages</div>
                </div>
            </div>

            @if($newMatches->count() > 0)
                <div class="section">
                    <h2 class="section-title">🎉 New Matches ({{ $newMatches->count() }})</h2>
                    <div class="profile-grid">
                        @foreach($newMatches->take(4) as $match)
                            <a href="{{ route('profile.show', $match->id) }}" class="profile-card">
                                @if($match->primaryPhoto)
                                    <img src="{{ $match->primaryPhoto->url }}" alt="{{ $match->name }}" class="profile-image">
                                @else
                                    <div class="profile-image" style="display: flex; align-items: center; justify-content: center; color: white; font-size: 48px; font-weight: 600;">
                                        {{ substr($match->name, 0, 1) }}
                                    </div>
                                @endif
                                <div class="profile-info">
                                    <div class="profile-name">{{ $match->name }}</div>
                                    <div class="profile-details">
                                        @if($match->profile)
                                            {{ $match->profile->age ?? '' }}
                                            @if($match->profile->age && $match->profile->city) • @endif
                                            {{ $match->profile->city ?? '' }}
                                        @endif
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                    @if($newMatches->count() > 4)
                        <div style="text-align: center; margin-top: 20px;">
                            <a href="{{ route('matches') }}" class="button">View All {{ $newMatches->count() }} Matches</a>
                        </div>
                    @endif
                </div>
            @endif

            @if($newLikes->count() > 0)
                <div class="section">
                    <h2 class="section-title">❤️ People Who Liked You ({{ $newLikes->count() }})</h2>
                    <p class="text">{{ $newLikes->count() }} {{ $newLikes->count() == 1 ? 'person has' : 'people have' }} shown interest in your profile. Like them back to start a conversation!</p>
                    <div style="text-align: center;">
                        <a href="{{ route('discover') }}" class="button">See Who Likes You</a>
                    </div>
                </div>
            @endif

            @if($unreadMessages > 0)
                <div class="section">
                    <h2 class="section-title">💬 Unread Messages ({{ $unreadMessages }})</h2>
                    <p class="text">You have {{ $unreadMessages }} unread {{ $unreadMessages == 1 ? 'message' : 'messages' }}. Don't keep your matches waiting!</p>
                    <div style="text-align: center;">
                        <a href="{{ route('messages') }}" class="button">Read Messages</a>
                    </div>
                </div>
            @endif

            @if($suggestedProfiles->count() > 0)
                <div class="section">
                    <h2 class="section-title">✨ Suggested for You</h2>
                    <p class="text">Based on your preferences, we think you'll like these profiles:</p>
                    <div class="profile-grid">
                        @foreach($suggestedProfiles->take(4) as $profile)
                            <a href="{{ route('profile.show', $profile->id) }}" class="profile-card">
                                @if($profile->primaryPhoto)
                                    <img src="{{ $profile->primaryPhoto->url }}" alt="{{ $profile->name }}" class="profile-image">
                                @else
                                    <div class="profile-image" style="display: flex; align-items: center; justify-content: center; color: white; font-size: 48px; font-weight: 600;">
                                        {{ substr($profile->name, 0, 1) }}
                                    </div>
                                @endif
                                <div class="profile-info">
                                    <div class="profile-name">{{ $profile->name }}</div>
                                    <div class="profile-details">
                                        @if($profile->profile)
                                            {{ $profile->profile->age ?? '' }}
                                            @if($profile->profile->age && $profile->profile->city) • @endif
                                            {{ $profile->profile->city ?? '' }}
                                        @endif
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                    <div style="text-align: center; margin-top: 20px;">
                        <a href="{{ route('discover') }}" class="button">Discover More Profiles</a>
                    </div>
                </div>
            @endif

            @if($newMatches->count() == 0 && $newLikes->count() == 0 && $unreadMessages == 0)
                <div class="empty-state">
                    <div class="empty-state-icon">🔍</div>
                    <p class="text">No new activity today, but there are plenty of amazing people waiting to meet you!</p>
                    <a href="{{ route('discover') }}" class="button">Start Discovering</a>
                </div>
            @endif

            <p class="text" style="margin-top: 40px;">
                Keep your profile up to date and stay active to increase your chances of finding the perfect match!
            </p>

            <p class="text" style="margin-bottom: 0;">
                Happy matching,<br>
                <strong>The {{ config('app.name') }} Team</strong>
            </p>
        </div>

        <div class="footer">
            <p>
                &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </p>
            <p>
                <a href="{{ route('settings') }}">Email Preferences</a> |
                <a href="{{ route('profile.edit') }}">Update Profile</a>
            </p>
            <p style="margin-top: 15px; font-size: 12px;">
                You're receiving this email because you subscribed to daily digest emails.<br>
                <a href="{{ route('settings') }}">Unsubscribe from daily digest</a>
            </p>
        </div>
    </div>
</body>
</html>
