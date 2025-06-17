@extends('layouts.app')

@section('title', __('messages.register'))

@section('content')
<div class="container d-flex justify-content-center align-items-center mt-5">
    <div class="card shadow p-4" style="max-width: 420px; width: 100%;">
        <h4 class="mb-4 text-center">{{ __('messages.register') }}</h4>

        <form id="registerForm" enctype="multipart/form-data" novalidate>
            @csrf

            <!-- Name -->
            <div class="form-group mb-2">
                <div class="d-flex align-items-center">
                    <label class="me-2 mb-0" style="width: 35%;">{{ __('messages.name') }}</label>
                    <input type="text" name="name" class="form-control form-control-sm animated-input" required>
                </div>
                <span class="text-danger error-text name_error"></span>
            </div>

            <!-- Email -->
            <div class="form-group mb-2">
                <div class="d-flex align-items-center">
                    <label class="me-2 mb-0" style="width: 35%;">{{ __('messages.email') }}</label>
                    <input type="email" name="email" class="form-control form-control-sm animated-input" required>
                </div>
                <span class="text-danger error-text email_error"></span>
            </div>

            <!-- Contact -->
            <div class="form-group mb-2">
                <div class="d-flex align-items-center">
                    <label class="me-2 mb-0" style="width: 35%;">{{ __('messages.contact') }}</label>
                    <input type="text" name="contact" class="form-control form-control-sm animated-input" required>
                </div>
                <span class="text-danger error-text contact_error"></span>
            </div>

            <!-- Password -->
            <div class="form-group mb-2">
                <div class="d-flex align-items-center">
                    <label class="me-2 mb-0" style="width: 35%;">{{ __('messages.password') }}</label>
                    <div class="input-group position-relative w-100">
                        <input type="password" name="password" class="form-control form-control-sm pe-5 animated-input" id="passwordField" required>
                        <button class="btn toggle-password position-absolute end-0 top-0 h-100 px-2 border-0 bg-transparent" type="button" tabindex="-1">
                            <i class="bi bi-eye-slash" id="toggleIcon"></i>
                        </button>
                    </div>
                </div>
                <span class="text-danger error-text password_error"></span>
            </div>

            <!-- Confirm Password -->
            <div class="form-group mb-2">
                <div class="d-flex align-items-center">
                    <label class="me-2 mb-0" style="width: 35%;">{{ __('messages.confirm_password') }}</label>
                    <div class="input-group position-relative w-100">
                        <input type="password" name="password_confirmation" class="form-control form-control-sm pe-5 animated-input" id="confirmPasswordField" required>
                        <button class="btn toggle-confirm-password position-absolute end-0 top-0 h-100 px-2 border-0 bg-transparent" type="button" tabindex="-1">
                            <i class="bi bi-eye-slash" id="confirmToggleIcon"></i>
                        </button>
                    </div>
                </div>
                <span class="text-danger error-text password_confirmation_error"></span>
            </div>

            <!-- Profile Picture -->
            <div class="form-group mb-2">
                <div class="d-flex align-items-center">
                    <label class="me-2 mb-0" style="width: 35%;">{{ __('messages.picture') }}</label>
                    <input type="file" name="profile_picture" class="form-control form-control-sm" accept="image/*">
                </div>
                <span class="text-danger error-text profile_picture_error"></span>
            </div>

            <!-- Submit Button -->
            <div class="mt-3 text-center">
                <button type="submit" class="btn btn-darkgreen btn-sm px-4" id="submitBtn">
                    {{ __('messages.register') }}
                </button>
            </div>
        </form>

        <div id="successMessage" class="alert alert-success mt-3 d-none text-center"></div>
    </div>
</div>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

<!-- Custom Styles -->
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

    .toggle-password, .toggle-confirm-password {
        top: 0;
        right: 0;
        z-index: 2;
    }

    .form-control-sm {
        font-size: 0.85rem;
        height: 30px;
        padding-right: 2.5rem;
    }

    .form-group label {
        font-weight: 500;
        font-size: 0.85rem;
    }

    .card {
        border-radius: 0.75rem;
    }

    .error-text {
        display: block;
        font-size: 0.75rem;
        color: red;
        margin-top: 0.2rem;
    }

    .animated-input {
        transition: 0.3s ease;
    }

    .animated-input:focus {
        border-color: #28a745;
        box-shadow: 0 0 5px rgba(40, 167, 69, 0.5);
        transform: scale(1.02);
    }
</style>

<!-- JavaScript -->
<script>
document.getElementById('registerForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const form = this;
    const formData = new FormData(form);
    const submitButton = document.getElementById('submitBtn');
    const successMessage = document.getElementById('successMessage');

    // Clear existing errors and message
    document.querySelectorAll('.error-text').forEach(el => el.textContent = '');
    successMessage.classList.add('d-none');
    successMessage.textContent = '';

    submitButton.disabled = true;
    submitButton.innerHTML = '{{ __("messages.registering") }}';

    fetch("{{ route('register.store') }}", {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
        },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        submitButton.disabled = false;
        submitButton.innerHTML = '{{ __("messages.register") }}';

        if (data.errors) {
            Object.keys(data.errors).forEach(field => {
                const errorEl = document.querySelector(`.${field}_error`);
                if (errorEl) errorEl.textContent = data.errors[field][0];
            });
        } else if (data.success) {
            successMessage.textContent = data.message;
            successMessage.classList.remove('d-none');
            form.reset();

            setTimeout(() => {
                window.location.href = data.redirect_url || '/';
            }, 1000);
        }
    })
    .catch(err => {
        console.error('Error:', err);
        alert('{{ __("messages.registration_failed") }}');
        submitButton.disabled = false;
        submitButton.innerHTML = '{{ __("messages.register") }}';
    });
});

// Toggle Password Visibility
document.querySelector('.toggle-password').addEventListener('click', function () {
    const passwordField = document.getElementById('passwordField');
    const toggleIcon = document.getElementById('toggleIcon');
    const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordField.setAttribute('type', type);
    toggleIcon.classList.toggle('bi-eye');
    toggleIcon.classList.toggle('bi-eye-slash');
});

document.querySelector('.toggle-confirm-password').addEventListener('click', function () {
    const confirmPasswordField = document.getElementById('confirmPasswordField');
    const confirmToggleIcon = document.getElementById('confirmToggleIcon');
    const type = confirmPasswordField.getAttribute('type') === 'password' ? 'text' : 'password';
    confirmPasswordField.setAttribute('type', type);
    confirmToggleIcon.classList.toggle('bi-eye');
    confirmToggleIcon.classList.toggle('bi-eye-slash');
});
</script>
@endsection
