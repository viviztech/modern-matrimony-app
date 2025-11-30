<x-guest-layout>
    <div>
        <!-- Header -->
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Create Your Account</h2>
            <p class="text-gray-600 dark:text-gray-400">Start your journey to find your perfect match</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-6">
            @csrf

            <!-- Name -->
            <div>
                <x-input-label for="name" value="Full Name" class="text-gray-700 dark:text-gray-300" />
                <x-text-input
                    id="name"
                    class="block mt-2 w-full px-4 py-3 rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary focus:ring-primary"
                    type="text"
                    name="name"
                    :value="old('name')"
                    required
                    autofocus
                    autocomplete="name"
                    placeholder="John Doe"
                />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- Email Address -->
            <div>
                <x-input-label for="email" value="Email Address" class="text-gray-700 dark:text-gray-300" />
                <x-text-input
                    id="email"
                    class="block mt-2 w-full px-4 py-3 rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary focus:ring-primary"
                    type="email"
                    name="email"
                    :value="old('email')"
                    required
                    autocomplete="username"
                    placeholder="you@example.com"
                />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Phone Number -->
            <div>
                <x-input-label for="phone" value="Phone Number" class="text-gray-700 dark:text-gray-300" />
                <x-text-input
                    id="phone"
                    class="block mt-2 w-full px-4 py-3 rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary focus:ring-primary"
                    type="tel"
                    name="phone"
                    :value="old('phone')"
                    required
                    autocomplete="tel"
                    placeholder="+91 98765 43210"
                />
                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
            </div>

            <!-- Gender -->
            <div>
                <x-input-label value="I am" class="text-gray-700 dark:text-gray-300 mb-3" />
                <div class="grid grid-cols-2 gap-4">
                    <label class="relative flex items-center justify-center px-4 py-3 rounded-xl border-2 border-gray-300 dark:border-gray-600 cursor-pointer hover:border-primary dark:hover:border-primary transition-all">
                        <input type="radio" name="gender" value="male" class="sr-only peer" required {{ old('gender') == 'male' ? 'checked' : '' }}>
                        <span class="text-gray-700 dark:text-gray-300 peer-checked:text-primary dark:peer-checked:text-primary font-medium">Male</span>
                        <div class="absolute inset-0 rounded-xl border-2 border-primary opacity-0 peer-checked:opacity-100 transition-opacity"></div>
                    </label>
                    <label class="relative flex items-center justify-center px-4 py-3 rounded-xl border-2 border-gray-300 dark:border-gray-600 cursor-pointer hover:border-primary dark:hover:border-primary transition-all">
                        <input type="radio" name="gender" value="female" class="sr-only peer" required {{ old('gender') == 'female' ? 'checked' : '' }}>
                        <span class="text-gray-700 dark:text-gray-300 peer-checked:text-primary dark:peer-checked:text-primary font-medium">Female</span>
                        <div class="absolute inset-0 rounded-xl border-2 border-primary opacity-0 peer-checked:opacity-100 transition-opacity"></div>
                    </label>
                </div>
                <x-input-error :messages="$errors->get('gender')" class="mt-2" />
            </div>

            <!-- Password -->
            <div>
                <x-input-label for="password" value="Password" class="text-gray-700 dark:text-gray-300" />
                <x-text-input
                    id="password"
                    class="block mt-2 w-full px-4 py-3 rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary focus:ring-primary"
                    type="password"
                    name="password"
                    required
                    autocomplete="new-password"
                    placeholder="••••••••"
                />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div>
                <x-input-label for="password_confirmation" value="Confirm Password" class="text-gray-700 dark:text-gray-300" />
                <x-text-input
                    id="password_confirmation"
                    class="block mt-2 w-full px-4 py-3 rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary focus:ring-primary"
                    type="password"
                    name="password_confirmation"
                    required
                    autocomplete="new-password"
                    placeholder="••••••••"
                />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <!-- Terms & Conditions -->
            <div class="flex items-start">
                <input
                    id="terms"
                    name="terms"
                    type="checkbox"
                    required
                    class="mt-1 rounded border-gray-300 dark:border-gray-600 text-primary shadow-sm focus:ring-primary dark:bg-gray-700"
                >
                <label for="terms" class="ml-2 text-sm text-gray-600 dark:text-gray-400">
                    I agree to the <a href="#" class="text-primary hover:text-primary-600 dark:hover:text-primary-400 transition-colors">Terms of Service</a> and <a href="#" class="text-primary hover:text-primary-600 dark:hover:text-primary-400 transition-colors">Privacy Policy</a>
                </label>
            </div>

            <!-- Submit Button -->
            <div>
                <x-primary-button class="w-full justify-center bg-gradient-to-r from-primary to-secondary hover:shadow-glow py-3 rounded-xl font-semibold transition-all duration-200">
                    Create Account
                </x-primary-button>
            </div>

            <!-- Divider -->
            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300 dark:border-gray-600"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-4 bg-white dark:bg-gray-800 text-gray-500 dark:text-gray-400">Or continue with</span>
                </div>
            </div>

            <!-- Social Login Buttons -->
            <div class="grid grid-cols-2 gap-4">
                <button
                    type="button"
                    class="flex items-center justify-center px-4 py-3 rounded-xl border-2 border-gray-300 dark:border-gray-600 hover:border-primary dark:hover:border-primary transition-all"
                >
                    <svg class="w-5 h-5" viewBox="0 0 24 24">
                        <path fill="#EA4335" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#4285F4" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                        <path fill="#34A853" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                    <span class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">Google</span>
                </button>

                <button
                    type="button"
                    class="flex items-center justify-center px-4 py-3 rounded-xl border-2 border-gray-300 dark:border-gray-600 hover:border-primary dark:hover:border-primary transition-all"
                >
                    <svg class="w-5 h-5 text-[#1877F2]" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                    </svg>
                    <span class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">Facebook</span>
                </button>
            </div>
        </form>

        <!-- Sign In Link -->
        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Already have an account?
                <a href="{{ route('login') }}" class="font-medium text-primary hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                    Sign in
                </a>
            </p>
        </div>
    </div>
</x-guest-layout>
