# Meilisearch Advanced Search Implementation

## Overview
This document explains the comprehensive Meilisearch-powered advanced search functionality for the matrimony application.

## Features Implemented

### 1. **Advanced Search with Multiple Filters**
- Age range (min/max)
- Height range (cm)
- Location (city, state, country, radius in km)
- Religion
- Caste/Community
- Mother tongue
- Education level
- Occupation
- Annual income range
- Marital status
- Dietary preferences
- Smoking/drinking habits
- Has children / Wants children
- Photo verified only
- Video verified only
- Premium members only
- Online users only
- Profile completion percentage

### 2. **Saved Searches**
- Save search criteria with custom names
- Enable/disable notifications for new matches
- View all saved searches
- Run saved searches
- Delete saved searches
- Auto-update results count

### 3. **Who Liked You (Premium Feature)**
- View all users who liked your profile
- See when they liked you
- Quick actions (Like back, Message)
- Gated for Gold+ members

### 4. **Profile Boost System**
- Boost profile visibility for 24h, 7d, or 30d
- 10x visibility multiplier
- Track views and likes gained during boost
- Premium feature (varies by plan)
- Automatic expiration tracking

### 5. **Search Service**
- Build complex Meilisearch queries
- Location-based radius search (Haversine formula)
- Apply boost logic to results
- Faceted search for filter counts
- Caching for performance

### 6. **Premium Feature Gating**
- "Who Liked You" - Gold+ plans
- Advanced filters (radius, income) - Gold+ plans
- Profile boost - Elite plan
- Upgrade prompts for locked features

## Installation & Setup

### 1. Run Migrations
```bash
php artisan migrate
```

This will create:
- `saved_searches` table
- `profile_boosts` table

### 2. Configure Meilisearch
Make sure your `.env` file has:
```env
SCOUT_DRIVER=meilisearch
MEILISEARCH_HOST=http://localhost:7700
MEILISEARCH_KEY=your-master-key
```

### 3. Index Users in Meilisearch
```bash
# First-time setup (fresh index)
php artisan meilisearch:index-users --fresh

# Update existing index
php artisan meilisearch:index-users

# Custom chunk size (default: 100)
php artisan meilisearch:index-users --chunk=500
```

This command will:
- Configure filterable attributes (40+ fields)
- Configure sortable attributes (age, height, activity, etc.)
- Configure searchable attributes (name, bio, occupation, etc.)
- Index all active users with profiles
- Show progress bar and statistics

### 4. Keep Index Updated
Add to your `User` model events or scheduler to automatically sync:

```php
// In User model - already implemented
protected static function booted()
{
    static::saved(function ($user) {
        if ($user->shouldBeSearchable()) {
            $user->searchable();
        }
    });
}
```

Or schedule regular reindexing:
```php
// In app/Console/Kernel.php
protected function schedule(Schedule $schedule)
{
    // Reindex users daily at 2 AM
    $schedule->command('meilisearch:index-users')->dailyAt('02:00');
}
```

## Usage Guide

### Basic Search
```php
// User visits /search
// Sees search interface with filters
// Can search by keyword, apply filters
// Results update in real-time with Alpine.js
```

### Advanced Search (AJAX)
```javascript
POST /search
Content-Type: application/json

{
  "keyword": "engineer",
  "age_min": 25,
  "age_max": 35,
  "city": "Mumbai",
  "education": ["Bachelor's Degree", "Master's Degree"],
  "religion": ["Hindu"],
  "video_verified": true,
  "sort": "active",
  "page": 1
}
```

Response:
```json
{
  "success": true,
  "data": {
    "users": [...],
    "total": 245,
    "page": 1,
    "perPage": 20,
    "hasMore": true,
    "facets": {
      "religion": {"Hindu": 150, "Muslim": 45, ...},
      "education": {...}
    }
  }
}
```

### Save a Search
```javascript
POST /search/save
Content-Type: application/json

{
  "name": "Engineers in Mumbai",
  "filters": {
    "occupation": "Software Engineer",
    "city": "Mumbai",
    "age_min": 25,
    "age_max": 35
  },
  "notify_on_match": true
}
```

### Boost Profile
```javascript
POST /profile/boost
Content-Type: application/json

{
  "duration_type": "24h"  // or "7d", "30d"
}
```

### Location-Based Search (Radius)
Premium users can search within a radius:
```php
$users = $searchService->searchByRadius(
    lat: 19.0760,
    lng: 72.8777,
    radiusKm: 50,
    filters: ['age_min' => 25, 'age_max' => 35]
);
```

## Files Created

### Models
- `/app/Models/SavedSearch.php` - Saved search model with relationships
- `/app/Models/ProfileBoost.php` - Profile boost model with duration tracking

### Controllers
- `/app/Http/Controllers/SearchController.php` - Main search controller with all methods

### Services
- `/app/Services/SearchService.php` - Meilisearch query builder and search logic

### Commands
- `/app/Console/Commands/IndexUsersInMeilisearch.php` - Indexing command

### Views
- `/resources/views/search/index.blade.php` - Main search page
- `/resources/views/search/results.blade.php` - Search results page
- `/resources/views/search/who-liked-you.blade.php` - Who liked you page (premium)
- `/resources/views/search/saved-searches.blade.php` - Manage saved searches
- `/resources/views/search/partials/filters.blade.php` - Reusable filters sidebar

