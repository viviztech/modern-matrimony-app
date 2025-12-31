# Quick Start Guide - Laravel Reverb WebSocket

Get your real-time features up and running in 5 minutes!

## Step 1: Install Reverb (1 minute)

```bash
php artisan reverb:install
```

This will:
- Add Reverb to your dependencies
- Generate credentials in `.env`
- Create reverb configuration

## Step 2: Configure Frontend Environment (30 seconds)

Add to your `.env` file:

```env
VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
VITE_REVERB_HOST="${REVERB_HOST}"
VITE_REVERB_PORT="${REVERB_PORT}"
VITE_REVERB_SCHEME="${REVERB_SCHEME}"
```

## Step 3: Register Middleware (1 minute)

Edit `bootstrap/app.php` and add:

```php
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    // ... existing configuration
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            \App\Http\Middleware\UpdateUserOnlineStatus::class,
        ]);
    })
    // ... rest of configuration
```

## Step 4: Run Migration (30 seconds)

```bash
php artisan migrate
```

## Step 5: Build Frontend Assets (1 minute)

```bash
npm install
npm run build
```

Or for development with hot reload:

```bash
npm run dev
```

## Step 6: Start Reverb Server (30 seconds)

Open a new terminal window and run:

```bash
php artisan reverb:start
```

You should see:
```
INFO  Reverb server started.
      Press Ctrl+C to stop.
```

## Step 7: Test It! (1 minute)

1. Open your app in two different browser windows (or incognito)
2. Login as different users in each window
3. Navigate to a message conversation
4. Send a message from one window
5. Watch it appear INSTANTLY in the other window!

## That's It!

You now have:
- ✅ Real-time messaging
- ✅ Typing indicators
- ✅ Online/offline status
- ✅ Read receipts
- ✅ Browser notifications
- ✅ Match alerts
- ✅ Video call notifications

## Common Issues

### "Connection Failed"
Make sure Reverb server is running:
```bash
php artisan reverb:start
```

### "Assets not loading"
Rebuild your assets:
```bash
npm run build
```

### "Middleware not working"
Clear config cache:
```bash
php artisan config:clear
php artisan cache:clear
```

### "Environment variables not found"
Restart your dev server after adding VITE_* variables:
```bash
# Stop npm run dev and restart it
npm run dev
```

## Development Tips

### Run Everything Together

Use Laravel Concurrently (if you want):

```bash
npm install -D concurrently

# Update package.json scripts:
"scripts": {
    "dev": "concurrently \"vite\" \"php artisan reverb:start\""
}

# Now just run:
npm run dev
```

### Debug Mode

Run Reverb with debug output:
```bash
php artisan reverb:start --debug
```

### Check Connection

In browser console:
```javascript
console.log(window.Echo.connector.pusher.connection.state);
// Should output: "connected"
```

## Production Deployment

See `REVERB_SETUP.md` for detailed production setup including:
- SSL/TLS configuration
- Nginx proxy setup
- Supervisor process management
- Firewall configuration

## Next Steps

- Read `IMPLEMENTATION_SUMMARY.md` for complete feature list
- Check `REVERB_SETUP.md` for detailed documentation
- Customize notification sounds in `/public/sounds/`
- Add rate limiting to typing endpoints
- Set up monitoring for production

## Need Help?

1. Check browser console for errors
2. Check Laravel logs: `storage/logs/laravel.log`
3. Check Reverb logs: `storage/logs/reverb.log`
4. Enable debug mode: `php artisan reverb:start --debug`

---

**Congratulations!** Your matrimony app now has professional real-time features! 🎉
