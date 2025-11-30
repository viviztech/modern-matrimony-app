# Test Credentials - Modern Matrimony App

## Admin Account
**Email:** admin@matrimony.app
**Password:** password
**Premium:** Yes (Valid until 2035)
**Features:** Full access, all premium features enabled, admin moderation panel
**Admin Panel:** `/admin/moderation`

### Admin Capabilities
- Review and manage user reports
- Review AI-flagged photo verifications
- Ban/suspend users
- Approve/reject photos manually
- View user activity and statistics
- Bulk moderation actions

---

## Test User Accounts

### User 1 - John Doe (Male)
**Email:** john@example.com
**Password:** password123
**Profile:**
- Age: 33 years
- Location: Delhi, India
- Occupation: Software Engineer
- Education: Bachelors
- Income: ₹15,00,000/year
- Interests: Technology, Travel, Photography
- Status: Email verified, Profile 80% complete

**Test Data:**
- Matched with Jane Smith
- Has active conversation with Jane
- Can send/receive messages
- Can initiate video/audio calls

---

### User 2 - Jane Smith (Female)
**Email:** jane@example.com
**Password:** password123
**Profile:**
- Age: 31 years
- Location: Bangalore, Karnataka
- Occupation: Product Manager
- Education: Masters
- Income: ₹18,00,000/year
- Interests: Design, Travel, Reading
- Status: Email verified, Profile 85% complete

**Test Data:**
- Matched with John Doe
- Has active conversation with John
- Can send/receive messages
- Can initiate video/audio calls

---

## Existing Seeded Users

The database contains 53 users created by the factory seeder. First user:
- **Email:** test@example.com
- **Password:** password (default from factory)

Other seeded users:
- cmccullough@example.net
- rhessel@example.org
- cristina.huels@example.org
- ypadberg@example.com

**Note:** All factory-seeded users use `password` as the default password.

---

## Testing Features

### 1. Login & Authentication
```
Route: /login
Use any of the credentials above
```

### 2. Messaging System
- Login as John or Jane
- Navigate to Messages
- You'll see an existing conversation
- Test features:
  - Send text messages
  - Record & send voice messages (click microphone icon)
  - Real-time message polling
  - Read receipts

### 3. Voice Messages
- In any conversation, click the microphone icon
- Browser will ask for microphone permission (allow it)
- Record up to 60 seconds
- Waveform visualization shows recording
- Click stop to send, or cancel to discard
- Play received voice messages with custom player

### 4. Video/Audio Calls
- In any conversation header, click video or audio call icon
- System creates a call record
- Test call lifecycle:
  - Initiate call
  - Accept/Decline
  - End call
  - View call history: `/video-calls/history`

### 5. Icebreaker Questions
- 135 icebreaker questions available
- Categorized into 7 groups
- Personalized suggestions based on user interests

### 6. Match Discovery
- Login as any user
- Navigate to Discover
- Swipe through potential matches
- Like users to create matches

---

## Database Statistics

- **Total Users:** 53
- **Total Profiles:** 51
- **Total Photos:** 230
- **Total Matches:** 2+
- **Total Conversations:** 1+
- **Total Icebreakers:** 135
- **Total Video Calls:** 2 (test calls)

---

## Routes for Testing

### Authentication
- `/login` - Login page
- `/register` - Registration
- `/dashboard` - Redirects to discover

### Core Features
- `/discover` - Match discovery (swipe interface)
- `/matches` - View all matches
- `/messages` - Conversation list
- `/messages/{conversation}` - Chat thread
- `/video-calls/history` - Call history
- `/profile` - User profile
- `/settings` - User settings

### API Endpoints (for AJAX)
- `POST /messages/{conversation}/voice` - Upload voice message
- `POST /video-calls/initiate/{user}` - Initiate call
- `GET /video-calls/stats` - Get call statistics
- `GET /messages/{conversation}/icebreakers` - Get icebreaker suggestions
- `POST /verification/send-otp` - Send OTP for phone verification
- `POST /verification/verify-otp` - Verify OTP code
- `POST /verification/resend-otp` - Resend OTP

