import './bootstrap';

// Theme management: cycles system, light, dark. Persisted to localStorage.
// Applies data-theme="light" (light) or data-theme="black" (dark) on <html>.
(function () {
    var CYCLE = ['system', 'light', 'dark'];
    var systemListener = null;

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
            var prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            document.documentElement.setAttribute('data-theme', prefersDark ? 'black' : 'light');
            systemListener = function (e) {
                document.documentElement.setAttribute('data-theme', e.matches ? 'black' : 'light');
            };
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', systemListener);
        }
    }

    function updateToggleIcon(mode) {
        var btn = document.getElementById('theme-toggle');
        if (!btn) return;
        btn.querySelectorAll('[data-theme-icon]').forEach(function (el) {
            el.classList.toggle('hidden', el.dataset.themeIcon !== mode);
        });
    }

    function cycleTheme() {
        var current = localStorage.getItem('colorMode') || 'system';
        var next = CYCLE[(CYCLE.indexOf(current) + 1) % CYCLE.length];
        localStorage.setItem('colorMode', next);
        applyTheme(next);
        updateToggleIcon(next);
    }

    function initTheme() {
        var mode = localStorage.getItem('colorMode') || 'system';
        // Theme is already applied by inline FOUC script; re-apply here to set system listener if needed
        applyTheme(mode);
        updateToggleIcon(mode);
        var btn = document.getElementById('theme-toggle');
        if (btn) {
            btn.addEventListener('click', cycleTheme);
        }
    }

    document.addEventListener('DOMContentLoaded', initTheme);
})();
