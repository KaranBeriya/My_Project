@extends('layouts.app')

@section('content')
<style>
    .btn-darkgreen {
        background-color: #155724;
        color: white;
        font-weight: 500;
        transition: all 0.3s ease-in-out;
    }

    .btn-darkgreen:hover {
        background-color: rgb(154, 177, 160);
        box-shadow: 0 5px 15px rgba(21, 87, 36, 0.4);
    }

    .form-group label {
        font-weight: 500;
        font-size: 0.9rem;
    }

    .error-text {
        color: red;
        font-size: 0.75rem;
        margin-top: 0.25rem;
        display: block;
    }

    .input-group {
        position: relative;
    }

    .toggle-password {
        position: absolute;
        top: 50%;
        right: 0.75rem;
        transform: translateY(-50%);
        cursor: pointer;
        z-index: 2;
        color: #6c757d;
    }

    .form-control:focus {
        border-color: #28a745;
        box-shadow: 0 0 5px rgba(40, 167, 69, 0.5);
        transform: scale(1.01);
    }
</style>

<div class="container mt-5">
    <div class="card p-4 mx-auto shadow" style="max-width: 400px;">
        <h4 class="mb-3 text-center">🔁 Reset Password</h4>

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ old('email', request()->email) }}">

            <!-- New Password -->
            <div class="form-group mb-3">
                <label for="password">New Password</label>
                <div class="input-group">
                    <input type="password" name="password" id="password" class="form-control" required>
                    <span class="toggle-password" data-toggle="password">
                        <i class="bi bi-eye-slash" id="passwordIcon"></i>
                    </span>
                </div>
                @error('password')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="form-group mb-3">
                <label for="password_confirmation">Confirm Password</label>
                <div class="input-group">
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                    <span class="toggle-password" data-toggle="password_confirmation">
                        <i class="bi bi-eye-slash" id="confirmPasswordIcon"></i>
                    </span>
                </div>
            </div>

            <button type="submit" class="btn btn-darkgreen w-100">Reset Password</button>
        </form>
    </div>
</div>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

<!-- Toggle Password JS -->
<script>
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function () {
            const inputId = this.getAttribute('data-toggle');
            const input = document.getElementById(inputId);
            const icon = this.querySelector('i');

            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);

            icon.classList.toggle('bi-eye');
            icon.classList.toggle('bi-eye-slash');
        });
    });
</script>
@endsection
