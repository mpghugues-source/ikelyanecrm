/* ═══════════════════════════════════════════════════════
   IkelyaneCRM — Service Worker avec mise à jour automatique
   ═══════════════════════════════════════════════════════ */

const CACHE_VERSION = 'ikelyanecrm-v1';
const STATIC_CACHE  = CACHE_VERSION + '-static';
const DYNAMIC_CACHE = CACHE_VERSION + '-dynamic';

// Ressources statiques à mettre en cache au premier install
const STATIC_ASSETS = [
  '/assets/css/app.css',
  '/assets/js/app.js',
  '/assets/img/icon-192.png',
  '/assets/img/icon-512.png',
  '/manifest.json',
];

// ── Install ──────────────────────────────────────────────
self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(STATIC_CACHE).then(cache => cache.addAll(STATIC_ASSETS))
  );
  // Active immédiatement le nouveau SW sans attendre la fermeture des onglets
  self.skipWaiting();
});

// ── Activate (nettoyage des anciens caches) ──────────────
self.addEventListener('activate', event => {
  event.waitUntil(
    caches.keys().then(keys =>
      Promise.all(
        keys
          .filter(k => k.startsWith('ikelyanecrm-') && k !== STATIC_CACHE && k !== DYNAMIC_CACHE)
          .map(k => caches.delete(k))
      )
    ).then(() => self.clients.claim())
  );
});

// ── Fetch (stratégie Network-First pour HTML, Cache-First pour assets) ──
self.addEventListener('fetch', event => {
  const { request } = event;
  const url = new URL(request.url);

  // Ne pas intercepter les requêtes non-GET, les API calls ou cross-origin
  if (request.method !== 'GET') return;
  if (!url.origin.includes(self.location.origin)) return;
  if (url.pathname.startsWith('/api/')) return;

  // Assets statiques → Cache-First
  if (
    url.pathname.startsWith('/assets/') ||
    url.pathname === '/manifest.json' ||
    url.pathname === '/sw.js'
  ) {
    event.respondWith(
      caches.match(request).then(cached => cached || fetchAndCache(request, STATIC_CACHE))
    );
    return;
  }

  // Pages HTML → Network-First (fraîcheur garantie, fallback cache)
  event.respondWith(
    fetch(request)
      .then(response => {
        if (response.ok) {
          const clone = response.clone();
          caches.open(DYNAMIC_CACHE).then(cache => cache.put(request, clone));
        }
        return response;
      })
      .catch(() => caches.match(request))
  );
});

function fetchAndCache(request, cacheName) {
  return fetch(request).then(response => {
    if (response.ok) {
      const clone = response.clone();
      caches.open(cacheName).then(cache => cache.put(request, clone));
    }
    return response;
  });
}

// ── Message : mise à jour forcée depuis le client ────────
self.addEventListener('message', event => {
  if (event.data && event.data.type === 'SKIP_WAITING') {
    self.skipWaiting();
  }
});
