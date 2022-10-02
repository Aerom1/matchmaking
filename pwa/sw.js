
self.addEventListener('install', function(e) {
    e.waitUntil(
        caches.open('Matchmaking1').then(function(cache) {
            return cache.addAll([
                '/',
                '/index.html',
                '/dragndrop.js',
                '/javascript.js',
                '/animation.css',
                '/style.css',
                '/rompwa.webmanifest',
                '/images/favicon.png',
                '/icons/icon-32.png', //Add any other assets your web page needs
                '/icons/icon-192.png', //Add any other assets your web page needs
                '/icons/icon-512.png' //Add any other assets your web page needs
            ]);
        })
    );
});

self.addEventListener('fetch', function(event) {
    console.log(event.request.url);
    event.respondWith(
        caches.match(event.request).then(function(response) {
            return response || fetch(event.request);
        })
    );
});