<?php
use App\Models\User;
$user = User::where('id', Auth::id())->first();

?>
<style>
    .profile-avatar {
        width: 45px;
        height: 45px;
        object-fit: cover;
        border-radius: 50%;

        /* Smooth border */
        border: 2px solid #4f46e5;

        /* Glow effect */
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.4);

        /* Smooth hover */
        transition: all 0.3s ease;
    }

    .profile-avatar:hover {
        transform: scale(1.08);
        box-shadow: 0 6px 18px rgba(79, 70, 229, 0.6);
    }


</style>

<nav class="navbar  bg-secondary shadow-sm px-3 d-flex justify-content-between align-items-center"  >

<!-- Left -->
<span class="navbar-brand mb-0 h5"><strong style="color: white">💬 CHAT APP</strong></span>

<!-- Right Profile -->
<div class="dropdown">
    <a class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" href="#"
        id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">

        <img src="{{ asset('storage/' . Auth::user()->profile_img) }}" class="profile-avatar me-3">
        
        <span class="d-none d-md-inline"><strong>{{ Auth::user()->name }}</strong></span>
    </a>

    <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="profileDropdown">
        <li>
            <a class="dropdown-item" href="{{ route('user.profile.page') }}">
                <strong>👤 Profile</strong>
            </a>
        </li>
        <li>
            <a class="dropdown-item" href="#">
                <strong> ⚙️ Settings</strong>
            </a>
        </li>
        <li>
            <hr class="dropdown-divider">
        </li>
        <li>
            <a class="dropdown-item text-danger" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">
                <strong> 🚪 Signout</strong>
            </a>
        </li>
    </ul>
</div>

</nav>

<!-- Logout Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-3 shadow">

            <div class="modal-header">
                <h5 class="modal-title" id="logoutModalLabel">Confirm Logout</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body text-center">
                <p class="mb-0">Are you sure you want to logout? 🤔</p>
            </div>

            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">
                    Cancel
                </button>

                <a href="{{ route('logout.user') }}" class="btn btn-danger px-4">
                    Yes, Logout
                </a>
            </div>

        </div>
    </div>
</div>
