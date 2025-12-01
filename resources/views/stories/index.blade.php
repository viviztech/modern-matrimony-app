<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Header --}}
            <div class="flex items-center justify-between mb-8">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Stories</h1>
                <a href="{{ route('stories.create') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg hover:from-indigo-700 hover:to-purple-700 transition-all shadow-md">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Create Story
                </a>
            </div>

            {{-- Stories Feed --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 mb-8">
                <div class="flex overflow-x-auto space-x-4 pb-4">
                    {{-- Your Story --}}
                    @if($myStories->count() > 0)
                        <a href="{{ route('stories.show', $myStories->first()) }}" class="flex-shrink-0">
                            <div class="relative">
                                <div class="w-20 h-20 rounded-full bg-gradient-to-br from-indigo-600 to-purple-600 p-0.5">
                                    <div class="w-full h-full rounded-full overflow-hidden bg-white dark:bg-gray-800">
                                        @if(auth()->user()->primaryPhoto)
                                            <img src="{{ auth()->user()->primaryPhoto->url }}" alt="Your story" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center text-white text-2xl font-bold">
                                                {{ substr(auth()->user()->name, 0, 1) }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="absolute bottom-0 right-0 w-6 h-6 bg-indigo-600 rounded-full flex items-center justify-center text-white text-xs font-bold border-2 border-white dark:border-gray-800">
                                    {{ $myStories->count() }}
                                </div>
                            </div>
                            <p class="text-xs text-center mt-2 text-gray-900 dark:text-white font-medium">Your Story</p>
                        </a>
                    @else
                        <a href="{{ route('stories.create') }}" class="flex-shrink-0">
                            <div class="relative">
                                <div class="w-20 h-20 rounded-full border-2 border-dashed border-gray-300 dark:border-gray-600 flex items-center justify-center hover:border-indigo-600 dark:hover:border-indigo-400 transition-colors">
                                    <svg class="w-8 h-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                </div>
                            </div>
                            <p class="text-xs text-center mt-2 text-gray-600 dark:text-gray-400">Add Story</p>
                        </a>
                    @endif

                    {{-- Match Stories --}}
                    @forelse($stories as $userId => $userStories)
                        <a href="{{ route('stories.show', $userStories->first()) }}" class="flex-shrink-0">
                            <div class="relative">
                                @php
                                    $hasUnviewed = $userStories->contains(function($story) {
                                        return !$story->isViewedBy(auth()->user());
                                    });
                                @endphp

                                <div class="w-20 h-20 rounded-full {{ $hasUnviewed ? 'bg-gradient-to-br from-indigo-600 to-purple-600' : 'bg-gray-300 dark:bg-gray-600' }} p-0.5">
                                    <div class="w-full h-full rounded-full overflow-hidden bg-white dark:bg-gray-800">
                                        @if($userStories->first()->user->primaryPhoto)
                                            <img src="{{ $userStories->first()->user->primaryPhoto->url }}" alt="{{ $userStories->first()->user->name }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center text-white text-2xl font-bold">
                                                {{ substr($userStories->first()->user->name, 0, 1) }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                @if($userStories->count() > 1)
                                    <div class="absolute bottom-0 right-0 w-6 h-6 bg-indigo-600 rounded-full flex items-center justify-center text-white text-xs font-bold border-2 border-white dark:border-gray-800">
                                        {{ $userStories->count() }}
                                    </div>
                                @endif
                            </div>
                            <p class="text-xs text-center mt-2 text-gray-900 dark:text-white truncate w-20">
                                {{ $userStories->first()->user->name }}
                            </p>
                        </a>
                    @empty
                        @if($myStories->count() == 0)
                            <div class="flex-1 text-center py-12">
                                <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                                <p class="text-gray-600 dark:text-gray-400 text-lg mb-2">No stories yet</p>
                                <p class="text-gray-500 dark:text-gray-500 text-sm">Your matches haven't posted any stories</p>
                            </div>
                        @endif
                    @endforelse
                </div>
            </div>

            {{-- Your Stories List --}}
            @if($myStories->count() > 0)
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Your Active Stories</h2>
                        <span class="text-sm text-gray-600 dark:text-gray-400">{{ $myStories->count() }} {{ $myStories->count() == 1 ? 'story' : 'stories' }}</span>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach($myStories as $story)
                            <div class="relative group">
                                <a href="{{ route('stories.show', $story) }}" class="block">
                                    <div class="aspect-[9/16] rounded-xl overflow-hidden bg-gray-100 dark:bg-gray-700">
                                        @if($story->type === 'photo')
                                            <img src="{{ $story->media_url }}" alt="Story" class="w-full h-full object-cover">
                                        @elseif($story->type === 'video')
                                            <video src="{{ $story->media_url }}" class="w-full h-full object-cover"></video>
                                        @else
                                            <div class="w-full h-full flex items-center justify-center p-4" style="background: {{ $story->background_color }}">
                                                <p class="text-white text-center font-medium">{{ $story->text_content }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </a>

                                {{-- Story Stats --}}
                                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/60 to-transparent p-3">
                                    <div class="flex items-center justify-between text-white text-xs">
                                        <div class="flex items-center space-x-3">
                                            <span class="flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                {{ $story->views_count }}
                                            </span>
                                            <span class="flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>
                                                </svg>
                                                {{ $story->likes_count }}
                                            </span>
                                        </div>
                                        <span class="text-xs">{{ $story->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>

                                {{-- Delete Button --}}
                                <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <form action="{{ route('stories.destroy', $story) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this story?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white rounded-full p-2 shadow-lg">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>

                                {{-- View Viewers Button --}}
                                @if($story->views_count > 0)
                                    <a href="{{ route('stories.viewers', $story) }}" class="absolute bottom-12 right-2 opacity-0 group-hover:opacity-100 transition-opacity bg-indigo-600 hover:bg-indigo-700 text-white rounded-full p-2 shadow-lg">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
