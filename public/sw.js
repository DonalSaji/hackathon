const preLoad = function () {
    return caches.open("offline").then(function (cache) {
        // caching index and important routes
        return cache.addAll(filesToCache);
    });
};

self.addEventListener("install", function (event) {
    event.waitUntil(preLoad());
});

const filesToCache = [
    '/',
    '/offline.html'
];

const checkResponse = function (request) {
    return new Promise(function (fulfill, reject) {
        fetch(request).then(function (response) {
            if (response.status !== 404) {
                fulfill(response);
            } else {
                reject();
            }
        }, reject);
    });
};

const addToCache = function (request) {
    // Only cache http(s) requests
    if (!request.url.startsWith('http')) {
        return Promise.resolve();
    }
    return caches.open("offline").then(function (cache) {
        return fetch(request).then(function (response) {
            return cache.put(request, response);
        });
    });
};


const returnFromCache = function (request) {
    return caches.open("offline").then(function (cache) {
        return cache.match(request).then(function (matching) {
            if (!matching || matching.status === 404) {
                return cache.match("offline.html");
            } else {
                return matching;
            }
        });
    });
};

self.addEventListener("fetch", function (event) {
    event.respondWith(checkResponse(event.request).catch(function () {
        return returnFromCache(event.request);
    }));
    if(!event.request.url.startsWith('http')){
        event.waitUntil(addToCache(event.request));
    }
});

//  public/sw.js
self.addEventListener("push", (event) => {
    if (!event.data) return;

    const payload = event.data.json();
    const n = payload.notification || payload;

    event.waitUntil(
        self.registration.showNotification(n.title, {
            body: n.body,
            icon: n.icon,
            data: n.data
        })
    );
});

self.addEventListener("notificationclick", function (event) {
    event.notification.close();

    let targetUrl = event.notification.data?.url || "/dashboard";

    // ✅ Ensure it's an absolute URL
    const absoluteUrl = new URL(targetUrl, self.location.origin).href;

    event.waitUntil(
        clients
            .matchAll({ type: "window", includeUncontrolled: true })
            .then((clientList) => {
                for (const client of clientList) {
                    if (client.url === absoluteUrl && "focus" in client) {
                        return client.focus();
                    }
                }
                if (clients.openWindow) {
                    return clients.openWindow(absoluteUrl);
                }
            })
    );
});
