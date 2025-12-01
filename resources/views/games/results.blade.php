<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Game Results</h1>
                <p class="text-gray-600 dark:text-gray-400 mb-8">{{ ucfirst(str_replace('_', ' ', $game->type)) }}</p>

                @if($game->type === 'compatibility_quiz' && $game->compatibility_score)
                    <div class="bg-gradient-to-r from-indigo-500 to-purple-500 rounded-2xl p-8 text-center mb-8">
                        <div class="text-white">
                            <p class="text-lg mb-2">Compatibility Score</p>
                            <p class="text-6xl font-bold">{{ $game->compatibility_score }}%</p>
                            <p class="text-sm mt-4 opacity-90">
                                You matched on {{ $game->results['matches'] ?? 0 }} out of {{ $game->results['total'] ?? 0 }} questions
                            </p>
                        </div>
                    </div>
                @endif

                <div class="space-y-6">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Answer Comparison</h2>

                    @if($game->type === 'compatibility_quiz')
                        @foreach($questions as $q)
                            @php
                                $user1Answer = $user1Answers[$q['key']] ?? null;
                                $user2Answer = $user2Answers[$q['key']] ?? null;
                                $match = $user1Answer === $user2Answer;
                            @endphp
                            <div class="p-6 {{ $match ? 'bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800' : 'bg-gray-50 dark:bg-gray-700' }} rounded-lg">
                                <h3 class="font-semibold text-gray-900 dark:text-white mb-4">{{ $q['question'] }}</h3>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">{{ $game->user1->name }}</p>
                                        <p class="font-medium text-gray-900 dark:text-white">{{ $q['options'][$user1Answer] ?? 'No answer' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">{{ $game->user2->name }}</p>
                                        <p class="font-medium text-gray-900 dark:text-white">{{ $q['options'][$user2Answer] ?? 'No answer' }}</p>
                                    </div>
                                </div>
                                @if($match)
                                    <p class="text-green-600 dark:text-green-400 text-sm mt-3 font-semibold">✓ Match!</p>
                                @endif
                            </div>
                        @endforeach
                    @else
                        @foreach($questions as $index => $question)
                            @php
                                $qKey = is_array($question) ? "q{$index}" : "q{$index}";
                                $questionText = is_array($question) ? $question['question'] : $question;
                            @endphp
                            <div class="p-6 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <h3 class="font-semibold text-gray-900 dark:text-white mb-4">{{ $questionText }}</h3>
                                <div class="space-y-4">
                                    <div>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">{{ $game->user1->name }}</p>
                                        <p class="text-gray-900 dark:text-white">{{ $user1Answers[$qKey] ?? 'No answer' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">{{ $game->user2->name }}</p>
                                        <p class="text-gray-900 dark:text-white">{{ $user2Answers[$qKey] ?? 'No answer' }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                <div class="mt-8 flex justify-center">
                    <a href="{{ route('games.index') }}" class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg hover:from-indigo-700 hover:to-purple-700">
                        Back to Games
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
