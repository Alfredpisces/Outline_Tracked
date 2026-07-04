import './dll-offline';

// Register Service Worker for offline PWA support
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js').catch(() => null);
    });
}
