import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;
//window.Pusher.logToConsole = true;

window.Echo = new Echo({
  broadcaster: 'reverb',
  key: import.meta.env.VITE_REVERB_APP_KEY,
  wsHost: import.meta.env.VITE_REVERB_HOST,
  wsPort: import.meta.env.VITE_REVERB_PORT,
  wssPort: import.meta.env.VITE_REVERB_PORT,
  wsPath: '',
  forceTLS: import.meta.env.VITE_REVERB_SCHEME === 'https',
  enabledTransports: ['ws', 'wss'],
  logToConsole: false,
  disableStats: true,
});

window.Echo.connector.pusher.connection.bind('connected', () => {
  console.log('✅ WebSocket connected via Reverb!');
});

window.Echo.connector.pusher.connection.bind('error', function (err) {
  console.error('❌ Echo connection error:', err);
});
