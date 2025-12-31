/**
 * Real-time messaging integration with Laravel Echo
 *
 * This module handles:
 * - Receiving new messages in real-time
 * - Showing typing indicators
 * - Updating read receipts
 * - Tracking online/offline status
 * - Playing notification sounds
 */

import Echo from './echo';

export class MessagingClient {
    constructor(conversationId, currentUserId, otherUserId) {
        this.conversationId = conversationId;
        this.currentUserId = currentUserId;
        this.otherUserId = otherUserId;
        this.typingTimeout = null;
        this.isTyping = false;
        this.notificationSound = null;
        this.soundEnabled = localStorage.getItem('message-sound-enabled') !== 'false';

        // Initialize notification sound
        this.initializeSound();

        // Subscribe to channels
        this.subscribeToConversation();
        this.subscribeToUserChannel();
        this.subscribeToOnlinePresence();
    }

    /**
     * Initialize notification sound
     */
    initializeSound() {
        // Create audio element for notification sound
        // You can replace this with your own sound file
        this.notificationSound = new Audio('/sounds/message-notification.mp3');
        this.notificationSound.volume = 0.5;
    }

    /**
     * Subscribe to conversation channel for messages and typing
     */
    subscribeToConversation() {
        window.Echo.private(`conversation.${this.conversationId}`)
            .listen('.message.sent', (event) => {
                this.handleNewMessage(event.message);
            })
            .listen('.message.read', (event) => {
                this.handleMessageRead(event);
            })
            .listen('.typing.started', (event) => {
                if (event.user_id !== this.currentUserId) {
                    this.handleTypingStarted(event);
                }
            })
            .listen('.typing.stopped', (event) => {
                if (event.user_id !== this.currentUserId) {
                    this.handleTypingStopped(event);
                }
            });
    }

    /**
     * Subscribe to user channel for notifications
     */
    subscribeToUserChannel() {
        window.Echo.private(`user.${this.currentUserId}`)
            .listen('.message.sent', (event) => {
                // Handle new messages from other conversations
                if (event.message.conversation_id !== this.conversationId) {
                    this.handleNotification(event.message);
                }
            })
            .listen('.match.created', (event) => {
                this.handleMatchCreated(event);
            })
            .listen('.video-call.incoming', (event) => {
                this.handleIncomingCall(event);
            });
    }

    /**
     * Subscribe to online presence channel
     */
    subscribeToOnlinePresence() {
        window.Echo.join('online')
            .here((users) => {
                // Users currently online
                this.handleOnlineUsers(users);
            })
            .joining((user) => {
                // User came online
                this.handleUserOnline(user);
            })
            .leaving((user) => {
                // User went offline
                this.handleUserOffline(user);
            });
    }

    /**
     * Handle new message received
     */
    handleNewMessage(message) {
        // Dispatch event for Alpine.js or other listeners
        window.dispatchEvent(new CustomEvent('message:received', {
            detail: { message }
        }));

        // Play notification sound if enabled
        if (this.soundEnabled && document.hidden) {
            this.playNotificationSound();
        }

        // Show browser notification if page is not visible
        if (document.hidden && 'Notification' in window && Notification.permission === 'granted') {
            new Notification(message.sender.name, {
                body: message.content.substring(0, 50) + (message.content.length > 50 ? '...' : ''),
                icon: message.sender.avatar || '/images/default-avatar.png',
                tag: `message-${message.id}`,
            });
        }
    }

    /**
     * Handle message read event
     */
    handleMessageRead(event) {
        window.dispatchEvent(new CustomEvent('message:read', {
            detail: event
        }));
    }

    /**
     * Handle typing started
     */
    handleTypingStarted(event) {
        window.dispatchEvent(new CustomEvent('typing:started', {
            detail: event
        }));
    }

    /**
     * Handle typing stopped
     */
    handleTypingStopped(event) {
        window.dispatchEvent(new CustomEvent('typing:stopped', {
            detail: event
        }));
    }

