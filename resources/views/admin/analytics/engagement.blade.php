<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Engagement Analytics</h1>
                <p class="mt-2 text-gray-600">Track user engagement and platform activity</p>
            </div>

            <!-- Date Range Filter -->
            <div class="mb-6 bg-white rounded-lg shadow-md p-4">
                <form method="GET" action="{{ route('admin.analytics.engagement') }}" class="flex gap-4 items-end">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                        <input type="date" name="start_date" value="{{ $startDate }}"
                               class="rounded-lg border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                        <input type="date" name="end_date" value="{{ $endDate }}"
                               class="rounded-lg border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500">
                    </div>
                    <button type="submit" class="bg-pink-600 text-white px-6 py-2 rounded-lg hover:bg-pink-700 transition">
                        Apply
                    </button>
                </form>
            </div>

            <!-- Engagement Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                @component('admin.analytics.partials.stats-card')
                    @slot('title', 'Profile Views')
                    @slot('value', number_format($metrics['engagement']['total_profile_views']))
                    @slot('subtitle', number_format($metrics['engagement']['unique_profile_views']) . ' unique profiles')
                    @slot('bgColor', '#FCE7F3')
                    @slot('iconColor', '#EC4899')
                    @slot('icon')
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    @endslot
                @endcomponent

                @component('admin.analytics.partials.stats-card')
                    @slot('title', 'Messages')
                    @slot('value', number_format($metrics['engagement']['total_messages']))
                    @slot('bgColor', '#DBEAFE')
                    @slot('iconColor', '#3B82F6')
                    @slot('icon')
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    @endslot
                @endcomponent

                @component('admin.analytics.partials.stats-card')
                    @slot('title', 'Matches')
                    @slot('value', number_format($metrics['engagement']['total_matches']))
                    @slot('bgColor', '#D1FAE5')
                    @slot('iconColor', '#10B981')
                    @slot('fill', 'currentColor')
                    @slot('icon')
                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                    @endslot
                @endcomponent

                @component('admin.analytics.partials.stats-card')
                    @slot('title', 'Avg Activities/User')
                    @slot('value', number_format($metrics['engagement']['avg_activities_per_user'], 1))
                    @slot('subtitle', number_format($metrics['engagement']['total_activities']) . ' total activities')
                    @slot('bgColor', '#FEF3C7')
                    @slot('iconColor', '#F59E0B')
                    @slot('icon')
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    @endslot
                @endcomponent
            </div>

            <!-- Activity Breakdown Chart -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Activity Type Distribution</h2>
                <canvas id="activityChart" height="100"></canvas>
            </div>

            <!-- Peak Usage Hours -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Peak Usage Hours</h2>
                <canvas id="peakHoursChart" height="100"></canvas>
                <div class="mt-4 grid grid-cols-3 gap-4 text-center">
                    <div>
                        <p class="text-sm text-gray-600">Morning Peak</p>
                        <p class="text-lg font-semibold text-gray-900">
                            {{ array_keys($peakHours, max(array_slice($peakHours, 6, 6, true)))[0] ?? 'N/A' }}:00
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Afternoon Peak</p>
                        <p class="text-lg font-semibold text-gray-900">
                            {{ array_keys($peakHours, max(array_slice($peakHours, 12, 6, true)))[0] ?? 'N/A' }}:00
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Evening Peak</p>
                        <p class="text-lg font-semibold text-gray-900">
                            {{ array_keys($peakHours, max(array_slice($peakHours, 18, 6, true)))[0] ?? 'N/A' }}:00
                        </p>
                    </div>
                </div>
            </div>

            <!-- Activity Details Table -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Detailed Activity Breakdown</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Activity Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Count</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Percentage</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @php
                                $totalActivities = array_sum(array_values($metrics['activity_breakdown']->toArray()));
                            @endphp
                            @foreach($metrics['activity_breakdown'] as $type => $count)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 capitalize">
                                    {{ str_replace('_', ' ', $type) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ number_format($count) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <span class="text-sm text-gray-500 mr-2">
                                            {{ $totalActivities > 0 ? round(($count / $totalActivities) * 100, 1) : 0 }}%
                                        </span>
                                        <div class="w-32 bg-gray-200 rounded-full h-2">
                                            <div class="bg-pink-600 h-2 rounded-full"
                                                 style="width: {{ $totalActivities > 0 ? ($count / $totalActivities) * 100 : 0 }}%"></div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        // Activity Breakdown Chart
        const activityCtx = document.getElementById('activityChart').getContext('2d');
        const activityData = @json($metrics['activity_breakdown']);
        new Chart(activityCtx, {
            type: 'bar',
            data: {
                labels: Object.keys(activityData).map(k => k.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase())),
                datasets: [{
                    label: 'Activity Count',
                    data: Object.values(activityData),
                    backgroundColor: 'rgb(236, 72, 153)'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: 'y',
                scales: { x: { beginAtZero: true } }
            }
        });

        // Peak Hours Chart
        const peakCtx = document.getElementById('peakHoursChart').getContext('2d');
        const peakHours = @json($peakHours);
        const hours = Array.from({length: 24}, (_, i) => i);
        const hourData = hours.map(h => peakHours[h] || 0);
        new Chart(peakCtx, {
            type: 'line',
            data: {
                labels: hours.map(h => h + ':00'),
                datasets: [{
                    label: 'Activity Count',
                    data: hourData,
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { y: { beginAtZero: true } }
            }
        });
    </script>
    @endpush
</x-app-layout>
