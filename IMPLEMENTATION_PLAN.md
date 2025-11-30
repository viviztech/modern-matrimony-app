# Phase-wise Implementation Plan
**Project:** Modern Matrimony App for Gen Z
**Tech Stack:** Laravel 12 + Tailwind CSS 4 + Livewire 3
**Start Date:** November 30, 2024

---

## 🎯 Implementation Philosophy

- **Build incrementally** - Each phase is functional and testable
- **Mobile-first** - Responsive from day one
- **Test as you go** - Feature tests for each module
- **Security first** - Validate, sanitize, authorize everything
- **Performance aware** - Optimize queries, cache aggressively
- **User feedback loop** - Test with real users at each phase

---

# PHASE 1: FOUNDATION (Weeks 1-4)

## Week 1: Database & Authentication

### 1.1 Database Schema Setup
**Files to create:**
- `database/migrations/xxxx_create_users_table.php` (enhanced)
- `database/migrations/xxxx_create_profiles_table.php`
- `database/migrations/xxxx_create_photos_table.php`
- `database/migrations/xxxx_create_preferences_table.php`
- `database/migrations/xxxx_create_verifications_table.php`

**Schema Details:**
```sql
users:
  - id, name, email, phone, password
  - dob, gender, location (city, state, country)
  - email_verified_at, phone_verified_at
  - is_active, is_premium, premium_until
  - last_active_at, profile_completion_percentage
  - timestamps, soft_deletes

profiles:
  - user_id (FK), bio, looking_for
  - video_intro_url, voice_intro_url
  - height, body_type, complexion
  - education, occupation, company, annual_income
  - diet, drinking, smoking
  - religion, religion_importance (1-10)
  - caste (nullable), show_caste (boolean)
  - mother_tongue, languages_known (JSON)
  - family_type, family_values, family_location
  - marital_status, have_children
  - interests (JSON), hobbies (JSON)
  - personality_type (MBTI), dealbreakers (JSON)
  - prompts (JSON: [{question, answer, order}])
  - timestamps

photos:
  - user_id (FK), url, thumbnail_url
  - order, is_primary
  - verification_score (0-100, AI check)
  - status (pending, approved, rejected)
  - rejection_reason
  - timestamps, soft_deletes

preferences:
  - user_id (FK)
  - age_min, age_max
  - height_min, height_max
  - location_preference (JSON: cities)
  - willing_to_relocate (boolean)
  - education_levels (JSON)
  - occupation_types (JSON)
  - income_min, income_max
  - religion_preferences (JSON)
  - caste_preferences (JSON)
  - diet_preferences (JSON)
  - drinking_preference, smoking_preference
  - marital_status_preferences (JSON)
  - distance_radius (km)
  - dealbreakers (JSON)
  - timestamps

verifications:
  - user_id (FK), type (email, phone, video, photo, document)
  - status (pending, verified, rejected)
  - data (JSON: verification details)
  - verified_at, verified_by (admin_id)
  - expiry_date
  - timestamps
```

**Tasks:**
- [ ] Create and run migrations
- [ ] Add indexes (user_id, status, created_at)
- [ ] Create model relationships
- [ ] Add validation rules to models
- [ ] Create factories for testing
- [ ] Seed sample data (100 fake users)

### 1.2 Authentication System
**Files to create:**
- `app/Http/Controllers/Auth/RegisterController.php`
- `app/Http/Controllers/Auth/LoginController.php`
- `app/Http/Controllers/Auth/VerificationController.php`
- `app/Livewire/Auth/Register.php`
- `app/Livewire/Auth/Login.php`
- `resources/views/livewire/auth/register.blade.php`
- `resources/views/livewire/auth/login.blade.php`
- `resources/views/layouts/auth.blade.php`
- `resources/views/layouts/app.blade.php`

**Features:**
- [ ] Email/Password registration
- [ ] Phone number collection (verify later)
- [ ] OTP-based phone verification (Twilio/SNS)
- [ ] Email verification
- [ ] Login (email or phone)
- [ ] Remember me functionality
- [ ] Password reset flow
- [ ] Social login (Google, Facebook) - optional

**Validation Rules:**
- Email: unique, valid format
- Phone: unique, E.164 format
- Password: min 8 chars, 1 uppercase, 1 number
- Name: 2-50 chars, no special chars
- DOB: 22-60 years old, 18+ mandatory
- Gender: required (male, female, other)

### 1.3 Base UI Components
**Files to create:**
- `resources/views/components/button.blade.php`
- `resources/views/components/input.blade.php`
- `resources/views/components/select.blade.php`
- `resources/views/components/textarea.blade.php`
- `resources/views/components/modal.blade.php`
- `resources/views/components/alert.blade.php`
- `resources/views/components/badge.blade.php`
- `resources/views/components/avatar.blade.php`
- `resources/css/app.css` (Tailwind setup)

