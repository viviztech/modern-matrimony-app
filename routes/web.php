<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\DiscoverController;
use App\Http\Controllers\MatchController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\VideoCallController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\VideoVerificationController;
use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\StoryController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\Admin\ModerationController;
use App\Http\Middleware\EnsureUserIsAdmin;
use Illuminate\Support\Facades\Route;

// Homepage
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Onboarding routes (auth required, but not verified)
Route::middleware(['auth'])->group(function () {
    Route::get('/onboarding/step1', [OnboardingController::class, 'step1'])->name('onboarding.step1');
    Route::post('/onboarding/step1', [OnboardingController::class, 'storeStep1'])->name('onboarding.step1.store');

    Route::get('/onboarding/step2', [OnboardingController::class, 'step2'])->name('onboarding.step2');
    Route::post('/onboarding/step2', [OnboardingController::class, 'storeStep2'])->name('onboarding.step2.store');

    Route::get('/onboarding/step3', [OnboardingController::class, 'step3'])->name('onboarding.step3');
    Route::post('/onboarding/step3', [OnboardingController::class, 'storeStep3'])->name('onboarding.step3.store');

    Route::get('/onboarding/step4', [OnboardingController::class, 'step4'])->name('onboarding.step4');
    Route::post('/onboarding/step4', [OnboardingController::class, 'storeStep4'])->name('onboarding.step4.store');

    Route::get('/onboarding/complete', [OnboardingController::class, 'complete'])->name('onboarding.complete');

    // Verification routes (auth required, email verification not required)
    Route::get('/verification/phone', [VerificationController::class, 'showPhoneVerification'])->name('verification.phone');
    Route::post('/verification/send-otp', [VerificationController::class, 'sendOTP'])->name('verification.send-otp');
    Route::post('/verification/verify-otp', [VerificationController::class, 'verifyOTP'])->name('verification.verify-otp');
    Route::post('/verification/resend-otp', [VerificationController::class, 'resendOTP'])->name('verification.resend-otp');
    Route::get('/verification/skip', [VerificationController::class, 'skipVerification'])->name('verification.skip');

    // Video verification routes
    Route::get('/verification/video', [VideoVerificationController::class, 'showVideoVerification'])->name('verification.video');
    Route::post('/verification/video/upload', [VideoVerificationController::class, 'uploadVideo'])->name('verification.video.upload');
    Route::get('/verification/video/status', [VideoVerificationController::class, 'checkStatus'])->name('verification.video.status');
    Route::get('/verification/video/retry', [VideoVerificationController::class, 'retry'])->name('verification.video.retry');
    Route::get('/verification/video/skip', [VideoVerificationController::class, 'skipVerification'])->name('verification.video.skip');

    // Social verification routes
    Route::get('/social/{provider}', [SocialAuthController::class, 'redirectToProvider'])->name('social.auth');
    Route::get('/social/{provider}/callback', [SocialAuthController::class, 'handleProviderCallback'])->name('social.callback');
    Route::get('/social/{provider}/verify-dev', [SocialAuthController::class, 'devVerify'])->name('social.verify.dev');
    Route::delete('/social/{provider}/disconnect', [SocialAuthController::class, 'disconnect'])->name('social.disconnect');
});

