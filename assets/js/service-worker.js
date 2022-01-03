const cacheName = "v1"
const cacheFiles = [
  '/assets/'
]

async function  updateCache(request, response){
  let res =  await response.then(res => res)
  if (cacheFiles.map(path => request.url.endsWith(path)).some(p => p)) {
    caches.open(cacheName).then(async (cache) => {cache.put(request.url, response)})
  }
  return res
}

// self.addEventListener('notificationclose', function(e) {
//   var notification = e.notification;
//   var primaryKey = notification.data.primaryKey;
//   console.log('Closed notification: ' + primaryKey);
// })

self.addEventListener('install', event => {
  event.waitUntil(caches.open(cacheName).then(cache => {
    return cache.addAll(cacheFiles).then(() => self.skipWaiting())
  }))
})

self.addEventListener('activate', event => {
  event.waitUntil(caches.keys().then(cacheNames => {
    return Promise.all(cacheNames.map(cache => {if (cache !== cacheName) return caches.delete(cache)}))
  }))
})

self.addEventListener('fetch', event => {
  event.respondWith(
    caches.match(event.request)
    .then((response) => {
      if (response) {return response}
      self.updateCache(event.request, fetch(event.request))
      return response
    }).catch(error => {
      console.log("Serviceworker response error: ", error)
      return error
    })
  )
})