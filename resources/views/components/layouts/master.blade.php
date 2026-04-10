<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Chat-App</title>
    @vite(['resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        html,
        body {
            height: 100%;

        }

        * {
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        *::-webkit-scrollbar {
            width: 0px;
            height: 0px;
        }

        li:hover {
            background: #f5f5f5;
            cursor: pointer;
        }

        div::-webkit-scrollbar {
            width: 5px;
        }

        div::-webkit-scrollbar-thumb {
            background: #bbb;
            border-radius: 10px;
        }

        .navbar {
            background: #020617 !important;
            border-bottom: 1px solid #1e293b;
        }

        .dropdown-menu {
            background: #020617;
            border: 1px solid #1e293b;
        }

        .dropdown-item {
            color: #e2e8f0;
        }

        .dropdown-item:hover {
            background: #1e293b;
            color: #fff;
        }

        .input-group input:focus {
            outline: none;
            box-shadow: inset 0 2px 6px rgba(0, 0, 0, 0.15);
            background-color: #f7f7f7;
        }

        .input-group-text i {
            font-size: 1rem;
        }

        .sticky-top {
            z-index: 10;
        }


        .header-avatar {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 50%;
        }

        .chat-img {
            width: 35px;
            height: 35px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #ddd;
        }

        .badge-animate {
            animation: pop 0.3s ease;
        }

        @keyframes pop {
            0% {
                transform: scale(0.8);
                opacity: 0.5;
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .message-bubble {
            cursor: pointer;
            transition: 0.2s;
        }

        .message-bubble:hover {
            opacity: 0.8;
        }

        .deleted-msg {
            color: #6c757d;
            font-style: italic;
        }

        .message-bubble {
            max-width: 65%;
            padding: 8px 12px;
            border-radius: 12px;
            font-size: 14px;
            line-height: 1.4;
            word-wrap: break-word;
            word-break: break-word;
            overflow-wrap: break-word;
            white-space: normal;
        }

        .message-bubble {
            overflow-wrap: anywhere;
        }

        .align-items-end .message-bubble {
            background-color: #45d279;
            color: white;
        }

        .align-items-start .message-bubble {
            background-color: #f1f0f0;
            color: black;
        }

        .message-item {
            display: flex;
            flex-direction: column;
        }

        .align-items-end {
            align-items: flex-end;
        }

        .message-bubble {
            max-width: 65%;
            display: inline-block;
        }

        .message-item {
            width: 100%;
        }

        .align-items-end .message-bubble {
            margin-left: auto;
        }
    </style>
</head>


<body>


    @if (session('success'))
        <div id="successAlert" class="alert alert-success alert-dismissible fade show">
            <strong> {{ session('success') }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let alertBox = document.getElementById('successAlert');

            if (alertBox) {
                setTimeout(() => {
                    alertBox.style.transition = "all 0.5s ease";
                    alertBox.style.opacity = "0";
                    alertBox.style.transform = "translateY(-10px)";
                }, 3000);
                setTimeout(() => {
                    alertBox.remove();
                }, 2000);
            }
        });
    </script>



    @include('components.layouts.navbar')

    <section>
        <div class="container-fluid" style="height: calc(100vh - 56px);">

            <div class="row h-100">

                <div class="col-md-4 col-lg-3 p-0 d-flex flex-column border-end">
                    <div class="card">
                        <div class="card-body p-0">

                            @include('components.layouts.sidebar')

                        </div>
                    </div>

                </div>

                <div class="col-md-8 col-lg-9 p-0 d-flex flex-column" style="border: solid 8px rgb(255, 255, 255)">
                    <div class="p-3 border-bottom chat-header d-flex align-items-center"
                        style="background-color: #075E54;">

                        <img src="/storage/{{ Auth::user()->profile_img }}" class="header-avatar rounded-circle me-3"
                            width="50" height="50">
                        <strong class="header-name text-white">Hii, {{ Auth::user()->name }}</strong>
                    </div>

                    <!-- Chat Body (SCROLL) -->
                    @include('components.layouts.body')


                    <!-- Chat Footer -->
                    @include('components.layouts.footer')

                    @yield('v-ch')

                </div>



            </div>

        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
    </script>

    <!-- jQuery (REQUIRED ) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/pusher-js"></script>



    <script>
        function getTicks(msg) {
            if (msg.read_at) {
                return `<i class="bi bi-check2-all text-primary"></i>`; // blue
            } else if (msg.delivered_at) {
                return `<i class="bi bi-check2-all text-secondary"></i>`; // double
            } else {
                return `<i class="bi bi-check"></i>`; // single
            }
        }

        let onlineUsers = [];
    </script>


    {{-- Script for Sending the Message to Controller for Store and Show sender Dashboard Message --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            console.log("JS Loaded ");

            let form = document.getElementById('chatForm');

            if (!form) {
                console.log("Form NOT FOUND ");
                return;
            }

            form.addEventListener('submit', function(e) {
                e.preventDefault();

                console.log("Form Submit Triggered ");

                let message = document.getElementById('messageInput').value;
                let receiver_id = document.getElementById('receiver_id').value;

                fetch('/send-message', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                        },
                        body: JSON.stringify({
                            message: message,
                            receiver_id: receiver_id
                        })
                    })
                    .then(res => res.json())

                    .then(data => {
                        let isOnline = onlineUsers.includes(parseInt(data.receiver_id));

                        let fakeMsg = {
                            delivered_at: isOnline ? true : null,
                            read_at: null
                        };

                        let myProfileImg = "{{ auth()->user()->profile_img }}";



                        let html = `
                                    <div id="msg-${data.id}"
                                    class="d-flex flex-column align-items-end mb-2 message-item" data-id="${data.id}">

                                    <div class="d-flex justify-content-end w-100">
                                    <div class="d-flex align-items-center" style="max-width: 65%;">
                                    <div class="p-2 bg-success text-white rounded message-bubble"> ${cleanMessage(data.message)}</div>

                                    <img src="/storage/${myProfileImg || 'default.png'}" class="chat-img ms-2"></div>
                                    </div>

                                    <div class="text-end small">
                                    ${data.created_at} ${getTicks(fakeMsg)}</div></div>`;

                        $('#chatBox').append(html);
                        $('#chatBox').scrollTop($('#chatBox')[0].scrollHeight);


                        document.getElementById('messageInput').value = '';

                        let sidebarItem = $('.userItem[data-id="' + data.receiver_id +
                            '"]');

                        if (sidebarItem.length) {

                            // last message update
                            sidebarItem.find('small').text(data.message);

                            // time update
                            sidebarItem.find('.text-muted').text('Now');

                            // move to top
                            $('#userList').prepend(sidebarItem);
                        }

                    })
                    .catch(err => console.log(err));
            });

        });
    </script>



    <script>
        let myProfileImg = "{{ auth()->user()->profile_img }}";
        $(document).on('click', '.message-bubble', function() {

            let msgId = $(this).closest('.message-item').data('id');

            if (!confirm("Delete this message?")) return;

            fetch('/delete-message/' + msgId, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'deleted') {
                        $('#msg-' + msgId).html(`<div class="text-muted fst-italic">You deleted this message
                        <img src="/storage/${myProfileImg || 'default.png'}" class="chat-img ms-2"></div>`);
                    }
                });
        });
    </script>



    {{-- for clicking the user list and open the dashboard for chat --}}
    <script>
        $(document).ready(function() {

            $('.userItem').on('click', function(e) {
                e.preventDefault();

                let userId = $(this).data('id');
                let name = $(this).data('name');
                let img = $(this).data('img');

                document.getElementById('receiver_id').value = userId;

                $('.chat-header .header-name').text(name);
                $('.chat-header .header-avatar').attr('src', img);

                console.log("Selected User ID:", userId);

            });
        });
    </script>

    <script>
        function cleanMessage(msg) {
            return msg
                .replace(/^[\s\r\n]+/, '')
                .replace(/[\s\r\n]+$/, '');
        }
    </script>

    <script>
        $(document).ready(function() {

            let myId = {{ auth()->id() }};
            let currentChannel = null;

            // CLICK USER (FIXED - delegated event)
            $(document).on('click', '.userItem', function(e) {
                e.preventDefault();

                let userId = $(this).data('id');
                let name = $(this).data('name');
                let img = $(this).data('img');

                $('#receiver_id').val(userId);

                $('.chat-header .header-name').text(name);
                $('.chat-header .header-avatar').attr('src', img);

                $('#chatBox').html('');

                // remove badge
                $(this).find('.badge').remove();

                //  mark as read
                fetch('/mark-as-read/' + userId, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    }
                });

                // load messages
                fetch('/get-messages/' + userId)
                    .then(res => res.json())
                    .then(data => {

                        data.forEach(msg => {

                            let isMe = msg.sender_id == myId;

                            let time = msg.created_at;

                            let html = isMe ? `
                                <div id="msg-${msg.id}" class="d-flex flex-column align-items-end mb-2 message-item" data-id="${msg.id}">
                                <div class="d-flex justify-content-end w-100">
                                <div class="d-flex align-items-center" style="max-width: 65%;">

                             ${
                                msg.deleted_at
                                ? `<div class="deleted-msg">You deleted this message</div>`
                                : `<div class="p-2 bg-success text-white rounded message-bubble border">${msg.message}</div>`
                                }

                            <img src="/storage/${msg.sender.profile_img}" class="chat-img ms-2"></div>
                            </div>
                            <div class="text-end small">${msg.created_at} ${getTicks(msg)}</div>

                            ` : `

                            <div id="msg-${msg.id}" class="d-flex flex-column align-items-start mb-2  message-item">
                            <div class="d-flex align-items-center" style="max-width: 55%" ;>
                            <img src="/storage/${msg.sender.profile_img}" class="chat-img me-2">
                            ${
                                msg.deleted_at
                                ? `<div class="deleted-msg">This message was deleted by sender</div>`
                                : `<div class="p-2 bg-light rounded message-bubble" style="pointer-events: none;">${msg.message}</div>`
                            }
                            </div>
                            <small class="text-muted ms-5">${msg.created_at}</small></div>`;

                            $('#chatBox').append(html);

                            $('#chatBox').scrollTop($('#chatBox')[0].scrollHeight);

                        });
                    });
            });

            //REALTIME LISTENER
            listenToUser(myId);

            function listenToUser(userId) {

                if (currentChannel) {
                    window.Echo.leave(currentChannel);
                }

                currentChannel = 'chat-channel.' + userId;

                window.Echo.join(currentChannel)
                    .here((users) => {
                        console.log("Users in channel:", users);

                        onlineUsers = users.map(u => parseInt(u.id));
                    })
                    .joining((user) => {
                        console.log("User joined:", user);
                        onlineUsers.push(parseInt(user.id));
                    })
                    .leaving((user) => {
                        console.log("User left:", user);
                        onlineUsers = onlineUsers.filter(id => id !== parseInt(user.id));
                    })

                    .listen('.message.status', (e) => {

                        let msgId = e.id;

                        let msgDiv = document.querySelector(`#msg-${msgId}`);

                        if (msgDiv) {

                            let tickContainer = msgDiv.querySelector('.text-end.small');

                            if (tickContainer) {

                                let fakeMsg = {
                                    delivered_at: e.delivered_at,
                                    read_at: e.read_at
                                };

                                let time = tickContainer.innerText.replace(/✓.*/, '').trim();
                                tickContainer.innerHTML = time + " " + getTicks(fakeMsg);
                            }
                        }
                    })

                    .listen('.message.deleted', (e) => {

                        console.log("DELETE EVENT RECEIVED:", e);

                        let msgDiv = document.getElementById(`msg-${e.id}`);

                        //  only update chat if exists (NO RETURN)
                        if (msgDiv) {
                            let bubble = msgDiv.querySelector('.message-bubble, .bg-light');

                            if (bubble) {
                                bubble.outerHTML =
                                    `
                                <div class="text-muted fst-italic deleted-msg">This message was deleted by sender</div>`;
                            }
                        }

                        //  ALWAYS update sidebar
                        let userId = (e.sender_id == myId) ? e.receiver_id : e.sender_id;

                        let sidebarItem = $('.userItem[data-id="' + userId + '"]');

                        if (sidebarItem.length && e.is_last_message) {

                            let text = (e.sender_id == myId) ?
                                'You deleted this message' :
                                'This message was deleted by sender';

                            sidebarItem.find('.last-msg').text(text);

                            sidebarItem.find('.text-muted').text('Now');

                            $('#userList').prepend(sidebarItem);
                        }

                    })

                    .listen('.message.sent', (e) => {
                        console.log("Realtime:", e);

                        let senderId = e.sender_id;

                        // delivered
                        fetch('/mark-as-delivered/' + senderId, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                            }
                        });

                        // agar chat open hai
                        if (parseInt($('#receiver_id').val()) === parseInt(senderId)) {

                        // mark read
                        fetch('/mark-as-read/' + senderId, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                                }
                        });

                        }

                        let sidebarItem = $('.userItem[data-id="' + e.sender_id + '"]');

                        if (sidebarItem.length) {

                            if ($('#receiver_id').val() != e.sender_id) {

                                // last message update
                                sidebarItem.find('small').text(e.message);

                                //  time update (simple)
                                sidebarItem.find('.text-muted').text('Now');

                                // move user to top
                                $('#userList').prepend(sidebarItem);

                                let badge = sidebarItem.find('.badge');

                                if (badge.length > 0) {
                                    let countEl = badge.find('.count');
                                    let count = parseInt(countEl.text()) || 0;
                                    countEl.text(count + 1);
                                } else {

                                    // Remove any existing badge (safety)
                                    sidebarItem.find('.badge').remove();

                                    sidebarItem.find('.text-muted').after(`
                                            <span class="badge rounded-pill bg-danger ms-2 px-3 py-1
                                            d-inline-flex align-items-center justify-content-center gap-1 shadow-sm badge-animate">
                                            <i class="bi bi-chat-dots-fill" style="font-size:16px;"></i>
                                            <span class="count fw-bold">1</span></span>`
                                    );
                                }
                            }
                        }

                        if (parseInt($('#receiver_id').val()) === parseInt(e.sender_id)) {

                            fetch('/mark-as-read/' + e.sender_id, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                                }
                            });

                            let html = `
                              <div id="msg-${e.id}" class="d-flex flex-column align-items-start mb-2 message-item">
                                <div class="d-flex align-items-center">
                                <img src="/storage/${e.sender.profile_img || 'default.png'}" class="chat-img me-2">
                              <div class="p-2 bg-light rounded message-bubble"
                                style="max-width:55%; pointer-events:none; word-break:break-word;">
                            ${cleanMessage(e.message)}
                                </div>
                                </div>
                                <small class="text-muted ms-5">${e.created_at || 'Now'}</small>
                                </div>`;

                            $('#chatBox').append(html);
                            $('#chatBox').scrollTop($('#chatBox')[0].scrollHeight);
                        }
                    });
            }

        });
    </script>


    <script>
        $(document).ready(function() {
            let myId = {{ auth()->id() }};

        });
    </script>

</body>

</html>
