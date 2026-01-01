<!DOCTYPE html>
<html x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' || (!localStorage.getItem('darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches) }"
      x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))"
      :class="{ 'dark': darkMode }"
      lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name', 'Matrimony'))</title>

        <!-- SEO Meta Tags -->
        <meta name="description" content="@yield('meta_description', 'Modern matchmaking for Gen Z and Millennials. Find your perfect life partner with AI-powered compatibility matching.')">
        <meta name="keywords" content="@yield('meta_keywords', 'matrimony, matchmaking, dating, marriage, relationships, AI matching')">

        <!-- Open Graph / Facebook -->
        <meta property="og:type" content="website">
        <meta property="og:title" content="@yield('og_title', config('app.name') . ' - Find Your Perfect Match')">
        <meta property="og:description" content="@yield('og_description', 'Modern matchmaking for Gen Z and Millennials')">
        <meta property="og:image" content="@yield('og_image', asset('images/og-image.jpg'))">

        <!-- Twitter -->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="@yield('twitter_title', config('app.name') . ' - Find Your Perfect Match')">
        <meta name="twitter:description" content="@yield('twitter_description', 'Modern matchmaking for Gen Z and Millennials')">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800,900" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <!-- Public Navigation -->
        <nav x-data="{ mobileMenuOpen: false }" class="bg-white/95 dark:bg-gray-900/95 backdrop-blur-sm shadow-sm sticky top-0 z-50 transition-colors duration-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <!-- Logo & Brand -->
                    <div class="flex items-center">
                        <a href="{{ route('home') }}" class="flex items-center space-x-2">
                            <div class="w-10 h-10 bg-gradient-to-br from-primary to-secondary rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            </div>
                            <span class="text-xl font-bold text-gradient bg-gradient-to-r from-primary to-secondary bg-clip-text text-transparent">{{ config('app.name', 'Matrimony') }}</span>
                        </a>
                    </div>

                    <!-- Desktop Navigation -->
                    <div class="hidden md:flex items-center space-x-8">
                        <a href="#features" class="text-gray-700 dark:text-gray-300 hover:text-primary dark:hover:text-primary transition-colors">
                            Features
                        </a>
                        <a href="#how-it-works" class="text-gray-700 dark:text-gray-300 hover:text-primary dark:hover:text-primary transition-colors">
                            How It Works
                        </a>
                        <a href="#pricing" class="text-gray-700 dark:text-gray-300 hover:text-primary dark:hover:text-primary transition-colors">
                            Pricing
                        </a>
                        <a href="#faq" class="text-gray-700 dark:text-gray-300 hover:text-primary dark:hover:text-primary transition-colors">
                            FAQ
                        </a>

                        <!-- Dark Mode Toggle -->
                        <button
                            @click="darkMode = !darkMode"
                            class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
                            aria-label="Toggle dark mode"
                        >
                            <svg x-show="!darkMode" class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                            </svg>
                            <svg x-show="darkMode" class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </button>

                        <a href="{{ route('login') }}" class="text-gray-700 dark:text-gray-300 hover:text-primary dark:hover:text-primary transition-colors font-medium">
                            Login
                        </a>
                        <a href="{{ route('register') }}" class="bg-gradient-to-r from-primary to-secondary text-white px-6 py-2.5 rounded-xl hover:shadow-glow transition-all duration-200 font-semibold">
                            Get Started
                        </a>
                    </div>

                    <!-- Mobile Menu Button -->
                    <div class="md:hidden flex items-center space-x-2">
                        <!-- Dark Mode Toggle (Mobile) -->
                        <button
                            @click="darkMode = !darkMode"
                            class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
                            aria-label="Toggle dark mode"
                        >
                            <svg x-show="!darkMode" class="w-5 h-5 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                            </svg>
                            <svg x-show="darkMode" class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </button>

                        <button @click="mobileMenuOpen = !mobileMenuOpen" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                            <svg x-show="!mobileMenuOpen" class="w-6 h-6 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                            <svg x-show="mobileMenuOpen" class="w-6 h-6 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div x-show="mobileMenuOpen" x-transition class="md:hidden bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-800" style="display: none;">
                <div class="px-2 pt-2 pb-3 space-y-1">
                    <a href="#features" @click="mobileMenuOpen = false" class="block px-3 py-2 rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800">
                        Features
                    </a>
                    <a href="#how-it-works" @click="mobileMenuOpen = false" class="block px-3 py-2 rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800">
                        How It Works
                    </a>
                    <a href="#pricing" @click="mobileMenuOpen = false" class="block px-3 py-2 rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800">
                        Pricing
                    </a>
                    <a href="#faq" @click="mobileMenuOpen = false" class="block px-3 py-2 rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800">
                        FAQ
                    </a>
                    <div class="border-t border-gray-200 dark:border-gray-800 my-2"></div>
                    <a href="{{ route('login') }}" class="block px-3 py-2 rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="block px-3 py-2 rounded-md bg-gradient-to-r from-primary to-secondary text-white mx-2">
                        Get Started
                    </a>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main>
            @yield('content')
        </main>

        <!-- Cookie Consent -->
        @include('components.cookie-consent')
    </body>
</html>
