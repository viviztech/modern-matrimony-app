<x-app-layout>
    <div class="py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Start a Game with {{ $partner->name }}</h1>

                <form action="{{ route('games.store', $partner) }}" method="POST">
                    @csrf

                    <div class="space-y-4">
                        <label class="block">
                            <input type="radio" name="type" value="compatibility_quiz" class="sr-only peer" required>
                            <div class="p-6 border-2 border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer peer-checked:border-indigo-600 peer-checked:bg-indigo-50 dark:peer-checked:bg-indigo-900/20">
                                <h3 class="font-semibold text-lg mb-2">Compatibility Quiz</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Answer 10 questions about your preferences and see how compatible you are!</p>
                            </div>
                        </label>

                        <label class="block">
                            <input type="radio" name="type" value="would_you_rather" class="sr-only peer">
                            <div class="p-6 border-2 border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer peer-checked:border-indigo-600 peer-checked:bg-indigo-50 dark:peer-checked:bg-indigo-900/20">
                                <h3 class="font-semibold text-lg mb-2">Would You Rather</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Fun questions to learn about each other's preferences and values.</p>
                            </div>
                        </label>

                        <label class="block">
                            <input type="radio" name="type" value="twenty_one_questions" class="sr-only peer">
                            <div class="p-6 border-2 border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer peer-checked:border-indigo-600 peer-checked:bg-indigo-50 dark:peer-checked:bg-indigo-900/20">
                                <h3 class="font-semibold text-lg mb-2">21 Questions</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Deep questions to get to know each other on a deeper level.</p>
                            </div>
                        </label>
                    </div>

                    <div class="mt-8 flex justify-end space-x-4">
                        <a href="{{ route('games.index') }}" class="px-6 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                            Cancel
                        </a>
                        <button type="submit" class="px-6 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg hover:from-indigo-700 hover:to-purple-700">
                            Start Game
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
