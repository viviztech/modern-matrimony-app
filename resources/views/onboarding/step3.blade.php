<x-guest-layout>
    <div>
        <div class="mb-8">
            <div class="flex justify-between items-center mb-2">
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Step 3 of 4</span>
                <span class="text-sm text-gray-500 dark:text-gray-400">75% Complete</span>
            </div>
            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                <div class="bg-gradient-to-r from-primary to-secondary h-2 rounded-full" style="width: 75%"></div>
            </div>
        </div>

        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Add Your Photos</h2>
            <p class="text-gray-600 dark:text-gray-400">Show your authentic self (We'll add photo upload later)</p>
        </div>

        <form method="POST" action="{{ route('onboarding.step3.store') }}" class="space-y-6">
            @csrf

            <div class="text-center py-12">
                <svg class="w-24 h-24 mx-auto text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <p class="text-gray-500 dark:text-gray-400 mb-4">Photo upload feature coming soon!</p>
                <p class="text-sm text-gray-400 dark:text-gray-500">You can add photos later from your profile settings</p>
            </div>

            <div class="flex justify-between items-center pt-4">
                <a href="{{ route('onboarding.step2') }}" class="text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-primary">
                    <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Back
                </a>

                <x-primary-button class="bg-gradient-to-r from-primary to-secondary hover:shadow-glow py-3 px-8 rounded-xl font-semibold">
                    Continue
                    <svg class="w-4 h-4 ml-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>
