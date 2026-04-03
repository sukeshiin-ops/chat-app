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

        /* Sidebar Scroll */
        ul::-webkit-scrollbar {
            width: 5px;
        }

        ul::-webkit-scrollbar-thumb {
            background: #ccc;
            border-radius: 10px;
        }

        /* Hover effect */
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

        /* Search bar hover effect */
        .input-group input:focus {
            outline: none;
            box-shadow: inset 0 2px 6px rgba(0, 0, 0, 0.15);
            background-color: #f7f7f7;
        }

        /* Smooth icon alignment */
        .input-group-text i {
            font-size: 1rem;
        }

        /* Optional: sticky shadow effect */
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
                        {{-- <div class="p-3 border-bottom chat-header d-flex align-items-center d-none"
                        style="background-color: #075E54;"> --}}

                        <img src="https://mdbcdn.b-cdn.net/img/Photos/Avatars/avatar-8.webp"
                            class="header-avatar rounded-circle me-3" width="50" height="50">
                        <strong class="header-name text-white">Brad Pitt</strong>
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


                        let html = `
                            <div class="d-flex flex-column align-items-end mb-2">
                            <div class="d-flex align-items-center">
                            <div class="p-2 bg-success text-white rounded">${data.message}</div>
                            <img src="/storage/${data.sender.profile_img}" class="chat-img ms-2">
                            </div>
                            <small class="text-muted">${data.created_at}</small></div>`;


                        $('#chatBox').append(html);
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
                                <div class="d-flex flex-column align-items-end mb-2">
                                <div class="d-flex align-items-center">
                                <div class="p-2 bg-success text-white rounded">${msg.message}</div>
                                 <img src="/storage/${msg.sender.profile_img}" class="chat-img ms-2">
                                </div>
                                <small class="text-muted">${time}</small>
                                </div>
                                ` : `
                                <div class="d-flex flex-column align-items-start mb-2">
                                <div class="d-flex align-items-center">
                                <img src="/storage/${msg.sender.profile_img}" class="chat-img me-2">
                                <div class="p-2 bg-light rounded">${msg.message}</div>
                                </div>
                                <small class="text-muted ms-5">${time}</small>
                                </div>`;

                            $('#chatBox').append(html);
                        });

                        $('#chatBox').scrollTop($('#chatBox')[0].scrollHeight);
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
                    })
                    .joining((user) => {
                        console.log("User joined:", user);
                    })
                    .leaving((user) => {
                        console.log("User left:", user);
                    })
                    .listen('.message.sent', (e) => {

                        console.log("Realtime:", e);

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
                                            <span class="count fw-bold">1</span></span>`);
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
                                <div class="d-flex flex-column align-items-start mb-2">
                                <div class="d-flex align-items-center">
                                <img src="/storage/${e.sender.profile_img || 'default.png'}" class="chat-img me-2">
                                <div class="p-2 bg-light rounded">${e.message}</div>
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
