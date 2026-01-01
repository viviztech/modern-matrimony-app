# Laravel Cloud Deployment Guide

**Application:** Modern Matrimony Platform
**Date:** January 1, 2026
**Laravel Version:** 12.x
**Target Platform:** Laravel Cloud

---

## 📋 Table of Contents

1. [Prerequisites](#prerequisites)
2. [Pre-Deployment Preparation](#pre-deployment-preparation)
3. [Laravel Cloud Account Setup](#laravel-cloud-account-setup)
4. [Environment Configuration](#environment-configuration)
5. [Database Setup](#database-setup)
6. [Redis Configuration](#redis-configuration)
7. [File Storage Setup](#file-storage-setup)
8. [Domain Configuration](#domain-configuration)
9. [Deployment Process](#deployment-process)
10. [Post-Deployment Tasks](#post-deployment-tasks)
11. [Environment Variables](#environment-variables)
12. [Scheduled Tasks](#scheduled-tasks)
13. [Queue Workers](#queue-workers)
14. [SSL Certificate](#ssl-certificate)
15. [Monitoring & Logs](#monitoring--logs)
16. [Performance Optimization](#performance-optimization)
17. [Troubleshooting](#troubleshooting)

---

## Prerequisites

Before deploying to Laravel Cloud, ensure you have:

- ✅ GitHub repository with latest code pushed
- ✅ Laravel Cloud account (sign up at https://cloud.laravel.com)
- ✅ Domain name (optional, Laravel Cloud provides subdomain)
- ✅ Database ready for production data
- ✅ Payment gateway credentials (Stripe/Razorpay)
- ✅ Pusher credentials for real-time features
- ✅ Email service credentials (SMTP/Mailgun/SES)
- ✅ Sentry account for error tracking (optional)

---

## Pre-Deployment Preparation

### Step 1: Verify Production Configuration

**1.1 Check composer.json dependencies:**
```bash
# Ensure all production dependencies are listed
composer install --no-dev --optimize-autoloader
```

**1.2 Update .env.example:**
```bash
# Make sure .env.example has all required variables
cp .env .env.example
# Remove sensitive values from .env.example
```

**1.3 Test production build locally:**
```bash
# Set environment to production
APP_ENV=production

# Build assets
npm run build

# Clear and cache config
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Test the application
php artisan serve
```

### Step 2: Prepare Database Migrations

**2.1 Verify all migrations are committed:**
```bash
# Check migration files
ls -la database/migrations/

# Test migrations on fresh database
php artisan migrate:fresh --seed
```

**2.2 Create production seeders (optional):**
```bash
# Create seeder for essential data only
php artisan make:seeder ProductionSeeder
```

### Step 3: Security Checklist

- ✅ Remove debug mode in production (.env: `APP_DEBUG=false`)
- ✅ Generate strong APP_KEY
- ✅ Verify CSRF protection is enabled
- ✅ Check all API credentials are secure
- ✅ Review SecurityHeaders middleware configuration
- ✅ Ensure rate limiting is properly configured
- ✅ Verify file upload validations

### Step 4: Commit Final Changes

```bash
# Add any missing files
git add -A

# Commit
git commit -m "Prepare for Laravel Cloud deployment"

# Push to GitHub
git push origin main
```

---

## Laravel Cloud Account Setup

### Step 1: Sign Up for Laravel Cloud

1. **Visit Laravel Cloud:**
   - Go to: https://cloud.laravel.com
   - Click "Sign Up" or "Get Started"

2. **Create Account:**
   - Use your GitHub account for authentication
   - Grant Laravel Cloud access to your repositories
   - Complete account verification

3. **Choose Plan:**
   - **Starter:** $19/month (1 GB RAM, 1 vCPU)
   - **Professional:** $39/month (2 GB RAM, 2 vCPU) ← **Recommended for this app**
   - **Business:** $99/month (4 GB RAM, 4 vCPU)

### Step 2: Create New Project

1. **Click "New Project"**

2. **Project Configuration:**
   ```
   Project Name: matrimony-app (or your preferred name)
   Region: Choose closest to your users
     - US East (Ohio)
     - US West (Oregon)
     - EU (Frankfurt)
     - Asia Pacific (Singapore)
     - Asia Pacific (Mumbai) ← Recommended for Indian users

   GitHub Repository: viviztech/modern-matrimony-app
   Branch: main
   ```

3. **Configure Build Settings:**
   ```
   PHP Version: 8.3 (recommended)
   Node Version: 20.x (latest LTS)
   Build Command: npm ci && npm run build
   ```

---

## Environment Configuration

### Step 1: Access Environment Variables

1. Navigate to your project dashboard
2. Click on "Environment" tab
3. Click "Add Variable" for each environment variable

### Step 2: Essential Environment Variables

**Application Settings:**
```env
APP_NAME="Modern Matrimony"
APP_ENV=production
APP_KEY=base64:GENERATE_NEW_KEY_HERE
APP_DEBUG=false
APP_TIMEZONE=Asia/Kolkata
APP_URL=https://your-domain.com

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_IN
APP_MAINTENANCE_DRIVER=file
```

**Database Configuration:**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=matrimony_production
DB_USERNAME=your_db_user
DB_PASSWORD=your_strong_password

# Laravel Cloud provides these automatically
# You'll get these values from the Laravel Cloud dashboard
```

**Redis Configuration:**
```env
REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
REDIS_DB=0

CACHE_STORE=redis
CACHE_PREFIX=matrimony_cache

SESSION_DRIVER=redis
SESSION_LIFETIME=120
SESSION_ENCRYPT=true
SESSION_PATH=/
SESSION_DOMAIN=null
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=lax

QUEUE_CONNECTION=redis
```

**Mail Configuration:**
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailgun.org
MAIL_PORT=587
MAIL_USERNAME=your_mailgun_username
MAIL_PASSWORD=your_mailgun_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"
```

**Broadcasting (Pusher for Real-time):**
```env
BROADCAST_CONNECTION=pusher

PUSHER_APP_ID=your_pusher_app_id
PUSHER_APP_KEY=your_pusher_key
PUSHER_APP_SECRET=your_pusher_secret
PUSHER_APP_CLUSTER=ap2
PUSHER_SCHEME=https
```

**Payment Gateway (Stripe):**
```env
STRIPE_KEY=pk_live_your_stripe_publishable_key
STRIPE_SECRET=sk_live_your_stripe_secret_key
STRIPE_WEBHOOK_SECRET=whsec_your_webhook_secret
```

**Payment Gateway (Razorpay - for Indian payments):**
```env
RAZORPAY_KEY=rzp_live_your_key
RAZORPAY_SECRET=your_razorpay_secret
```

**File Storage (S3):**
```env
FILESYSTEM_DISK=s3

AWS_ACCESS_KEY_ID=your_aws_access_key
AWS_SECRET_ACCESS_KEY=your_aws_secret_key
AWS_DEFAULT_REGION=ap-south-1
AWS_BUCKET=matrimony-uploads
AWS_USE_PATH_STYLE_ENDPOINT=false
AWS_URL=https://your-bucket.s3.ap-south-1.amazonaws.com
```

**Error Tracking (Sentry):**
```env
SENTRY_LARAVEL_DSN=https://your_sentry_dsn@sentry.io/project_id
SENTRY_TRACES_SAMPLE_RATE=1.0
SENTRY_PROFILES_SAMPLE_RATE=1.0
```

**Additional Services:**
```env
# Analytics
GOOGLE_ANALYTICS_ID=G-XXXXXXXXXX

# Logging
LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

# Rate Limiting
RATE_LIMIT_ENABLED=true

# Feature Flags
FEATURE_VIDEO_CALLS=true
FEATURE_STORIES=true
FEATURE_GAMES=true
FEATURE_ADVANCED_SEARCH=true
```

---

## Database Setup

### Step 1: Create Database via Laravel Cloud Dashboard

1. **Navigate to "Databases" tab**
2. **Click "Create Database"**
3. **Configuration:**
   ```
   Database Type: MySQL 8.0
   Name: matrimony_production
   Region: Same as your app region
   ```

4. **Laravel Cloud will provide:**
   - Database Host
   - Database Port
   - Database Name
   - Database Username
   - Database Password

5. **Update environment variables** with provided credentials

### Step 2: Run Migrations

**Option A: Via Laravel Cloud Dashboard**
1. Go to "Deployments" tab
2. Click "Run Command"
3. Execute: `php artisan migrate --force`

**Option B: Via Laravel Cloud CLI**
```bash
# Install Laravel Cloud CLI
composer global require laravel/cloud-cli

# Authenticate
cloud auth

# Run migration
cloud ssh matrimony-app
php artisan migrate --force
```

### Step 3: Seed Essential Data (if needed)

```bash
# Connect via SSH
cloud ssh matrimony-app

# Run production seeder
php artisan db:seed --class=ProductionSeeder --force
```

---

## Redis Configuration

### Step 1: Enable Redis

1. **Navigate to "Databases" tab**
2. **Click "Add Redis"**
3. **Configuration:**
   ```
   Redis Version: 7.x
   Memory: 256 MB (minimum for this app)
   Region: Same as your app
   ```

### Step 2: Update Environment Variables

Laravel Cloud will automatically provide Redis credentials:
```env
REDIS_HOST=provided_by_laravel_cloud
REDIS_PASSWORD=provided_by_laravel_cloud
REDIS_PORT=6379
```

### Step 3: Verify Redis Connection

```bash
# SSH into server
cloud ssh matrimony-app

# Test Redis
php artisan tinker
>>> Redis::set('test', 'Hello');
>>> Redis::get('test');
```

---

## File Storage Setup

### Step 1: Create S3 Bucket (AWS)

**1.1 Sign in to AWS Console:**
- Go to: https://console.aws.amazon.com
- Navigate to S3 service

**1.2 Create Bucket:**
```
Bucket Name: matrimony-uploads-production
Region: Asia Pacific (Mumbai) ap-south-1
Block Public Access: OFF (we'll use signed URLs)
Versioning: Enabled (recommended)
Encryption: Enabled (AES-256)
```

**1.3 Configure CORS:**
```json
[
    {
        "AllowedHeaders": ["*"],
        "AllowedMethods": ["GET", "PUT", "POST", "DELETE", "HEAD"],
        "AllowedOrigins": ["https://yourdomain.com"],
        "ExposeHeaders": ["ETag"],
        "MaxAgeSeconds": 3000
    }
]
```

**1.4 Create IAM User:**
```
User Name: matrimony-s3-user
Permissions: AmazonS3FullAccess (or custom policy)
Access Type: Programmatic access
```

**1.5 Save Credentials:**
- Access Key ID
- Secret Access Key
- Add to Laravel Cloud environment variables

### Step 2: Configure Laravel Filesystem

The app is already configured in `config/filesystems.php`. Verify:

```php
's3' => [
    'driver' => 's3',
    'key' => env('AWS_ACCESS_KEY_ID'),
    'secret' => env('AWS_SECRET_ACCESS_KEY'),
    'region' => env('AWS_DEFAULT_REGION'),
    'bucket' => env('AWS_BUCKET'),
    'url' => env('AWS_URL'),
    'endpoint' => env('AWS_ENDPOINT'),
    'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
    'throw' => false,
],
```

### Step 3: Test File Upload

```bash
# SSH into server
cloud ssh matrimony-app

# Test upload
php artisan tinker
>>> Storage::disk('s3')->put('test.txt', 'Hello World');
>>> Storage::disk('s3')->url('test.txt');
```

---

## Domain Configuration

### Step 1: Add Custom Domain (Optional)

**If using your own domain:**

1. **In Laravel Cloud Dashboard:**
   - Go to "Domains" tab
   - Click "Add Domain"
   - Enter: `yourdomain.com`
   - Also add: `www.yourdomain.com`

2. **DNS Configuration:**
   ```
   Type: CNAME
   Name: @
   Value: [provided-by-laravel-cloud].laravel.cloud
   TTL: 3600

   Type: CNAME
   Name: www
   Value: [provided-by-laravel-cloud].laravel.cloud
   TTL: 3600
   ```

3. **Wait for DNS Propagation** (can take 1-48 hours)

**If using Laravel Cloud subdomain:**
- Laravel Cloud provides: `your-app.laravel.cloud`
- No DNS configuration needed

### Step 2: Update Environment Variables

```env
APP_URL=https://yourdomain.com
SESSION_DOMAIN=.yourdomain.com
```

---

## Deployment Process

### Step 1: Initial Deployment

1. **In Laravel Cloud Dashboard:**
   - Go to "Deployments" tab
   - Click "Deploy Now"

2. **Deployment Steps (Automatic):**
   ```
   ✓ Clone repository from GitHub
   ✓ Install Composer dependencies
   ✓ Install NPM dependencies
   ✓ Build frontend assets (npm run build)
   ✓ Run database migrations
   ✓ Cache configuration
   ✓ Cache routes
   ✓ Cache views
   ✓ Optimize autoloader
   ✓ Start application
   ```

3. **Monitor Deployment:**
   - Watch real-time logs in dashboard
   - Deployment typically takes 2-5 minutes

### Step 2: Verify Deployment

```bash
# Check deployment status
cloud deployments

# View logs
cloud logs

# SSH into server
cloud ssh matrimony-app

# Check application status
php artisan about
```

### Step 3: Post-Deployment Commands

```bash
# SSH into server
cloud ssh matrimony-app

# Run migrations
php artisan migrate --force

# Clear all caches
php artisan optimize:clear

# Rebuild caches
php artisan optimize

# Generate APP_KEY if needed
php artisan key:generate --force
```

---

## Post-Deployment Tasks

### Step 1: Database Migrations

```bash
# Run all pending migrations
php artisan migrate --force

# Or migrate specific tables
php artisan migrate --path=/database/migrations/2025_12_31_000001_create_profile_views_table.php --force
```

### Step 2: Storage Link

```bash
# Create symbolic link for public storage
php artisan storage:link
```

### Step 3: Optimize Application

```bash
# Cache everything
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Optimize composer autoloader
composer install --optimize-autoloader --no-dev

# Clear application cache
php artisan cache:clear
```

### Step 4: Test Critical Features

**✅ Test Authentication:**
- Register new account
- Login/Logout
- Password reset

**✅ Test Profile Features:**
- Create profile
- Upload photos
- Edit profile

**✅ Test Matching:**
- View discover page
- Send connection requests
- View matches

**✅ Test Real-time:**
- Send messages
- Test notifications
- Check online status

**✅ Test Payments:**
- Test Stripe payment flow
- Verify webhook endpoints
- Check subscription activation

### Step 5: Configure Webhooks

**Stripe Webhooks:**
1. Go to: https://dashboard.stripe.com/webhooks
2. Add endpoint: `https://yourdomain.com/stripe/webhook`
3. Select events:
   - `payment_intent.succeeded`
   - `payment_intent.payment_failed`
   - `customer.subscription.created`
   - `customer.subscription.deleted`
4. Copy webhook secret to environment variables

**Razorpay Webhooks:**
1. Go to Razorpay Dashboard → Webhooks
2. Add webhook: `https://yourdomain.com/razorpay/webhook`
3. Select events:
   - `payment.authorized`
   - `payment.failed`
   - `subscription.activated`
4. Copy webhook secret

---

## Environment Variables

### Complete List for Production

```env
# Application
APP_NAME="Modern Matrimony"
APP_ENV=production
APP_KEY=base64:YOUR_GENERATED_KEY_HERE
APP_DEBUG=false
APP_TIMEZONE=Asia/Kolkata
APP_URL=https://yourdomain.com
APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_IN
APP_MAINTENANCE_DRIVER=file
APP_MAINTENANCE_STORE=database

# Database
DB_CONNECTION=mysql
DB_HOST=your_laravel_cloud_db_host
DB_PORT=3306
DB_DATABASE=matrimony_production
DB_USERNAME=your_db_username
DB_PASSWORD=your_db_password

# Redis
REDIS_CLIENT=phpredis
REDIS_HOST=your_redis_host
REDIS_PASSWORD=your_redis_password
REDIS_PORT=6379
REDIS_DB=0

# Cache
CACHE_STORE=redis
CACHE_PREFIX=matrimony_

# Session
SESSION_DRIVER=redis
SESSION_LIFETIME=120
SESSION_ENCRYPT=true
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=lax

# Queue
QUEUE_CONNECTION=redis

# Mail
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailgun.org
MAIL_PORT=587
MAIL_USERNAME=postmaster@yourdomain.com
MAIL_PASSWORD=your_mailgun_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"

# Broadcasting
BROADCAST_CONNECTION=pusher
PUSHER_APP_ID=your_app_id
PUSHER_APP_KEY=your_key
PUSHER_APP_SECRET=your_secret
PUSHER_APP_CLUSTER=ap2
PUSHER_SCHEME=https

# Filesystem
FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=your_access_key
AWS_SECRET_ACCESS_KEY=your_secret_key
AWS_DEFAULT_REGION=ap-south-1
AWS_BUCKET=matrimony-uploads
AWS_URL=https://matrimony-uploads.s3.ap-south-1.amazonaws.com

# Payments - Stripe
STRIPE_KEY=pk_live_your_key
STRIPE_SECRET=sk_live_your_secret
STRIPE_WEBHOOK_SECRET=whsec_your_webhook_secret

# Payments - Razorpay
RAZORPAY_KEY=rzp_live_your_key
RAZORPAY_SECRET=your_secret

# Error Tracking
SENTRY_LARAVEL_DSN=https://your_dsn@sentry.io/project
SENTRY_TRACES_SAMPLE_RATE=0.1
SENTRY_PROFILES_SAMPLE_RATE=0.1

# Analytics
GOOGLE_ANALYTICS_ID=G-XXXXXXXXXX

# Logging
LOG_CHANNEL=stack
LOG_STACK=single,sentry
LOG_LEVEL=error
LOG_DEPRECATIONS_CHANNEL=null

# Rate Limiting
RATE_LIMIT_ENABLED=true

# Features
FEATURE_VIDEO_CALLS=true
FEATURE_STORIES=true
FEATURE_GAMES=true
FEATURE_ADVANCED_SEARCH=true

# Security
TRUSTED_PROXIES=*
ASSET_URL=https://yourdomain.com
```

---

## Scheduled Tasks

### Step 1: Configure Laravel Scheduler

Laravel Cloud automatically runs the scheduler. Your scheduled tasks in `app/Console/Kernel.php`:

```php
protected function schedule(Schedule $schedule): void
{
    // Daily metrics calculation (runs at 1 AM)
    $schedule->command('metrics:calculate-daily')
        ->dailyAt('01:00')
        ->environments(['production']);

    // Process account deletions (runs every hour)
    $schedule->command('gdpr:process-deletions')
        ->hourly()
        ->environments(['production']);

    // Clean up expired sessions (runs daily)
    $schedule->command('session:gc')
        ->daily();

    // Send engagement reminders (runs at 10 AM)
    $schedule->command('engagement:send-reminders')
        ->dailyAt('10:00')
        ->environments(['production']);
}
```

### Step 2: Verify Scheduler

```bash
# SSH into server
cloud ssh matrimony-app

# List scheduled tasks
php artisan schedule:list

# Test scheduler manually
php artisan schedule:run
```

---

## Queue Workers

### Step 1: Configure Queue Workers

1. **In Laravel Cloud Dashboard:**
   - Go to "Workers" tab
   - Click "Add Worker"

2. **Worker Configuration:**
   ```
   Name: default-worker
   Command: php artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
   Processes: 2
   Memory Limit: 128M
   ```

3. **Add Additional Workers (if needed):**
   ```
   Name: notifications-worker
   Command: php artisan queue:work redis --queue=notifications --sleep=3 --tries=3
   Processes: 1
   ```

### Step 2: Monitor Queue

```bash
# Check queue status
php artisan queue:monitor redis

# View failed jobs
php artisan queue:failed

# Retry failed jobs
php artisan queue:retry all
```

---

## SSL Certificate

### Step 1: Enable SSL (Automatic)

Laravel Cloud automatically provisions SSL certificates via Let's Encrypt.

**Automatic SSL for:**
- ✅ Laravel Cloud subdomain (your-app.laravel.cloud)
- ✅ Custom domains (after DNS verification)

### Step 2: Force HTTPS

Already configured in your app:

```php
// app/Http/Middleware/TrustProxies.php
protected $proxies = '*';

// .env
SESSION_SECURE_COOKIE=true
```

### Step 3: Verify SSL

```bash
# Test SSL certificate
curl -I https://yourdomain.com

# Check SSL expiration
openssl s_client -connect yourdomain.com:443 | openssl x509 -noout -dates
```

---

## Monitoring & Logs

### Step 1: Application Monitoring

**Laravel Cloud Dashboard provides:**
- ✅ CPU usage
- ✅ Memory usage
- ✅ Disk usage
- ✅ Request metrics
- ✅ Error rates

### Step 2: View Logs

**Via Dashboard:**
1. Go to "Logs" tab
2. Filter by:
   - Application logs
   - Web server logs
   - Queue worker logs
   - Scheduler logs

**Via CLI:**
```bash
# View real-time logs
cloud logs --follow

# View specific log type
cloud logs --type=app
cloud logs --type=worker
cloud logs --type=scheduler
```

### Step 3: Error Tracking with Sentry

Your app is already configured with Sentry:

```php
// config/sentry.php already configured
// All exceptions are automatically sent to Sentry
```

**Sentry Dashboard:**
- View errors at: https://sentry.io
- Set up alerts for critical errors
- Monitor performance issues

### Step 4: Set Up Uptime Monitoring

**Recommended Services:**
- **Laravel Pulse** (built-in, free)
- **Oh Dear** (https://ohdear.app) - $10/month
- **Pingdom** (https://pingdom.com) - Free tier available
- **UptimeRobot** (https://uptimerobot.com) - Free

**Configure endpoint:**
```
Monitor URL: https://yourdomain.com/health
Check Interval: 5 minutes
```

---

## Performance Optimization

### Step 1: Enable OPcache

Laravel Cloud enables OPcache by default. Verify:

```bash
php -i | grep opcache
```

### Step 2: Optimize Assets

Already done in your build:
```bash
# Production build with optimization
npm run build
# - Code splitting ✓
# - Minification ✓
# - Compression (gzip + brotli) ✓
```

### Step 3: Database Optimization

```bash
# Add indexes (already done in migrations)
php artisan migrate

# Analyze query performance
php artisan telescope:install  # Optional
```

### Step 4: CDN Configuration (Optional)

**Using CloudFlare (Free Tier):**

1. **Sign up at:** https://cloudflare.com
2. **Add your domain**
3. **Update nameservers** to CloudFlare's
4. **Configure settings:**
   ```
   SSL/TLS: Full (strict)
   Caching Level: Standard
   Browser Cache TTL: 4 hours
   Auto Minify: CSS, JS, HTML
   Brotli Compression: On
   ```

### Step 5: Enable Response Caching

```bash
# Cache responses for static pages
php artisan route:cache
php artisan view:cache
php artisan config:cache
```

---

## Troubleshooting

### Issue 1: 500 Internal Server Error

**Diagnosis:**
```bash
# View error logs
cloud logs --type=app --tail=100

# Check Laravel logs
cloud ssh matrimony-app
tail -f storage/logs/laravel.log
```

**Common Causes:**
- Missing APP_KEY → Run: `php artisan key:generate --force`
- Wrong database credentials → Check environment variables
- Missing dependencies → Run: `composer install --no-dev`
- Permission issues → Run: `chmod -R 755 storage bootstrap/cache`

### Issue 2: Assets Not Loading

**Diagnosis:**
```bash
# Check if assets are built
ls -la public/build/

# Verify manifest
cat public/build/manifest.json
```

**Fix:**
```bash
# Rebuild assets
npm run build

# Clear asset cache
php artisan optimize:clear
```

### Issue 3: Database Connection Failed

**Check:**
```bash
# Test database connection
php artisan tinker
>>> DB::connection()->getPdo();
```

**Fix:**
- Verify database credentials in environment variables
- Check if database is running: Laravel Cloud Dashboard → Databases
- Test connection from SSH: `mysql -h HOST -u USER -p`

### Issue 4: Queue Jobs Not Processing

**Check:**
```bash
# View queue status
php artisan queue:monitor redis

# Check failed jobs
php artisan queue:failed
```

**Fix:**
```bash
# Restart queue workers
cloud workers:restart

# Retry failed jobs
php artisan queue:retry all
```

### Issue 5: Slow Performance

**Diagnosis:**
```bash
# Check application cache
php artisan config:show cache

# Monitor Redis
redis-cli info stats
```

**Fix:**
```bash
# Clear and rebuild caches
php artisan optimize:clear
php artisan optimize

# Restart application
cloud restart
```

### Issue 6: Email Not Sending

**Check:**
```bash
# Test email configuration
php artisan tinker
>>> Mail::raw('Test', function($msg) { $msg->to('test@example.com')->subject('Test'); });
```

**Fix:**
- Verify SMTP credentials
- Check mail logs: `storage/logs/laravel.log`
- Test with different mail driver (Mailgun/SES)

### Issue 7: File Upload Failing

**Check:**
```bash
# Test S3 connection
php artisan tinker
>>> Storage::disk('s3')->put('test.txt', 'Hello');
```

**Fix:**
- Verify AWS credentials
- Check S3 bucket permissions
- Verify CORS configuration
- Check file size limits in php.ini

---

## Post-Launch Checklist

### ✅ Security
- [ ] APP_DEBUG=false
- [ ] Strong APP_KEY generated
- [ ] HTTPS enforced
- [ ] Security headers configured
- [ ] Rate limiting enabled
- [ ] CSRF protection active
- [ ] SQL injection prevention verified
- [ ] XSS protection enabled

### ✅ Performance
- [ ] Asset optimization complete
- [ ] Database indexes added
- [ ] Redis caching working
- [ ] Queue workers running
- [ ] OPcache enabled
- [ ] CDN configured (optional)

### ✅ Monitoring
- [ ] Sentry configured
- [ ] Uptime monitoring active
- [ ] Error alerts set up
- [ ] Performance monitoring enabled
- [ ] Log rotation configured

### ✅ Backups
- [ ] Database backups enabled (Laravel Cloud auto-backup)
- [ ] S3 versioning enabled
- [ ] Backup testing completed

### ✅ Testing
- [ ] Registration flow tested
- [ ] Login/logout working
- [ ] Profile creation working
- [ ] File uploads working
- [ ] Payment processing tested
- [ ] Email sending verified
- [ ] Real-time features working
- [ ] Mobile responsiveness checked

### ✅ SEO
- [ ] Meta tags configured
- [ ] Sitemap generated
- [ ] robots.txt configured
- [ ] Google Analytics active
- [ ] Schema markup added

### ✅ Legal
- [ ] Privacy policy accessible
- [ ] Terms of service accessible
- [ ] Cookie policy configured
- [ ] GDPR compliance verified

---

## Useful Commands Reference

### Laravel Cloud CLI

```bash
# Authentication
cloud auth
cloud logout

# Deployments
cloud deploy matrimony-app
cloud deployments
cloud deployment:status

# SSH Access
cloud ssh matrimony-app

# Logs
cloud logs
cloud logs --follow
cloud logs --type=app
cloud logs --since="1 hour ago"

# Workers
cloud workers
cloud workers:restart
cloud workers:scale default-worker 3

# Environment
cloud env
cloud env:set KEY=value
cloud env:unset KEY

# Database
cloud database:shell
cloud database:backup
cloud database:restore

# Scaling
cloud scale --instances=2
cloud scale --memory=2048

# Maintenance Mode
cloud down
cloud up

# Cache
cloud cache:clear
cloud config:cache
```

### Application Commands (via SSH)

```bash
# Artisan commands
php artisan about
php artisan optimize
php artisan optimize:clear
php artisan migrate --force
php artisan db:seed --force
php artisan queue:work
php artisan schedule:run
php artisan cache:clear
php artisan view:clear
php artisan route:clear
php artisan config:clear

# Composer
composer install --no-dev --optimize-autoloader
composer dump-autoload

# NPM
npm ci
npm run build

# Storage
php artisan storage:link

# Queue
php artisan queue:monitor
php artisan queue:failed
php artisan queue:retry all
php artisan queue:flush

# Telescope (if installed)
php artisan telescope:install
php artisan telescope:prune
```

---

## Support & Resources

### Laravel Cloud Documentation
- **Main Docs:** https://cloud.laravel.com/docs
- **API Reference:** https://cloud.laravel.com/api-docs
- **Video Tutorials:** https://laracasts.com/series/laravel-cloud

### Community Support
- **Laravel Discord:** https://discord.gg/laravel
- **Laravel Forums:** https://laracasts.com/discuss
- **Stack Overflow:** Tag with `laravel` and `laravel-cloud`

### Laravel Cloud Support
- **Email:** support@laravel.com
- **Response Time:** 24-48 hours
- **Priority Support:** Available on higher tiers

---

## Deployment Checklist Summary

### Before Deployment
- [ ] Code committed and pushed to GitHub
- [ ] Environment variables prepared
- [ ] Database credentials ready
- [ ] Third-party API keys ready
- [ ] Domain configured (if using custom domain)
- [ ] SSL certificate ready (auto via Laravel Cloud)

### During Deployment
- [ ] Project created in Laravel Cloud
- [ ] Environment variables configured
- [ ] Database created and connected
- [ ] Redis enabled
- [ ] S3 bucket configured
- [ ] Workers configured
- [ ] Domain added (if custom)
- [ ] Initial deployment triggered

### After Deployment
- [ ] Migrations run successfully
- [ ] Application accessible via URL
- [ ] Authentication working
- [ ] File uploads working
- [ ] Payments tested
- [ ] Emails sending
- [ ] Real-time features working
- [ ] Queue processing
- [ ] Scheduler running
- [ ] Monitoring configured
- [ ] Backups enabled

---

## Cost Estimation

### Laravel Cloud Costs

**Professional Plan (Recommended):**
```
Base Application: $39/month
  - 2 GB RAM
  - 2 vCPU
  - 50 GB Storage
  - Automatic backups
  - SSL included

Database (MySQL): $15/month
  - 1 GB RAM
  - 10 GB Storage

Redis: $10/month
  - 256 MB
  - 1 GB Storage

Total: ~$64/month
```

### Additional Costs

```
AWS S3 Storage: ~$5-10/month
  - 50 GB storage
  - Data transfer

Pusher (Real-time): $49/month
  - 500 concurrent connections
  - Unlimited messages

Email (Mailgun): $35/month
  - 50,000 emails/month

Sentry (Error Tracking): $26/month
  - 100K events

Domain (.com): $12/year

Total Monthly: ~$170-180/month
Total Yearly: ~$2,050/year
```

**Note:** Prices are estimates and subject to change. Check current pricing on respective platforms.

---

## Next Steps After Deployment

1. **Monitor Application:**
   - Check logs daily for first week
   - Monitor performance metrics
   - Track error rates

2. **Gradual Rollout:**
   - Start with beta users
   - Collect feedback
   - Fix issues before full launch

3. **Marketing Launch:**
   - Announce on social media
   - Email marketing campaign
   - SEO optimization
   - Content marketing

4. **Continuous Improvement:**
   - Monitor user analytics
   - A/B test features
   - Performance optimization
   - Regular updates

---

**Deployment Status:** 📋 Ready for Laravel Cloud
**Estimated Setup Time:** 2-3 hours
**Support Available:** 24/7 via Laravel Cloud Dashboard

---

🚀 **Your matrimony application is production-ready and optimized for Laravel Cloud deployment!**

Good luck with your launch! 🎉
