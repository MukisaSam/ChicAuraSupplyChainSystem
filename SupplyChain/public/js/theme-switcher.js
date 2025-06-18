// Theme Switcher Utility
class ThemeSwitcher {
    constructor() {
        this.theme = localStorage.getItem('theme') || 'light';
        this.init();
    }

    init() {
        // Apply saved theme on page load
        this.applyTheme();
        
        // Add event listeners to theme toggle buttons
        document.addEventListener('DOMContentLoaded', () => {
            const themeToggles = document.querySelectorAll('[data-theme-toggle]');
            themeToggles.forEach(toggle => {
                toggle.addEventListener('click', () => this.toggleTheme());
            });
        });
    }

    toggleTheme() {
        this.theme = this.theme === 'light' ? 'dark' : 'light';
        localStorage.setItem('theme', this.theme);
        this.applyTheme();
        this.updateToggleIcons();
    }

    applyTheme() {
        const root = document.documentElement;
        
        if (this.theme === 'dark') {
            root.classList.add('dark');
            root.setAttribute('data-theme', 'dark');
        } else {
            root.classList.remove('dark');
            root.setAttribute('data-theme', 'light');
        }
    }

    updateToggleIcons() {
        const toggles = document.querySelectorAll('[data-theme-toggle]');
        toggles.forEach(toggle => {
            const icon = toggle.querySelector('i');
            if (icon) {
                if (this.theme === 'dark') {
                    icon.className = 'fas fa-sun text-lg';
                    toggle.title = 'Switch to Light Mode';
                } else {
                    icon.className = 'fas fa-moon text-lg';
                    toggle.title = 'Switch to Dark Mode';
                }
            }
        });
    }

    getCurrentTheme() {
        return this.theme;
    }
}

// Initialize theme switcher
window.themeSwitcher = new ThemeSwitcher(); 