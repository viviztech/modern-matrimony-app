@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Call History</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">View your past video and audio calls</p>
            </div>

            <!-- Filter Options -->
            <div class="flex gap-2">
                <select id="callTypeFilter" class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                    <option value="">All Types</option>
                    <option value="video">Video</option>
                    <option value="audio">Audio</option>
                </select>

                <select id="statusFilter" class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                    <option value="">All Status</option>
                    <option value="ended">Completed</option>
                    <option value="missed">Missed</option>
                    <option value="declined">Declined</option>
                </select>
            </div>
        </div>

        <!-- Call Stats Summary -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-lg">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Total Calls</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white" id="totalCalls">{{ $calls->total() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Total Duration</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white" id="totalDuration">-</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-purple-100 dark:bg-purple-900/30 rounded-lg">
                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Video Calls</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white" id="videoCalls">-</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-orange-100 dark:bg-orange-900/30 rounded-lg">
                        <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Audio Calls</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white" id="audioCalls">-</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Call History List -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
            @forelse($calls as $call)
                <div class="border-b border-gray-200 dark:border-gray-700 p-6 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                    <div class="flex items-center justify-between">
                        <!-- User Info -->
                        <div class="flex items-center gap-4 flex-1">
                            @if($call->other_user->primaryPhoto)
                                <img src="{{ $call->other_user->primaryPhoto->url }}"
                                     alt="{{ $call->other_user->name }}"
                                     class="w-14 h-14 rounded-full object-cover border-2 border-gray-200 dark:border-gray-600">
                            @else
                                <div class="w-14 h-14 rounded-full bg-gradient-to-br from-primary to-secondary flex items-center justify-center text-white font-bold text-lg">
                                    {{ substr($call->other_user->name, 0, 1) }}
                                </div>
                            @endif

                            <div class="flex-1">
                                <div class="flex items-center gap-2">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                        {{ $call->other_user->name }}
                                    </h3>

                                    <!-- Call Type Badge -->
                                    @if($call->call_type === 'video')
                                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-300 text-xs font-medium rounded">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                            </svg>
                                            Video
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-orange-100 dark:bg-orange-900/30 text-orange-800 dark:text-orange-300 text-xs font-medium rounded">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                            </svg>
                                            Audio
                                        </span>
                                    @endif

                                    <!-- Direction Badge -->
                                    @if($call->is_caller)
                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                            <svg class="w-3 h-3 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                            </svg>
                                            Outgoing
                                        </span>
                                    @else
                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                            <svg class="w-3 h-3 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18" />
                                            </svg>
                                            Incoming
                                        </span>
                                    @endif
                                </div>

                                <div class="flex items-center gap-4 mt-1 text-sm text-gray-600 dark:text-gray-400">
                                    <span>{{ $call->created_at->diffForHumans() }}</span>

                                    @if($call->duration)
                                        <span>{{ gmdate('H:i:s', $call->duration) }}</span>
                                    @endif

                                    <!-- Status Badge -->
                                    @switch($call->status)
                                        @case('ended')
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 text-xs font-medium rounded">
                                                Completed
                                            </span>
                                            @break
                                        @case('missed')
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300 text-xs font-medium rounded">
                                                Missed
                                            </span>
                                            @break
                                        @case('declined')
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300 text-xs font-medium rounded">
                                                Declined
                                            </span>
                                            @break
                                        @default
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300 text-xs font-medium rounded">
                                                {{ ucfirst($call->status) }}
                                            </span>
                                    @endswitch
                                </div>
                            </div>
                        </div>

                        <!-- Call Again Button -->
                        <div class="flex items-center gap-2">
                            <a href="{{ route('messages.create', $call->other_user) }}" class="p-2 text-gray-600 dark:text-gray-400 hover:text-primary dark:hover:text-primary transition-colors" title="Message">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                            </a>

                            <button onclick="initiateCall('{{ $call->call_type }}', {{ $call->other_user->id }})" class="p-2 text-primary hover:text-primary-dark transition-colors" title="Call Again">
                                @if($call->call_type === 'video')
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                    </svg>
                                @else
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                @endif
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center">
                    <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                    <p class="text-gray-600 dark:text-gray-400">No call history yet</p>
                    <p class="text-sm text-gray-500 dark:text-gray-500 mt-1">Start connecting with your matches!</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($calls->hasPages())
            <div class="mt-6">
                {{ $calls->links() }}
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    // Fetch and display stats
    async function loadStats() {
        try {
            const response = await fetch('/video-calls/stats');
            const stats = await response.json();

            document.getElementById('totalCalls').textContent = stats.total_calls || 0;
            document.getElementById('totalDuration').textContent = formatDuration(stats.total_duration || 0);
            document.getElementById('videoCalls').textContent = stats.video_calls || 0;
            document.getElementById('audioCalls').textContent = stats.audio_calls || 0;
        } catch (error) {
            console.error('Error loading stats:', error);
        }
    }

    function formatDuration(seconds) {
        if (seconds < 60) return `${seconds}s`;
        if (seconds < 3600) return `${Math.floor(seconds / 60)}m`;
        return `${Math.floor(seconds / 3600)}h ${Math.floor((seconds % 3600) / 60)}m`;
    }

    // Call initiation
    async function initiateCall(callType, userId) {
        try {
            const response = await fetch(`/video-calls/initiate/${userId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ call_type: callType })
            });

            const data = await response.json();

            if (data.success) {
                window.location.href = `/video-call/${data.call.id}`;
            } else {
                alert(data.message || 'Unable to initiate call');
            }
        } catch (error) {
            console.error('Error initiating call:', error);
            alert('Failed to initiate call. Please try again.');
        }
    }

    // Filter functionality
    document.getElementById('callTypeFilter').addEventListener('change', (e) => {
        const urlParams = new URLSearchParams(window.location.search);
        if (e.target.value) {
            urlParams.set('call_type', e.target.value);
        } else {
            urlParams.delete('call_type');
        }
        window.location.search = urlParams.toString();
    });

    document.getElementById('statusFilter').addEventListener('change', (e) => {
        const urlParams = new URLSearchParams(window.location.search);
        if (e.target.value) {
            urlParams.set('status', e.target.value);
        } else {
            urlParams.delete('status');
        }
        window.location.search = urlParams.toString();
    });

    // Load stats on page load
    loadStats();
</script>
@endpush
@endsection
