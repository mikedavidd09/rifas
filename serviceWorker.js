self.addEventListener('push', function (event) {
  console.log('[Service Worker] Push Received.');
  const title = 'Nuevo Mensaje';
  const options = {
    body: `${event.data.text()}`,
    icon: 'images/icon.png',
  };
event.waitUntil(self.registration.showNotification(title, options));
});
