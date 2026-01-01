# Laravel Cloud Deployment - Quick Checklist

**Application:** Modern Matrimony Platform
**Target:** Laravel Cloud
**Estimated Time:** 2-3 hours

---

## 📋 Pre-Deployment (30 minutes)

### Code Preparation
- [ ] All code committed to GitHub
- [ ] `main` branch is up to date
- [ ] `.env.example` updated with all variables
- [ ] `composer.json` dependencies verified
- [ ] Test production build locally: `npm run build`

### Accounts Required
- [ ] Laravel Cloud account created
- [ ] AWS account (for S3)
- [ ] Pusher account (for real-time)
- [ ] Email service account (Mailgun/SES)
- [ ] Stripe/Razorpay account (for payments)
- [ ] Sentry account (optional, for error tracking)
- [ ] Domain registrar access (if using custom domain)

### Credentials to Prepare
- [ ] Database credentials ready
- [ ] AWS Access Key & Secret Key
- [ ] Pusher App ID, Key, Secret
- [ ] Mail SMTP credentials
- [ ] Stripe API keys
- [ ] Razorpay API keys (if applicable)
- [ ] Sentry DSN

---

## 🚀 Laravel Cloud Setup (60 minutes)

### Step 1: Create Project (10 min)
- [ ] Sign up at https://cloud.laravel.com
- [ ] Choose plan: **Professional ($39/mo)** recommended
- [ ] Create new project
- [ ] Connect GitHub repository: `viviztech/modern-matrimony-app`
- [ ] Select region: **Asia Pacific (Mumbai)** for Indian users
- [ ] Configure build settings:
  - PHP: 8.3
  - Node: 20.x
  - Build command: `npm ci && npm run build`

### Step 2: Database Setup (10 min)
- [ ] Navigate to "Databases" tab
- [ ] Create MySQL database
- [ ] Note down credentials:
  - DB_HOST
  - DB_PORT
  - DB_DATABASE
  - DB_USERNAME
  - DB_PASSWORD

### Step 3: Redis Setup (5 min)
- [ ] Navigate to "Databases" tab
- [ ] Add Redis (256 MB minimum)
- [ ] Note down credentials:
  - REDIS_HOST
  - REDIS_PASSWORD

### Step 4: Environment Variables (20 min)
Go to "Environment" tab and add these variables:

**Required Variables:**
```env
APP_NAME="Modern Matrimony"
APP_ENV=production
APP_KEY=                    # Generate: php artisan key:generate --show
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=                    # From Laravel Cloud
DB_DATABASE=                # From Laravel Cloud
DB_USERNAME=                # From Laravel Cloud
DB_PASSWORD=                # From Laravel Cloud

REDIS_HOST=                 # From Laravel Cloud
REDIS_PASSWORD=             # From Laravel Cloud

CACHE_STORE=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=          # From AWS
AWS_SECRET_ACCESS_KEY=      # From AWS
AWS_DEFAULT_REGION=ap-south-1
AWS_BUCKET=matrimony-uploads

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailgun.org
MAIL_USERNAME=              # From Mailgun
MAIL_PASSWORD=              # From Mailgun
MAIL_FROM_ADDRESS=noreply@yourdomain.com

BROADCAST_CONNECTION=pusher
PUSHER_APP_ID=              # From Pusher
PUSHER_APP_KEY=             # From Pusher
PUSHER_APP_SECRET=          # From Pusher
PUSHER_APP_CLUSTER=ap2

STRIPE_KEY=                 # From Stripe
STRIPE_SECRET=              # From Stripe
STRIPE_WEBHOOK_SECRET=      # From Stripe

SENTRY_LARAVEL_DSN=         # From Sentry (optional)
```

### Step 5: Queue Workers (10 min)
- [ ] Go to "Workers" tab
- [ ] Add worker:
  - Name: `default-worker`
  - Command: `php artisan queue:work redis --sleep=3 --tries=3`
  - Processes: 2

### Step 6: Domain Configuration (5 min)
**Option A: Use Laravel Cloud subdomain**
- [ ] No action needed
- [ ] Access at: `your-app.laravel.cloud`

