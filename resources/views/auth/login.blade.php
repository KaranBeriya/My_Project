@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="container d-flex justify-content-center align-items-center mt-5">
    <div class="card shadow p-4" style="max-width: 420px; width: 100%; border-radius: 0.75rem;">
        <h4 class="mb-4 text-center">Login</h4>

        <form id="loginForm" method="POST" action="{{ route('login.store') }}">
            @csrf
            <input name="email" type="email" placeholder="Email" class="form-control mb-2" required><br>

            <div class="position-relative mb-2">
                <input id="passwordField" name="password" type="password" placeholder="Password" class="form-control pe-5" required>
                <button type="button" tabindex="-1" class="btn toggle-password position-absolute end-0 top-0 h-100 px-3 border-0 bg-transparent">
                    <i class="bi bi-eye-slash" id="toggleIcon"></i>
                </button>
            </div>
            <div class="text-end mt-2">
                <a href="{{ route('password.request') }}" class="text-decoration-none">Forgot Password?</a>
            </div>
            <button type="submit" class="btn btn-darkgreen w-100">Login</button>
        </form>

        @if ($errors->any())
            <div class="mt-3">
                @foreach ($errors->all() as $error)
                    <span class="text-danger error-text d-block">{{ $error }}</span>
                @endforeach
            </div>
        @endif
    </div>
</div>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

<style>
    .btn-darkgreen {
        background-color: #155724;
        color: white;
        font-weight: 500;
    }
    .btn-darkgreen:hover {
        background-color: #1e7e34;
    }
    .error-text {
        font-size: 0.75rem;
        color: red;
    }
    .toggle-password {
        top: 0;
        right: 0;
        z-index: 2;
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {
    $('.toggle-password').on('click', function () {
        const passwordField = $('#passwordField');
        const type = passwordField.attr('type') === 'password' ? 'text' : 'password';
        passwordField.attr('type', type);
        $('#toggleIcon').toggleClass('bi-eye bi-eye-slash');
    });
});
</script>
@endsection
