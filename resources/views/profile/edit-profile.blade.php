<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <title>Profile</title>
</head>



<body>

    @include('components.layouts.navbar')

    <div class="container mt-5">
        <div class="card shadow-lg border-0 rounded-4">
            <div class="card-body p-4">

                <h4 class="mb-4 fw-bold text-center">Edit Profile</h4>

                <form action="{{ route('user.profile.store', $user->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf

                    <!-- Profile Image -->
                    <div class="text-center mb-4 position-relative">
                        <img src="{{ asset('storage/' . $user->profile_img) }}"
                            class="rounded-circle shadow profile-img" width="120" height="120">


                        <label for="profileImage" class="edit-icon bg-success">
                            <i class="fa-solid fa-pen"></i>
                        </label>


                        <input type="file" id="profileImage" name="profile_image" hidden
                            onchange="previewImage(event)">

                        <script>
                            function previewImage(event) {
                                const img = document.querySelector('.profile-img');
                                img.src = URL.createObjectURL(event.target.files[0]);
                            }
                        </script>
                    </div>

                    <!-- Name -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Full Name</label>
                        <input type="text" name="name" class="form-control rounded-3" value="{{ $user->name }}"
                            required>
                    </div>


                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" name="email" readonly class="form-control rounded-3"
                            value="{{ $user->email }}" required>
                    </div>

                    {{-- <!-- Bio -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Bio</label>
                        <textarea name="bio" rows="3" class="form-control rounded-3" placeholder="Write something about you...">{{ $user->bio }}</textarea>
                    </div> --}}

                    <!-- Password -->
                    {{-- <div class="mb-3">
                        <label class="form-label fw-semibold">New Password</label>
                        <input type="password" name="password" class="form-control rounded-3"
                            placeholder="Leave blank if not changing">
                    </div> --}}

                    <!-- Button -->
                    <div class="text-center">
                        <button class="btn btn-danger px-5 rounded-pill shadow-sm">
                            Update Profile
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>

    <style>
        .profile-img {
            object-fit: cover;
            border: 3px solid #4f46e5;
        }

        .edit-icon {
            position: absolute;
            bottom: 10px;
            right: calc(50% - 60px);
            background: #4f46e5;
            color: #fff;
            padding: 6px 8px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 12px;
        }

        .edit-icon:hover {
            background: #4338ca;
        }
    </style>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
    </script>
</body>

</html>
