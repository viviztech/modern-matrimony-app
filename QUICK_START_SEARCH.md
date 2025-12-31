# Quick Start Guide - Meilisearch Search

## 🚀 Get Started in 5 Minutes

### Step 1: Start Meilisearch (if not running)
```bash
# Using Docker
docker run -d -p 7700:7700 \
  -e MEILI_MASTER_KEY=masterKey123 \
  -v $(pwd)/meili_data:/meili_data \
  getmeili/meilisearch:latest
```

### Step 2: Configure Environment
Add to `.env`:
```env
SCOUT_DRIVER=meilisearch
MEILISEARCH_HOST=http://localhost:7700
MEILISEARCH_KEY=masterKey123
```

### Step 3: Run Migrations
```bash
php artisan migrate
```

### Step 4: Index Users
```bash
php artisan meilisearch:index-users --fresh
```

### Step 5: Visit Search Page
```
http://localhost:8000/search
```

## ✅ That's it! Your search is now live!

## 🎯 Quick Features Overview

### For All Users
- **Basic Search**: Age, location, education, religion
- **Smart Filters**: Height, diet, marital status
- **Quick Filters**: Verified, online, has photos

### For Premium Users (Gold+)
- **Advanced Filters**: Income, location radius
- **Who Liked You**: See all users who liked you
- **Save Searches**: Save up to 5 (or unlimited for Platinum)
- **Profile Boost**: Get 10x visibility

## 🔧 Common Commands

```bash
# Reindex users
php artisan meilisearch:index-users

# Fresh reindex (delete and recreate)
php artisan meilisearch:index-users --fresh

# Custom chunk size for large databases
php artisan meilisearch:index-users --chunk=500

# Check Meilisearch health
curl http://localhost:7700/health

# View index stats
curl http://localhost:7700/indexes/users/stats
```

## 📱 API Endpoints

All endpoints require authentication.

### Search
```http
POST /search
Content-Type: application/json

{
  "keyword": "engineer",
  "age_min": 25,
  "age_max": 35,
  "city": "Mumbai"
}
```

### Save Search
```http
POST /search/save
Content-Type: application/json

{
  "name": "My Search",
  "filters": {...},
  "notify_on_match": true
}
```

### Boost Profile
```http
POST /profile/boost
Content-Type: application/json

{
  "duration_type": "24h"
}
```

## 🎨 UI Components

### Main Search Page
- Location: `/search`
- Features: Live search, filters sidebar, infinite scroll

### Who Liked You
- Location: `/search/who-liked-you`
- Requires: Gold+ membership

### Saved Searches
- Location: `/search/saved`
- Features: Manage, run, delete searches

## 🔐 Premium Feature Access

Check access programmatically:
```php
$featureGate = app(FeatureGateService::class);

// Check if user can see who liked them
$canSee = $featureGate->canSeeWhoLiked($user);

// Check if user can use advanced filters
$canUse = $featureGate->canUseAdvancedFilters($user);

// Check if user can boost profile
$canBoost = $featureGate->canUseProfileBoost($user);
```

## 📊 Test with Sample Data

```bash
php artisan tinker
```

```php
// Create 100 test users
User::factory(100)->create();

// Create profiles for them
User::all()->each(function($user) {
    if (!$user->profile) {
        Profile::factory()->create(['user_id' => $user->id]);
    }
});

// Index them
exit;
```

```bash
php artisan meilisearch:index-users --fresh
```

## 🐛 Troubleshooting

### "No results found"
```bash
# Check if Meilisearch is running
curl http://localhost:7700/health

# Check if users are indexed
curl http://localhost:7700/indexes/users/stats

# Reindex
php artisan meilisearch:index-users --fresh
```

### "Advanced filters locked"
- Ensure user has active premium subscription
- Check subscription plan features
- Verify FeatureGateService integration

### "Search is slow"
- Enable Redis caching
- Increase Meilisearch memory allocation
- Use chunked indexing
- Consider adding database indexes

## 📖 Full Documentation

For detailed documentation, see:
- `MEILISEARCH_SETUP.md` - Complete setup guide
- `SEARCH_IMPLEMENTATION_SUMMARY.md` - Technical details

## 🎉 Success Indicators

Your search is working correctly if:
- ✅ Search page loads without errors
- ✅ Filters update results in real-time
- ✅ Results show profile cards with photos
- ✅ Sorting works (newest, active, etc.)
- ✅ Premium features show upgrade prompts
- ✅ Save search creates new saved search
- ✅ Boost profile (for premium) activates boost

## 💡 Pro Tips

1. **Regular Reindexing**: Schedule `php artisan meilisearch:index-users` daily
2. **Monitor Performance**: Check Meilisearch dashboard at `http://localhost:7700`
3. **Cache Warmup**: Visit search pages after deployment to warm caches
4. **Test Premium**: Test all premium features in staging before production
5. **Mobile Testing**: Ensure search works on mobile devices

## 🚨 Production Deployment

Before going live:
1. Set strong MEILISEARCH_KEY
2. Enable HTTPS for Meilisearch
3. Configure proper backups
4. Set up monitoring and alerts
5. Test all premium features
6. Load test with concurrent users
7. Configure CDN for static assets

## 📞 Need Help?

- Check logs: `storage/logs/laravel.log`
- Meilisearch logs: `docker logs meilisearch`
- Review documentation files
- Test in isolated environment first

---

**Happy Searching! 🔍💕**
