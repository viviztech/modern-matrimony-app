import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

/**
 * Configure Laravel Echo with Reverb WebSocket connection
 *
 * This establishes a WebSocket connection to Laravel Reverb for real-time
 * broadcasting of events like messages, typing indicators, online status, etc.
 */
// Only initialize Echo if we have the required environment variables
if (import.meta.env.VITE_REVERB_APP_KEY) {
    try {
        window.Echo = new Echo({
            broadcaster: 'reverb',
            key: import.meta.env.VITE_REVERB_APP_KEY,
            wsHost: import.meta.env.VITE_REVERB_HOST || '127.0.0.1',
            wsPort: import.meta.env.VITE_REVERB_PORT || 8080,
            wssPort: import.meta.env.VITE_REVERB_PORT || 8080,
            forceTLS: (import.meta.env.VITE_REVERB_SCHEME || 'https') === 'https',
            enabledTransports: ['ws', 'wss'],
            disableStats: true,

            // Authorization endpoint for private/presence channels
            authEndpoint: '/broadcasting/auth',

            // Include CSRF token in authorization requests
            auth: {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                },
            },

            // Connection configuration
            cluster: import.meta.env.VITE_REVERB_APP_CLUSTER || 'mt1',
            encrypted: true,

            // Reconnection settings
            enableLogging: import.meta.env.DEV,
            logToConsole: import.meta.env.DEV,
        });
    } catch (error) {
        console.warn('Echo initialization failed:', error);
        window.Echo = null;
    }
} else {
    console.log('Reverb not configured - real-time features disabled');
    window.Echo = null;
}

/**
 * Handle Echo connection events
 */
if (window.Echo && window.Echo.connector && window.Echo.connector.pusher) {
    // Connection established
    window.Echo.connector.pusher.connection.bind('connected', () => {
        console.log('WebSocket connected successfully');
        window.dispatchEvent(new CustomEvent('echo:connected'));
    });

    // Connection state change
    window.Echo.connector.pusher.connection.bind('state_change', (states) => {
        console.log('Connection state changed:', states.current);
        window.dispatchEvent(new CustomEvent('echo:state-change', {
            detail: { state: states.current, previous: states.previous }
        }));
    });

    // Connection error
    window.Echo.connector.pusher.connection.bind('error', (error) => {
        console.error('WebSocket connection error:', error);
        window.dispatchEvent(new CustomEvent('echo:error', { detail: error }));
    });

    // Connection disconnected
    window.Echo.connector.pusher.connection.bind('disconnected', () => {
        console.warn('WebSocket disconnected');
        window.dispatchEvent(new CustomEvent('echo:disconnected'));
    });
}

export default window.Echo;
