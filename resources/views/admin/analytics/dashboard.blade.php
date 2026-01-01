<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Admin Analytics Dashboard</h1>
                <p class="mt-2 text-gray-600">Complete overview of platform performance</p>
            </div>

            <!-- Date Range Filter -->
            <div class="mb-6 bg-white rounded-lg shadow-md p-4">
                <form method="GET" action="{{ route('admin.analytics.dashboard') }}" class="flex gap-4 items-end">
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

            <!-- User Metrics -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">User Metrics</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @component('admin.analytics.partials.stats-card')
                        @slot('title', 'Total Users')
                        @slot('value', number_format($metrics['users']['total']))
                        @slot('bgColor', '#DBEAFE')
                        @slot('iconColor', '#2563EB')
                        @slot('icon')
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        @endslot
                    @endcomponent

                    @component('admin.analytics.partials.stats-card')
                        @slot('title', 'New Users')
                        @slot('value', number_format($metrics['users']['new']))
                        @slot('subtitle', 'In selected period')
                        @slot('bgColor', '#D1FAE5')
                        @slot('iconColor', '#059669')
                        @slot('icon')
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        @endslot
                    @endcomponent

                    @component('admin.analytics.partials.stats-card')
                        @slot('title', 'Active Users')
                        @slot('value', number_format($metrics['users']['active']))
                        @slot('subtitle', 'In selected period')
                        @slot('bgColor', '#FEF3C7')
                        @slot('iconColor', '#D97706')
                        @slot('icon')
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        @endslot
                    @endcomponent

                    @component('admin.analytics.partials.stats-card')
                        @slot('title', 'Verified Users')
                        @slot('value', number_format($metrics['users']['verified']))
                        @slot('subtitle', $metrics['users']['verification_rate'] . '% verification rate')
                        @slot('bgColor', '#E0E7FF')
                        @slot('iconColor', '#6366F1')
                        @slot('icon')
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        @endslot
                    @endcomponent
                </div>
            </div>

            <!-- Engagement Metrics -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Engagement Metrics</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
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
                        @slot('title', 'Total Activities')
                        @slot('value', number_format($metrics['engagement']['total_activities']))
                        @slot('subtitle', number_format($metrics['engagement']['avg_activities_per_user'], 1) . ' per active user')
                        @slot('bgColor', '#FEF3C7')
                        @slot('iconColor', '#F59E0B')
                        @slot('icon')
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        @endslot
                    @endcomponent
                </div>
            </div>

            <!-- Subscription Metrics -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Subscription & Revenue</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @component('admin.analytics.partials.stats-card')
                        @slot('title', 'Active Subscriptions')
                        @slot('value', number_format($metrics['subscriptions']['active']))
                        @slot('bgColor', '#E0E7FF')
                        @slot('iconColor', '#6366F1')
                        @slot('icon')
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        @endslot
                    @endcomponent

                    @component('admin.analytics.partials.stats-card')
                        @slot('title', 'New Subscriptions')
                        @slot('value', number_format($metrics['subscriptions']['new']))
                        @slot('subtitle', 'In selected period')
                        @slot('bgColor', '#D1FAE5')
                        @slot('iconColor', '#059669')
                        @slot('icon')
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        @endslot
                    @endcomponent

                    @component('admin.analytics.partials.stats-card')
                        @slot('title', 'Revenue')
                        @slot('value', '$' . number_format($metrics['subscriptions']['revenue'], 2))
                        @slot('subtitle', 'In selected period')
                        @slot('bgColor', '#D1FAE5')
                        @slot('iconColor', '#059669')
                        @slot('icon')
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        @endslot
                    @endcomponent

                    @component('admin.analytics.partials.stats-card')
                        @slot('title', 'Conversion Rate')
                        @slot('value', $metrics['subscriptions']['conversion_rate'] . '%')
                        @slot('subtitle', 'Users to subscribers')
                        @slot('bgColor', '#FEF3C7')
                        @slot('iconColor', '#D97706')
                        @slot('icon')
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        @endslot
                    @endcomponent
                </div>
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Daily Active Users Chart -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Daily Active Users</h2>
                    <canvas id="dauChart" height="300"></canvas>
                </div>

                <!-- Activity Breakdown Chart -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Activity Breakdown</h2>
                    <canvas id="activityChart" height="300"></canvas>
                </div>

                <!-- Device Breakdown Chart -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Device Distribution</h2>
                    <canvas id="deviceChart" height="300"></canvas>
                </div>

                <!-- Demographics Chart -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">User Demographics</h2>
                    <canvas id="demographicsChart" height="300"></canvas>
                </div>
            </div>

            <!-- Conversion Funnel -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Conversion Funnel</h2>
                <div class="space-y-4">
                    @foreach($conversionFunnel as $stage)
                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-sm font-medium text-gray-700">{{ $stage['stage'] }}</span>
                            <span class="text-sm font-medium text-gray-900">
                                {{ number_format($stage['count']) }} ({{ $stage['percentage'] }}%)
                            </span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="bg-pink-600 h-2.5 rounded-full" style="width: {{ $stage['percentage'] }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Peak Usage Hours -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Peak Usage Hours</h2>
                <canvas id="peakHoursChart" height="100"></canvas>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        // Daily Active Users Chart
        const dauCtx = document.getElementById('dauChart').getContext('2d');
        new Chart(dauCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($metrics['daily_active_users']->pluck('date')) !!},
                datasets: [{
                    label: 'Active Users',
                    data: {!! json_encode($metrics['daily_active_users']->pluck('count')) !!},
                    borderColor: 'rgb(236, 72, 153)',
                    backgroundColor: 'rgba(236, 72, 153, 0.1)',
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

        // Activity Breakdown Chart
        const activityCtx = document.getElementById('activityChart').getContext('2d');
        const activityData = @json($metrics['activity_breakdown']);
        new Chart(activityCtx, {
            type: 'doughnut',
            data: {
                labels: Object.keys(activityData).map(k => k.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase())),
                datasets: [{
                    data: Object.values(activityData),
                    backgroundColor: [
                        'rgb(236, 72, 153)', 'rgb(59, 130, 246)', 'rgb(34, 197, 94)',
                        'rgb(251, 191, 36)', 'rgb(168, 85, 247)', 'rgb(239, 68, 68)',
                        'rgb(6, 182, 212)', 'rgb(245, 158, 11)'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'right' } }
            }
        });

        // Device Distribution Chart
        const deviceCtx = document.getElementById('deviceChart').getContext('2d');
        const deviceData = @json($metrics['device_breakdown']);
        new Chart(deviceCtx, {
            type: 'pie',
            data: {
                labels: Object.keys(deviceData).map(k => k.charAt(0).toUpperCase() + k.slice(1)),
                datasets: [{
                    data: Object.values(deviceData),
                    backgroundColor: ['rgb(236, 72, 153)', 'rgb(59, 130, 246)', 'rgb(34, 197, 94)']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'right' } }
            }
        });

        // Demographics Chart
        const demoCtx = document.getElementById('demographicsChart').getContext('2d');
        const demographics = @json($demographics);
        new Chart(demoCtx, {
            type: 'bar',
            data: {
                labels: Object.keys(demographics.gender || {}),
                datasets: [{
                    label: 'Gender Distribution',
                    data: Object.values(demographics.gender || {}),
                    backgroundColor: 'rgb(236, 72, 153)'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { y: { beginAtZero: true } }
            }
        });

        // Peak Hours Chart
        const peakCtx = document.getElementById('peakHoursChart').getContext('2d');
        const peakHours = @json($peakHours);
        const hours = Array.from({length: 24}, (_, i) => i);
        const hourData = hours.map(h => peakHours[h] || 0);
        new Chart(peakCtx, {
            type: 'bar',
            data: {
                labels: hours.map(h => h + ':00'),
                datasets: [{
                    label: 'Activity Count',
                    data: hourData,
                    backgroundColor: 'rgb(59, 130, 246)'
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
