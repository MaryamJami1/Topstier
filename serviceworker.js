console.log('this service worker is working');
var staticCacheName = "the-shop-pwa-" + new Date().getTime();
var filesToCache = [
    '/public/web-assets/img/icons/icon-72x72.png',
    '/public/web-assets/img/icons/icon-96x96.png',
    '/public/web-assets/img/icons/icon-128x128.png',
    '/public/web-assets/img/icons/icon-144x144.png',
    '/public/web-assets/img/icons/icon-152x152.png',
    '/public/web-assets/img/icons/icon-192x192.png',
    '/public/web-assets/img/icons/icon-384x384.png',
    '/public/web-assets/img/icons/icon-512x512.png',
];

// Cache on install
self.addEventListener("install", event => {
    self.skipWaiting();
    event.waitUntil(
        caches.open(staticCacheName)
            .then(cache => {
                return cache.addAll(filesToCache);
            })
    )
});

// Clear cache on activate
self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames
                    .filter(cacheName => (cacheName.startsWith("the-shop-pwa-")))
                    .filter(cacheName => (cacheName !== staticCacheName))
                    .map(cacheName => caches.delete(cacheName))
            );
        })
    );
});

// Serve from Cache - only intercept GET requests
self.addEventListener("fetch", event => {
    // Skip non-GET requests (POST, PUT, DELETE, etc.) - let them pass through directly
    if (event.request.method !== 'GET') {
        return;
    }

    // Bug fix for Chrome: skip only-if-cached requests that are not same-origin
    if (event.request.cache === 'only-if-cached' && event.request.mode !== 'same-origin') {
        return;
    }

    // Skip non-http requests (like chrome-extension://)
    if (!event.request.url.startsWith('http')) {
        return;
    }

    event.respondWith(
        caches.match(event.request)
            .then(response => {
                return response || fetch(event.request);
            })
            .catch(() => {
                // Return a proper offline response instead of undefined
                return new Response('Offline', {
                    status: 503,
                    statusText: 'Service Unavailable',
                    headers: new Headers({ 'Content-Type': 'text/plain' }),
                });
            })
    )
});
