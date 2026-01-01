# Analytics & Engagement Tracking System Documentation

## Overview

A comprehensive analytics and engagement tracking system for the matrimony application that tracks user behavior, generates insights, and provides detailed metrics for both users and administrators.

## Features Implemented

### 1. Database Schema

#### Tables Created:
- **profile_views** - Tracks profile viewing activity
- **user_activities** - Tracks all user actions
- **engagement_metrics** - Daily aggregate metrics per user

### 2. Core Components

#### Models
- `ProfileView` - Track who viewed whose profile
- `UserActivity` - Track all user actions with 25+ activity types
- `EngagementMetric` - Daily engagement metrics with calculated scores

#### Services
- `AnalyticsService` - Central service for all analytics operations
  - Profile view tracking
  - Activity tracking
  - Metrics calculation
  - Admin analytics
  - Cohort analysis
  - Conversion funnel tracking

#### Controllers
- `AnalyticsController` - Handles both user and admin analytics routes

#### Middleware
- `TrackUserActivity` - Automatically tracks page views and time spent

#### Console Commands
- `CalculateDailyMetrics` - Aggregates daily metrics (run via cron)

## Installation & Setup

### 1. Run Migrations

```bash
php artisan migrate
```

This will create the three analytics tables:
- 2025_12_31_000001_create_profile_views_table
- 2025_12_31_000002_create_user_activities_table
- 2025_12_31_000003_create_engagement_metrics_table

### 2. Register Middleware

Add to `app/Http/Kernel.php` in the `$middlewareGroups['web']` array:

```php
\App\Http\Middleware\TrackUserActivity::class,
```

### 3. Schedule Daily Metrics Calculation

Add to `app/Console/Kernel.php` in the `schedule()` method:

```php
$schedule->command('analytics:calculate-daily-metrics')->dailyAt('01:00');
```

### 4. Add Chart.js to Layout

If not already present, add to your main layout (`resources/views/layouts/app.blade.php`):

```html
<!-- In the head section -->
@stack('styles')

<!-- Before closing body tag -->
@stack('scripts')
```

### 5. Update User Model (Optional)

Add `is_admin` field to users table if not present for admin access control.

## Usage

### User Analytics

Users can access their analytics at:
- `/analytics` - Main dashboard with charts and metrics
- `/analytics/profile-views` - See who viewed their profile
- `/analytics/engagement` - API endpoint for engagement stats
- `/analytics/export` - Export data as CSV

### Admin Analytics

Admins can access analytics at:
- `/admin/analytics` - Main admin dashboard
- `/admin/analytics/users` - User demographics and metrics
- `/admin/analytics/engagement` - Engagement analytics
- `/admin/analytics/revenue` - Revenue and subscription metrics
- `/admin/analytics/cohorts` - Cohort retention analysis

### Tracking Activities

Activities are tracked automatically via:

1. **Middleware** - Tracks page views and time spent
2. **Controllers** - Track specific actions:

```php
// Example: Track a profile edit
$analyticsService->trackActivity(
    $user,
    UserActivity::TYPE_PROFILE_EDIT,
    ['fields_updated' => ['name', 'bio']]
);

// Example: Track a profile view
$analyticsService->trackProfileView($viewer, $profile, 'discover', 120);
```

### Activity Types

Available activity types (defined in `UserActivity` model):
- `login` / `logout`
- `profile_view` / `profile_edit`
- `photo_upload` / `photo_delete`
- `message_sent` / `message_received`
- `like_sent` / `like_received`
- `match_created`
- `search_performed` / `filter_applied`
- `subscription_purchased` / `subscription_cancelled`
- `profile_verified`
- `story_posted` / `story_viewed`
- `game_played`
- `icebreaker_sent`
- `save_search`
- And more...

## Metrics Tracked

### User Metrics
- Profile views (given and received)
- Unique profile viewers
- Likes sent/received
- Messages sent/received
- Matches created
- Searches performed
- Time spent on platform
- Engagement score (0-100)
- Response rate

### Admin Metrics
- Daily/Monthly Active Users (DAU/MAU)
- New user signups
- Active users
- Verified users
- Verification rate
- Total profile views
- Total messages
- Total matches
- Activity breakdown by type
- Device distribution (mobile/tablet/desktop)
- Peak usage hours
- Conversion funnel
- Revenue metrics (MRR, ARR, ARPU)
- Subscription conversion rate
- Cohort retention analysis

## Charts & Visualizations

Uses Chart.js for:
- Line charts (engagement trends, DAU)
- Bar charts (activity breakdown, demographics)
- Pie/Doughnut charts (sources, device distribution)
- Progress bars (retention, conversion rates)

## Performance Optimizations

### Caching
- User metrics cached for 5 minutes
- Admin metrics cached for 5 minutes
- Cohort analysis cached for 1 hour
- Peak hours cached for 30 minutes
- Demographics cached for 30 minutes

### Database Indexes
All tables have appropriate indexes on:
- Foreign keys
- Date columns
- Frequently queried columns

### Aggregation
- Daily metrics are pre-aggregated via cron job
- Heavy calculations run in background
- Pagination for large datasets

## Privacy & GDPR Compliance

