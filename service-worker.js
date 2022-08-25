const cacheName = "v1"

const localCacheFiles = [
  "/assets/",
]

const remoteCacheFiles = [
  "https://cdn.onesignal.com/sdks/OneSignalSDK.js",
  "https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js",
  "https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js",
  "https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css",
  "https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js",
  "https://cdn.jsdelivr.net/npm/sweetalert2@10"
]

const denyCacheFiles = []

function isCacheFileDenied(url) {
  return denyCacheFiles.map(path => url == path || url.includes(path)).some(v => v)
}

function isCacheFileAllow(url) {
  return isCacheFileDenied(url) ? false : [
    localCacheFiles.map(path => url == path || url.includes(path)).some(v => v),
    remoteCacheFiles.map(path => url == path || url.includes(path)).some(v => v),
  ].some(v => v)
}

async function updateCache(request, response){
  if (isCacheFileAllow(request.url) && request.method.toLowerCase() != 'post') {
    caches.open(cacheName).then(cache => cache.put(request, response))
  }
}

function installAndUpdate(event){
  let localFiles = localCacheFiles.filter(path => path.includes('.'))
  event.waitUntil(caches.open(cacheName).then(cache => {
    return cache.addAll(localFiles).then(() => {
      localCacheFiles.forEach(path => { if(isCacheFileDenied(path)) cache.delete(path)})
      self.skipWaiting()
    })
  }))
}

self.addEventListener('install', event => installAndUpdate(event))

self.addEventListener('activate', event => {
  event.waitUntil(caches.keys().then(cacheNames => {
    return Promise.all(cacheNames.map(cache => {if (cache !== cacheName) return caches.delete(cache)}))
  }))
})

self.addEventListener('fetch', async (event )=> {
  event.respondWith(
    caches.match(event.request).then(async (response) => {
      return response || fetch(event.request).then((res) => {
        self.updateCache(event.request, res.clone())
        return res
      })
    })
  )
})

// Additions to your service worker code:
self.addEventListener('message', (event) => {
  if (event.data === 'update') {
    installAndUpdate(event)
  }
});