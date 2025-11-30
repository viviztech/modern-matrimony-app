<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Profile;
use App\Models\Preference;

class OnboardingController extends Controller
{
    /**
     * Show onboarding step 1: Basic Info (DOB, Location)
     */
    public function step1()
    {
        return view('onboarding.step1');
    }

    /**
     * Store onboarding step 1 data
     */
    public function storeStep1(Request $request)
    {
        $validated = $request->validate([
            'dob' => ['required', 'date', 'before:today', 'after:1940-01-01'],
            'city' => ['required', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:255'],
            'country' => ['required', 'string', 'max:255'],
        ]);

        $user = Auth::user();

        // Calculate age and check minimum age requirement (18 years)
        $age = now()->diffInYears($validated['dob']);
        if ($age < 18) {
            return back()->withErrors(['dob' => 'You must be at least 18 years old to register.']);
        }

        $user->update($validated);

        return redirect()->route('onboarding.step2');
    }

    /**
     * Show onboarding step 2: Profile Details
     */
    public function step2()
    {
        return view('onboarding.step2');
    }

    /**
     * Store onboarding step 2 data
     */
    public function storeStep2(Request $request)
    {
        $validated = $request->validate([
            'height' => ['required', 'integer', 'min:100', 'max:250'],
            'body_type' => ['required', 'in:slim,average,athletic,curvy,heavy'],
            'marital_status' => ['required', 'in:never_married,divorced,widowed,separated'],
            'education' => ['required', 'string', 'max:255'],
            'occupation' => ['required', 'string', 'max:255'],
            'annual_income' => ['required', 'string', 'max:100'],
            'religion' => ['nullable', 'string', 'max:100'],
            'caste' => ['nullable', 'string', 'max:100'],
            'mother_tongue' => ['nullable', 'string', 'max:100'],
            'diet' => ['nullable', 'in:vegetarian,non_vegetarian,vegan,eggetarian'],
            'drinking' => ['nullable', 'in:never,socially,regularly'],
            'smoking' => ['nullable', 'in:never,socially,regularly'],
            'bio' => ['nullable', 'string', 'max:500'],
        ]);

        $user = Auth::user();

        // Create or update profile
        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            $validated
        );

        return redirect()->route('onboarding.step3');
    }

    /**
     * Show onboarding step 3: Photos Upload
     */
    public function step3()
    {
        return view('onboarding.step3');
    }

    /**
     * Store onboarding step 3 data (handled via separate upload endpoint)
     */
    public function storeStep3(Request $request)
    {
        // Photos will be uploaded via AJAX/separate endpoint
        // For now, just redirect to next step
        return redirect()->route('onboarding.step4');
    }

    /**
     * Show onboarding step 4: Preferences
     */
    public function step4()
    {
        return view('onboarding.step4');
    }

    /**
     * Store onboarding step 4 data
     */
    public function storeStep4(Request $request)
    {
        $validated = $request->validate([
            'age_min' => ['required', 'integer', 'min:18', 'max:100'],
            'age_max' => ['required', 'integer', 'min:18', 'max:100', 'gte:age_min'],
            'height_min' => ['nullable', 'integer', 'min:100', 'max:250'],
            'height_max' => ['nullable', 'integer', 'min:100', 'max:250'],
            'distance_radius' => ['nullable', 'integer', 'in:25,50,100,200,500'],
            'religion_preferences' => ['nullable', 'array'],
            'education_levels' => ['nullable', 'array'],
            'marital_status_preferences' => ['nullable', 'array'],
        ]);

        $user = Auth::user();

        // Create or update preferences
        $user->preference()->updateOrCreate(
            ['user_id' => $user->id],
            $validated
        );

        // Mark profile as complete
        $completion = $user->profile->calculateCompletion();
        $user->update([
            'profile_completion_percentage' => $completion,
        ]);

        return redirect()->route('onboarding.complete');
    }

    /**
     * Show onboarding complete page
     */
    public function complete()
    {
        return view('onboarding.complete');
    }
}