**Option B: Custom domain**
- [ ] Go to "Domains" tab
- [ ] Add domain: `yourdomain.com`
- [ ] Update DNS records (CNAME)
- [ ] Wait for DNS propagation

---

## 🗄️ External Services Setup (45 minutes)

### AWS S3 (15 min)
- [ ] Create S3 bucket: `matrimony-uploads-production`
- [ ] Region: `ap-south-1`
- [ ] Disable "Block all public access"
- [ ] Enable versioning
- [ ] Configure CORS:
```json
[{
    "AllowedHeaders": ["*"],
    "AllowedMethods": ["GET", "PUT", "POST", "DELETE"],
    "AllowedOrigins": ["https://yourdomain.com"],
    "ExposeHeaders": ["ETag"]
}]
```
- [ ] Create IAM user with S3 access
- [ ] Save Access Key ID and Secret Key

### Pusher (10 min)
- [ ] Sign up at https://pusher.com
- [ ] Create new app
- [ ] Select cluster: `ap2` (Asia Pacific)
- [ ] Enable client events
- [ ] Copy credentials: App ID, Key, Secret

### Email Service - Mailgun (10 min)
- [ ] Sign up at https://mailgun.com
- [ ] Add domain
- [ ] Verify domain (DNS records)
- [ ] Get SMTP credentials
- [ ] Test email sending

### Stripe (10 min)
- [ ] Sign up at https://stripe.com
- [ ] Switch to Live mode
- [ ] Get API keys (Publishable & Secret)
- [ ] Set up webhook endpoint: `https://yourdomain.com/stripe/webhook`
- [ ] Select events:
  - `payment_intent.succeeded`
  - `payment_intent.payment_failed`
  - `customer.subscription.created`
  - `customer.subscription.deleted`
- [ ] Copy webhook secret

---

## 🚢 Initial Deployment (15 minutes)

### Deploy Application
- [ ] Go to "Deployments" tab in Laravel Cloud
- [ ] Click "Deploy Now"
- [ ] Monitor deployment logs
- [ ] Wait for deployment to complete (~5 min)

### Post-Deployment Commands
SSH into server: `cloud ssh matrimony-app`

```bash
# Run migrations
php artisan migrate --force

# Create storage link
php artisan storage:link

# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Test application
php artisan about
```

---

## ✅ Testing & Verification (30 minutes)

### Application Access
- [ ] Visit your application URL
- [ ] Landing page loads correctly
- [ ] All assets load (CSS, JS, images)

### Authentication Flow
- [ ] Register new account
- [ ] Receive verification email
- [ ] Login successfully
- [ ] Logout works

### Profile Features
- [ ] Create profile
- [ ] Upload profile photo
- [ ] Upload gallery images
- [ ] Edit profile information
- [ ] Profile displays correctly

### Matching & Discovery
- [ ] Discover page loads
- [ ] View other profiles
- [ ] Send connection request
- [ ] View matches page

### Real-time Features
- [ ] Send message
- [ ] Receive message notification
- [ ] Online status updates
- [ ] Typing indicators work

### Payment System
- [ ] Access premium features page
- [ ] Test Stripe payment (use test card)
- [ ] Verify subscription activation
- [ ] Test webhook delivery

### File Storage
- [ ] Upload profile photo (S3)
- [ ] Upload gallery images (S3)
- [ ] Images display correctly
- [ ] Test image deletion

### Email System
- [ ] Registration email received
- [ ] Password reset email works
- [ ] Notification emails sending

### Queue System
- [ ] Check queue is processing: `php artisan queue:monitor`
- [ ] No failed jobs: `php artisan queue:failed`

---

## 🔒 Security Checklist

- [ ] `APP_DEBUG=false` in production
- [ ] Strong `APP_KEY` generated
- [ ] HTTPS enforced (automatic)
- [ ] SSL certificate active (automatic)
- [ ] `SESSION_SECURE_COOKIE=true`
- [ ] Rate limiting enabled
- [ ] CSRF protection active
- [ ] Security headers configured
- [ ] File upload validation working
- [ ] XSS protection enabled

---

## 📊 Monitoring Setup (15 minutes)

