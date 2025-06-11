@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2>Verify Your Email Address</h2>

    @if (session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    <p>
        A verification link has been sent to your email address.
        Please check your inbox and click the link to verify your email.
    </p>

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="btn-darkgreen">Resend Verification Email</button>
    </form>
</div>
@endsection

@section('styles')
<style>
    .btn-darkgreen {
        background-color: #155724;
        color: white;
        font-weight: 500;
        transition: all 0.3s ease-in-out;
        border: none;
        padding: 10px 20px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 1rem;
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
@endsection