**Design System:**
```css
Colors:
  - Primary: #FF6B6B (Coral)
  - Secondary: #6C63FF (Purple)
  - Accent: #4ECDC4 (Mint)
  - Success: #51CF66
  - Error: #FF6B6B
  - Warning: #FFD93D
  - Gray scale: 50-900

Typography:
  - Font: Inter (system-ui fallback)
  - Sizes: xs(12px) sm(14px) base(16px) lg(18px) xl(20px)
  - Weights: normal(400) medium(500) semibold(600) bold(700)

Spacing: 4px base unit (0.5, 1, 2, 3, 4, 6, 8, 12, 16, 24)
Border radius: sm(4px) md(8px) lg(12px) xl(16px) full(9999px)
Shadows: soft elevation system
```

**Tasks:**
- [ ] Setup Tailwind config with custom colors
- [ ] Create reusable Blade components
- [ ] Build auth layout (centered card design)
- [ ] Build app layout (navbar, sidebar for desktop)
- [ ] Add dark mode toggle (default: dark)
- [ ] Responsive breakpoints (mobile-first)

---

## Week 2: Profile Creation & Onboarding

### 2.1 Multi-Step Onboarding Flow
**Files to create:**
- `app/Livewire/Onboarding/Welcome.php`
- `app/Livewire/Onboarding/BasicInfo.php`
- `app/Livewire/Onboarding/PersonalityQuiz.php`
- `app/Livewire/Onboarding/PhotoUpload.php`
- `app/Livewire/Onboarding/VideoIntro.php`
- `app/Livewire/Onboarding/Preferences.php`
- `app/Livewire/Onboarding/Verification.php`
- `app/Services/OnboardingService.php`
- `resources/views/livewire/onboarding/*`

**Onboarding Steps:**
```
Step 1: Welcome (explain the process)
Step 2: Basic Info (location, education, occupation)
Step 3: Personality Quiz (10 questions, MBTI-style)
Step 4: About You (bio, interests, prompts)
Step 5: Photos (3-8 photos, AI quality check)
Step 6: Video Intro (30s recording or upload)
Step 7: Looking For (preferences, dealbreakers)
Step 8: Verification (phone OTP)
Step 9: Review Profile
```

