<x-guest-layout>
    <div>
        <div class="mb-8">
            <div class="flex justify-between items-center mb-2">
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Step 4 of 4</span>
                <span class="text-sm text-gray-500 dark:text-gray-400">100% Complete</span>
            </div>
            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                <div class="bg-gradient-to-r from-primary to-secondary h-2 rounded-full" style="width: 100%"></div>
            </div>
        </div>

        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Partner Preferences</h2>
            <p class="text-gray-600 dark:text-gray-400">Help us find your perfect match</p>
        </div>

        <form method="POST" action="{{ route('onboarding.step4.store') }}" class="space-y-6">
            @csrf

            <!-- Age Range -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <x-input-label for="age_min" value="Min Age" class="text-gray-700 dark:text-gray-300" />
                    <x-text-input id="age_min" class="block mt-2 w-full px-4 py-3 rounded-xl" type="number" name="age_min" :value="old('age_min', 22)" required min="18" max="100" />
                    <x-input-error :messages="$errors->get('age_min')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="age_max" value="Max Age" class="text-gray-700 dark:text-gray-300" />
                    <x-text-input id="age_max" class="block mt-2 w-full px-4 py-3 rounded-xl" type="number" name="age_max" :value="old('age_max', 30)" required min="18" max="100" />
                    <x-input-error :messages="$errors->get('age_max')" class="mt-2" />
                </div>
            </div>

            <!-- Height Range (Optional) -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <x-input-label for="height_min" value="Min Height (cm)" class="text-gray-700 dark:text-gray-300" />
                    <x-text-input id="height_min" class="block mt-2 w-full px-4 py-3 rounded-xl" type="number" name="height_min" :value="old('height_min')" min="100" max="250" placeholder="Optional" />
                </div>
                <div>
                    <x-input-label for="height_max" value="Max Height (cm)" class="text-gray-700 dark:text-gray-300" />
                    <x-text-input id="height_max" class="block mt-2 w-full px-4 py-3 rounded-xl" type="number" name="height_max" :value="old('height_max')" min="100" max="250" placeholder="Optional" />
                </div>
            </div>

            <!-- Distance Radius -->
            <div>
                <x-input-label for="distance_radius" value="Maximum Distance" class="text-gray-700 dark:text-gray-300" />
                <select id="distance_radius" name="distance_radius" class="block mt-2 w-full px-4 py-3 rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                    <option value="">No Preference</option>
                    <option value="25">Within 25 km</option>
                    <option value="50">Within 50 km</option>
                    <option value="100">Within 100 km</option>
                    <option value="200">Within 200 km</option>
                    <option value="500">Within 500 km</option>
                </select>
            </div>

            <!-- Marital Status Preferences -->
            <div>
                <x-input-label value="Marital Status (Select all that apply)" class="text-gray-700 dark:text-gray-300 mb-3" />
                <div class="space-y-2">
                    @foreach(['never_married' => 'Never Married', 'divorced' => 'Divorced', 'widowed' => 'Widowed'] as $value => $label)
                        <label class="flex items-center">
                            <input type="checkbox" name="marital_status_preferences[]" value="{{ $value }}" class="rounded border-gray-300 dark:border-gray-600 text-primary focus:ring-primary" {{ in_array($value, old('marital_status_preferences', [])) ? 'checked' : '' }}>
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ $label }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="flex justify-between items-center pt-4">
                <a href="{{ route('onboarding.step3') }}" class="text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-primary">
                    <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Back
                </a>

                <x-primary-button class="bg-gradient-to-r from-primary to-secondary hover:shadow-glow py-3 px-8 rounded-xl font-semibold">
                    Complete Profile
                    <svg class="w-4 h-4 ml-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>
