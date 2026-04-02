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

                            <!-- SEARCH BAR -->
                            {{-- @include('components.layouts.search') --}}


                            {{-- Side Bar --}}
                            {{-- @include('components.layouts.sidebar') --}}

                            @include('components.layouts.sidebar')

                        </div>
                    </div>

                </div>

                <div class="col-md-8 col-lg-9 p-0 d-flex flex-column" style="border: solid 8px rgb(255, 255, 255)">


                    <div class="p-3 border-bottom chat-header d-flex align-items-center"
                        style="background-color: #075E54;">
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

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            console.log("JS Loaded "); //  test

            let form = document.getElementById('chatForm');

            if (!form) {
                console.log("Form NOT FOUND ");
                return;
            }

            form.addEventListener('submit', function(e) {
                e.preventDefault(); // sabse important

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
                    // .then(data => {
                    //     console.log("Response:", data);

                    //     let html = `<div class="mb-3 text-end"><strong>${data.message}</strong></div>`;
                    //     document.getElementById('chatBox').innerHTML += html;

                    //     document.getElementById('messageInput').value = '';
                    // })

                    .then(data => {

                        let html = `
    <div class="d-flex justify-content-end mb-2 align-items-center">

        <div class="p-2 bg-success text-white rounded">
            ${data.message}
        </div>

        <img src="/storage/${data.sender.profile_img}"
             class="chat-img ms-2">
    </div>
    `;

                        $('#chatBox').append(html);
                        document.getElementById('messageInput').value = '';
                    })
                    .catch(err => console.log(err));
            });

        });
    </script>



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


    {{-- get Message --}}
    <script>
        $(document).ready(function() {

            $('.userItem').on('click', function(e) {
                e.preventDefault();

                let userId = $(this).data('id');
                let name = $(this).data('name');
                let img = $(this).data('img');

                // receiver set
                $('#receiver_id').val(userId);

                // header update
                $('.chat-header .header-name').text(name);
                $('.chat-header .header-avatar').attr('src', img);

                // chatBox clear
                $('#chatBox').html('');

                // messages fetch karo


                // setInterval(() => {

                //     let userId = document.getElementById('receiver_id').value;

                //     if (!userId) return;

                //     fetch('/get-messages/' + userId)
                //         .then(res => res.json())
                //         .then(data => {

                //             $('#chatBox').html('');

                //             data.forEach(msg => {

                //                 let isMe = msg.sender_id == {{ auth()->id() }};

                //                 let html = '';

                //                 if (isMe) {
                //                     html = `
            //     <div class="d-flex justify-content-end mb-2 align-items-center">
            //         <div class="p-2 bg-success text-white rounded">
            //             ${msg.message}
            //         </div>
            //         <img src="/storage/${msg.sender.profile_img}" class="chat-img ms-2">
            //     </div>`;
                //                 } else {
                //                     html = `
            //     <div class="d-flex justify-content-start mb-2 align-items-center">
            //         <img src="/storage/${msg.sender.profile_img}" class="chat-img me-2">
            //         <div class="p-2 bg-light rounded">
            //             ${msg.message}
            //         </div>
            //     </div>`;
                //                 }

                //                 $('#chatBox').append(html);
                //             });

                //         });

                // }, 1000); // har 2 sec
                fetch('/get-messages/' + userId)
                    .then(res => res.json())
                    .then(data => {

                        data.forEach(msg => {

                            let isMe = msg.sender_id == {{ auth()->id() }};

                            let html = '';

                            if (isMe) {
                                html = `
            <div class="d-flex justify-content-end mb-2 align-items-center">

                <div class="p-2 bg-success text-white rounded">
                    ${msg.message}
                </div>

                <img src="/storage/${msg.sender.profile_img}"
                     class="chat-img ms-2">
            </div>`;
                            } else {
                                html = `
            <div class="d-flex justify-content-start mb-2 align-items-center">

                <img src="/storage/${msg.sender.profile_img}"
                     class="chat-img me-2">

                <div class="p-2 bg-light rounded">
                    ${msg.message}
                </div>
            </div>`;
                            }

                            $('#chatBox').append(html);
                            $('#chatBox').scrollTop($('#chatBox')[0].scrollHeight);
                        });

                    });

            });

        });
    </script>




    <script src="https://cdn.jsdelivr.net/npm/laravel-echo/dist/echo.iife.js"></script>

    {{-- <script>
        window.Pusher = Pusher;
        window.Echo = new Echo({
            broadcaster: 'reverb',
            key: "{{ env('REVERB_APP_KEY') }}",
            wsHost: "{{ env('REVERB_HOST') }}",
            wsPort: "{{ env('REVERB_PORT') }}",
            forceTLS: false,
            enabledTransports: ['ws'],
        });
        let myId = {{ auth()->id() }};
        console.log("Echo Connected:", window.Echo);
    </script> --}}


    <script>
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
    </script>


    <script>
        $(document).ready(function() {

            let myId = {{ auth()->id() }};

            listenToUser(myId); // 🔥 auto listen start

        });
    </script>



    {{-- <script>
        let currentChannel = null;

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

                    let html = `
            <div class="d-flex justify-content-start mb-2 align-items-center">
                <img src="/storage/default.png" class="chat-img me-2">
                <div class="p-2 bg-light rounded">
                    ${e.message}
                </div>
            </div>
            `;

                    $('#chatBox').append(html);
                });
        }
    </script> --}}



    <script>
        let currentChannel = null;

        function listenToUser(userId) {

            if (currentChannel) {
                window.Echo.leave(currentChannel);
            }

            currentChannel = 'chat-channel.' + userId;

            // window.Echo.join(currentChannel)
            //     .here((users) => {
            //         console.log("Users in channel:", users);
            //     })
            //     .joining((user) => {
            //         console.log("User joined:", user);
            //     })
            //     .leaving((user) => {
            //         console.log("User left:", user);
            //     })
            //     .listen('.message.sent', (e) => {
            //         console.log("Realtime:", e);

            //         // Only append if current receiver matches
            //         if ($('#receiver_id').val() == e.sender_id || $('#receiver_id').val() == e.receiver_id) {
            //             let isMe = e.sender_id == {{ auth()->id() }};
            //             let html = '';

            //             if (isMe) {
            //                 html = `
        //         <div class="d-flex justify-content-end mb-2 align-items-center">
        //             <div class="p-2 bg-success text-white rounded">
        //                 ${e.message}
        //             </div>
        //             <img src="/storage/${e.sender.profile_img || 'default.png'}" class="chat-img ms-2">
        //         </div>`;
            //             } else {
            //                 html = `
        //         <div class="d-flex justify-content-start mb-2 align-items-center">
        //             <img src="/storage/${e.sender.profile_img || 'default.png'}" class="chat-img me-2">
        //             <div class="p-2 bg-light rounded">
        //                 ${e.message}
        //             </div>
        //         </div>`;
            //             }

            //             $('#chatBox').append(html);
            //             $('#chatBox').scrollTop($('#chatBox')[0].scrollHeight);
            //         }
            // });

            window.Echo.join('chat-channel.' + userId)
                .listen('.message.sent', (e) => {
                    console.log("Realtime:", e);

                    // 1️⃣ Always update the sidebar
                    let sidebarItem = $('.userItem[data-id="' + e.sender_id + '"]');
                    if (sidebarItem.length) {
                        // Update last message
                        sidebarItem.find('.last-message').text(e.message);

                        // Update badge
                        if ($('#receiver_id').val() != e.sender_id) {
                            let badge = sidebarItem.find('.badge');
                            if (badge.length) {
                                let count = parseInt(badge.text()) || 0;
                                badge.text(count + 1);
                            } else {
                                sidebarItem.append('<span class="badge bg-success ms-2">1</span>');
                            }
                        }
                    }

                    // 2️⃣ Only append to chat if open
                    if ($('#receiver_id').val() == e.sender_id) {
                        let html = `
                <div class="d-flex justify-content-start mb-2 align-items-center">
                    <img src="/storage/${e.sender.profile_img || 'default.png'}" class="chat-img me-2">
                    <div class="p-2 bg-light rounded">${e.message}</div>
                </div>
            `;
                        $('#chatBox').append(html);
                        $('#chatBox').scrollTop($('#chatBox')[0].scrollHeight);
                    }
                });
        }
    </script>

    {{-- <script>
        let currentChannel = null;

        function listenToUser(userId) {

            // Leave previous channel
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
                    console.log("Realtime message:", e);

                    let currentReceiver = $('#receiver_id').val();

                    // --------------------------
                    // 1️⃣ Chat box update
                    // --------------------------
                    if (currentReceiver == e.sender_id || currentReceiver == e.receiver_id) {
                        let isMe = e.sender_id == {{ auth()->id() }};
                        let html = isMe ?
                            `<div class="d-flex justify-content-end mb-2 align-items-center">
                            <div class="p-2 bg-success text-white rounded">${e.message}</div>
                            <img src="/storage/${e.sender.profile_img || 'default.png'}" class="chat-img ms-2">
                        </div>` :
                            `<div class="d-flex justify-content-start mb-2 align-items-center">
                            <img src="/storage/${e.sender.profile_img || 'default.png'}" class="chat-img me-2">
                            <div class="p-2 bg-light rounded">${e.message}</div>
                        </div>`;

                        $('#chatBox').append(html);
                        $('#chatBox').scrollTop($('#chatBox')[0].scrollHeight);
                    }

                    // --------------------------
                    // 2️⃣ Sidebar update: last message + unread count
                    // --------------------------
                    let sidebarItem = $('.userItem[data-id="' + e.sender_id + '"]');
                    if (sidebarItem.length) {
                        // update last message text
                        sidebarItem.find('.last-message').text(e.message);

                        // update badge (unread count)
                        if (currentReceiver != e.sender_id) { // only if chat not active
                            let badge = sidebarItem.find('.badge');
                            if (badge.length) {
                                let count = parseInt(badge.text()) || 0;
                                badge.text(count + 1);
                            } else {
                                sidebarItem.append('<span class="badge bg-success ms-2">1</span>');
                            }
                        }
                    }

                });
        }
    </script> --}}
</body>

</html>
