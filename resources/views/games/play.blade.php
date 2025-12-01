<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">{{ ucfirst(str_replace('_', ' ', $game->type)) }}</h1>
                <p class="text-gray-600 dark:text-gray-400 mb-8">with {{ $otherUser->name }}</p>

                @if($hasAnswered)
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mb-6">
                        <p class="text-blue-800 dark:text-blue-200">
                            You've already answered!
                            @if($otherUserAnswered)
                                <a href="{{ route('games.results', $game) }}" class="underline font-semibold">View Results</a>
                            @else
                                Waiting for {{ $otherUser->name }} to answer.
                            @endif
                        </p>
                    </div>
                @else
                    <form action="{{ route('games.submit', $game) }}" method="POST">
                        @csrf

                        <div class="space-y-6">
                            @if($game->type === 'compatibility_quiz')
                                @foreach($questions as $index => $q)
                                    <div class="p-6 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                        <h3 class="font-semibold text-gray-900 dark:text-white mb-4">{{ $index + 1 }}. {{ $q['question'] }}</h3>
                                        <div class="space-y-2">
                                            @foreach($q['options'] as $value => $label)
                                                <label class="flex items-center p-3 bg-white dark:bg-gray-800 rounded-lg cursor-pointer hover:bg-indigo-50 dark:hover:bg-indigo-900/20">
                                                    <input type="radio" name="answers[{{ $q['key'] }}]" value="{{ $value }}" class="mr-3" required>
                                                    <span class="text-gray-900 dark:text-white">{{ $label }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            @elseif($game->type === 'would_you_rather')
                                @foreach($questions as $index => $q)
                                    <div class="p-6 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                        <h3 class="font-semibold text-gray-900 dark:text-white mb-4">{{ $index + 1 }}. {{ $q['question'] }}</h3>
                                        <textarea name="answers[q{{ $index }}]" rows="3" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white" placeholder="Your answer..." required></textarea>
                                    </div>
                                @endforeach
                            @elseif($game->type === 'twenty_one_questions')
                                @foreach($questions as $index => $question)
                                    <div class="p-6 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                        <h3 class="font-semibold text-gray-900 dark:text-white mb-4">{{ $index + 1 }}. {{ $question }}</h3>
                                        <textarea name="answers[q{{ $index }}]" rows="3" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white" placeholder="Your answer..." required></textarea>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        <div class="mt-8 flex justify-end">
                            <button type="submit" class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg hover:from-indigo-700 hover:to-purple-700">
                                Submit Answers
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
