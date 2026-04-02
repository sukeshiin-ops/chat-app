<?php
use App\Models\User;
$users = User::where('id', '!=', Auth::id())->get();
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
                        <div class="d-flex flex-row">
                            <img src="{{ asset('storage/' . $user->profile_img) }}" class="profile-avatar me-3">
                            <div>
                                <p class="fw-bold mb-0"><strong style="color: white">{{ $user->name }}</strong></p>
                            </div>
                        </div>
                        <div>
                            <small class="text-muted">Now</small><br>
                            <span class="badge bg-success">1</span>
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
                                <li class="p-2 border-bottom bg-body-tertiary">
                                    <a href="#!" class="d-flex justify-content-between text-decoration-none list-group-item-action bg-success text-white ">
                                        <div class="d-flex flex-row">
                                            <img src="/storage/${user.profile_img}" class="profile-avatar me-3">
                                            <div>
                                                <p class="fw-bold mb-0">${user.name}</p>
                                            </div>
                                        </div>
                                        <div>
                                            <small class="text-muted">Now</small><br>
                                            <span class="badge bg-success">1</span>
                                        </div>
                                    </a>
                                </li>
                            `;
                                });

                            }

                            $('#userList').html(html);

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
