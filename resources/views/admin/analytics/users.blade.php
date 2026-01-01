<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">User Metrics</h1>
                <p class="mt-2 text-gray-600">Detailed user analytics and demographics</p>
            </div>

            <!-- Date Range Filter -->
            <div class="mb-6 bg-white rounded-lg shadow-md p-4">
                <form method="GET" action="{{ route('admin.analytics.users') }}" class="flex gap-4 items-end">
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

            <!-- User Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
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
                    @slot('title', 'Verification Rate')
                    @slot('value', $metrics['users']['verification_rate'] . '%')
                    @slot('subtitle', number_format($metrics['users']['verified']) . ' verified users')
                    @slot('bgColor', '#E0E7FF')
                    @slot('iconColor', '#6366F1')
                    @slot('icon')
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    @endslot
                @endcomponent
            </div>

            <!-- Demographics Charts -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Gender Distribution</h2>
                    <canvas id="genderChart" height="250"></canvas>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Age Distribution</h2>
                    <canvas id="ageChart" height="250"></canvas>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Religion Distribution</h2>
                    <canvas id="religionChart" height="250"></canvas>
                </div>
            </div>

            <!-- Detailed Demographics Table -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Detailed Demographics</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Value</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Count</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Percentage</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($demographics['gender'] ?? [] as $gender => $count)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Gender</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 capitalize">{{ $gender }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($count) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ round(($count / $metrics['users']['total']) * 100, 1) }}%
                                </td>
                            </tr>
                            @endforeach

                            @foreach($demographics['age'] ?? [] as $ageGroup => $count)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Age Group</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $ageGroup }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($count) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ round(($count / $metrics['users']['total']) * 100, 1) }}%
                                </td>
                            </tr>
                            @endforeach

                            @foreach($demographics['religion'] ?? [] as $religion => $count)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Religion</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 capitalize">{{ $religion }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($count) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ round(($count / $metrics['users']['total']) * 100, 1) }}%
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
        const demographics = @json($demographics);

        // Gender Chart
        const genderCtx = document.getElementById('genderChart').getContext('2d');
        new Chart(genderCtx, {
            type: 'pie',
            data: {
                labels: Object.keys(demographics.gender || {}).map(g => g.charAt(0).toUpperCase() + g.slice(1)),
                datasets: [{
                    data: Object.values(demographics.gender || {}),
                    backgroundColor: ['rgb(236, 72, 153)', 'rgb(59, 130, 246)', 'rgb(168, 85, 247)']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } }
            }
        });

        // Age Chart
        const ageCtx = document.getElementById('ageChart').getContext('2d');
        new Chart(ageCtx, {
            type: 'bar',
            data: {
                labels: Object.keys(demographics.age || {}),
                datasets: [{
                    label: 'Users',
                    data: Object.values(demographics.age || {}),
                    backgroundColor: 'rgb(59, 130, 246)'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });

        // Religion Chart
        const religionCtx = document.getElementById('religionChart').getContext('2d');
        new Chart(religionCtx, {
            type: 'doughnut',
            data: {
                labels: Object.keys(demographics.religion || {}).map(r => r.charAt(0).toUpperCase() + r.slice(1)),
                datasets: [{
                    data: Object.values(demographics.religion || {}),
                    backgroundColor: [
                        'rgb(236, 72, 153)', 'rgb(59, 130, 246)', 'rgb(34, 197, 94)',
                        'rgb(251, 191, 36)', 'rgb(168, 85, 247)', 'rgb(239, 68, 68)'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } }
            }
        });
    </script>
    @endpush
</x-app-layout>