### User Rights
- Users can view all their tracked data
- Export functionality available (CSV)
- Data is anonymized after user deletion (implement in User model's deleting event)

### Data Retention
Implement data retention policies:

```php
// Example: Delete old activities after 1 year
UserActivity::where('created_at', '<', now()->subYear())->delete();
```

## API Endpoints

### User Endpoints
```
GET  /analytics                 - User analytics dashboard
GET  /analytics/profile-views   - Profile viewers list
GET  /analytics/engagement      - Engagement stats (JSON)
GET  /analytics/export          - Export CSV
```

### Admin Endpoints
```
GET  /admin/analytics                    - Admin dashboard
GET  /admin/analytics/users              - User metrics
GET  /admin/analytics/engagement         - Engagement metrics
GET  /admin/analytics/revenue            - Revenue metrics
GET  /admin/analytics/cohorts            - Cohort analysis
GET  /admin/analytics/conversion-funnel  - Conversion funnel (JSON)
POST /admin/analytics/clear-cache        - Clear analytics cache
```

## Examples

### Track Profile View
```php
use App\Services\AnalyticsService;

$analyticsService = app(AnalyticsService::class);
$analyticsService->trackProfileView(
    $viewer,      // User who viewed
    $profile,     // User whose profile was viewed
    'discover',   // Source: discover, search, match, recommendation, direct
    120           // Duration in seconds (optional)
);
```

### Get User Metrics
```php
$metrics = $analyticsService->getUserEngagementMetrics($user, 30); // Last 30 days

// Access totals
$totalViews = $metrics['totals']['total_profile_views'];
$uniqueViewers = $metrics['totals']['unique_profile_viewers'];
$engagementScore = $metrics['totals']['avg_engagement_score'];

// Access daily breakdown
foreach ($metrics['daily_metrics'] as $day) {
    echo "{$day['date']}: {$day['profile_views']} views\n";
}
```

### Get Admin Metrics
```php
$metrics = $analyticsService->getAdminMetrics('2024-01-01', '2024-01-31');

// User metrics
$totalUsers = $metrics['users']['total'];
$newUsers = $metrics['users']['new'];
$activeUsers = $metrics['users']['active'];

// Engagement metrics
$totalMessages = $metrics['engagement']['total_messages'];
$totalMatches = $metrics['engagement']['total_matches'];

// Revenue metrics
$revenue = $metrics['subscriptions']['revenue'];
$conversionRate = $metrics['subscriptions']['conversion_rate'];
```

### Cohort Analysis
```php
$cohortData = $analyticsService->getCohortAnalysis('2024-01-01');

echo "Cohort size: " . $cohortData['cohort_size'] . "\n";

foreach ($cohortData['retention'] as $week) {
    echo "Week {$week['week']}: {$week['retention_rate']}% retained\n";
}
```

## Customization

### Add New Activity Types

1. Add constant to `UserActivity` model:
```php
const TYPE_NEW_ACTIVITY = 'new_activity';
```

2. Add to `getActivityTypes()` method

3. Track in relevant controller:
```php
$analyticsService->trackActivity(
    $user,
    UserActivity::TYPE_NEW_ACTIVITY,
    ['additional_data' => 'value']
);
```

### Add New Metrics

1. Add column to `engagement_metrics` table via migration
2. Update `EngagementMetric` model `$fillable` and `$casts`
3. Update `CalculateDailyMetrics` command to calculate new metric
4. Update views to display new metric

### Customize Charts

Edit the Chart.js configuration in the view files:
- `resources/views/analytics/index.blade.php`
- `resources/views/admin/analytics/dashboard.blade.php`
- etc.

## Testing

### Manual Testing

```bash
# Track some test activities
php artisan tinker

$user = User::first();
$analyticsService = app(\App\Services\AnalyticsService::class);

// Track activities
$analyticsService->trackActivity($user, 'login', []);
$analyticsService->trackActivity($user, 'profile_view', ['profile_id' => 2]);

// Calculate metrics
php artisan analytics:calculate-daily-metrics

# View analytics
Visit /analytics or /admin/analytics
```

### Automated Testing

Create tests for:
- Activity tracking
- Metrics calculation
- API endpoints
- Privacy compliance

## Troubleshooting

### Metrics Not Showing
1. Ensure migrations have run
2. Run `php artisan analytics:calculate-daily-metrics` manually
3. Check if middleware is registered
4. Clear cache: `php artisan cache:clear`

### Performance Issues
1. Check database indexes
2. Reduce cache TTL
3. Implement queue for heavy calculations
4. Archive old data

### Missing Data
1. Verify tracking code in controllers
2. Check middleware is active
3. Review error logs
4. Ensure user is authenticated

## Future Enhancements

### Recommended Additions
1. Real-time analytics with WebSockets
2. Custom reports builder
3. A/B testing framework
4. Predictive analytics (ML-based)
5. Mobile app analytics integration
6. Email campaign tracking
7. Event tracking (custom events)
8. Funnel visualization
9. User journey mapping
10. Anomaly detection

### Advanced Features
- Segment users by behavior
- Automated insights generation
- Goal tracking
- Attribution modeling
- Heatmaps
- Session recordings
- Feature usage analytics

## Best Practices

1. **Don't Track PII Directly** - Store user IDs, not sensitive data
2. **Aggregate When Possible** - Use daily metrics instead of querying raw data
3. **Cache Aggressively** - Analytics data doesn't need to be real-time
4. **Index Everything** - Especially date and user_id columns
5. **Archive Old Data** - Move data older than 1 year to cold storage
6. **Monitor Performance** - Track query times and optimize slow queries
7. **Respect Privacy** - Always provide opt-out options
8. **Test Thoroughly** - Ensure accurate data collection

## Support

For questions or issues:
1. Check this documentation
2. Review the code comments
3. Check Laravel logs in `storage/logs`
4. Review database queries with Laravel Debugbar

## Credits

Built with:
- Laravel 10.x
- Chart.js 4.4.0
- Tailwind CSS 3.x
- Alpine.js (if used)
- MySQL/PostgreSQL

## License

Part of the Matrimony Application - All Rights Reserved