// Protected routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard (redirect to discover for now)
    Route::get('/dashboard', function () {
        return redirect()->route('discover');
    })->name('dashboard');

    // Discover routes
    Route::get('/discover', [DiscoverController::class, 'index'])->name('discover');
    Route::post('/discover/like/{targetUser}', [DiscoverController::class, 'like'])->name('discover.like');
    Route::post('/discover/pass/{targetUser}', [DiscoverController::class, 'pass'])->name('discover.pass');
    Route::get('/discover/next-batch', [DiscoverController::class, 'nextBatch'])->name('discover.next');

    // Matches routes
    Route::get('/matches', [MatchController::class, 'index'])->name('matches');
    Route::delete('/matches/{matchId}/unmatch', [MatchController::class, 'unmatch'])->name('matches.unmatch');

    // Messages routes
    Route::get('/messages', [MessageController::class, 'index'])->name('messages');
    Route::get('/messages/create/{user}', [MessageController::class, 'create'])->name('messages.create');
    Route::get('/messages/{conversation}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{conversation}', [MessageController::class, 'store'])->name('messages.store');
    Route::post('/messages/{conversation}/voice', [MessageController::class, 'uploadVoice'])->name('messages.voice');
    Route::get('/messages/{conversation}/messages', [MessageController::class, 'getMessages'])->name('messages.get');
    Route::get('/messages/{conversation}/icebreakers', [MessageController::class, 'getIcebreakers'])->name('messages.icebreakers');
    Route::post('/messages/{conversation}/read', [MessageController::class, 'markAsRead'])->name('messages.read');
    Route::delete('/messages/message/{message}', [MessageController::class, 'destroy'])->name('messages.destroy');
    Route::get('/messages/{conversation}/search', [MessageController::class, 'search'])->name('messages.search');

    // Video call routes
    Route::post('/video-calls/initiate/{receiver}', [VideoCallController::class, 'initiate'])->name('video-calls.initiate');
    Route::post('/video-calls/{videoCall}/ring', [VideoCallController::class, 'ring'])->name('video-calls.ring');
    Route::post('/video-calls/{videoCall}/accept', [VideoCallController::class, 'accept'])->name('video-calls.accept');
    Route::post('/video-calls/{videoCall}/decline', [VideoCallController::class, 'decline'])->name('video-calls.decline');
    Route::post('/video-calls/{videoCall}/end', [VideoCallController::class, 'end'])->name('video-calls.end');
    Route::post('/video-calls/{videoCall}/missed', [VideoCallController::class, 'missed'])->name('video-calls.missed');
    Route::get('/video-calls/{videoCall}/status', [VideoCallController::class, 'status'])->name('video-calls.status');
    Route::post('/video-calls/{videoCall}/rate', [VideoCallController::class, 'rate'])->name('video-calls.rate');
    Route::post('/video-calls/{videoCall}/report', [VideoCallController::class, 'report'])->name('video-calls.report');
    Route::get('/video-calls/history', [VideoCallController::class, 'history'])->name('video-calls.history');
    Route::get('/video-calls/stats', [VideoCallController::class, 'stats'])->name('video-calls.stats');
    Route::get('/video-calls/can-call/{receiver}', [VideoCallController::class, 'canCall'])->name('video-calls.can-call');

    Route::get('/profile', function () {
        return view('profile.show');
    })->name('profile.show');

    // Breeze profile routes
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/settings', function () {
        return view('settings.index');
    })->name('settings');

    // Notification routes
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unread-count');

    // Story routes
    Route::get('/stories', [StoryController::class, 'index'])->name('stories.index');
    Route::get('/stories/create', [StoryController::class, 'create'])->name('stories.create');
    Route::post('/stories', [StoryController::class, 'store'])->name('stories.store');
    Route::get('/stories/{story}', [StoryController::class, 'show'])->name('stories.show');
    Route::delete('/stories/{story}', [StoryController::class, 'destroy'])->name('stories.destroy');
    Route::get('/stories/{story}/viewers', [StoryController::class, 'viewers'])->name('stories.viewers');
    Route::post('/stories/{story}/like', [StoryController::class, 'like'])->name('stories.like');

    // Game routes
    Route::get('/games', [GameController::class, 'index'])->name('games.index');
    Route::get('/games/create/{partner}', [GameController::class, 'create'])->name('games.create');
    Route::post('/games/create/{partner}', [GameController::class, 'store'])->name('games.store');
    Route::get('/games/{game}/play', [GameController::class, 'play'])->name('games.play');
    Route::post('/games/{game}/submit', [GameController::class, 'submitAnswers'])->name('games.submit');
    Route::get('/games/{game}/results', [GameController::class, 'results'])->name('games.results');
});

// Admin routes
Route::middleware(['auth', EnsureUserIsAdmin::class])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/moderation', [ModerationController::class, 'index'])->name('moderation.index');

    // Photo verifications
    Route::get('/moderation/photos', [ModerationController::class, 'photos'])->name('moderation.photos');
    Route::post('/moderation/photos/{verification}/approve', [ModerationController::class, 'approvePhoto'])->name('moderation.photos.approve');
    Route::post('/moderation/photos/{verification}/reject', [ModerationController::class, 'rejectPhoto'])->name('moderation.photos.reject');

    // Reports
    Route::get('/moderation/reports', [ModerationController::class, 'reports'])->name('moderation.reports');
    Route::get('/moderation/reports/{report}', [ModerationController::class, 'showReport'])->name('moderation.reports.show');
    Route::post('/moderation/reports/{report}/resolve', [ModerationController::class, 'resolveReport'])->name('moderation.reports.resolve');
    Route::post('/moderation/reports/{report}/dismiss', [ModerationController::class, 'dismissReport'])->name('moderation.reports.dismiss');
    Route::post('/moderation/reports/bulk-action', [ModerationController::class, 'bulkAction'])->name('moderation.reports.bulk');

    // User management
    Route::get('/moderation/users', [ModerationController::class, 'users'])->name('moderation.users');
    Route::get('/moderation/users/{user}', [ModerationController::class, 'showUser'])->name('moderation.users.show');
    Route::post('/moderation/users/{user}/ban', [ModerationController::class, 'banUser'])->name('moderation.users.ban');
    Route::post('/moderation/users/{user}/unban', [ModerationController::class, 'unbanUser'])->name('moderation.users.unban');
    Route::post('/moderation/users/{user}/suspend', [ModerationController::class, 'suspendUser'])->name('moderation.users.suspend');

    // Activity logs
    Route::get('/moderation/activity-logs', [ModerationController::class, 'activityLogs'])->name('moderation.logs');
});

require __DIR__.'/auth.php';
