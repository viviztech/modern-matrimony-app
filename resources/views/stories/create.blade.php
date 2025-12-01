<x-app-layout>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-8">Create Story</h1>

                <form action="{{ route('stories.store') }}" method="POST" enctype="multipart/form-data" x-data="{ type: 'photo' }">
                    @csrf

                    {{-- Type Selection --}}
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Story Type</label>
                        <div class="flex space-x-4">
                            <label class="flex-1">
                                <input type="radio" name="type" value="photo" x-model="type" class="sr-only peer" required>
                                <div class="flex flex-col items-center p-4 border-2 border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer peer-checked:border-indigo-600 peer-checked:bg-indigo-50 dark:peer-checked:bg-indigo-900/20">
                                    <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="text-sm font-medium">Photo</span>
                                </div>
                            </label>

                            <label class="flex-1">
                                <input type="radio" name="type" value="video" x-model="type" class="sr-only peer">
                                <div class="flex flex-col items-center p-4 border-2 border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer peer-checked:border-indigo-600 peer-checked:bg-indigo-50 dark:peer-checked:bg-indigo-900/20">
                                    <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="text-sm font-medium">Video</span>
                                </div>
                            </label>

                            <label class="flex-1">
                                <input type="radio" name="type" value="text" x-model="type" class="sr-only peer">
                                <div class="flex flex-col items-center p-4 border-2 border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer peer-checked:border-indigo-600 peer-checked:bg-indigo-50 dark:peer-checked:bg-indigo-900/20">
                                    <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                    </svg>
                                    <span class="text-sm font-medium">Text</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- Media Upload --}}
                    <div x-show="type !== 'text'" class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Upload <span x-text="type === 'photo' ? 'Photo' : 'Video'"></span>
                        </label>
                        <input type="file" name="media" accept="image/*,video/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-indigo-900/50 dark:file:text-indigo-300" required>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Max size: 50MB</p>
                    </div>

                    {{-- Text Content --}}
                    <div x-show="type === 'text'" class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Text Content</label>
                        <textarea name="text_content" rows="6" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white" placeholder="What's on your mind?" maxlength="500"></textarea>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Max: 500 characters</p>
                    </div>

                    {{-- Background Color --}}
                    <div x-show="type === 'text'" class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Background Color</label>
                        <input type="color" name="background_color" value="#667eea" class="h-12 w-24 rounded-lg border-gray-300 dark:border-gray-600">
                    </div>

                    {{-- Buttons --}}
                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('stories.index') }}" class="px-6 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                            Cancel
                        </a>
                        <button type="submit" class="px-6 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg hover:from-indigo-700 hover:to-purple-700">
                            Create Story
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
