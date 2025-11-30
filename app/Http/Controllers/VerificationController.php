<?php

namespace App\Http\Controllers;

use App\Models\PhoneVerification;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

class VerificationController extends Controller
{
    protected SmsService $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    /**
     * Show phone verification page.
     */
    public function showPhoneVerification()
    {
        $user = Auth::user();

        // If already verified, redirect
        if ($user->hasVerifiedPhone()) {
            return redirect()->route('dashboard')->with('info', 'Phone already verified');
        }

        return view('verification.phone');
    }

    /**
     * Send OTP to phone number.
     */
    public function sendOTP(Request $request)
    {
        $user = Auth::user();

        // Rate limiting - max 3 OTP requests per hour per user
        $key = 'send-otp:' . $user->id;
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            return response()->json([
                'success' => false,
                'message' => "Too many OTP requests. Please try again in " . ceil($seconds / 60) . " minutes.",
            ], 429);
        }

        $request->validate([
            'phone' => 'required|string|min:10|max:15',
        ]);

        $phone = $request->input('phone');

        // Validate phone format
        if (!SmsService::validatePhone($phone)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid phone number format',
            ], 422);
        }

        // Format phone number
        $formattedPhone = SmsService::formatPhone($phone);

        // Check if phone is already verified by another user
        $existingUser = \App\Models\User::where('phone', $formattedPhone)
            ->whereNotNull('phone_verified_at')
            ->where('id', '!=', $user->id)
            ->first();

        if ($existingUser) {
            return response()->json([
                'success' => false,
                'message' => 'This phone number is already registered',
            ], 422);
        }

        try {
            // Create verification record
            $verification = PhoneVerification::createForUser(
                $user,
                $formattedPhone,
                $request->ip()
            );

            // Send OTP via SMS
            $sent = $this->smsService->sendOTP($formattedPhone, $verification->otp);

            if (!$sent) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to send OTP. Please try again.',
                ], 500);
            }

            // Increment rate limiter
            RateLimiter::hit($key, 3600); // 1 hour

            return response()->json([
                'success' => true,
                'message' => 'OTP sent successfully',
                'expires_at' => $verification->expires_at->toIso8601String(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred. Please try again.',
            ], 500);
        }
    }

    /**
     * Verify OTP.
     */
    public function verifyOTP(Request $request)
    {
        $user = Auth::user();

        // Rate limiting - max 5 verify attempts per 10 minutes
        $key = 'verify-otp:' . $user->id;
        if (RateLimiter::tooManyAttempts($key, 5)) {
            return response()->json([
                'success' => false,
                'message' => 'Too many verification attempts. Please request a new OTP.',
            ], 429);
        }

        $request->validate([
            'phone' => 'required|string',
            'otp' => 'required|string|size:6',
        ]);

        $phone = SmsService::formatPhone($request->input('phone'));
        $otp = $request->input('otp');

        // Find active verification
        $verification = PhoneVerification::findActiveVerification($user, $phone);

        if (!$verification) {
            return response()->json([
                'success' => false,
                'message' => 'No active verification found. Please request a new OTP.',
            ], 404);
        }

        // Verify OTP
        $verified = $verification->verifyOTP($otp);

        if ($verified) {
            // Clear rate limiters
            RateLimiter::clear($key);
            RateLimiter::clear('send-otp:' . $user->id);

            return response()->json([
                'success' => true,
                'message' => 'Phone verified successfully!',
            ]);
        }

        // Increment rate limiter
        RateLimiter::hit($key, 600); // 10 minutes

        // Check why verification failed
        if ($verification->isExpired()) {
            return response()->json([
                'success' => false,
                'message' => 'OTP has expired. Please request a new one.',
            ], 422);
        }

        if ($verification->maxAttemptsReached()) {
            return response()->json([
                'success' => false,
                'message' => 'Maximum attempts reached. Please request a new OTP.',
            ], 422);
        }

        $remainingAttempts = 3 - $verification->attempts;
        return response()->json([
            'success' => false,
            'message' => "Invalid OTP. {$remainingAttempts} attempts remaining.",
            'remaining_attempts' => $remainingAttempts,
        ], 422);
    }

    /**
     * Resend OTP (same as sendOTP but with different rate limit).
     */
    public function resendOTP(Request $request)
    {
        $user = Auth::user();

        // Rate limiting - max 2 resend requests per 5 minutes
        $key = 'resend-otp:' . $user->id;
        if (RateLimiter::tooManyAttempts($key, 2)) {
            $seconds = RateLimiter::availableIn($key);
            return response()->json([
                'success' => false,
                'message' => "Please wait " . ceil($seconds / 60) . " minutes before resending OTP.",
            ], 429);
        }

        // Use the same sendOTP logic
        $response = $this->sendOTP($request);

        if ($response->getStatusCode() === 200) {
            RateLimiter::hit($key, 300); // 5 minutes
        }

        return $response;
    }

    /**
     * Skip verification (temporary - for testing).
     */
    public function skipVerification()
    {
        if (!app()->environment('local')) {
            abort(403, 'This action is only available in local environment');
        }

        $user = Auth::user();
        $user->update([
            'phone_verified_at' => now(),
        ]);

        return redirect()->route('dashboard')->with('success', 'Phone verification skipped (development only)');
    }
}
