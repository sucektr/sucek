const CACHE_ADI = 'sucek-urun-ekle-v1';
const APP_KABUGU = ['/manifest-yardimci.json', '/favicon.png'];

self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_ADI).then((cache) => cache.addAll(APP_KABUGU))
  );
  self.skipWaiting();
});

self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((anahtarlar) =>
      Promise.all(anahtarlar.filter((k) => k !== CACHE_ADI).map((k) => caches.delete(k)))
    )
  );
  self.clients.claim();
});

// Form verisi/sayfalar her zaman ağdan taze gelmeli; sadece statik
// app-shell dosyaları (ikon, manifest) cache'den sunulur.
self.addEventListener('fetch', (event) => {
  if (event.request.method !== 'GET') return;

  const url = new URL(event.request.url);
  if (APP_KABUGU.includes(url.pathname)) {
    event.respondWith(
      caches.match(event.request).then((cevap) => cevap || fetch(event.request))
    );
    return;
  }

  event.respondWith(fetch(event.request).catch(() => caches.match(event.request)));
});
