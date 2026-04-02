<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat App | Sign In</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Optional: Font Awesome for icons -->
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
    </style>
</head>

<body class="d-flex align-items-center justify-content-center vh-100">

    <div class="card shadow-lg p-4 p-md-5 w-100" style="max-width: 400px;">
        <div class="text-center mb-4">

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

            <h2 class="fw-bold">Sign In</h2>
            <p class="text-muted">Welcome back! Enter your email and password to sign in.</p>
        </div>



        <!-- Form -->
        <form method="POST" action="{{ route('user.login') }}">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="you@example.com"
                    required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password"
                    placeholder="Enter your password" required>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="rememberMe">
                    <label class="form-check-label" for="rememberMe">Keep me logged in</label>
                </div>
                <a href="/reset-password" class="text-primary text-decoration-none">Forgot password?</a>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-success btn-lg">Sign In</button>
            </div>
        </form>

        <p class="text-center mt-4 text-muted">
            Don't have an account? <a href="{{ route('sign-up.page') }}" class="text-primary text-decoration-none">Sign
                Up</a>
        </p>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
