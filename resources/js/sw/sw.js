// var CACHE_NAME = 'rc-cache-v2';
// var urlsToCache = [
//     // '/listings',
//     // '/css/app.css',
//     // '/css/fontawesome.css',
//     // '/js/app.js',
// ];
//
// self.addEventListener('install', function (event) {
// Perform install steps
// event.waitUntil(
//     caches.open(CACHE_NAME)
//         .then(function (cache) {
//             return cache.addAll(urlsToCache);
//         })
// );
// });
//
// self.addEventListener('fetch', function (event) {
// event.respondWith(
//     caches.match(event.request)
//         .then(function (response) {
//                 // Cache hit - return response
//                 if (response) {
//                     return response;
//                 }
//                 return fetch(event.request);
//             }
//         )
// );
// });
