/*let swRegistration = null;
if ('serviceWorker' in navigator && 'PushManager' in window) {
  
  console.log('Service Worker and Push is supported');
  navigator.serviceWorker.register('serviceWorker.js')
    .then((swReg) => {
       console.log('Service Worker is registered', swReg);
       swRegistration = swReg;
       initialiseUI();
    }).catch(function (error) {
       console.error('Service Worker Error', error);
    });
} else {
  console.warn('Push messaging is not supported');
}

function initialiseUI() {
  swRegistration.pushManager.getSubscription()
    .then((subscription) => {
      
   //  updateDetails(subscription);
      
     if (subscription) {
       console.log('User IS subscribed.');
     } else {
       subscribeUser();
     }
   });
}

function subscribeUser() { 
  const applicationServerKey = urlB64ToUint8Array(applicationServerPublicKey);
  swRegistration.pushManager.subscribe({
     userVisibleOnly: true,
     applicationServerKey: applicationServerKey
  }).then((subscription)=> { 
     console.log('User is subscribed:', subscription);
     // TODO: Send subscription to application server
     //updateDetails(subscription);
  }).catch((err)=> {
     console.log('Failed to subscribe the user: ', err);
  });
}
const router = {
 find: (url) => router.routes.find(it => url.match(it.url)),
 routes: [
   { 
    url:'https://wolfinanciero.net/'
    }
    ]
  };

  const cacheFirst = (event) => {
 event.respondWith(
   caches.match(event.request).then((cacheResponse) => {
     return cacheResponse || fetch(event.request).then((networkResponse) => {
       return caches.open(currentCache).then((cache) => {
         cache.put(event.request, networkResponse.clone());
         return networkResponse;
       })
     })
   })
 )
};

const cacheOnly = (event) => {
 event.respondWith(caches.match(event.request));
};

const networkFirst = (event) => {
 event.respondWith(
   fetch(event.request)
     .then((networkResponse) => {
       return caches.open(currentCache).then((cache) => {
         cache.put(event.request, networkResponse.clone());
         return networkResponse;
       })
     })
     .catch(() => {
       return caches.match(event.request);
     })
 )
};
const networkOnly = (event) => {
 event.respondWith(fetch(event.request));
};

const staleWhileRevalidate = (event) => {
 event.respondWith(
   caches.match(event.request).then((cacheResponse) => {
     if (cacheResponse) {
       fetch(event.request).then((networkResponse) => {
         return caches.open(currentCache).then((cache) => {
           cache.put(event.request, networkResponse.clone());
           return networkResponse;
         })
       });
       return cacheResponse;
     } else {
       return fetch(event.request).then((networkResponse) => {
         return caches.open(currentCache).then((cache) => {
           cache.put(event.request, networkResponse.clone());
           return networkResponse;
         })
       });
     }
   })
 );
};
self.addEventListener("fetch", event => {

const found = router.find(event.request.url);

if (found) found.handle(event);

});

const currentCache = 'v1'; // ← CHANGE IT TO RESET CACHE
self.addEventListener('activate', event => {
 event.waitUntil(
   caches.keys().then(cacheNames => Promise.all(
     cacheNames
       .filter(cacheName => cacheName !== currentCache)
       .map(cacheName => caches.delete(cacheName))
   ))
 );
});
/*const subscriptionDetails =    
   document.querySelector('#subcription-details');
   console.log(subscriptionDetails);
function updateDetails(subscription) {
   if (subscription) {
     subscriptionDetails.textContent = JSON.stringify(subscription);
   } else {
      subscriptionDetails.textContent = "hola noti";
   }
}*/

/*const sendPushButton = document.querySelector('#btn-notify');
document.addEventListener("DOMContentLoaded", () => {
 sendPushButton.addEventListener('click',()=> {   
   const contentEncoding =
     (PushManager.supportedContentEncodings||['aesgcm'])[0];
    const jsonSubscription = subscription.toJSON();
    fetch('app/sendPushNotification.php', {
       method: 'POST',
       body: JSON.stringify(Object.assign(jsonSubscription, 
              {contentEncoding })),
    });
  });
});*/