# Analytics System - Quick Setup Guide

## Immediate Next Steps (5 Minutes)

### Step 1: Register the Middleware

Edit `app/Http/Kernel.php` and add the TrackUserActivity middleware:

```php
protected $middlewareGroups = [
    'web' => [
        \App\Http\Middleware\EncryptCookies::class,
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \App\Http\Middleware\VerifyCsrfToken::class,
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
        \App\Http\Middleware\TrackUserActivity::class, // ← ADD THIS LINE
    ],
];
```

### Step 2: Schedule the Daily Metrics Command

Edit `app/Console/Kernel.php` and add to the schedule method:

```php
protected function schedule(Schedule $schedule)
{
    // Calculate daily engagement metrics at 1 AM
    $schedule->command('analytics:calculate-daily-metrics')
             ->dailyAt('01:00');
}
```

### Step 3: Update User Model (if needed)

If your users table doesn't have an `is_admin` column, create a migration:

```bash
php artisan make:migration add_is_admin_to_users_table
```

Then edit the migration:

```php
public function up()
{
    Schema::table('users', function (Blueprint $table) {
        $table->boolean('is_admin')->default(false)->after('email');
    });
}
```

Run it:

```bash
php artisan migrate
```

### Step 4: Add Navigation Links

#### For User Dashboard Navigation

Edit `resources/views/layouts/navigation.blade.php` or your main navigation file and add:

```html
<!-- Analytics Link -->
<a href="{{ route('analytics.index') }}"
   class="nav-link {{ request()->routeIs('analytics.*') ? 'active' : '' }}">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
    </svg>
    Analytics
</a>
```

#### For Admin Dashboard Navigation

Add to admin navigation:

```html
<!-- Admin Analytics Link -->
<a href="{{ route('admin.analytics.dashboard') }}"
   class="nav-link {{ request()->routeIs('admin.analytics.*') ? 'active' : '' }}">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
    </svg>
    Analytics
</a>
```

### Step 5: Test the System

1. **Make yourself an admin:**

```bash
php artisan tinker
```

```php
$user = User::where('email', 'your-email@example.com')->first();
$user->is_admin = true;
$user->save();
exit
```

2. **Generate some test data:**

```bash
php artisan tinker
```

```php
$analyticsService = app(\App\Services\AnalyticsService::class);
$user = User::first();

// Track some activities
$analyticsService->trackActivity($user, 'login', []);
$analyticsService->trackActivity($user, 'profile_view', ['profile_id' => 2]);
$analyticsService->trackActivity($user, 'search_performed', ['filters' => ['age_min' => 25]]);

// Track profile view
$viewer = User::find(1);
$profile = User::find(2);
$analyticsService->trackProfileView($viewer, $profile, 'discover', 120);

exit
```

3. **Calculate metrics:**

```bash
php artisan analytics:calculate-daily-metrics
```

4. **Visit the dashboards:**

- User Analytics: `http://your-app.test/analytics`
- Admin Analytics: `http://your-app.test/admin/analytics`

### Step 6: Optional - Configure Redis Cache

For better performance, use Redis:

**.env:**
```env
CACHE_DRIVER=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

Install Redis PHP extension if not installed:
```bash
pecl install redis
```

Clear config cache:
```bash
php artisan config:cache
```

---

## Verification Checklist

- [ ] Migrations run successfully
- [ ] Middleware registered in Kernel.php
- [ ] Cron job scheduled in Kernel.php
- [ ] is_admin column exists in users table
- [ ] At least one admin user created
- [ ] Navigation links added
- [ ] Test data created
- [ ] Daily metrics command runs successfully
- [ ] User analytics dashboard accessible
- [ ] Admin analytics dashboard accessible
- [ ] Charts display correctly

---

## Quick Commands Reference

```bash
# Run migrations
php artisan migrate

# Calculate daily metrics (manual)
php artisan analytics:calculate-daily-metrics

# Calculate metrics for specific date
php artisan analytics:calculate-daily-metrics 2024-12-30

# Clear analytics cache
php artisan cache:clear

# Make user admin
php artisan tinker
User::find(1)->update(['is_admin' => true]);
```

---

## Troubleshooting

### Charts not showing?
1. Check browser console for JavaScript errors
2. Ensure Chart.js is loaded (check page source)
3. Clear browser cache

### No data in analytics?
1. Run `php artisan analytics:calculate-daily-metrics`
2. Ensure middleware is registered
3. Create test activities (see Step 5 above)

### Permission denied?
1. Verify user has `is_admin = true`
2. Check route middleware in web.php
3. Clear route cache: `php artisan route:clear`

### Slow performance?
1. Enable Redis cache
2. Run: `php artisan config:cache`
3. Run: `php artisan route:cache`
4. Run: `php artisan view:cache`

---

## What's Next?

After setup is complete, you can:

1. **Customize Charts** - Edit view files to change colors, types
2. **Add More Metrics** - Extend EngagementMetric model
3. **Create Reports** - Build custom report views
4. **Add Alerts** - Send notifications based on metrics
5. **Integrate Email** - Track email campaign performance
6. **Add Goals** - Track conversion goals
7. **Export Data** - Build advanced export features

---

## Need Help?

- Read: `ANALYTICS_DOCUMENTATION.md` for complete docs
- Read: `ANALYTICS_IMPLEMENTATION_SUMMARY.md` for overview
- Check Laravel logs: `storage/logs/laravel.log`
- Check database: Use TablePlus or phpMyAdmin

---

## Success!

Once all steps are complete, you'll have a fully functional analytics system that:
- Tracks user behavior automatically
- Provides beautiful dashboards
- Calculates metrics daily
- Helps improve the platform
- Gives actionable insights

**Time to complete setup: ~5 minutes**
**Time to see results: Immediately after creating test data**

Happy analyzing! 📊
