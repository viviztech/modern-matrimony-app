<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Revenue Analytics</h1>
                <p class="mt-2 text-gray-600">Track subscription revenue and conversions</p>
            </div>

            <!-- Date Range Filter -->
            <div class="mb-6 bg-white rounded-lg shadow-md p-4">
                <form method="GET" action="{{ route('admin.analytics.revenue') }}" class="flex gap-4 items-end">
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

            <!-- Revenue Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                @component('admin.analytics.partials.stats-card')
                    @slot('title', 'Total Revenue')
                    @slot('value', '$' . number_format($metrics['subscriptions']['revenue'], 2))
                    @slot('subtitle', 'In selected period')
                    @slot('bgColor', '#D1FAE5')
                    @slot('iconColor', '#059669')
                    @slot('icon')
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    @endslot
                @endcomponent

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
                    @slot('bgColor', '#DBEAFE')
                    @slot('iconColor', '#3B82F6')
                    @slot('icon')
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
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

            <!-- Key Metrics Cards -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-sm font-medium text-gray-500 mb-2">ARPU (Average Revenue Per User)</h3>
                    <p class="text-3xl font-bold text-gray-900">
                        ${{ $metrics['users']['total'] > 0 ? number_format($metrics['subscriptions']['revenue'] / $metrics['users']['total'], 2) : '0.00' }}
                    </p>
                    <p class="text-xs text-gray-500 mt-1">Per user in period</p>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-sm font-medium text-gray-500 mb-2">MRR (Monthly Recurring Revenue)</h3>
                    <p class="text-3xl font-bold text-gray-900">
                        ${{ number_format($metrics['subscriptions']['revenue'] / max(1, ((strtotime($endDate) - strtotime($startDate)) / (30 * 86400))), 2) }}
                    </p>
                    <p class="text-xs text-gray-500 mt-1">Estimated monthly</p>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-sm font-medium text-gray-500 mb-2">ARR (Annual Recurring Revenue)</h3>
                    <p class="text-3xl font-bold text-gray-900">
                        ${{ number_format(($metrics['subscriptions']['revenue'] / max(1, ((strtotime($endDate) - strtotime($startDate)) / (30 * 86400)))) * 12, 2) }}
                    </p>
                    <p class="text-xs text-gray-500 mt-1">Projected annual</p>
                </div>
            </div>

            <!-- Revenue Insights -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Revenue Insights</h2>
                <div class="space-y-4">
                    <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg">
                        <div>
                            <p class="text-sm font-medium text-gray-700">Total Paying Users</p>
                            <p class="text-xs text-gray-500 mt-1">Active subscribers</p>
                        </div>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($metrics['subscriptions']['active']) }}</p>
                    </div>

                    <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg">
                        <div>
                            <p class="text-sm font-medium text-gray-700">Free Users</p>
                            <p class="text-xs text-gray-500 mt-1">Non-subscribers</p>
                        </div>
                        <p class="text-2xl font-bold text-gray-900">
                            {{ number_format($metrics['users']['total'] - $metrics['subscriptions']['active']) }}
                        </p>
                    </div>

                    <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg">
                        <div>
                            <p class="text-sm font-medium text-gray-700">Conversion Potential</p>
                            <p class="text-xs text-gray-500 mt-1">Revenue if all users subscribe at avg rate</p>
                        </div>
                        <p class="text-2xl font-bold text-green-600">
                            ${{ $metrics['subscriptions']['active'] > 0 ? number_format(($metrics['subscriptions']['revenue'] / $metrics['subscriptions']['active']) * $metrics['users']['total'], 2) : '0.00' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Subscription Trends -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Subscription Health</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <div class="flex justify-between mb-2">
                            <span class="text-sm font-medium text-gray-700">Subscriber Rate</span>
                            <span class="text-sm font-semibold text-gray-900">{{ $metrics['subscriptions']['conversion_rate'] }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="bg-green-600 h-3 rounded-full" style="width: {{ $metrics['subscriptions']['conversion_rate'] }}%"></div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Of total users</p>
                    </div>

                    <div>
                        <div class="flex justify-between mb-2">
                            <span class="text-sm font-medium text-gray-700">Free User Rate</span>
                            <span class="text-sm font-semibold text-gray-900">{{ 100 - $metrics['subscriptions']['conversion_rate'] }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="bg-gray-400 h-3 rounded-full" style="width: {{ 100 - $metrics['subscriptions']['conversion_rate'] }}%"></div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Of total users</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