    /**
     * Send typing started event
     */
    sendTypingStarted() {
        if (!this.isTyping) {
            this.isTyping = true;
            fetch(`/messages/${this.conversationId}/typing/start`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                },
            }).catch(console.error);
        }

        // Auto-stop typing after 3 seconds of inactivity
        clearTimeout(this.typingTimeout);
        this.typingTimeout = setTimeout(() => {
            this.sendTypingStopped();
        }, 3000);
    }

    /**
     * Send typing stopped event
     */
    sendTypingStopped() {
        if (this.isTyping) {
            this.isTyping = false;
            clearTimeout(this.typingTimeout);
            fetch(`/messages/${this.conversationId}/typing/stop`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                },
            }).catch(console.error);
        }
    }

    /**
     * Handle online users list
     */
    handleOnlineUsers(users) {
        window.dispatchEvent(new CustomEvent('presence:online-users', {
            detail: { users }
        }));

        // Check if the other user in this conversation is online
        const otherUserOnline = users.some(u => u.id === this.otherUserId);
        if (otherUserOnline) {
            this.updateOnlineStatus(true);
        }
    }

    /**
     * Handle user coming online
     */
    handleUserOnline(user) {
        window.dispatchEvent(new CustomEvent('presence:user-online', {
            detail: { user }
        }));

        if (user.id === this.otherUserId) {
            this.updateOnlineStatus(true);
        }
    }

    /**
     * Handle user going offline
     */
    handleUserOffline(user) {
        window.dispatchEvent(new CustomEvent('presence:user-offline', {
            detail: { user }
        }));

        if (user.id === this.otherUserId) {
            this.updateOnlineStatus(false);
        }
    }

    /**
     * Update online status indicator
     */
    updateOnlineStatus(isOnline) {
        window.dispatchEvent(new CustomEvent('user:status-changed', {
            detail: { userId: this.otherUserId, isOnline }
        }));
    }

    /**
     * Handle notification from other conversation
     */
    handleNotification(message) {
        // Update unread count
        const unreadBadge = document.querySelector('.unread-count');
        if (unreadBadge) {
            const currentCount = parseInt(unreadBadge.textContent) || 0;
            unreadBadge.textContent = currentCount + 1;
            unreadBadge.classList.remove('hidden');
        }

        // Play sound
        if (this.soundEnabled) {
            this.playNotificationSound();
        }
    }

    /**
     * Handle new match created
     */
    handleMatchCreated(event) {
        window.dispatchEvent(new CustomEvent('match:created', {
            detail: event
        }));

        // Show notification
        if ('Notification' in window && Notification.permission === 'granted') {
            new Notification('New Match!', {
                body: `You matched with ${event.matched_user.name}`,
                icon: event.matched_user.avatar || '/images/default-avatar.png',
            });
        }
    }

    /**
     * Handle incoming video call
     */
    handleIncomingCall(event) {
        window.dispatchEvent(new CustomEvent('call:incoming', {
            detail: event
        }));
    }

    /**
     * Play notification sound
     */
    playNotificationSound() {
        if (this.notificationSound) {
            this.notificationSound.play().catch(() => {
                // Ignore errors (e.g., if user hasn't interacted with page)
            });
        }
    }

    /**
     * Toggle notification sound
     */
    toggleSound() {
        this.soundEnabled = !this.soundEnabled;
        localStorage.setItem('message-sound-enabled', this.soundEnabled.toString());
        return this.soundEnabled;
    }

    /**
     * Mark conversation as read
     */
    markAsRead() {
        fetch(`/messages/${this.conversationId}/read`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
            },
        }).catch(console.error);
    }

    /**
     * Disconnect from channels
     */
    disconnect() {
        window.Echo.leave(`conversation.${this.conversationId}`);
        window.Echo.leave(`user.${this.currentUserId}`);
        window.Echo.leave('online');
    }
}

/**
 * Request notification permission
 */
export function requestNotificationPermission() {
    if ('Notification' in window && Notification.permission === 'default') {
        Notification.requestPermission();
    }
}

/**
 * Create a messaging client instance
 */
export function createMessagingClient(conversationId, currentUserId, otherUserId) {
    return new MessagingClient(conversationId, currentUserId, otherUserId);
}

// Export for global access
window.MessagingClient = MessagingClient;
window.createMessagingClient = createMessagingClient;
window.requestNotificationPermission = requestNotificationPermission;
