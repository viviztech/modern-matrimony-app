<x-app-layout>
    <div class="fixed inset-0 bg-black z-50" x-data="storyViewer()">
        {{-- Story Content --}}
        <div class="h-full flex items-center justify-center">
            <div class="max-w-md w-full h-full relative">
                {{-- Story Media/Content --}}
                @if($story->type === 'photo')
                    <img src="{{ $story->media_url }}" alt="Story" class="w-full h-full object-contain">
                @elseif($story->type === 'video')
                    <video src="{{ $story->media_url }}" class="w-full h-full object-contain" autoplay loop></video>
                @else
                    <div class="w-full h-full flex items-center justify-center p-8" style="background: {{ $story->background_color }}">
                        <p class="text-white text-2xl text-center font-medium">{{ $story->text_content }}</p>
                    </div>
                @endif

                {{-- Story Header --}}
                <div class="absolute top-0 left-0 right-0 bg-gradient-to-b from-black/60 to-transparent p-4">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center space-x-3 flex-1">
                            @if($story->user->primaryPhoto)
                                <img src="{{ $story->user->primaryPhoto->url }}" alt="{{ $story->user->name }}" class="w-10 h-10 rounded-full object-cover">
                            @else
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center text-white font-bold">
                                    {{ substr($story->user->name, 0, 1) }}
                                </div>
                            @endif
                            <div class="flex-1">
                                <p class="text-white font-semibold">{{ $story->user->name }}</p>
                                <p class="text-white/80 text-xs">{{ $story->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <a href="{{ route('stories.index') }}" class="text-white hover:text-gray-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </a>
                    </div>

                    {{-- Progress Bars --}}
                    <div class="flex space-x-1">
                        @foreach($userStories as $index => $userStory)
                            <div class="flex-1 h-0.5 bg-white/30 rounded-full overflow-hidden">
                                @if($userStory->id === $story->id)
                                    <div class="h-full bg-white animate-progress"></div>
                                @elseif($loop->index < $userStories->search(fn($s) => $s->id === $story->id))
                                    <div class="h-full bg-white"></div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Story Footer --}}
                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/60 to-transparent p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4 text-white">
                            <button @click="like()" class="flex items-center space-x-1 hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>
                                </svg>
                                <span x-text="likes"></span>
                            </button>
                            <span class="flex items-center space-x-1">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <span>{{ $story->views_count }}</span>
                            </span>
                        </div>

                        @if($story->user_id !== auth()->id())
                            <a href="{{ route('messages.create', $story->user) }}" class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg backdrop-blur-sm">
                                Reply
                            </a>
                        @endif
                    </div>
                </div>

                {{-- Navigation --}}
                <button @click="previousStory()" class="absolute left-4 top-1/2 -translate-y-1/2 text-white/50 hover:text-white">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </button>
                <button @click="nextStory()" class="absolute right-4 top-1/2 -translate-y-1/2 text-white/50 hover:text-white">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function storyViewer() {
            return {
                likes: {{ $story->likes_count }},

                like() {
                    fetch('{{ route('stories.like', $story) }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        this.likes = data.likes_count;
                    });
                },

                nextStory() {
                    const stories = @json($userStories->pluck('id'));
                    const currentIndex = stories.indexOf({{ $story->id }});
                    if (currentIndex < stories.length - 1) {
                        window.location.href = '/stories/' + stories[currentIndex + 1];
                    } else {
                        window.location.href = '{{ route('stories.index') }}';
                    }
                },

                previousStory() {
                    const stories = @json($userStories->pluck('id'));
                    const currentIndex = stories.indexOf({{ $story->id }});
                    if (currentIndex > 0) {
                        window.location.href = '/stories/' + stories[currentIndex - 1];
                    }
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
