// resources/js/app.js

import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT,
    forceTLS: false,
    enabledTransports: ['ws'],
});

let onlineUsers = [];

window.Echo.join('online-users')
    .here((users) => {
        onlineUsers = users;
        updateUserStatus();
    })
    .joining((user) => {
        onlineUsers.push(user);
        updateUserStatus();
    })
    .leaving((user) => {
        onlineUsers = onlineUsers.filter(u => u.id !== user.id);
        updateUserStatus();
    });

function updateUserStatus() {
    $('.status-dot').removeClass('online').addClass('offline');

    onlineUsers.forEach(user => {
        $('#status-' + user.id)
            .removeClass('offline')
            .addClass('online');
    });
}

console.log("Echo Loaded....... ");
