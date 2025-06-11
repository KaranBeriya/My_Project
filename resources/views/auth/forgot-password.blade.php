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

        .toggle-password {
            position: absolute;
            top: 50%;
            right: 1rem;
            transform: translateY(-50%);
            cursor: pointer;
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
            color: red;
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

    <div class="container mt-5">
        <div class="card p-4 mx-auto shadow" style="max-width: 400px;">
            <h4 class="mb-3 text-center">🔐 Forgot Password</h4>

            @if (session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="form-group mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-darkgreen w-100">Send Reset Link</button>
            </form>
        </div>
    </div>
    @endsection
