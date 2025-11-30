<nav x-data="{ mobileMenuOpen: false }" class="bg-white shadow-sm sticky top-0 z-50 transition-colors duration-200">
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
                    <span class="text-xl font-bold text-gradient hidden sm:block">{{ config('app.name', 'Matrimony') }}</span>
                </a>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center space-x-8">
                @auth
                    <a href="{{ route('discover') }}" class="text-gray-700 hover:text-primary transition-colors">
                        Discover
                    </a>
                    <a href="{{ route('matches') }}" class="text-gray-700 hover:text-primary transition-colors">
                        Matches
                    </a>
                    <a href="{{ route('messages') }}" class="relative text-gray-700 hover:text-primary transition-colors">
                        Messages
                        <span class="absolute -top-1 -right-2 bg-primary text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">3</span>
                    </a>
                    <a href="{{ route('profile.show') }}" class="text-gray-700 hover:text-primary transition-colors">
                        Profile
                    </a>
                @else
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-primary transition-colors">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="bg-gradient-to-r from-primary to-secondary text-white px-6 py-2 rounded-lg hover:shadow-glow transition-all duration-200">
                        Get Started
                    </a>
                @endauth

                <!-- Dark Mode Toggle -->
                <button
                    @click="darkMode = !darkMode"
                    class="p-2 rounded-lg hover:bg-gray-100 transition-colors"
                    aria-label="Toggle dark mode"
                >
                    <svg x-show="!darkMode" class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                    <svg x-show="darkMode" class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </button>

                @auth
                    <!-- User Menu -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="flex items-center space-x-2 focus:outline-none">
                            <img src="https://i.pravatar.cc/40?img={{ auth()->id() }}" alt="{{ auth()->user()->name }}" class="w-8 h-8 rounded-full">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-1" style="display: none;">
                            <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                My Profile
                            </a>
                            <a href="{{ route('settings') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Settings
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @endauth
            </div>

            <!-- Mobile Menu Button -->
            <div class="md:hidden flex items-center">
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="p-2 rounded-lg hover:bg-gray-100 transition-colors">
                    <svg x-show="!mobileMenuOpen" class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg x-show="mobileMenuOpen" class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div x-show="mobileMenuOpen" x-transition class="md:hidden bg-white border-t border-gray-200" style="display: none;">
        <div class="px-2 pt-2 pb-3 space-y-1">
            @auth
                <a href="{{ route('discover') }}" class="block px-3 py-2 rounded-md text-gray-700 hover:bg-gray-100">
                    Discover
                </a>
                <a href="{{ route('matches') }}" class="block px-3 py-2 rounded-md text-gray-700 hover:bg-gray-100">
                    Matches
                </a>
                <a href="{{ route('messages') }}" class="block px-3 py-2 rounded-md text-gray-700 hover:bg-gray-100">
                    Messages
                </a>
                <a href="{{ route('profile.show') }}" class="block px-3 py-2 rounded-md text-gray-700 hover:bg-gray-100">
                    Profile
                </a>
                <a href="{{ route('settings') }}" class="block px-3 py-2 rounded-md text-gray-700 hover:bg-gray-100">
                    Settings
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full text-left px-3 py-2 rounded-md text-gray-700 hover:bg-gray-100">
                        Logout
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="block px-3 py-2 rounded-md text-gray-700 hover:bg-gray-100">
                    Login
                </a>
                <a href="{{ route('register') }}" class="block px-3 py-2 rounded-md bg-gradient-to-r from-primary to-secondary text-white">
                    Get Started
                </a>
            @endauth
        </div>
    </div>
</nav>
