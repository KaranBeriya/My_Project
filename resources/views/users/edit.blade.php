@extends('layouts.public')

@section('title', 'Edit User')

@section('content')
<div class="container" style="max-width: 480px; margin: 30px auto;">
    <h2 class="mb-4 text-center">Edit User</h2>
    <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label fw-semibold">Name</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="role" class="form-label fw-semibold">Role</label>
            <select name="role" class="form-select" required>
                <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User</option>
                <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label fw-semibold">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="contact" class="form-label fw-semibold">Contact</label>
            <input type="text" name="contact" value="{{ old('contact', $user->contact) }}" class="form-control">
        </div>

        <div class="mb-3 text-center">
            @if ($user->profile_picture)
                <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile Picture" width="60" height="60" class="rounded-circle mb-2">
            @endif
            <label for="profile_picture" class="form-label fw-semibold d-block">Profile Picture</label>
            <input type="file" name="profile_picture" class="form-control form-control-sm">
        </div>

        <button type="submit" class="btn btn-success w-100">Update User</button>
    </form>
</div>

<!-- Bootstrap Icons CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

<!-- Toggle password script (remove if password field not used) -->
<script>
    const toggleBtn = document.querySelector('.toggle-password');
    if(toggleBtn) {
        toggleBtn.addEventListener('click', function () {
            const passwordField = document.getElementById('editPasswordField');
            const toggleIcon = document.getElementById('editToggleIcon');
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);
            toggleIcon.classList.toggle('bi-eye');
            toggleIcon.classList.toggle('bi-eye-slash');
        });
    }
</script>

@endsection
 