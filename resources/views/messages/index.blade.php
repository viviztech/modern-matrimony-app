@extends('layouts.app')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Messages</h1>
                <p class="text-gray-600 dark:text-gray-400">Chat with your matches</p>
            </div>
            @if($totalUnread > 0)
                <span class="bg-primary text-white px-4 py-2 rounded-full text-sm font-semibold">
                    {{ $totalUnread }} unread
                </span>
            @endif
        </div>

        @if($conversations->isEmpty())
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="w-32 h-32 mx-auto mb-6 bg-gradient-to-br from-primary/10 to-secondary/10 rounded-full flex items-center justify-center">
                    <svg class="w-16 h-16 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">No Messages Yet</h2>
                <p class="text-gray-600 dark:text-gray-400 mb-6">Start matching with people to begin conversations</p>

                <div class="flex gap-4 justify-center">
                    <a href="{{ route('discover') }}" class="bg-gradient-to-r from-primary to-secondary text-white px-6 py-3 rounded-xl hover:shadow-glow transition-all duration-200 font-semibold">
                        Discover Profiles
                    </a>
                    <a href="{{ route('matches') }}" class="bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-6 py-3 rounded-xl border-2 border-gray-200 dark:border-gray-700 hover:border-primary dark:hover:border-primary transition-all duration-200 font-semibold">
                        View Matches
                    </a>
                </div>
            </div>
        @else
            <!-- Conversations List -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden">
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($conversations as $conversation)
                        @php
                            $otherUser = $conversation->other_user;
                            $lastMessage = $conversation->lastMessage;
                            $unreadCount = $conversation->unread_count;
                        @endphp
                        <a href="{{ route('messages.show', $conversation) }}"
                           class="flex items-center gap-4 p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors {{ $unreadCount > 0 ? 'bg-primary/5 dark:bg-primary/10' : '' }}">

                            <!-- Avatar -->
                            <div class="relative flex-shrink-0">
                                @if($otherUser->primaryPhoto)
                                    <img src="{{ $otherUser->primaryPhoto->url }}"
                                         alt="{{ $otherUser->name }}"
                                         class="w-16 h-16 rounded-full object-cover border-2 {{ $unreadCount > 0 ? 'border-primary' : 'border-gray-200 dark:border-gray-700' }}">
                                @else
                                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-primary to-secondary flex items-center justify-center text-white text-xl font-bold">
                                        {{ substr($otherUser->name, 0, 1) }}
                                    </div>
                                @endif

                                @if($unreadCount > 0)
                                    <span class="absolute -top-1 -right-1 w-6 h-6 bg-primary text-white text-xs font-bold rounded-full flex items-center justify-center">
                                        {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                                    </span>
                                @endif
                            </div>

                            <!-- Message Info -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between mb-1">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white truncate">
                                        {{ $otherUser->name }}
                                        @if($otherUser->age)
                                            <span class="text-gray-500 dark:text-gray-400 font-normal">, {{ $otherUser->age }}</span>
                                        @endif
                                    </h3>
                                    @if($lastMessage)
                                        <span class="text-xs text-gray-500 dark:text-gray-400 flex-shrink-0 ml-2">
                                            {{ $lastMessage->created_at->diffForHumans(null, true) }}
                                        </span>
                                    @endif
                                </div>

                                @if($otherUser->profile)
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">
                                        {{ $otherUser->profile->occupation ?? 'Professional' }}
                                        @if($otherUser->city)
                                            · {{ $otherUser->city }}
                                        @endif
                                    </p>
                                @endif

                                @if($lastMessage)
                                    <p class="text-sm text-gray-600 dark:text-gray-300 truncate {{ $unreadCount > 0 ? 'font-semibold' : '' }}">
                                        @if($lastMessage->sender_id === auth()->id())
                                            <span class="text-gray-500 dark:text-gray-400">You:</span>
                                        @endif
                                        {{ Str::limit($lastMessage->content, 50) }}
                                    </p>
                                @else
                                    <p class="text-sm text-gray-500 dark:text-gray-400 italic">
                                        Start a conversation
                                    </p>
                                @endif
                            </div>

                            <!-- Arrow Icon -->
                            <svg class="w-5 h-5 text-gray-400 dark:text-gray-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
