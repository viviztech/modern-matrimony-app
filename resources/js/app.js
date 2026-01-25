import './bootstrap';

// Import Tailwind CSS
import '../css/app.css';

import Alpine from 'alpinejs';

// Initialize dark mode store
Alpine.store('darkMode', {
    init() {
        this.value = localStorage.getItem('darkMode') === 'true' || 
            (!localStorage.getItem('darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches);
    },
    
    get value() {
        return this._value;
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

// Initialize dark mode on page load
document.addEventListener('DOMContentLoaded', () => {
    const isDark = Alpine.store('darkMode').value;
    if (isDark) {
        document.documentElement.classList.add('dark');
    }
});

window.Alpine = Alpine;

Alpine.start();

// Import messaging module for real-time features
import './messaging';