### Laravel Cloud Dashboard
- [ ] Check CPU usage
- [ ] Check memory usage
- [ ] Check disk usage
- [ ] Review request metrics

### Sentry (Optional)
- [ ] Verify Sentry is receiving errors
- [ ] Configure error alerts
- [ ] Set up performance monitoring

### Uptime Monitoring (Optional)
- [ ] Sign up for uptime monitoring service
- [ ] Add health check endpoint: `https://yourdomain.com/health`
- [ ] Set check interval: 5 minutes
- [ ] Configure alert notifications

---

## 🔄 Scheduled Tasks Verification

```bash
# SSH into server
cloud ssh matrimony-app

# List scheduled tasks
php artisan schedule:list

# Expected tasks:
# - metrics:calculate-daily (daily at 01:00)
# - gdpr:process-deletions (hourly)
# - session:gc (daily)
```

- [ ] Scheduler is running automatically (Laravel Cloud handles this)
- [ ] Verify tasks execute correctly after 24 hours

---

## 📝 Post-Launch Tasks

### Immediate (Day 1)
- [ ] Monitor error logs closely
- [ ] Watch performance metrics
- [ ] Test all critical features again
- [ ] Verify backups are working

### First Week
- [ ] Check logs daily
- [ ] Monitor user signups
- [ ] Track payment conversions
- [ ] Collect user feedback
- [ ] Fix any reported bugs

### Ongoing
- [ ] Weekly performance review
- [ ] Monthly cost analysis
- [ ] Security updates
- [ ] Feature improvements
- [ ] A/B testing

---

## 🆘 Common Issues & Quick Fixes

### Issue: 500 Error
```bash
# View logs
cloud logs --tail=100

# Common fixes:
php artisan key:generate --force
php artisan migrate --force
php artisan optimize:clear
```

### Issue: Assets Not Loading
```bash
# Rebuild assets
npm run build

# Clear cache
php artisan optimize:clear
```

### Issue: Database Connection Failed
```bash
# Check connection
php artisan tinker
>>> DB::connection()->getPdo();

# Verify environment variables in Laravel Cloud dashboard
```

### Issue: Queue Not Processing
```bash
# Check queue status
php artisan queue:monitor

# Restart workers
cloud workers:restart
```

### Issue: Emails Not Sending
```bash
# Test email
php artisan tinker
>>> Mail::raw('Test', fn($m) => $m->to('test@example.com')->subject('Test'));

# Check mail logs
tail -f storage/logs/laravel.log | grep mail
```

---

## 💰 Cost Summary

**Monthly Costs:**
```
Laravel Cloud (Professional): $39
MySQL Database:               $15
Redis:                        $10
AWS S3:                       $10
Pusher:                       $49
Mailgun:                      $35
Sentry (optional):            $26
Domain (.com/year):           $1

Total: ~$185/month
```

---

## 📞 Support Resources

**Laravel Cloud:**
- Dashboard: https://cloud.laravel.com
- Docs: https://cloud.laravel.com/docs
- Support: support@laravel.com

**Community:**
- Discord: https://discord.gg/laravel
- Forums: https://laracasts.com/discuss

**Emergency:**
- Check status page: https://status.laravel.com
- View deployment logs in dashboard
- SSH access: `cloud ssh matrimony-app`

---

## ✅ Final Verification

Before announcing launch:

- [ ] All tests passing
- [ ] No critical errors in logs
- [ ] Performance is acceptable
- [ ] All integrations working
- [ ] Monitoring configured
- [ ] Backups enabled
- [ ] SSL certificate valid
- [ ] Domain working correctly
- [ ] Email delivery verified
- [ ] Payment processing tested
- [ ] Legal pages accessible
- [ ] Privacy policy visible
- [ ] Terms of service visible
- [ ] Cookie consent working

---

## 🎉 Launch!

- [ ] Announce on social media
- [ ] Send email to beta users
- [ ] Submit to search engines
- [ ] Start marketing campaign
- [ ] Monitor first 24 hours closely

---

**Status:** 🚀 Ready for Laravel Cloud Deployment

**Estimated Total Time:** 2-3 hours
**Difficulty:** Intermediate
**Documentation:** Complete

Good luck with your deployment! 🎊
