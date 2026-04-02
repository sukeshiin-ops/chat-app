<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat App | Sign Up</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: #f0f2f5;
        }

        .card {
            border-radius: 1rem;
        }

        .social-btn {
            border-radius: 50px;
            font-weight: 500;
        }

        .social-btn i {
            margin-right: 8px;
        }

        .profile-img-input {
            display: none;
        }

        .profile-img-label {
            cursor: pointer;
        }

        .profile-img-preview {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #ddd;
            margin-bottom: 10px;
        }
    </style>
</head>

<body class="d-flex align-items-center justify-content-center vh-100">

    <div class="card shadow-lg p-4 p-md-5 w-100" style="max-width: 450px;">
        <div class="text-center mb-4">



            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <strong> {{ implode(', ', $errors->all()) }}</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <script>
                setTimeout(() => {
                    let alertBox = document.querySelector('.alert-danger');
                    if (alertBox) {
                        alertBox.style.transition = "0.5s";
                        alertBox.style.opacity = "0";
                        setTimeout(() => {
                            alertBox.remove();
                        }, 500);
                    }
                }, 3000);
            </script>



            <h2 class="fw-bold">Sign Up</h2>
            <p class="text-muted">Create your account to start chatting!</p>
        </div>

        <!-- Form -->
        <form method="POST" action="{{ route('user.register') }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Full Name</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Your name"
                    required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="you@example.com"
                    required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Enter password"
                    required>
            </div>

            <div class="mb-3">
                <label for="confirmPassword" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="confirmPassword" name="password_confirmation"
                    placeholder="Confirm password" required>
            </div>

            <div class="mb-3">
                <label>Profile Picture</label>
                <input type="file" name="profile_img" class="form-control">
            </div>


            <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-lg">Sign Up</button>
            </div>
        </form>

        <p class="text-center mt-4 text-muted">
            Already have an account? <a href="{{ route('sign-in.page') }}"
                class="text-primary text-decoration-none">Sign In</a>
        </p>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
