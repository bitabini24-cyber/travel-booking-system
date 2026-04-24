/**
 * TravelLux Theme Switcher
 * Themes: light, dark, neon, ocean, sunset
 */

const THEMES = [
    { id: 'light',  icon: '☀️',  name: 'Light',  desc: 'Clean & bright',      swatch: 'swatch-light'  },
    { id: 'dark',   icon: '🌙',  name: 'Dark',   desc: 'Easy on the eyes',    swatch: 'swatch-dark'   },
    { id: 'neon',   icon: '⚡',  name: 'Neon',   desc: 'Cyberpunk vibes',     swatch: 'swatch-neon'   },
    { id: 'ocean',  icon: '🌊',  name: 'Ocean',  desc: 'Deep sea calm',       swatch: 'swatch-ocean'  },
    { id: 'sunset', icon: '🌅',  name: 'Sunset', desc: 'Warm golden hour',    swatch: 'swatch-sunset' },
];

const STORAGE_KEY = 'travellux_theme';

function getTheme() {
    return localStorage.getItem(STORAGE_KEY) || 'dark';
}

function applyTheme(id) {
    document.documentElement.setAttribute('data-theme', id);
    localStorage.setItem(STORAGE_KEY, id);

    // Update button icon
    const btn = document.getElementById('themeBtn');
    if (btn) {
        const t = THEMES.find(t => t.id === id);
        btn.querySelector('.theme-icon').textContent = t?.icon || '☀️';
        btn.querySelector('.theme-label').textContent = t?.name || 'Theme';
    }

    // Update active state in dropdown
    document.querySelectorAll('.theme-option').forEach(opt => {
        opt.classList.toggle('active', opt.dataset.theme === id);
    });
}

function buildThemeSwitcher() {
    const container = document.getElementById('themeSwitcher');
    if (!container) return;

    const current = getTheme();

    const currentTheme = THEMES.find(t => t.id === current) || THEMES[0];

    container.innerHTML = `
        <div class="theme-switcher">
            <button class="theme-btn" id="themeBtn" aria-label="Switch theme" aria-expanded="false">
                <span class="theme-icon">${currentTheme.icon}</span>
                <span class="theme-label">${currentTheme.name}</span>
                <span style="font-size:0.65rem; opacity:0.6;">▼</span>
            </button>
            <div class="theme-dropdown" id="themeDropdown" role="menu">
                <div class="theme-dropdown-title">Choose Theme</div>
                ${THEMES.map(t => `
                    <div class="theme-option ${t.id === current ? 'active' : ''}"
                         data-theme="${t.id}" role="menuitem" tabindex="0">
                        <div class="theme-option-swatch ${t.swatch}"></div>
                        <div class="theme-option-info">
                            <span class="theme-option-name">${t.icon} ${t.name}</span>
                            <span class="theme-option-desc">${t.desc}</span>
                        </div>
                        <span class="theme-option-check">✓</span>
                    </div>
                `).join('')}
            </div>
        </div>
    `;

    const btn      = document.getElementById('themeBtn');
    const dropdown = document.getElementById('themeDropdown');

    // Toggle dropdown
    btn.addEventListener('click', (e) => {
        e.stopPropagation();
        const isOpen = dropdown.classList.toggle('open');
        btn.setAttribute('aria-expanded', isOpen);
    });

    // Select theme
    dropdown.querySelectorAll('.theme-option').forEach(opt => {
        opt.addEventListener('click', () => {
            applyTheme(opt.dataset.theme);
            dropdown.classList.remove('open');
            btn.setAttribute('aria-expanded', 'false');
        });
        // Keyboard support
        opt.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                applyTheme(opt.dataset.theme);
                dropdown.classList.remove('open');
            }
        });
    });

    // Close on outside click
    document.addEventListener('click', () => {
        dropdown.classList.remove('open');
        btn.setAttribute('aria-expanded', 'false');
    });

    // Close on Escape
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            dropdown.classList.remove('open');
            btn.setAttribute('aria-expanded', 'false');
        }
    });
}

// Apply saved theme immediately (before DOM ready to avoid flash)
(function() {
    const saved = localStorage.getItem(STORAGE_KEY) || 'dark';
    document.documentElement.setAttribute('data-theme', saved);
})();

document.addEventListener('DOMContentLoaded', () => {
    buildThemeSwitcher();
    applyTheme(getTheme()); // Sync UI state
});