### Migrations
- `database/migrations/2025_12_01_071833_create_saved_searches_table.php`
- `database/migrations/2025_12_31_164531_create_profile_boosts_table.php`

### Configuration
- `config/scout.php` - Updated with comprehensive Meilisearch settings

### Routes
Added to `routes/web.php`:
- `GET /search` - Search page
- `POST /search` - Perform search
- `GET /search/who-liked-you` - Who liked you (premium)
- `POST /search/save` - Save search
- `GET /search/saved` - Saved searches list
- `DELETE /search/saved/{id}` - Delete saved search
- `GET /search/saved/{id}/run` - Run saved search
- `POST /profile/boost` - Boost profile (premium)

## Premium Features & Plans

### Free Plan
- Basic search (age, location, education, religion)
- Limited to 20 results
- No saved searches

### Gold Plan ($9.99/month)
- Unlimited search results
- Save up to 5 searches
- Advanced filters (income, radius)
- "Who Liked You" feature
- 1 profile boost per month (24h)

### Platinum Plan ($19.99/month)
- Everything in Gold
- Save unlimited searches
- Priority in search results
- 2 profile boosts per month (7d each)

### Elite Plan ($49.99/month)
- Everything in Platinum
- Weekly profile boosts (30d)
- Dedicated relationship manager
- Featured in "Top Picks"

## Performance Optimizations

1. **Caching**
   - Filter options cached for 1 hour
   - Boosted user IDs cached for 5 minutes
   - Search suggestions cached for 1 hour per user

2. **Lazy Loading**
   - Profile images lazy loaded
   - Infinite scroll for results

3. **Efficient Indexing**
   - Chunked indexing (100 users at a time)
   - Only index active users with profiles
   - Selective field indexing

4. **Database Optimization**
   - Proper indexes on foreign keys
   - Index on `expires_at` for boosts
   - Index on `last_checked_at` for saved searches

## Monitoring & Maintenance

### Check Index Status
```bash
# View Meilisearch stats
curl http://localhost:7700/stats

# View specific index stats
curl http://localhost:7700/indexes/users/stats
```

### Troubleshooting

**Users not appearing in search:**
1. Check if user is active: `is_active = true`
2. Check if user has profile: `profile !== null`
3. Check if user is banned/suspended
4. Re-index: `php artisan meilisearch:index-users --fresh`

**Search is slow:**
1. Check Meilisearch is running: `curl http://localhost:7700/health`
2. Increase Meilisearch RAM allocation
3. Enable Redis caching
4. Reduce number of filterable attributes

**Boost not working:**
1. Check boost hasn't expired
2. Clear boosted users cache: `Cache::forget('boosted_user_ids')`
3. Verify boost is active in database

## API Integration Examples

### React/Vue Frontend
```javascript
// Search component
const search = async (filters) => {
  const response = await fetch('/search', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': csrfToken,
    },
    body: JSON.stringify(filters),
  });

  const data = await response.json();
  return data.data.users;
};
```

### Mobile App (React Native)
```javascript
// Search API service
export const searchUsers = async (filters) => {
  const response = await api.post('/search', filters);
  return response.data.data;
};

export const saveSearch = async (name, filters) => {
  const response = await api.post('/search/save', {
    name,
    filters,
    notify_on_match: true,
  });
  return response.data;
};
```

## Testing

### Test Search Functionality
```bash
# Create test users
php artisan tinker
>>> User::factory(100)->create();

# Index them
php artisan meilisearch:index-users --fresh

# Test search
>>> $results = app(SearchService::class)->search(['age_min' => 25], auth()->user());
>>> $results['total']
```

### Test Premium Features
```php
// Test feature gating
$user = User::find(1);
$canSeeWhoLiked = app(FeatureGateService::class)->canSeeWhoLiked($user);
// Should be false for free users

// Upgrade user to Gold
$user->update(['is_premium' => true, 'premium_until' => now()->addMonth()]);
// Subscribe to Gold plan...

// Now should return true
$canSeeWhoLiked = app(FeatureGateService::class)->canSeeWhoLiked($user);
```

## Future Enhancements

1. **AI-Powered Matching**
   - Use Meilisearch's vector search for compatibility scoring
   - Machine learning recommendations

2. **Search Analytics**
   - Track popular searches
   - Search-to-match conversion rates
   - A/B testing different filter layouts

3. **Real-time Updates**
   - WebSocket notifications for new matches
   - Live update of "Online Now" status
   - Real-time boost statistics

4. **Advanced Geo Search**
   - Meilisearch geo search (when stable)
   - Distance-based sorting
   - Map view of nearby matches

5. **Search Export**
   - Export search results to CSV
   - Share search links
   - Collaborative searching (family/friends)

## Support

For issues or questions:
1. Check Meilisearch logs: `docker logs meilisearch`
2. Check Laravel logs: `storage/logs/laravel.log`
3. Enable query logging in SearchService for debugging
4. Use Meilisearch dashboard: `http://localhost:7700`

## License
This implementation is part of the matrimony application.
