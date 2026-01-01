<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">My Analytics</h1>
                <p class="mt-2 text-gray-600">Track your profile performance and engagement</p>
            </div>

            <!-- Date Range Filter -->
            <div class="mb-6 flex justify-end">
                <select id="daysFilter" onchange="window.location.href='?days='+this.value"
                        class="rounded-lg border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500">
                    <option value="7" {{ $days == 7 ? 'selected' : '' }}>Last 7 days</option>
                    <option value="30" {{ $days == 30 ? 'selected' : '' }}>Last 30 days</option>
                    <option value="90" {{ $days == 90 ? 'selected' : '' }}>Last 90 days</option>
                </select>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Profile Views -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-pink-100 rounded-md p-3">
                            <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </div>
                        <div class="ml-5">
                            <p class="text-sm font-medium text-gray-500">Profile Views</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ number_format($metrics['totals']['total_profile_viewed_by']) }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $metrics['totals']['unique_profile_viewers'] }} unique viewers</p>
                        </div>
                    </div>
                </div>

                <!-- Likes Received -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-red-100 rounded-md p-3">
                            <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-5">
                            <p class="text-sm font-medium text-gray-500">Likes Received</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ number_format($metrics['totals']['total_likes_received']) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Messages -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-100 rounded-md p-3">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                        </div>
                        <div class="ml-5">
                            <p class="text-sm font-medium text-gray-500">Messages</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ number_format($metrics['totals']['total_messages_sent']) }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $metrics['totals']['response_rate'] }}% response rate</p>
                        </div>
                    </div>
                </div>

                <!-- Matches -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-100 rounded-md p-3">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div class="ml-5">
                            <p class="text-sm font-medium text-gray-500">Matches</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ number_format($metrics['totals']['total_matches']) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Engagement Trend Chart -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Engagement Trend</h2>
                    <canvas id="engagementChart" height="300"></canvas>
                </div>

                <!-- Activity Sources Chart -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Profile View Sources</h2>
                    <canvas id="sourcesChart" height="300"></canvas>
                </div>
            </div>

            <!-- Detailed Metrics -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Activity Breakdown -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Activity Breakdown</h2>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center py-3 border-b">
                            <span class="text-gray-600">Profile Views Given</span>
                            <span class="font-semibold text-gray-900">{{ number_format($metrics['totals']['total_profile_views']) }}</span>
                        </div>
                        <div class="flex justify-between items-center py-3 border-b">
                            <span class="text-gray-600">Likes Sent</span>
                            <span class="font-semibold text-gray-900">{{ number_format($metrics['totals']['total_likes_sent']) }}</span>
                        </div>
                        <div class="flex justify-between items-center py-3 border-b">
                            <span class="text-gray-600">Messages Received</span>
                            <span class="font-semibold text-gray-900">{{ number_format($metrics['totals']['total_messages_received']) }}</span>
                        </div>
                        <div class="flex justify-between items-center py-3 border-b">
                            <span class="text-gray-600">Searches Performed</span>
                            <span class="font-semibold text-gray-900">{{ number_format($metrics['totals']['total_searches']) }}</span>
                        </div>
                        <div class="flex justify-between items-center py-3">
                            <span class="text-gray-600">Time Spent</span>
                            <span class="font-semibold text-gray-900">{{ number_format($metrics['totals']['total_time_spent_minutes']) }} min</span>
                        </div>
                    </div>
                </div>

                <!-- Engagement Score -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Engagement Score</h2>
                    <div class="flex flex-col items-center justify-center py-8">
                        <div class="relative w-48 h-48">
                            <svg class="transform -rotate-90 w-48 h-48">
                                <circle cx="96" cy="96" r="88" stroke="#e5e7eb" stroke-width="16" fill="none"/>
                                <circle cx="96" cy="96" r="88" stroke="#ec4899" stroke-width="16" fill="none"
                                        stroke-dasharray="{{ 2 * 3.14159 * 88 }}"
                                        stroke-dashoffset="{{ 2 * 3.14159 * 88 * (1 - $metrics['totals']['avg_engagement_score'] / 100) }}"
                                        stroke-linecap="round"/>
                            </svg>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <span class="text-4xl font-bold text-gray-900">{{ $metrics['totals']['avg_engagement_score'] }}</span>
                            </div>
                        </div>
                        <p class="mt-4 text-gray-600 text-center">Your average engagement score out of 100</p>
                        @if($metrics['totals']['avg_engagement_score'] < 30)
                            <p class="mt-2 text-sm text-yellow-600">Try being more active to improve your score!</p>
                        @elseif($metrics['totals']['avg_engagement_score'] < 70)
                            <p class="mt-2 text-sm text-blue-600">Good engagement! Keep it up.</p>
                        @else
                            <p class="mt-2 text-sm text-green-600">Excellent engagement!</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Export Button -->
            <div class="mt-8 flex justify-end">
                <a href="{{ route('analytics.export', ['days' => $days]) }}"
                   class="bg-pink-600 text-white px-6 py-3 rounded-lg hover:bg-pink-700 transition">
                    Export Data (CSV)
                </a>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        // Engagement Trend Chart
        const engagementCtx = document.getElementById('engagementChart').getContext('2d');
        const engagementChart = new Chart(engagementCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($metrics['daily_metrics']->pluck('date')) !!},
                datasets: [
                    {
                        label: 'Profile Views',
                        data: {!! json_encode($metrics['daily_metrics']->pluck('profile_viewed_by')) !!},
                        borderColor: 'rgb(236, 72, 153)',
                        backgroundColor: 'rgba(236, 72, 153, 0.1)',
                        tension: 0.4
                    },
                    {
                        label: 'Likes Received',
                        data: {!! json_encode($metrics['daily_metrics']->pluck('likes_received')) !!},
                        borderColor: 'rgb(239, 68, 68)',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        tension: 0.4
                    },
                    {
                        label: 'Messages',
                        data: {!! json_encode($metrics['daily_metrics']->pluck('messages_sent')) !!},
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Sources Pie Chart
        const sourcesCtx = document.getElementById('sourcesChart').getContext('2d');
        const sourcesData = @json($sources);
        const sourcesChart = new Chart(sourcesCtx, {
            type: 'pie',
            data: {
                labels: Object.keys(sourcesData).map(s => s.charAt(0).toUpperCase() + s.slice(1)),
                datasets: [{
                    data: Object.values(sourcesData),
                    backgroundColor: [
                        'rgb(236, 72, 153)',
                        'rgb(59, 130, 246)',
                        'rgb(34, 197, 94)',
                        'rgb(251, 191, 36)',
                        'rgb(168, 85, 247)'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                    }
                }
            }
        });
    </script>
    @endpush
</x-app-layout>
