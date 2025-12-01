<x-app-layout>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8">
                <div class="flex items-center justify-between mb-8">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Story Viewers</h1>
                    <a href="{{ route('stories.index') }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </a>
                </div>

                <p class="text-gray-600 dark:text-gray-400 mb-6">{{ $viewers->count() }} {{ $viewers->count() == 1 ? 'person has' : 'people have' }} viewed your story</p>

                <div class="space-y-4">
                    @forelse($viewers as $view)
                        <div class="flex items-center justify-between p-4 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-colors">
                            <div class="flex items-center space-x-4">
                                @if($view->viewer->primaryPhoto)
                                    <img src="{{ $view->viewer->primaryPhoto->url }}" alt="{{ $view->viewer->name }}" class="w-12 h-12 rounded-full object-cover">
                                @else
                                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center text-white font-bold text-lg">
                                        {{ substr($view->viewer->name, 0, 1) }}
                                    </div>
                                @endif

                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ $view->viewer->name }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $view->viewed_at->diffForHumans() }}</p>
                                </div>
                            </div>

                            <a href="{{ route('profile.show', $view->viewer) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300">
                                View Profile
                            </a>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            <p class="text-gray-600 dark:text-gray-400">No views yet</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
