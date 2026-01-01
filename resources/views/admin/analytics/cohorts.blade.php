<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Cohort Analysis</h1>
                <p class="mt-2 text-gray-600">Track user retention over time by signup cohort</p>
            </div>

            <!-- Cohort Date Selector -->
            <div class="mb-6 bg-white rounded-lg shadow-md p-4">
                <form method="GET" action="{{ route('admin.analytics.cohorts') }}" class="flex gap-4 items-end">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Cohort Date</label>
                        <input type="date" name="cohort_date" value="{{ $cohortDate }}"
                               class="rounded-lg border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500">
                    </div>
                    <button type="submit" class="bg-pink-600 text-white px-6 py-2 rounded-lg hover:bg-pink-700 transition">
                        Analyze
                    </button>
                </form>
            </div>

            <!-- Cohort Summary -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Cohort Date</h3>
                    <p class="text-2xl font-bold text-gray-900">{{ \Carbon\Carbon::parse($cohortDate)->format('M d, Y') }}</p>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Cohort Size</h3>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($cohortData['cohort_size']) }}</p>
                    <p class="text-xs text-gray-500 mt-1">Users signed up on this date</p>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Current Retention</h3>
                    @php
                        $lastWeek = end($cohortData['retention']);
                    @endphp
                    <p class="text-2xl font-bold text-gray-900">{{ $lastWeek['retention_rate'] ?? 0 }}%</p>
                    <p class="text-xs text-gray-500 mt-1">Week {{ $lastWeek['week'] ?? 0 }} retention</p>
                </div>
            </div>

            <!-- Retention Chart -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Retention Curve</h2>
                <canvas id="retentionChart" height="100"></canvas>
            </div>

            <!-- Retention Table -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Weekly Retention Breakdown</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Week</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Active Users</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Retention Rate</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Drop-off</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Retention Bar</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($cohortData['retention'] as $index => $week)
                            <tr class="{{ $week['retention_rate'] < 20 ? 'bg-red-50' : ($week['retention_rate'] < 50 ? 'bg-yellow-50' : 'bg-green-50') }}">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    Week {{ $week['week'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ number_format($week['active_users']) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                    {{ $week['retention_rate'] }}%
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    @if($index > 0)
                                        @php
                                            $prevRate = $cohortData['retention'][$index - 1]['retention_rate'];
                                            $dropOff = $prevRate - $week['retention_rate'];
                                        @endphp
                                        <span class="text-red-600">-{{ number_format($dropOff, 1) }}%</span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="w-full bg-gray-200 rounded-full h-4">
                                        <div class="h-4 rounded-full {{ $week['retention_rate'] < 20 ? 'bg-red-600' : ($week['retention_rate'] < 50 ? 'bg-yellow-500' : 'bg-green-600') }}"
                                             style="width: {{ $week['retention_rate'] }}%"></div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            @if($cohortData['cohort_size'] == 0)
            <div class="mt-8 bg-yellow-50 border-l-4 border-yellow-400 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            No users signed up on this date. Try selecting a different cohort date.
                        </p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        const retentionCtx = document.getElementById('retentionChart').getContext('2d');
        const retentionData = @json($cohortData['retention']);

        new Chart(retentionCtx, {
            type: 'line',
            data: {
                labels: retentionData.map(w => 'Week ' + w.week),
                datasets: [{
                    label: 'Retention Rate (%)',
                    data: retentionData.map(w => w.retention_rate),
                    borderColor: 'rgb(236, 72, 153)',
                    backgroundColor: 'rgba(236, 72, 153, 0.1)',
                    tension: 0.4,
                    fill: true
                }, {
                    label: 'Active Users',
                    data: retentionData.map(w => w.active_users),
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true,
                    yAxisID: 'y1'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: {
                            display: true,
                            text: 'Retention Rate (%)'
                        },
                        min: 0,
                        max: 100
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Active Users'
                        },
                        grid: {
                            drawOnChartArea: false,
                        },
                    }
                }
            }
        });
    </script>
    @endpush
</x-app-layout>
