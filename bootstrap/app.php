<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Web middleware group - user activity tracking
        $middleware->web(append: [
            \App\Http\Middleware\TrackUserActivity::class,
            \App\Http\Middleware\UpdateUserOnlineStatus::class,
            \App\Http\Middleware\SecurityHeaders::class,
        ]);

        // API middleware group - feature-based rate limiting
        $middleware->api(append: [
            \App\Http\Middleware\ThrottleWithFeatureGate::class,
        ]);
    })
    ->withSchedule(function (Schedule $schedule): void {
        // Delete expired stories every hour
        $schedule->command('stories:delete-expired')->hourly();

        // Calculate daily metrics at 1 AM
        $schedule->command('analytics:calculate-daily-metrics')
                 ->dailyAt('01:00')
                 ->withoutOverlapping();

        // Process account deletions (30-day grace period)
        $schedule->command('users:process-deletions')
                 ->daily()
                 ->withoutOverlapping();

        // Clean old sessions
        $schedule->command('session:gc')
                 ->daily()
                 ->withoutOverlapping();
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
