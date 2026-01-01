<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Who Viewed My Profile</h1>
                <p class="mt-2 text-gray-600">See who has been checking out your profile</p>
            </div>

            <!-- Date Range Filter -->
            <div class="mb-6 flex justify-between items-center">
                <div class="bg-white rounded-lg shadow-md p-4">
                    <p class="text-sm text-gray-600">Total Profile Views</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $viewers->total() }}</p>
                </div>
                <select id="daysFilter" onchange="window.location.href='?days='+this.value"
                        class="rounded-lg border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500">
                    <option value="7" {{ $days == 7 ? 'selected' : '' }}>Last 7 days</option>
                    <option value="30" {{ $days == 30 ? 'selected' : '' }}>Last 30 days</option>
                    <option value="90" {{ $days == 90 ? 'selected' : '' }}>Last 90 days</option>
                </select>
            </div>

            <!-- Sources Breakdown -->
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
                @foreach($sources as $source => $count)
                <div class="bg-white rounded-lg shadow-md p-4 text-center">
                    <p class="text-2xl font-bold text-pink-600">{{ $count }}</p>
                    <p class="text-sm text-gray-600 capitalize">{{ $source }}</p>
                </div>
                @endforeach
            </div>

            <!-- Profile Viewers List -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                @if($viewers->count() > 0)
                    <ul class="divide-y divide-gray-200">
                        @foreach($viewers as $view)
                        <li class="p-6 hover:bg-gray-50 transition">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <!-- Profile Picture -->
                                    <a href="{{ route('profile.show', $view->viewer->id) }}" class="flex-shrink-0">
                                        @if($view->viewer->profile_picture)
                                            <img src="{{ Storage::url($view->viewer->profile_picture) }}"
                                                 alt="{{ $view->viewer->name }}"
                                                 class="w-16 h-16 rounded-full object-cover">
                                        @else
                                            <div class="w-16 h-16 rounded-full bg-pink-100 flex items-center justify-center">
                                                <span class="text-xl font-semibold text-pink-600">
                                                    {{ substr($view->viewer->name, 0, 1) }}
                                                </span>
                                            </div>
                                        @endif
                                    </a>

                                    <!-- Profile Info -->
                                    <div>
                                        <a href="{{ route('profile.show', $view->viewer->id) }}"
                                           class="text-lg font-semibold text-gray-900 hover:text-pink-600">
                                            {{ $view->viewer->name }}
                                        </a>
                                        <div class="flex items-center space-x-3 mt-1 text-sm text-gray-600">
                                            @if($view->viewer->age)
                                                <span>{{ $view->viewer->age }} years</span>
                                            @endif
                                            @if($view->viewer->city)
                                                <span>&bull;</span>
                                                <span>{{ $view->viewer->city }}</span>
                                            @endif
                                            @if($view->viewer->profession)
                                                <span>&bull;</span>
                                                <span>{{ $view->viewer->profession }}</span>
                                            @endif
                                        </div>
                                        <div class="flex items-center space-x-3 mt-2 text-xs text-gray-500">
                                            <span class="bg-gray-100 px-2 py-1 rounded">
                                                {{ $view->viewed_at->diffForHumans() }}
                                            </span>
                                            <span class="bg-pink-100 text-pink-600 px-2 py-1 rounded capitalize">
                                                via {{ $view->source }}
                                            </span>
                                            @if($view->duration_seconds > 0)
                                                <span class="bg-blue-100 text-blue-600 px-2 py-1 rounded">
                                                    {{ round($view->duration_seconds / 60, 1) }} min
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex space-x-2">
                                    <a href="{{ route('profile.show', $view->viewer->id) }}"
                                       class="bg-pink-600 text-white px-4 py-2 rounded-lg hover:bg-pink-700 transition text-sm">
                                        View Profile
                                    </a>
                                    @if(!$view->viewer->isLikedBy(auth()->user()))
                                        <button onclick="likeProfile({{ $view->viewer->id }})"
                                                class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition text-sm">
                                            Like Back
                                        </button>
                                    @else
                                        <span class="text-green-600 text-sm flex items-center">
                                            <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            Liked
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>

                    <!-- Pagination -->
                    <div class="bg-gray-50 px-6 py-4">
                        {{ $viewers->links() }}
                    </div>
                @else
                    <div class="p-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No profile views yet</h3>
                        <p class="mt-1 text-sm text-gray-500">When someone views your profile, they'll appear here.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function likeProfile(userId) {
            fetch(`/api/likes/${userId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
    @endpush
</x-app-layout>
