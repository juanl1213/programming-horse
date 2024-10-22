import './bootstrap';
import './dark-mode';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// DARK MODE TOGGLE BUTTON
document.addEventListener('DOMContentLoaded', function () {
    const darkModeToggle = document.getElementById('dark_mode');
    if (darkModeToggle) {
        darkModeToggle.addEventListener('change', function () {
            document.documentElement.classList.toggle('dark', this.checked);
        });
    }
});
