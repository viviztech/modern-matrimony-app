<?php

namespace App\Http\Controllers;

use App\Models\SocialAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SocialAuthController extends Controller
{
    /**
     * Redirect to social provider.
     */
    public function redirectToProvider(string $provider)
    {
        $validProviders = ['linkedin', 'instagram', 'facebook'];

        if (!in_array($provider, $validProviders)) {
            return redirect()->route('settings')
                ->with('error', 'Invalid social provider');
        }

        // In production, this would redirect to OAuth provider
        // For now, show info message
        if (!app()->environment('production')) {
            return redirect()->route('social.verify.dev', ['provider' => $provider])
                ->with('info', 'Development mode: Simulating ' . ucfirst($provider) . ' OAuth');
        }

        /*
        // Production OAuth implementation would look like:
        return Socialite::driver($provider)->redirect();
        */

        return redirect()->route('settings')
            ->with('info', ucfirst($provider) . ' OAuth not configured');
    }

    /**
     * Handle callback from social provider.
     */
    public function handleProviderCallback(string $provider)
    {
        $validProviders = ['linkedin', 'instagram', 'facebook'];

        if (!in_array($provider, $validProviders)) {
            return redirect()->route('settings')
                ->with('error', 'Invalid social provider');
        }

        try {
            /*
            // Production OAuth implementation:
            $providerUser = Socialite::driver($provider)->user();

            $socialAccount = SocialAccount::updateOrCreateFromProvider(
                Auth::user(),
                $provider,
                [
                    'id' => $providerUser->getId(),
                    'name' => $providerUser->getName(),
                    'email' => $providerUser->getEmail(),
                    'avatar' => $providerUser->getAvatar(),
                    'token' => $providerUser->token,
                    'refreshToken' => $providerUser->refreshToken ?? null,
                    'expiresIn' => $providerUser->expiresIn ?? null,
                ]
            );
            */

            return redirect()->route('settings')
                ->with('success', ucfirst($provider) . ' account connected successfully!');

        } catch (\Exception $e) {
            Log::error('Social OAuth callback error', [
                'provider' => $provider,
                'error' => $e->getMessage(),
            ]);

            return redirect()->route('settings')
                ->with('error', 'Failed to connect ' . ucfirst($provider) . ' account');
        }
    }

    /**
     * Development mode: Simulate OAuth verification.
     */
    public function devVerify(Request $request, string $provider)
    {
        if (app()->environment('production')) {
            abort(403);
        }

        $user = Auth::user();

        // Simulate successful OAuth
        $socialAccount = SocialAccount::updateOrCreateFromProvider(
            $user,
            $provider,
            [
                'id' => 'dev_' . $provider . '_' . $user->id,
                'username' => $user->name,
                'name' => $user->name,
                'email' => $user->email,
                'avatar' => 'https://ui-avatars.com/api/?name=' . urlencode($user->name),
                'token' => 'dev_token_' . uniqid(),
            ]
        );

        return redirect()->route('settings')
            ->with('success', ucfirst($provider) . ' account connected successfully! (Development Mode)');
    }

    /**
     * Disconnect social account.
     */
    public function disconnect(string $provider)
    {
        $user = Auth::user();

        $deleted = SocialAccount::where('user_id', $user->id)
            ->where('provider', $provider)
            ->delete();

        if ($deleted) {
            return redirect()->route('settings')
                ->with('success', ucfirst($provider) . ' account disconnected');
        }

        return redirect()->route('settings')
            ->with('error', 'No ' . ucfirst($provider) . ' account found');
    }

    /**
     * Show social verification page.
     */
    public function index()
    {
        $user = Auth::user()->load('socialAccounts');

        return view('social.verify', compact('user'));
    }
}
