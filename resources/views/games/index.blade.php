<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-8">Games & Activities</h1>

            @if($activeGames->count() > 0)
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 mb-8">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Active Games</h2>
                    <div class="space-y-4">
                        @foreach($activeGames as $game)
                            @php
                                $partner = $game->getOtherUser(auth()->user());
                                $hasAnswered = $game->hasAnswered(auth()->user());
                            @endphp
                            <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex items-center space-x-4">
                                    @if($partner->primaryPhoto)
                                        <img src="{{ $partner->primaryPhoto->url }}" alt="{{ $partner->name }}" class="w-12 h-12 rounded-full object-cover">
                                    @else
                                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center text-white font-bold">
                                            {{ substr($partner->name, 0, 1) }}
                                        </div>
                                    @endif
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white">{{ ucfirst(str_replace('_', ' ', $game->type)) }}</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">with {{ $partner->name }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-4">
                                    @if($hasAnswered)
                                        <span class="text-sm text-green-600 dark:text-green-400">Answered</span>
                                    @else
                                        <a href="{{ route('games.play', $game) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                                            Play Now
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($completedGames->count() > 0)
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Completed Games</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($completedGames as $game)
                            @php
                                $partner = $game->getOtherUser(auth()->user());
                            @endphp
                            <a href="{{ route('games.results', $game) }}" class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                <div class="flex items-center justify-between mb-2">
                                    <p class="font-medium text-gray-900 dark:text-white">{{ ucfirst(str_replace('_', ' ', $game->type)) }}</p>
                                    @if($game->compatibility_score)
                                        <span class="px-3 py-1 bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200 rounded-full text-sm font-semibold">
                                            {{ $game->compatibility_score }}% Match
                                        </span>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">with {{ $partner->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-500 mt-2">{{ $game->completed_at->diffForHumans() }}</p>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($activeGames->count() == 0 && $completedGames->count() == 0)
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-12 text-center">
                    <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">No Games Yet</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-6">Start a game with one of your matches to get to know them better!</p>
                    <a href="{{ route('matches') }}" class="inline-block px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg hover:from-indigo-700 hover:to-purple-700">
                        View Matches
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
