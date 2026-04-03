<?php
use App\Models\User;

use App\Models\Message;

$users = User::where('id', '!=', Auth::id())
    ->get()
    ->map(function ($user) {
        $lastMessage = Message::where(function ($q) use ($user) {
            $q->where('sender_id', Auth::id())->where('receiver_id', $user->id);
        })
            ->orWhere(function ($q) use ($user) {
                $q->where('sender_id', $user->id)->where('receiver_id', Auth::id());
            })
            ->latest()
            ->first();

        $user->last_message = $lastMessage?->message;
        $user->last_time = $lastMessage?->created_at;

        $user->unread_count = Message::where('sender_id', $user->id)->where('receiver_id', Auth::id())->whereNull('read_at')->count();

        return $user;
    })
    ->sortByDesc('last_time')
    ->values();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>User Search</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .profile-avatar {
            width: 45px;
            height: 45px;
            object-fit: cover;
            border-radius: 50%;
        }

        .status-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            position: absolute;
            bottom: 5px;
            right: 5px;
            border: 2px solid white;
        }

        .online {
            background-color: #22c55e;

        }

        .offline {
            background-color: #ef4444;
        }
    </style>
</head>

<body>

    <div class="container mt-3">

        <!-- Search Bar (UL ke bahar) -->
        <div class="p-3 border-bottom bg-white shadow-sm">
            <input type="text" id="searchUser" class="form-control" placeholder="Search users...">
        </div>

        <!-- User List -->
        <ul id="userList" class="list-unstyled mb-0" style="max-height: 85vh; overflow-y: auto;">

            @foreach ($users as $user)
                <li class="p-2 border-bottom bg-body-tertiary userItem list-group" data-id="{{ $user->id }}"
                    data-name="{{ $user->name }}" data-img="{{ asset('storage/' . $user->profile_img) }}">
                    <a href="#!"
                        class= " d-flex justify-content-between text-decoration-none list-group-item list-group-item-action bg-info text-white ">
                        <div class="d-flex flex-row ">
                            <div class="position-relative">
                                <img src="{{ asset('storage/' . $user->profile_img) }}" class="profile-avatar me-3">
                                <span class="status-dot offline" id="status-{{ $user->id }}"></span>
                            </div>
                            <div>
                                <p class="fw-bold mb-0 "><strong style="color: white">{{ $user->name }}</strong></p>

                                @if ($user->unread_count > 0)
                                    <small class="text-dark">
                                        {{ $user->last_message }}
                                    </small>
                                @endif
                            </div>
                        </div>
                        <div>

                            <small class="text-muted">
                                @if ($user->last_time)
                                    {{ $user->last_time->diffForHumans() }}
                                @endif
                            </small>
                            {{-- <small class="text-muted">Now</small><br> --}}
                            {{-- <span class="user-name badge bg-danger"></span> --}}
                            {{--
                            @if ($user->unread_count > 0)
                                <span class=" bg-danger">{{ $user->unread_count }}</span>
                            @endif --}}
                        </div>
                    </a>
                </li>
            @endforeach

        </ul>

    </div>

    <!-- jQuery for search -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script>
        $(document).ready(function() {

            let timer;

            $('#searchUser').on('keyup', function() {

                clearTimeout(timer);

                let query = $(this).val();

                timer = setTimeout(() => {

                    $.ajax({
                        url: "/search-users",
                        type: "GET",
                        data: {
                            search: query
                        },

                        success: function(data) {

                            let html = '';

                            if (data.length === 0) {
                                html =
                                    '<li class="p-3 text-center text-muted">No users found</li>';
                            } else {

                                $.each(data, function(index, user) {
                                    html += `
                            <li class="p-2 border-bottom bg-body-tertiary userItem" data-id="${user.id}"  data-name="${user.name}"
                            data-img="/storage/${user.profile_img}">

                            <a href="#!" class="d-flex justify-content-between text-decoration-none list-group-item-action bg-info text-white">

                            <div class="d-flex flex-row">
                                <div class="position-relative">
                                <img src="/storage/${user.profile_img}" class="profile-avatar me-3">
                                <span class="status-dot offline" id="status-${user.id}"></span>
                            </div>

                            <div>
                                <p class="fw-bold mb-0">${user.name}</p>
                            </div>
                        </div>

                        <div>
                            <small class="text-muted">Now</small>
                        </div>
                    </a>
                </li>
            `;
                                });
                            }

                            $('#userList').html(html);

                            updateUserStatus();
                        }
                    });

                }, 300);

            });

        });
    </script>

    {{-- for view chat --}}
    <script>
        $(document).ready(function() {
            $('.userItem').on('click', function(e) {
                e.preventDefault();

                let name = $(this).data('name');
                let img = $(this).data('img');

                $('.chat-header .header-name').text(name);
                $('.chat-header .header-avatar').attr('src', img);

                //   listenToUser(userId);
            });
        });
    </script>

</body>

</html>
