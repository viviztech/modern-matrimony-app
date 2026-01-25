import './bootstrap';

// Import Tailwind CSS
import '../css/app.css';

import Alpine from 'alpinejs';

// Cookie Consent Component
Alpine.data('cookieConsent', () => ({
    hasConsent: false,
    showSettings: false,
    preferences: {
        essential: true,
        functional: true,
        analytics: true,
        marketing: false
    },

    init() {
        const consent = this.getCookie('cookie_consent');
        this.hasConsent = consent !== null;

        if (consent) {
            try {
                const saved = JSON.parse(consent);
                this.preferences = { ...this.preferences, ...saved };
            } catch (e) {
                console.error('Error parsing cookie preferences:', e);
            }
        }

        if (this.hasConsent) {
            this.applyPreferences();
        }
    },

    toggleSettings() {
        this.showSettings = !this.showSettings;
    },

    acceptAll() {
        this.preferences = { essential: true, functional: true, analytics: true, marketing: true };
        this.saveConsent();
    },

    rejectAll() {
        this.preferences = { essential: true, functional: false, analytics: false, marketing: false };
        this.saveConsent();
    },

    acceptSelected() {
        this.saveConsent();
    },

    saveConsent() {
        this.setCookie('cookie_consent', JSON.stringify(this.preferences), 365);
        this.hasConsent = true;
        this.showSettings = false;
        this.applyPreferences();
        this.sendConsentToBackend();
    },

    applyPreferences() {
        if (this.preferences.analytics) {
            console.log('Analytics enabled');
        }
        if (this.preferences.marketing) {
            console.log('Marketing cookies enabled');
        }
    },

    sendConsentToBackend() {
        fetch('/api/cookie-consent', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            },
            body: JSON.stringify(this.preferences)
        }).catch(err => console.error('Error saving consent:', err));
    },

    setCookie(name, value, days) {
        const expires = new Date();
        expires.setTime(expires.getTime() + (days * 24 * 60 * 60 * 1000));
        document.cookie = `${name}=${value};expires=${expires.toUTCString()};path=/;SameSite=Lax`;
    },

    getCookie(name) {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${name}=`);
        if (parts.length === 2) {
            return parts.pop().split(';').shift();
        }
        return null;
    }
}));

// Initialize dark mode store
Alpine.store('darkMode', {
    init() {
        this._value = localStorage.getItem('darkMode') === 'true' || 
            (!localStorage.getItem('darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches);
        if (this._value) {
            document.documentElement.classList.add('dark');
        }
    },
    
    get value() {
        return this._value ?? false;
    },
    
    set value(val) {
        this._value = val;
        localStorage.setItem('darkMode', val);
        if (val) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    },
    
    toggle() {
        this.value = !this.value;
    }
});

window.Alpine = Alpine;

Alpine.start();

// Import messaging module for real-time features
import './messaging';
