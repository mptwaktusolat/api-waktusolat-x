import './bootstrap';

// Theme management functions.
const CYCLE = ['system', 'light', 'dark'];
let systemListener = null;

function applyTheme(mode) {
    if (systemListener) {
        window.matchMedia('(prefers-color-scheme: dark)').removeEventListener('change', systemListener);
        systemListener = null;
    }
    if (mode === 'light') {
        document.documentElement.setAttribute('data-theme', 'light');
    } else if (mode === 'dark') {
        document.documentElement.setAttribute('data-theme', 'black');
    } else {
        // System: follow OS preference and listen for changes
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        document.documentElement.setAttribute('data-theme', prefersDark ? 'black' : 'light');
        systemListener = function (e) {
            document.documentElement.setAttribute('data-theme', e.matches ? 'black' : 'light');
        };
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', systemListener);
    }
}

function updateToggleIcon(mode) {
    const btn = document.getElementById('theme-toggle');
    if (!btn) return;
    btn.querySelectorAll('[data-theme-icon]').forEach(function (el) {
        el.classList.toggle('hidden', el.dataset.themeIcon !== mode);
    });
}

function cycleTheme() {
    const current = localStorage.getItem('colorMode') || 'system';
    const next = CYCLE[(CYCLE.indexOf(current) + 1) % CYCLE.length];
    localStorage.setItem('colorMode', next);
    applyTheme(next);
    updateToggleIcon(next);
}

// This function allow the page to update the theme when system OS changes the theme.
function initTheme() {
    const mode = localStorage.getItem('colorMode') || 'system';
    applyTheme(mode);
    updateToggleIcon(mode);
    const btn = document.getElementById('theme-toggle');
    if (btn) {
        btn.addEventListener('click', cycleTheme);
    }
}

document.addEventListener('DOMContentLoaded', initTheme);
