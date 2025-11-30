<x-guest-layout>
    <div>
        <!-- Progress Bar -->
        <div class="mb-8">
            <div class="flex justify-between items-center mb-2">
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Step 1 of 4</span>
                <span class="text-sm text-gray-500 dark:text-gray-400">25% Complete</span>
            </div>
            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                <div class="bg-gradient-to-r from-primary to-secondary h-2 rounded-full" style="width: 25%"></div>
            </div>
        </div>

        <!-- Header -->
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Tell us about yourself</h2>
            <p class="text-gray-600 dark:text-gray-400">Let's start with some basic information</p>
        </div>

        <form method="POST" action="{{ route('onboarding.step1.store') }}" class="space-y-6">
            @csrf

            <!-- Date of Birth -->
            <div>
                <x-input-label for="dob" value="Date of Birth" class="text-gray-700 dark:text-gray-300" />
                <x-text-input
                    id="dob"
                    class="block mt-2 w-full px-4 py-3 rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary focus:ring-primary"
                    type="date"
                    name="dob"
                    :value="old('dob', auth()->user()->dob)"
                    required
                    max="{{ now()->subYears(18)->format('Y-m-d') }}"
                />
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">You must be at least 18 years old</p>
                <x-input-error :messages="$errors->get('dob')" class="mt-2" />
            </div>

            <!-- City -->
            <div>
                <x-input-label for="city" value="City" class="text-gray-700 dark:text-gray-300" />
                <x-text-input
                    id="city"
                    class="block mt-2 w-full px-4 py-3 rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary focus:ring-primary"
                    type="text"
                    name="city"
                    :value="old('city', auth()->user()->city)"
                    required
                    placeholder="e.g., Mumbai, Delhi, Bangalore"
                />
                <x-input-error :messages="$errors->get('city')" class="mt-2" />
            </div>

            <!-- State -->
            <div>
                <x-input-label for="state" value="State/Province" class="text-gray-700 dark:text-gray-300" />
                <x-text-input
                    id="state"
                    class="block mt-2 w-full px-4 py-3 rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary focus:ring-primary"
                    type="text"
                    name="state"
                    :value="old('state', auth()->user()->state)"
                    placeholder="e.g., Maharashtra, Delhi"
                />
                <x-input-error :messages="$errors->get('state')" class="mt-2" />
            </div>

            <!-- Country -->
            <div>
                <x-input-label for="country" value="Country" class="text-gray-700 dark:text-gray-300" />
                <select
                    id="country"
                    name="country"
                    required
                    class="block mt-2 w-full px-4 py-3 rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary focus:ring-primary"
                >
                    <option value="">Select Country</option>
                    <option value="India" {{ old('country', auth()->user()->country) == 'India' ? 'selected' : '' }}>India</option>
                    <option value="United States" {{ old('country', auth()->user()->country) == 'United States' ? 'selected' : '' }}>United States</option>
                    <option value="United Kingdom" {{ old('country', auth()->user()->country) == 'United Kingdom' ? 'selected' : '' }}>United Kingdom</option>
                    <option value="Canada" {{ old('country', auth()->user()->country) == 'Canada' ? 'selected' : '' }}>Canada</option>
                    <option value="Australia" {{ old('country', auth()->user()->country) == 'Australia' ? 'selected' : '' }}>Australia</option>
                </select>
                <x-input-error :messages="$errors->get('country')" class="mt-2" />
            </div>

            <!-- Navigation Buttons -->
            <div class="flex justify-between items-center pt-4">
                <a
                    href="{{ route('dashboard') }}"
                    class="text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-primary transition-colors"
                >
                    Skip for now
                </a>

                <x-primary-button class="bg-gradient-to-r from-primary to-secondary hover:shadow-glow py-3 px-8 rounded-xl font-semibold transition-all duration-200">
                    Continue
                    <svg class="w-4 h-4 ml-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>
