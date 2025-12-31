# Laravel Reverb WebSocket Real-time Features - Setup Guide

This guide covers the complete setup and usage of Laravel Reverb WebSocket real-time features for the matrimony application.

## Table of Contents

1. [Features Implemented](#features-implemented)
2. [Environment Configuration](#environment-configuration)
3. [Installation Steps](#installation-steps)
4. [Running Reverb Server](#running-reverb-server)
5. [Real-time Events](#real-time-events)
6. [Broadcasting Channels](#broadcasting-channels)
7. [Frontend Integration](#frontend-integration)
8. [Testing](#testing)
9. [Production Deployment](#production-deployment)
10. [Troubleshooting](#troubleshooting)

## Features Implemented

### Core Real-time Features
- **Instant Message Delivery**: Messages appear instantly without page refresh
- **Typing Indicators**: See when the other user is typing
- **Read Receipts**: Know when messages have been read
- **Online/Offline Status**: Real-time presence tracking
- **Match Notifications**: Instant notifications for new matches
- **Video Call Alerts**: Real-time incoming call notifications
- **Browser Notifications**: Desktop notifications for new messages
- **Sound Notifications**: Optional audio alerts (user toggleable)

## Environment Configuration

### 1. Add to `.env` file:

```env
# Broadcasting Configuration
BROADCAST_CONNECTION=reverb

# Reverb WebSocket Server Configuration
REVERB_APP_ID=your-app-id
REVERB_APP_KEY=your-app-key
REVERB_APP_SECRET=your-app-secret
REVERB_HOST=127.0.0.1
REVERB_PORT=8080
REVERB_SCHEME=http

# For production (with SSL)
# REVERB_SCHEME=https
# REVERB_PORT=443
```

### 2. Add to `.env` (Frontend - Vite):

```env
# These are used by the JavaScript Echo configuration
VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
VITE_REVERB_HOST="${REVERB_HOST}"
VITE_REVERB_PORT="${REVERB_PORT}"
VITE_REVERB_SCHEME="${REVERB_SCHEME}"
```

### 3. Generate Reverb credentials:

```bash
php artisan reverb:install
```

This will automatically add the required environment variables to your `.env` file.

## Installation Steps

### Step 1: Install Dependencies

The required packages are already in `package.json`:
- `laravel-echo`: ^2.2.7
- `pusher-js`: ^8.4.0

If not installed, run:

```bash
npm install
```

### Step 2: Run Database Migration

Add online status tracking to users table:

```bash
php artisan migrate
```

This adds:
- `online_at` - Timestamp of last online activity
- `is_online` - Boolean flag for quick status checks

### Step 3: Register Middleware

Add the `UpdateUserOnlineStatus` middleware to your `app/Http/Kernel.php`:

```php
protected $middlewareGroups = [
    'web' => [
        // ... other middleware
        \App\Http\Middleware\UpdateUserOnlineStatus::class,
    ],
];
```

Or in Laravel 11+ using `bootstrap/app.php`:

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->web(append: [
        \App\Http\Middleware\UpdateUserOnlineStatus::class,
    ]);
})
```

### Step 4: Build Frontend Assets

Compile JavaScript with Echo integration:

```bash
npm run build
# or for development
npm run dev
```

## Running Reverb Server

### Development

Start the Reverb WebSocket server:

```bash
php artisan reverb:start
```

Or run it in debug mode:

```bash
php artisan reverb:start --debug
```

### Using with Laravel Sail

If using Laravel Sail:

```bash
sail artisan reverb:start
```

### Production

For production, use a process manager like Supervisor:

```ini
[program:reverb]
command=php /path/to/your/app/artisan reverb:start
directory=/path/to/your/app
autostart=true
autorestart=true
user=www-data
redirect_stderr=true
stdout_logfile=/path/to/your/app/storage/logs/reverb.log
```

## Real-time Events

### Event Classes Created

All events are in `app/Events/`:

1. **MessageSent** - Broadcasts when a message is sent
   - Channels: `conversation.{id}`, `user.{receiverId}`
   - Payload: Full message with sender details

2. **MessageRead** - Broadcasts when messages are marked as read
   - Channel: `conversation.{id}`
   - Payload: Message IDs and reader info

3. **UserOnline** - Broadcasts when user comes online
   - Channel: `online` (presence)
   - Payload: User ID, name, avatar

4. **UserOffline** - Broadcasts when user goes offline
   - Channel: `online` (presence)
   - Payload: User ID

5. **TypingStarted** - Broadcasts when user starts typing
   - Channel: `conversation.{id}`
   - Payload: User ID and name
   - Note: Uses `ShouldBroadcastNow` for instant delivery

6. **TypingStopped** - Broadcasts when user stops typing
   - Channel: `conversation.{id}`
   - Payload: User ID
   - Note: Uses `ShouldBroadcastNow` for instant delivery

7. **MatchCreated** - Broadcasts when a new match occurs
   - Channels: `user.{userId}` for both matched users
   - Payload: Match details and matched user profile

8. **VideoCallIncoming** - Broadcasts when receiving a video call
   - Channel: `user.{receiverId}`
   - Payload: Call details and caller info
   - Note: Uses `ShouldBroadcastNow` for instant delivery

### Broadcasting Events

Events are automatically broadcast when:
- A message is sent (in `MessageController@store`)
- A message is read (in `MessageController@markAsRead`)
- User types (in `MessageController@typingStarted/typingStopped`)
- A voice message is uploaded (in `MessageController@uploadVoice`)

To manually broadcast from anywhere in your app:

```php
use App\Events\MessageSent;

// Broadcast to others (not the current user)
broadcast(new MessageSent($message))->toOthers();

// Broadcast to everyone
broadcast(new MessageSent($message));
```

## Broadcasting Channels

### Channel Authorization

Defined in `routes/channels.php`:

1. **Private User Channel**: `user.{id}`
   - User-specific notifications
   - Authorization: User must own the channel

2. **Private Conversation Channel**: `conversation.{conversationId}`
   - Messages between two users
   - Authorization: User must be part of the conversation

3. **Presence Channel**: `online`
   - Online users tracking
   - Returns: User ID, name, avatar

4. **Presence Conversation Channel**: `conversation.{conversationId}.presence`
   - Who's viewing the conversation
   - Authorization: User must be part of the conversation

## Frontend Integration

### JavaScript Components

1. **Echo Client** (`resources/js/echo.js`)
   - Configures Laravel Echo with Reverb
   - Handles connection events
   - Manages reconnection

2. **Messaging Client** (`resources/js/messaging.js`)
   - Main real-time messaging class
   - Subscribes to channels
   - Handles all real-time events
   - Manages typing indicators
   - Shows notifications

### Using the Messaging Client

The client is automatically initialized in message views:

```javascript
// Automatically created in messages/show.blade.php
const messagingClient = createMessagingClient(
    conversationId,
    currentUserId,
    otherUserId
);
```

### Custom Event Listeners

Listen to real-time events anywhere in your app:

```javascript
// New message received
window.addEventListener('message:received', (event) => {
    console.log('New message:', event.detail.message);
});

// User online status changed
window.addEventListener('user:status-changed', (event) => {
    console.log('User status:', event.detail);
});

// Typing indicator
window.addEventListener('typing:started', (event) => {
    console.log('User is typing:', event.detail);
});

// New match
window.addEventListener('match:created', (event) => {
    console.log('New match!', event.detail);
});

// Incoming call
window.addEventListener('call:incoming', (event) => {
    console.log('Incoming call:', event.detail);
});
```

### Notification Permissions

Request browser notification permission:

```javascript
requestNotificationPermission();
```

### Toggle Sound Notifications

```javascript
messagingClient.toggleSound(); // Returns new state (true/false)
```

## Testing

### Test WebSocket Connection

1. Open browser console
2. Navigate to a message conversation
3. You should see: "WebSocket connected successfully"
4. Check connection state:

```javascript
console.log(window.Echo.connector.pusher.connection.state);
// Should output: "connected"
```

### Test Real-time Features

1. **Test Messaging**:
   - Open two browser windows with different users
   - Send a message from one window
   - Message should appear instantly in the other window

2. **Test Typing Indicator**:
   - Start typing in one window
   - The other window should show "typing..." indicator

3. **Test Online Status**:
   - Have one user online
   - The other user should see green "online" badge
   - Close/logout one user
   - Badge should change to "offline"

4. **Test Read Receipts**:
   - Send messages from one user
   - Open conversation in the other user's window
   - Sender should see double checkmarks turn green

### Debug Mode

Enable Echo logging in development:

```javascript
// In resources/js/echo.js
enableLogging: true,
logToConsole: true,
```

## Production Deployment

### 1. SSL/TLS Configuration

For production with SSL:

```env
REVERB_SCHEME=https
REVERB_PORT=443
REVERB_HOST=your-domain.com
```

### 2. Reverse Proxy (Nginx)

Add WebSocket support to your Nginx configuration:

```nginx
location /app {
    proxy_pass http://127.0.0.1:8080;
    proxy_http_version 1.1;
    proxy_set_header Upgrade $http_upgrade;
    proxy_set_header Connection "upgrade";
    proxy_set_header Host $host;
    proxy_cache_bypass $http_upgrade;
}
```

### 3. Process Manager

Use Supervisor to keep Reverb running:

```bash
sudo nano /etc/supervisor/conf.d/reverb.conf
```

Add:

```ini
[program:reverb]
command=php /var/www/matrimony/artisan reverb:start
directory=/var/www/matrimony
autostart=true
autorestart=true
user=www-data
redirect_stderr=true
stdout_logfile=/var/www/matrimony/storage/logs/reverb.log
```

Start:

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start reverb
```

### 4. Firewall Rules

Open WebSocket port:

```bash
sudo ufw allow 8080/tcp
# Or for SSL
sudo ufw allow 443/tcp
```

### 5. Environment Variables

Ensure all Reverb environment variables are set on production server.

## Troubleshooting

### Connection Failed

**Problem**: "WebSocket connection failed"

**Solutions**:
1. Check if Reverb server is running: `php artisan reverb:start`
2. Verify `.env` configuration matches
3. Check firewall allows WebSocket port
4. Verify CORS settings if using different domains

### Messages Not Appearing

**Problem**: Messages sent but not received in real-time

**Solutions**:
1. Check browser console for errors
2. Verify user is subscribed to correct channel
3. Check channel authorization in `routes/channels.php`
4. Ensure event is broadcasting: Add `ShouldBroadcast` interface

### Typing Indicator Not Working

**Problem**: Typing indicator doesn't show

**Solutions**:
1. Verify typing routes exist in `routes/web.php`
2. Check CSRF token is being sent
3. Ensure `TypingStarted`/`TypingStopped` events use `ShouldBroadcastNow`

### Online Status Not Updating

**Problem**: User shows offline when online

**Solutions**:
1. Verify `UpdateUserOnlineStatus` middleware is registered
2. Check cache is working: `php artisan cache:clear`
3. Ensure presence channel authorization works
4. Verify `isOnline()` method checks cache correctly

### Production Issues

**Problem**: Works in development but not production

**Solutions**:
1. Check SSL/TLS configuration
2. Verify environment variables on production
3. Check Nginx proxy configuration
4. Ensure Supervisor is running Reverb
5. Check production logs: `storage/logs/laravel.log`

## API Routes

Typing indicator endpoints:
- POST `/messages/{conversation}/typing/start` - Start typing
- POST `/messages/{conversation}/typing/stop` - Stop typing

## Performance Considerations

1. **Connection Pooling**: Reverb handles thousands of concurrent connections
2. **Message Queue**: Events can be queued for better performance
3. **Cache**: Online status uses cache to reduce database queries
4. **Throttling**: Typing events are throttled to prevent spam

## Security

1. **Channel Authorization**: All private channels require authentication
2. **CSRF Protection**: All POST requests include CSRF tokens
3. **User Verification**: Middleware verifies user identity
4. **Rate Limiting**: Consider adding rate limits to typing endpoints

## Additional Resources

- [Laravel Broadcasting Documentation](https://laravel.com/docs/broadcasting)
- [Laravel Reverb Documentation](https://laravel.com/docs/reverb)
- [Laravel Echo Documentation](https://github.com/laravel/echo)
- [Pusher JavaScript Client](https://pusher.com/docs/channels/using_channels/client-api/)

## Support

For issues or questions:
1. Check Laravel logs: `storage/logs/laravel.log`
2. Check Reverb logs: `storage/logs/reverb.log`
3. Enable debug mode for detailed error messages
4. Check browser console for JavaScript errors
