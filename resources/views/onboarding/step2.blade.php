<x-guest-layout>
    <div>
        <!-- Progress Bar -->
        <div class="mb-8">
            <div class="flex justify-between items-center mb-2">
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Step 2 of 4</span>
                <span class="text-sm text-gray-500 dark:text-gray-400">50% Complete</span>
            </div>
            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                <div class="bg-gradient-to-r from-primary to-secondary h-2 rounded-full" style="width: 50%"></div>
            </div>
        </div>

        <!-- Header -->
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Complete Your Profile</h2>
            <p class="text-gray-600 dark:text-gray-400">Help others get to know you better</p>
        </div>

        <form method="POST" action="{{ route('onboarding.step2.store') }}" class="space-y-6">
            @csrf

            <!-- Height -->
            <div>
                <x-input-label for="height" value="Height (cm)" class="text-gray-700 dark:text-gray-300" />
                <x-text-input
                    id="height"
                    class="block mt-2 w-full px-4 py-3 rounded-xl"
                    type="number"
                    name="height"
                    :value="old('height', auth()->user()->profile?->height)"
                    required
                    min="100"
                    max="250"
                    placeholder="e.g., 170"
                />
                <x-input-error :messages="$errors->get('height')" class="mt-2" />
            </div>

            <!-- Body Type -->
            <div>
                <x-input-label value="Body Type" class="text-gray-700 dark:text-gray-300 mb-3" />
                <div class="grid grid-cols-3 gap-3">
                    @foreach(['slim' => 'Slim', 'average' => 'Average', 'athletic' => 'Athletic', 'curvy' => 'Curvy', 'heavy' => 'Heavy'] as $value => $label)
                        <label class="relative flex items-center justify-center px-4 py-3 rounded-xl border-2 border-gray-300 dark:border-gray-600 cursor-pointer hover:border-primary transition-all">
                            <input type="radio" name="body_type" value="{{ $value }}" class="sr-only peer" required {{ old('body_type', auth()->user()->profile?->body_type) == $value ? 'checked' : '' }}>
                            <span class="text-sm text-gray-700 dark:text-gray-300 peer-checked:text-primary font-medium">{{ $label }}</span>
                            <div class="absolute inset-0 rounded-xl border-2 border-primary opacity-0 peer-checked:opacity-100 transition-opacity"></div>
                        </label>
                    @endforeach
                </div>
                <x-input-error :messages="$errors->get('body_type')" class="mt-2" />
            </div>

            <!-- Marital Status -->
            <div>
                <x-input-label for="marital_status" value="Marital Status" class="text-gray-700 dark:text-gray-300" />
                <select id="marital_status" name="marital_status" required class="block mt-2 w-full px-4 py-3 rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    <option value="">Select Status</option>
                    <option value="never_married" {{ old('marital_status', auth()->user()->profile?->marital_status) == 'never_married' ? 'selected' : '' }}>Never Married</option>
                    <option value="divorced" {{ old('marital_status', auth()->user()->profile?->marital_status) == 'divorced' ? 'selected' : '' }}>Divorced</option>
                    <option value="widowed" {{ old('marital_status', auth()->user()->profile?->marital_status) == 'widowed' ? 'selected' : '' }}>Widowed</option>
                    <option value="separated" {{ old('marital_status', auth()->user()->profile?->marital_status) == 'separated' ? 'selected' : '' }}>Separated</option>
                </select>
                <x-input-error :messages="$errors->get('marital_status')" class="mt-2" />
            </div>

            <!-- Education -->
            <div>
                <x-input-label for="education" value="Education" class="text-gray-700 dark:text-gray-300" />
                <x-text-input id="education" class="block mt-2 w-full px-4 py-3 rounded-xl" type="text" name="education" :value="old('education', auth()->user()->profile?->education)" required placeholder="e.g., Bachelor's in Engineering" />
                <x-input-error :messages="$errors->get('education')" class="mt-2" />
            </div>

            <!-- Occupation -->
            <div>
                <x-input-label for="occupation" value="Occupation" class="text-gray-700 dark:text-gray-300" />
                <x-text-input id="occupation" class="block mt-2 w-full px-4 py-3 rounded-xl" type="text" name="occupation" :value="old('occupation', auth()->user()->profile?->occupation)" required placeholder="e.g., Software Engineer" />
                <x-input-error :messages="$errors->get('occupation')" class="mt-2" />
            </div>

            <!-- Annual Income -->
            <div>
                <x-input-label for="annual_income" value="Annual Income" class="text-gray-700 dark:text-gray-300" />
                <select id="annual_income" name="annual_income" required class="block mt-2 w-full px-4 py-3 rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                    <option value="">Select Income Range</option>
                    <option value="0-3 LPA">0-3 LPA</option>
                    <option value="3-5 LPA">3-5 LPA</option>
                    <option value="5-7 LPA">5-7 LPA</option>
                    <option value="7-10 LPA">7-10 LPA</option>
                    <option value="10-15 LPA">10-15 LPA</option>
                    <option value="15-20 LPA">15-20 LPA</option>
                    <option value="20+ LPA">20+ LPA</option>
                </select>
                <x-input-error :messages="$errors->get('annual_income')" class="mt-2" />
            </div>

            <!-- Religion -->
            <div>
                <x-input-label for="religion" value="Religion (Optional)" class="text-gray-700 dark:text-gray-300" />
                <x-text-input id="religion" class="block mt-2 w-full px-4 py-3 rounded-xl" type="text" name="religion" :value="old('religion', auth()->user()->profile?->religion)" placeholder="e.g., Hindu, Muslim, Christian" />
                <x-input-error :messages="$errors->get('religion')" class="mt-2" />
            </div>

            <!-- Bio -->
            <div>
                <x-input-label for="bio" value="About You (Optional)" class="text-gray-700 dark:text-gray-300" />
                <textarea id="bio" name="bio" rows="4" maxlength="500" class="block mt-2 w-full px-4 py-3 rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary focus:ring-primary" placeholder="Tell us a bit about yourself...">{{ old('bio', auth()->user()->profile?->bio) }}</textarea>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Max 500 characters</p>
                <x-input-error :messages="$errors->get('bio')" class="mt-2" />
            </div>

            <!-- Navigation Buttons -->
            <div class="flex justify-between items-center pt-4">
                <a href="{{ route('onboarding.step1') }}" class="text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-primary transition-colors">
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