### Verification Routes
- `/verification/phone` - Phone verification page
- `/verification/skip` - Skip phone verification (local env only)
- `/verification/video` - Video selfie verification page
- `/verification/video/skip` - Skip video verification (local env only)

---

## Browser Requirements

### Voice Messages
- **Chrome/Edge:** Full support
- **Firefox:** Full support
- **Safari:** Requires HTTPS or localhost
- **Required:** Microphone permission

### Video Verification
- **Chrome/Edge:** Full support
- **Firefox:** Full support
- **Safari:** Requires HTTPS or localhost
- **Required:** Camera permission

### Features Used
- MediaRecorder API (voice recording, video recording)
- Web Audio API (waveform visualization)
- FormData API (file uploads)
- HTML5 Audio (playback)
- getUserMedia API (camera access)

---

## File Upload Paths

Voice messages are stored in:
```
/storage/app/public/voice-messages/YYYY/MM/
```

Video verifications are stored in:
```
/storage/app/private/verifications/videos/
```

To access uploaded files, ensure storage is linked:
```bash
php artisan storage:link
```

---

## Testing Checklist

- [ ] Login with test accounts
- [ ] **Phone Verification** - Request OTP and verify
- [ ] **Video Verification** - Record video selfie and verify
- [ ] Send text messages
- [ ] Record and send voice message
- [ ] Play received voice message
- [ ] Initiate video call
- [ ] Initiate audio call
- [ ] View call history
- [ ] Check call statistics
- [ ] Use icebreaker questions
- [ ] Swipe on discover page
- [ ] Create new matches
- [ ] View profile completion
- [ ] Test read receipts
- [ ] Check verification badges on profile

---

## Notes

1. **Phone Verification:**
   - SMS provider set to 'log' mode for development
   - OTP codes are logged to `storage/logs/laravel.log`
   - Check logs or console output for OTP codes during testing
   - In production: Configure Twilio, MSG91, or AWS SNS in `.env`
   - Rate limits: 3 OTP requests per hour, 5 verify attempts per 10 minutes
   - Skip button available in local environment only

2. **Video Verification (NEW!):**
   - Face verification set to 'log' mode for development
   - Records 5-10 second video selfie
   - Liveness detection simulated in dev mode (always passes with score 0.92)
   - Face matching simulated in dev mode (always passes with score 0.87)
   - In production: Configure AWS Rekognition or DeepFace API
   - Thresholds: Liveness > 0.80, Face Match > 0.75 for auto-pass
   - Borderline cases (0.65-0.75) go to manual review
   - Verification badges appear on user profiles
   - Skip button available in local environment only
   - Requires camera permission

3. **Voice Messages:** First time recording will request microphone permission
4. **File Size Limits:** Voice messages max 5MB (60 seconds), Video verifications max 10MB (10 seconds)
5. **Call System:** Currently records metadata only (WebRTC integration pending)
6. **Real-time Updates:** Messages poll every 3 seconds
7. **Premium Features:** Admin account has all premium features enabled

---

## Troubleshooting

### Can't see OTP code?
```bash
# Check Laravel logs
tail -f storage/logs/laravel.log

# Or use tinker to see recent OTP
php artisan tinker
>>> \App\Models\PhoneVerification::latest()->first()->otp
```

### Phone verification errors?
- Check that phone number is 10 digits
- OTP expires after 10 minutes
- Max 3 attempts per OTP
- Max 3 OTP requests per hour
- Use skip button in development: `/verification/skip`

### Video verification not working?
- Grant camera permission when prompted
- Ensure HTTPS or localhost
- Check browser console for errors
- Video must be 3-10 seconds long
- Max file size: 10MB
- Supported formats: MP4, WebM, MOV
- Use skip button in development: `/verification/video/skip`
- Check that you have a profile photo for face matching

### Voice recording not working?
- Check browser console for errors
- Ensure HTTPS or localhost
- Grant microphone permission
- Try Chrome/Firefox for best compatibility

### Files not displaying?
```bash
php artisan storage:link
```

### Database connection issues?
```bash
export DB_CONNECTION=mysql
php artisan migrate:fresh --seed
```

---

**Generated:** 2025-11-30
**Laravel Version:** 12.x
**Environment:** Development