**Step 2: Basic Info Fields**
- [ ] Education level (dropdown: High School, Bachelor's, Master's, PhD)
- [ ] Field of study (autocomplete)
- [ ] Occupation (autocomplete: 100+ common jobs)
- [ ] Company name (text)
- [ ] Annual income (range slider: ₹0-50L+)
- [ ] Current city (autocomplete: Indian cities)
- [ ] Hometown (autocomplete)
- [ ] Willing to relocate (yes/no/maybe)
- [ ] Height (slider: 4'0" - 7'0")
- [ ] Diet (veg, non-veg, vegan, jain)
- [ ] Drinking (never, socially, regularly, prefer not to say)
- [ ] Smoking (never, socially, regularly, prefer not to say)

**Step 3: Personality Quiz**
- [ ] 10 questions with 4 options each
- [ ] Calculate MBTI-like personality type
- [ ] Store answers in profile
- [ ] Show personality badge on completion

**Sample Questions:**
```
1. In social gatherings, you prefer to:
   A) Be the center of attention
   B) Have deep conversations with few people
   C) Observe and listen more
   D) Mix between groups

2. When making decisions, you rely more on:
   A) Logic and data
   B) Gut feeling and emotions
   C) Others' opinions
   D) Past experiences

... (8 more questions)
```

**Step 4: About You**
- [ ] Bio (150 words, AI writing assistant suggestions)
- [ ] Interests (multi-select tags: 50+ options)
- [ ] Prompts (3 mandatory, Hinge-style)

**Prompt Examples:**
```
- "I'll know I found the one when..."
- "My ideal Sunday looks like..."
- "I geek out on..."
- "My love language is..."
- "I'm looking for someone who..."
- "Together we could..."
- "The key to my heart is..."
```

**Step 5: Photo Upload**
- [ ] Drag & drop interface
- [ ] Crop & rotate functionality
- [ ] Min 3 photos, max 8 photos
- [ ] AI quality check (face visible, well-lit, not blurry)
- [ ] Set primary photo
- [ ] Photo order drag-to-reorder
- [ ] Progressive upload (background)

**Step 6: Video Intro**
- [ ] Record in-browser (WebRTC, MediaRecorder API)
- [ ] Upload video file (max 30s, 50MB)
- [ ] Preview before saving
- [ ] Video compression (client-side)
- [ ] Thumbnail generation
- [ ] Optional: face verification during recording

**Step 7: Preferences**
- [ ] Age range (slider: 22-60)
- [ ] Height preference (optional)
- [ ] Location (within X km or specific cities)
- [ ] Education level (multi-select)
- [ ] Religion importance (slider 1-10)
- [ ] Dealbreakers (multi-select)

**Step 8: Verification**
- [ ] Phone OTP verification
- [ ] Email verification link
- [ ] Video selfie verification (liveness check)
- [ ] Show verification badges

### 2.2 Profile Management
**Files to create:**
- `app/Http/Controllers/ProfileController.php`
- `app/Livewire/Profile/View.php`
- `app/Livewire/Profile/Edit.php`
- `app/Services/ProfileService.php`
- `app/Services/MediaService.php`
- `resources/views/profile/view.blade.php`
- `resources/views/profile/edit.blade.php`

**Features:**
- [ ] View own profile (preview mode)
- [ ] Edit profile (each section separately)
- [ ] Profile completion percentage
- [ ] Profile visibility toggle
- [ ] Delete photos/videos
- [ ] Re-upload media
- [ ] Profile analytics (views, likes)

### 2.3 Media Upload Service
**Files to create:**
- `app/Services/MediaUploadService.php`
- `config/media.php`

**Features:**
- [ ] Image optimization (resize, compress)
- [ ] Thumbnail generation (multiple sizes)
- [ ] Video transcoding (compress to web format)
- [ ] Video thumbnail extraction
- [ ] Upload to S3/local storage
- [ ] Generate signed URLs (private access)
- [ ] Virus/malware scanning
- [ ] EXIF data stripping (privacy)

**Configuration:**
```php
'allowed_image_types' => ['jpg', 'jpeg', 'png', 'webp'],
'allowed_video_types' => ['mp4', 'mov', 'webm'],
'max_image_size' => 10 * 1024 * 1024, // 10MB
'max_video_size' => 50 * 1024 * 1024, // 50MB
'thumbnail_sizes' => [
    'thumb' => [150, 150],
    'small' => [300, 300],
    'medium' => [600, 600],
    'large' => [1200, 1200],
],
```

---

## Week 3: Discovery & Matching

### 3.1 Basic Matching Algorithm
**Files to create:**
- `app/Services/MatchingService.php`
- `app/Models/Match.php`
- `app/Models/Swipe.php`
- `database/migrations/xxxx_create_matches_table.php`
- `database/migrations/xxxx_create_swipes_table.php`

**Schema:**
```sql
swipes:
  - id, user_id (who swiped), swiped_user_id (profile swiped on)
  - direction (left, right, super_like)
  - created_at
  - UNIQUE(user_id, swiped_user_id)

matches:
  - id, user_id, matched_user_id
  - compatibility_score (0-100)
  - matched_at
  - status (active, unmatched, blocked)
  - conversation_started_at
  - last_message_at
  - timestamps
```

**Basic Compatibility Algorithm:**
```php
Scoring (0-100):
- Age preference match: 15 points
- Location proximity: 15 points
- Education level match: 10 points
- Religion/caste preference: 10 points
- Lifestyle (diet, drinking, smoking): 10 points
- Interests overlap: 15 points
- Personality compatibility: 15 points
- Dealbreakers check: -100 (instant fail) or 0
- Height preference: 5 points
- Income range: 5 points

Total: 100 points (70+ = good match)
```

**Tasks:**
- [ ] Implement compatibility scoring
- [ ] Match detection (mutual right swipe)
- [ ] Match notification
- [ ] Prevent seeing same profile twice
- [ ] Respect privacy settings
- [ ] Block list filtering

### 3.2 Swipe Interface
**Files to create:**
- `app/Livewire/Discover/SwipeCards.php`
- `resources/views/livewire/discover/swipe-cards.blade.php`
- `resources/js/swipe.js` (Swiper.js integration)

**Features:**
- [ ] Card stack UI (show 3 cards at a time)
- [ ] Swipe left (pass), right (like), up (super like)
- [ ] Button alternatives (mobile & desktop)
- [ ] Card animations (smooth transitions)
- [ ] Profile preview on card
- [ ] Load more profiles (infinite scroll)
- [ ] Daily limit enforcement (50 for free users)
- [ ] Undo last swipe (premium feature)

**Card Content:**
```
- Primary photo (full screen)
- Name, Age
- Location, Distance
- Occupation
- Education
- Compatibility score badge
- Verification badges
- 1-2 prompts preview
- "View Full Profile" button
```

### 3.3 Profile Discovery Feed
**Files to create:**
- `app/Livewire/Discover/Feed.php`
- `app/Livewire/Discover/DailyRecommendations.php`
- `resources/views/livewire/discover/*`

**Discovery Modes:**
1. **Daily Recommendations** (5-10 curated profiles)
   - Highest compatibility scores
   - Active users only (last 7 days)
   - Not previously swiped

2. **Explore** (Swipe interface)
   - All eligible profiles
   - Filtered by preferences
   - Randomized order (with quality boost)

3. **Advanced Search** (Premium)
   - Filter by all criteria
   - Sort by: newest, compatibility, distance
   - Grid view option

**Tasks:**
- [ ] Daily recommendations cron job
- [ ] Cache recommendations (refresh daily)
- [ ] Track profile views
- [ ] Implement search filters
- [ ] Pagination (cursor-based)

### 3.4 Profile Viewing
**Files to create:**
- `app/Livewire/Profile/PublicView.php`
- `resources/views/livewire/profile/public-view.blade.php`

**Full Profile View:**
- [ ] Photo gallery (swipeable)
- [ ] Video intro (autoplay muted)
- [ ] Voice intro (play button)
- [ ] All prompts and answers
- [ ] Interests tags
- [ ] About section
- [ ] Basic info grid
- [ ] Verification badges
- [ ] "Like" and "Pass" buttons
- [ ] "Report" option
- [ ] Track view analytics

---

## Week 4: Messaging & Communication

### 4.1 Chat System
**Files to create:**
- `database/migrations/xxxx_create_messages_table.php`
- `database/migrations/xxxx_create_conversations_table.php`
- `app/Models/Message.php`
- `app/Models/Conversation.php`
- `app/Livewire/Chat/ConversationList.php`
- `app/Livewire/Chat/MessageThread.php`
- `app/Services/ChatService.php`

**Schema:**
```sql
conversations:
  - id, match_id (FK)
  - user_one_id, user_two_id
  - last_message_id (FK)
  - last_message_at
  - user_one_unread_count, user_two_unread_count
  - timestamps

messages:
  - id, conversation_id (FK), match_id (FK)
  - sender_id, receiver_id
  - type (text, image, voice, video_call_request, icebreaker)
  - content (text), media_url
  - read_at, delivered_at
  - deleted_by_sender_at, deleted_by_receiver_at
  - quality_score (AI analysis, 0-100)
  - timestamps
```

**Features:**
- [ ] Real-time messaging (Livewire polling initially)
- [ ] Text messages
- [ ] Image sharing
- [ ] Voice notes (record & send)
- [ ] Message delivery status (sent, delivered, read)
- [ ] Read receipts (premium feature)
- [ ] Typing indicators
- [ ] Message search within conversation
- [ ] Delete message (for self)
- [ ] Block user
- [ ] Report conversation

### 4.2 Icebreaker System
**Files to create:**
- `app/Services/IcebreakerService.php`
- `database/seeders/IcebreakersSeeder.php`

**Mandatory First Message:**
- [ ] User must select an icebreaker prompt
- [ ] Or answer a profile prompt
- [ ] Prevents generic "Hi" messages
- [ ] AI-generated personalized suggestions

**Icebreaker Prompts (100+ questions):**
```
Categories:
- Getting to know you (20)
- Hobbies & interests (15)
- Life goals & dreams (15)
- Food & travel (15)
- Fun & quirky (15)
- Values & beliefs (10)
- This or that (10)

Examples:
- "If you could travel anywhere right now, where would you go and why?"
- "What's a skill you'd love to learn?"
- "Coffee or tea? Justify your answer!"
- "What's your go-to comfort food?"
- "Mountains or beaches for a vacation?"
```

**Tasks:**
- [ ] Create icebreakers table & seed
- [ ] Random icebreaker selection
- [ ] Personalized suggestions based on profile
- [ ] Track which icebreakers are most effective

### 4.3 Conversation List UI
**Features:**
- [ ] List all conversations
- [ ] Sort by: last message, unread
- [ ] Unread count badges
- [ ] Last message preview
- [ ] Online status indicator
- [ ] Search conversations
- [ ] Filter: all, unread, archived
- [ ] Archive conversation
- [ ] Delete conversation
- [ ] Pin important conversations (premium)

### 4.4 Message Thread UI
**Features:**
- [ ] Chat bubble design (modern, readable)
- [ ] Group messages by date
- [ ] Auto-scroll to bottom
- [ ] Load older messages (pagination)
- [ ] Message input with emoji picker
- [ ] Voice recording interface
- [ ] Image preview before send
- [ ] Link preview generation
- [ ] Copy message text
- [ ] Message reactions (premium)

---

# PHASE 2: CORE FEATURES (Weeks 5-8)

## Week 5: Video Calling & Voice Features

### 5.1 Video Call Integration
**Files to create:**
- `app/Services/VideoCallService.php`
- `app/Livewire/VideoCall/Room.php`
- `app/Models/VideoCall.php`
- `database/migrations/xxxx_create_video_calls_table.php`
- `resources/views/livewire/video-call/room.blade.php`
- `resources/js/webrtc.js`

**Technology Options:**
- Agora.io SDK (recommended, easy, scalable)
- Twilio Video (alternative)
- Native WebRTC (complex, full control)

**Schema:**
```sql
video_calls:
  - id, match_id, conversation_id
  - caller_id, receiver_id
  - status (initiated, ringing, active, ended, missed, declined)
  - started_at, ended_at, duration (seconds)
  - call_type (video, audio)
  - recording_url (optional, for disputes)
  - quality_rating (1-5, post-call feedback)
  - timestamps
```

**Features:**
- [ ] Initiate video call (after 3+ messages)
- [ ] In-app ringing notification
- [ ] Accept/Decline call
- [ ] Video call UI (full screen)
- [ ] Mute audio, disable video
- [ ] Switch camera (front/back on mobile)
- [ ] End call button
- [ ] Call duration timer
- [ ] Panic button (report + end call)
- [ ] Call history tracking
- [ ] Daily call limit (3 for free, unlimited premium)

**Safety Features:**
- [ ] First call must be under 15 mins
- [ ] Panic button (screenshot + report)
- [ ] Auto-end if one user leaves for 30s
- [ ] Report inappropriate behavior
- [ ] Block after call

### 5.2 Voice Messages
**Files to create:**
- `resources/js/voice-recorder.js`
- `app/Services/AudioService.php`

**Features:**
- [ ] Record voice message (max 60s)
- [ ] Waveform visualization
- [ ] Play/pause controls
- [ ] Playback speed control
- [ ] Audio compression before upload
- [ ] Mark as played

---

## Week 6: Verification & Trust

### 6.1 Phone Verification
**Files to create:**
- `app/Services/SmsService.php` (Twilio/SNS/MSG91)
- `app/Livewire/Verification/PhoneVerification.php`

**Features:**
- [ ] Send OTP via SMS
- [ ] Verify OTP (6-digit)
- [ ] Resend OTP (with cooldown)
- [ ] Rate limiting (prevent spam)
- [ ] Update verification status
- [ ] Show verified badge

### 6.2 Video Selfie Verification
**Files to create:**
- `app/Services/FaceVerificationService.php` (AWS Rekognition)
- `app/Livewire/Verification/VideoSelfie.php`

**Features:**
- [ ] Record 5-second selfie video
- [ ] Liveness detection (blink, turn head)
- [ ] Face match with profile photos (AI)
- [ ] Compare with government ID (optional)
- [ ] Manual review queue for edge cases
- [ ] Verified badge on profile

### 6.3 Photo Verification (AI)
**Features:**
- [ ] Detect face in all photos
- [ ] Match all faces to primary photo (same person check)
- [ ] Detect overly edited/filtered photos (score)
- [ ] Flag inappropriate content (nudity, violence)
- [ ] Watermark detection
- [ ] Age estimation (match DOB)
- [ ] Quality score (lighting, resolution, clarity)

### 6.4 Social Verification
**Features:**
- [ ] LinkedIn OAuth integration
- [ ] Instagram OAuth integration
- [ ] Facebook OAuth (optional)
- [ ] Show verification badges
- [ ] Pull basic info (mutual friends count)
- [ ] Privacy: don't show full social profiles

### 6.5 Admin Moderation Panel
**Files to create:**
- `app/Http/Controllers/Admin/ModerationController.php`
- `resources/views/admin/moderation/*`

**Features:**
- [ ] Review pending photo verifications
- [ ] Review reported profiles
- [ ] Review reported messages/calls
- [ ] Approve/Reject profiles
- [ ] Ban users
- [ ] View user activity logs
- [ ] Bulk actions

---

## Week 7: Premium Features & Monetization

### 7.1 Subscription System
**Files to create:**
- `database/migrations/xxxx_create_subscriptions_table.php`
- `database/migrations/xxxx_create_subscription_plans_table.php`
- `app/Models/Subscription.php`
- `app/Models/SubscriptionPlan.php`
- `app/Services/SubscriptionService.php`

**Schema:**
```sql
subscription_plans:
  - id, name (Gold, Platinum, Elite)
  - price_monthly, price_yearly
  - features (JSON)
  - is_active, display_order
  - timestamps

subscriptions:
  - id, user_id, plan_id
  - status (active, cancelled, expired, paused)
  - started_at, ends_at, cancelled_at
  - payment_method, transaction_id
  - auto_renew (boolean)
  - timestamps
```

**Plans:**
```
Free:
- 50 swipes/day
- 10 likes/day
- 3 video calls/week
- Basic filters
- See limited matches

Gold (₹499/mo, ₹4,999/yr):
- Unlimited swipes
- Unlimited likes
- See who liked you
- Unlimited video calls
- 5 super likes/week
- Advanced filters
- Read receipts
- Rewind feature (undo swipe)

Platinum (₹999/mo, ₹9,999/yr):
- All Gold features
- Priority profile placement (2x visibility)
- Incognito mode
- Background verification included
- Profile boost (1/month)
- Message before matching
- Video call recording

Elite (₹2,999/mo):
- All Platinum features
- Dedicated relationship manager
- Monthly coaching session
- Private events access
- Featured profile
- Professional photo shoot (2/year)
- Priority customer support
```

**Tasks:**
- [ ] Create subscription plans seeder
- [ ] Feature gates (check subscription)
- [ ] Upgrade/downgrade logic
- [ ] Cancel subscription
- [ ] Refund handling
- [ ] Trial period (7 days free)

### 7.2 Payment Integration
**Files to create:**
- `app/Services/PaymentService.php`
- `app/Http/Controllers/PaymentController.php`
- `database/migrations/xxxx_create_payments_table.php`

**Payment Gateway:** Razorpay (India) + Stripe (International)

**Schema:**
```sql
payments:
  - id, user_id, subscription_id
  - amount, currency, status
  - payment_method (card, upi, netbanking, wallet)
  - gateway (razorpay, stripe)
  - transaction_id, gateway_response (JSON)
  - paid_at, refunded_at, refund_amount
  - timestamps
```

**Features:**
- [ ] Razorpay integration
- [ ] Checkout page
- [ ] Payment success/failure handling
- [ ] Invoice generation (PDF)
- [ ] Email invoice
- [ ] Payment history
- [ ] Refund processing
- [ ] Webhook handling (auto-renewal)

### 7.3 Premium Feature Gates
**Files to create:**
- `app/Services/FeatureGateService.php`
- `app/Http/Middleware/CheckSubscription.php`

**Feature Checks:**
```php
- canSwipeMore() // Daily limit check
- canLike() // Daily like limit
- canVideoCall() // Weekly limit check
- canUseSuperLike() // Weekly limit
- canRewind() // Premium only
- canSeeWhoLikedMe() // Premium only
- canUseIncognito() // Platinum+
- canUseAdvancedFilters() // Gold+
```

**Tasks:**
- [ ] Create feature gate service
- [ ] Implement limit tracking (Redis)
- [ ] Reset daily/weekly limits (cron)
- [ ] Show upgrade prompts
- [ ] Graceful limit messages

---

## Week 8: Notifications & Real-time

### 8.1 Notification System
**Files to create:**
- `database/migrations/xxxx_create_notifications_table.php`
- `app/Models/Notification.php`
- `app/Services/NotificationService.php`
- `app/Livewire/Notifications/List.php`

**Notification Types:**
```
- New match
- New message
- Someone liked you
- Video call incoming
- Profile view (premium)
- Daily recommendations ready
- Subscription expiring soon
- Event reminder
- Success story featured
```

**Channels:**
- In-app (database notifications)
- Email (important only)
- SMS (video call, important alerts)
- Push (mobile app, future)

**Tasks:**
- [ ] Create notification templates
- [ ] Email templates (responsive)
- [ ] SMS templates
- [ ] Notification preferences (user settings)
- [ ] Mark as read
- [ ] Bulk mark as read
- [ ] Notification center UI

### 8.2 Real-time Features (Laravel Reverb)
**Files to create:**
- `config/reverb.php`
- `app/Events/NewMessage.php`
- `app/Events/UserOnline.php`
- `app/Events/VideoCallIncoming.php`

**Real-time Events:**
- [ ] New message received
- [ ] Message read status
- [ ] User online/offline
- [ ] Typing indicator
- [ ] Video call incoming
- [ ] Match notification

**Tasks:**
- [ ] Setup Laravel Reverb
- [ ] Create broadcast events
- [ ] Setup Echo on frontend
- [ ] Connect WebSocket in Livewire
- [ ] Handle reconnection
- [ ] Online presence tracking

### 8.3 Email System
**Files to create:**
- `app/Mail/WelcomeEmail.php`
- `app/Mail/MatchNotification.php`
- `app/Mail/DailyDigest.php`
- `resources/views/emails/*`

**Email Types:**
- [ ] Welcome email (after registration)
- [ ] Email verification
- [ ] New match notification
- [ ] Daily digest (3 best matches)
- [ ] Weekly activity summary
- [ ] Subscription receipt
- [ ] Password reset
- [ ] Account deactivation confirmation

**Tasks:**
- [ ] Design email templates (responsive)
- [ ] Setup mail driver (SES/Mailgun)
- [ ] Queue email jobs
- [ ] Unsubscribe link
- [ ] Email preferences

---

# PHASE 3: ENGAGEMENT (Weeks 9-12)

## Week 9: Stories & Interactive Content

### 9.1 Stories Feature
**Files to create:**
- `database/migrations/xxxx_create_stories_table.php`
- `database/migrations/xxxx_create_story_views_table.php`
- `app/Models/Story.php`
- `app/Livewire/Stories/Create.php`
- `app/Livewire/Stories/Viewer.php`

**Schema:**
```sql
stories:
  - id, user_id
  - type (photo, video, text)
  - media_url, thumbnail_url
  - text_content, background_color
  - views_count, likes_count
  - created_at, expires_at (24 hours)

story_views:
  - id, story_id, viewer_id
  - viewed_at
```

**Features:**
- [ ] Create photo/video story
- [ ] Text-only story (with backgrounds)
- [ ] Stories feed (matches only)
- [ ] Story viewer (Instagram-style)
- [ ] Auto-progress stories
- [ ] Tap to skip
- [ ] See who viewed (story creator)
- [ ] Like story
- [ ] Reply to story (DM)
- [ ] Auto-delete after 24hrs (cron)

### 9.2 Prompts & Games
**Files to create:**
- `app/Livewire/Games/TwentyOneQuestions.php`
- `app/Livewire/Games/WouldYouRather.php`
- `app/Livewire/Games/CompatibilityQuiz.php`

**Features:**
- [ ] 21 Questions game (play with match)
- [ ] Would You Rather (pre-made questions)
- [ ] Compatibility quiz (take together, see results)
- [ ] Two Truths and a Lie
- [ ] Guess the Preference

---

## Week 10: Search & Discovery Improvements

### 10.1 Advanced Search (Premium)
**Files to create:**
- `app/Livewire/Search/Advanced.php`
- `app/Services/SearchService.php` (Meilisearch)

**Filters:**
- [ ] All preference filters
- [ ] Keyword search (bio, prompts)
- [ ] Recently active (last 24hrs, 7days)
- [ ] New profiles (joined this week)
- [ ] Verified only
- [ ] With video intro
- [ ] Sort options (compatibility, distance, newest, active)

### 10.2 "Who Liked You" Feature
**Features:**
- [ ] Show profiles who swiped right
- [ ] Blurred preview (free users)
- [ ] Full access (premium)
- [ ] Filter by compatibility
- [ ] Quick match (skip to chat)

### 10.3 Profile Boost
**Features:**
- [ ] Boost profile for 30 minutes
- [ ] 10x visibility in discovery
- [ ] Analytics (views during boost)
- [ ] Premium: 1 boost/month, Free: ₹99/boost

---

## Week 11: Events & Community

### 11.1 Virtual Events
**Files to create:**
- `database/migrations/xxxx_create_events_table.php`
- `database/migrations/xxxx_create_event_participants_table.php`
- `app/Models/Event.php`
- `app/Livewire/Events/List.php`
- `app/Livewire/Events/Details.php`

**Event Types:**
- Cooking class (virtual)
- Book club discussion
- Movie watch party
- Speed dating (video rooms)
- Hobby workshops (photography, art)
- Fitness challenges
- Language exchange

**Features:**
- [ ] Create event (admin only, initially)
- [ ] Event listing
- [ ] RSVP/Register
- [ ] Event reminders
- [ ] Virtual room (video call for multiple users)
- [ ] Event chat (before & during)
- [ ] Post-event connections (suggest matches)

### 11.2 Community Groups (Future)
**Concept:**
- Interest-based groups (hiking, foodies, bookworms)
- Group chat
- Group events
- Matchmaking within groups

---

## Week 12: Analytics & Admin

### 12.1 User Analytics Dashboard
**Files to create:**
- `app/Livewire/Dashboard/Analytics.php`
- `app/Services/AnalyticsService.php`

**User Metrics:**
- [ ] Profile views (daily/weekly/monthly)
- [ ] Likes received
- [ ] Match rate (likes to matches ratio)
- [ ] Response rate
- [ ] Average message response time
- [ ] Video calls completed
- [ ] Profile completion impact
- [ ] Suggestions to improve profile

### 12.2 Admin Dashboard
**Files to create:**
- `app/Http/Controllers/Admin/DashboardController.php`
- `resources/views/admin/dashboard.blade.php`

**Admin Metrics:**
- Total users, active users (DAU, MAU)
- New signups (daily trend)
- Conversion rate (free to paid)
- Revenue (daily, monthly)
- Total matches, messages
- Average response time
- User retention (cohort analysis)
- Churn rate
- Top performing profiles
- Most active regions

### 12.3 Reporting & Moderation
**Features:**
- [ ] View all reports
- [ ] Review reported content
- [ ] Ban/warn users
- [ ] Delete inappropriate content
- [ ] Send warning emails
- [ ] View user activity logs
- [ ] Export data (compliance)

---

# PHASE 4: POLISH & SCALE (Weeks 13-16)

## Week 13: Performance Optimization

### 13.1 Database Optimization
**Tasks:**
- [ ] Add missing indexes
- [ ] Optimize N+1 queries (use with())
- [ ] Query caching (Redis)
- [ ] Database connection pooling
- [ ] Partition large tables (messages, swipes)
- [ ] Archive old data

### 13.2 Caching Strategy
**Cache Layers:**
```
- User profile (1 hour)
- Daily recommendations (24 hours)
- Match compatibility scores (until preference change)
- Photo URLs (permanent, invalidate on update)
- Feed queries (15 minutes)
- Search results (5 minutes)
```

**Tasks:**
- [ ] Implement cache tags
- [ ] Cache invalidation strategies
- [ ] Use Redis for hot data
- [ ] CDN for static assets
- [ ] Image optimization (WebP, lazy loading)

### 13.3 Frontend Performance
**Tasks:**
- [ ] Lazy load images (Intersection Observer)
- [ ] Code splitting (Vite)
- [ ] Minimize JavaScript bundle
- [ ] Use Alpine.js for small interactions
- [ ] Optimize Livewire polling intervals
- [ ] Implement service worker (PWA)
- [ ] Add loading skeletons

---

## Week 14: Security Hardening

### 14.1 Security Audit
**Tasks:**
- [ ] SQL injection prevention (use Eloquent)
- [ ] XSS protection (escape output)
- [ ] CSRF protection (enabled)
- [ ] Rate limiting on all APIs
- [ ] Input validation on all forms
- [ ] File upload restrictions
- [ ] Secure headers (CSP, HSTS)
- [ ] Prevent clickjacking
- [ ] API authentication (Sanctum)
- [ ] Encrypt sensitive data at rest

### 14.2 Privacy Features
**Tasks:**
- [ ] GDPR compliance (data export, deletion)
- [ ] Privacy policy page
- [ ] Terms of service
- [ ] Cookie consent banner
- [ ] Data retention policy
- [ ] User data download (JSON export)
- [ ] Permanent account deletion
- [ ] Anonymize deleted user data

---

## Week 15: Testing

### 15.1 Automated Testing
**Tasks:**
- [ ] Unit tests for services (80%+ coverage)
- [ ] Feature tests for all endpoints
- [ ] Browser tests (Dusk) for critical flows
  - Registration & onboarding
  - Profile creation
  - Swipe & match
  - Messaging
  - Payment
  - Video call initiation
- [ ] API tests
- [ ] Performance tests (load testing)

### 15.2 Manual Testing
**Tasks:**
- [ ] Cross-browser testing (Chrome, Safari, Firefox)
- [ ] Mobile responsive testing
- [ ] Accessibility testing (WCAG 2.1)
- [ ] User acceptance testing (UAT)
- [ ] Beta testing (100 users)

---

## Week 16: Launch Preparation

### 16.1 Production Setup
**Tasks:**
- [ ] Setup production server (AWS/DigitalOcean)
- [ ] Configure SSL certificates
- [ ] Setup CI/CD pipeline (GitHub Actions)
- [ ] Database backups (automated daily)
- [ ] Monitoring (Sentry, New Relic)
- [ ] Log aggregation (CloudWatch)
- [ ] Setup Redis cluster
- [ ] Configure CDN (Cloudflare)
- [ ] Email deliverability (warm up)
- [ ] SMS provider testing

### 16.2 Content & Marketing
**Tasks:**
- [ ] Write help documentation
- [ ] Create video tutorials
- [ ] Design marketing materials
- [ ] Setup social media accounts
- [ ] Press release draft
- [ ] Influencer outreach list
- [ ] App Store assets (screenshots, description)
- [ ] Google Play assets

### 16.3 Soft Launch
**Tasks:**
- [ ] Invite 500 beta users
- [ ] Monitor error rates
- [ ] Gather feedback
- [ ] Fix critical bugs
- [ ] Iterate on UX
- [ ] Prepare for scale
- [ ] Load testing (simulate 10k users)

---

# ONGOING TASKS (Post-Launch)

## Daily
- [ ] Monitor error logs
- [ ] Review user reports
- [ ] Approve pending verifications
- [ ] Customer support tickets

## Weekly
- [ ] Analyze user metrics
- [ ] Review new signups quality
- [ ] Check conversion rates
- [ ] Plan feature iterations
- [ ] Content moderation review

## Monthly
- [ ] Feature releases
- [ ] Performance review
- [ ] Security updates
- [ ] Database optimization
- [ ] User survey & feedback
- [ ] Competitor analysis

---

# DEVELOPMENT WORKFLOW

## Git Workflow
```
main (production)
  ├── staging (pre-production testing)
  ├── develop (integration branch)
      ├── feature/user-profile
      ├── feature/messaging
      ├── feature/video-call
      └── bugfix/login-issue
```

## Daily Routine
1. Pull latest from develop
2. Create feature branch
3. Write tests first (TDD)
4. Implement feature
5. Run tests locally
6. Commit with meaningful message
7. Push and create PR
8. Code review
9. Merge to develop
10. Deploy to staging
11. QA testing
12. Merge to main (weekly)

## Code Standards
- PSR-12 coding style
- Laravel best practices
- Meaningful variable/function names
- Comment complex logic
- Keep functions small (<50 lines)
- DRY principle
- SOLID principles

---

# SUCCESS CRITERIA FOR EACH PHASE

## Phase 1 Complete When:
- ✅ User can register & login
- ✅ User can complete full onboarding
- ✅ Profile shows all information correctly
- ✅ Photos & videos upload successfully
- ✅ Basic matching works (see eligible profiles)
- ✅ Can swipe and create matches
- ✅ Can send text messages
- ✅ Notifications work

## Phase 2 Complete When:
- ✅ Video calls work smoothly
- ✅ Voice messages work
- ✅ All verification types implemented
- ✅ Premium subscription system works
- ✅ Payment integration tested
- ✅ Feature gates enforce limits
- ✅ Email system functional
- ✅ Real-time updates work

## Phase 3 Complete When:
- ✅ Stories feature live
- ✅ Interactive games work
- ✅ Advanced search functional
- ✅ Events system operational
- ✅ Analytics dashboards complete
- ✅ Admin moderation tools ready

## Phase 4 Complete When:
- ✅ Performance benchmarks met (<2s page load)
- ✅ Security audit passed
- ✅ Test coverage >80%
- ✅ Beta testing complete
- ✅ Production environment ready
- ✅ Monitoring & alerts configured

---

# RISK MITIGATION

## Technical Risks
- **Risk:** Video calls don't scale
  - **Mitigation:** Use proven SDK (Agora), load test early

- **Risk:** Database performance issues
  - **Mitigation:** Index properly, cache aggressively, partition tables

- **Risk:** Real-time messaging lag
  - **Mitigation:** Use Redis, WebSockets, test with load

## Business Risks
- **Risk:** Low user acquisition
  - **Mitigation:** Invite-only beta, referral program, influencer marketing

- **Risk:** Fake profiles damage trust
  - **Mitigation:** Multi-layer verification, AI detection, manual review

- **Risk:** Safety incidents
  - **Mitigation:** Comprehensive reporting, quick response team, insurance

---

# READY TO START?

**Current Status:** Planning Complete ✅
**Next Action:** Begin Week 1, Day 1 - Database Schema

**Shall we start implementing?** 🚀

Say "Let's start" and I'll begin with:
1. Creating enhanced migration files
2. Setting up models with relationships
3. Creating factories and seeders
4. Running migrations and seeding test data
