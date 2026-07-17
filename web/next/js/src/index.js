// web/next/js/src/index.js
// Alpine-based behavior for the next UI. Kept dependency-light: Alpine + FA only.

import alpineInit from '../../../js/src/alpineInit.js';

document.addEventListener('alpine:init', () => {
	alpineInit();

	Alpine.store('ui', {
		navOpen: false,
		theme: document.documentElement.dataset.theme || 'dark',
		toggleNav() {
			this.navOpen = !this.navOpen;
			document.querySelector('.app-shell')?.setAttribute('data-nav-open', String(this.navOpen));
		},
		closeNav() {
			this.navOpen = false;
			document.querySelector('.app-shell')?.setAttribute('data-nav-open', 'false');
		},
		toggleTheme() {
			this.theme = this.theme === 'dark' ? 'light' : 'dark';
			document.documentElement.dataset.theme = this.theme;
		},
	});

	Alpine.data('logFeed', () => ({
		logs: [],
		init() {
			// Hook for live log polling; populated by the page view-model.
			if (window.__nextLogs) {
				this.logs = window.__nextLogs;
			}
		},
	}));
});

window.addEventListener('DOMContentLoaded', () => {
	// Close nav drawer on Escape (a11y)
	document.addEventListener('keydown', (e) => {
		if (e.key === 'Escape') Alpine.store('ui').closeNav();
	});
});
