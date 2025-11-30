<x-guest-layout>
    <div class="text-center">
        <!-- Success Animation -->
        <div class="mb-8">
            <div class="w-24 h-24 mx-auto bg-gradient-to-br from-primary to-secondary rounded-full flex items-center justify-center shadow-glow animate-fade-in">
                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                </svg>
            </div>
        </div>

        <!-- Header -->
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">Welcome to the Community!</h2>
            <p class="text-lg text-gray-600 dark:text-gray-400 mb-2">Your profile is all set up</p>
            <p class="text-sm text-gray-500 dark:text-gray-500">Start discovering your perfect match</p>
        </div>

        <!-- Profile Completion Stats -->
        <div class="bg-gradient-to-br from-primary-50 to-secondary-50 dark:from-gray-700 dark:to-gray-800 rounded-2xl p-6 mb-8">
            <div class="flex items-center justify-between mb-4">
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Profile Completion</span>
                <span class="text-sm font-bold text-primary">{{ auth()->user()->profile_completion_percentage ?? 0 }}%</span>
            </div>
            <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-3">
                <div class="bg-gradient-to-r from-primary to-secondary h-3 rounded-full transition-all duration-500" style="width: {{ auth()->user()->profile_completion_percentage ?? 0 }}%"></div>
            </div>
        </div>

        <!-- Quick Tips -->
        <div class="text-left mb-8 space-y-3">
            <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Quick Tips to Get Started:</h3>
            <div class="flex items-start space-x-3">
                <svg class="w-5 h-5 text-primary mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-sm text-gray-600 dark:text-gray-400">Add profile photos to increase your visibility by 10x</p>
            </div>
            <div class="flex items-start space-x-3">
                <svg class="w-5 h-5 text-primary mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-sm text-gray-600 dark:text-gray-400">Verify your email to unlock all features</p>
            </div>
            <div class="flex items-start space-x-3">
                <svg class="w-5 h-5 text-primary mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-sm text-gray-600 dark:text-gray-400">Start swiping to find compatible matches</p>
            </div>
        </div>

        <!-- CTA Button -->
        <a href="{{ route('dashboard') }}" class="inline-block w-full bg-gradient-to-r from-primary to-secondary text-white px-8 py-4 rounded-xl hover:shadow-glow transition-all duration-200 font-semibold text-lg">
            Start Discovering Matches
            <svg class="w-5 h-5 ml-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
            </svg>
        </a>
    </div>
</x-guest-layout>
