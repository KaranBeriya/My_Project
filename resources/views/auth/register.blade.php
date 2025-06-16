@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="container d-flex justify-content-center align-items-center mt-5">
    <div class="card shadow p-4" style="max-width: 420px; width: 100%;">
        <h4 class="mb-4 text-center">Register</h4>

        <form id="registerForm" enctype="multipart/form-data" novalidate>
            @csrf

            <!-- Name -->
            <div class="form-group mb-2 d-flex align-items-center">
                <label class="me-2 mb-0" style="width: 35%;">Name</label>
                <input type="text" name="name" class="form-control form-control-sm animated-input" required>
            </div>
            <span class="text-danger error-text name_error"></span>

            <!-- Email -->
            <div class="form-group mb-2 d-flex align-items-center">
                <label class="me-2 mb-0" style="width: 35%;">Email</label>
                <input type="email" name="email" class="form-control form-control-sm animated-input" required>
            </div>
            <span class="text-danger error-text email_error"></span>

            <!-- Contact -->
            <div class="form-group mb-2 d-flex align-items-center">
                <label class="me-2 mb-0" style="width: 35%;">Contact</label>
                <input type="text" name="contact" class="form-control form-control-sm animated-input" required>
            </div>
            <span class="text-danger error-text contact_error"></span>

            <!-- Password -->
            <div class="form-group mb-2 d-flex align-items-center">
                <label class="me-2 mb-0" style="width: 35%;">Password</label>
                <div class="input-group position-relative w-100">
                    <input type="password" name="password" class="form-control form-control-sm pe-5 animated-input" id="passwordField" required>
                    <button class="btn toggle-password position-absolute end-0 top-0 h-100 px-2 border-0 bg-transparent" type="button" tabindex="-1">
                        <i class="bi bi-eye-slash" id="toggleIcon"></i>
                    </button>
                </div>
            </div>
            <span class="text-danger error-text password_error"></span>

            <!-- Confirm Password -->
            <div class="form-group mb-2 d-flex align-items-center">
                <label class="me-2 mb-0" style="width: 35%;">Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-control form-control-sm animated-input" required>
            </div>
            <span class="text-danger error-text password_confirmation_error"></span>

            <!-- Profile Picture -->
            <div class="form-group mb-2 d-flex align-items-center">
                <label class="me-2 mb-0" style="width: 35%;">Picture</label>
                <input type="file" name="profile_picture" class="form-control form-control-sm" accept="image/*">
            </div>
            <span class="text-danger error-text profile_picture_error"></span>

            <!-- Submit Button -->
            <div class="mt-3 text-center">
                <button type="submit" class="btn btn-darkgreen btn-sm px-4" id="submitBtn">Register</button>
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

    .toggle-password {
        top: 0;
        right: 0;
        z-index: 2;
    }

    .form-control-sm {
        font-size: 0.85rem;
        height: 30px;
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
        margin-top: 0.2rem;
        margin-left: 35%;
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

    // Clear previous errors
    document.querySelectorAll('.error-text').forEach(el => el.textContent = '');
    successMessage.classList.add('d-none');
    successMessage.textContent = '';

    // Disable the button and change text
    submitButton.disabled = true;
    submitButton.innerHTML = 'Registering...';

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
        submitButton.innerHTML = 'Register';

        if (data.errors) {
            for (let key in data.errors) {
                let errorSpan = document.querySelector(`.${key}_error`);
                if (errorSpan) errorSpan.textContent = data.errors[key][0];
            }
        } else if (data.success) {
            successMessage.textContent = data.message;
            successMessage.classList.remove('d-none');
            form.reset();
            setTimeout(() => window.location.href = data.redirect_url || '/', 1000);
        }
    })
    .catch(err => {
        console.error('Error:', err);
        alert('Something went wrong, please try again.');
        submitButton.disabled = false;
        submitButton.innerHTML = 'Register';
    });
});

document.querySelector('.toggle-password').addEventListener('click', function () {
    const passwordField = document.getElementById('passwordField');
    const toggleIcon = document.getElementById('toggleIcon');
    const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordField.setAttribute('type', type);
    toggleIcon.classList.toggle('bi-eye');
    toggleIcon.classList.toggle('bi-eye-slash');
});
</script>
@endsection
