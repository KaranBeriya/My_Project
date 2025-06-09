@extends('layouts.app')
@section('title', 'Forgot Password')

@section('content')
<div class="container mt-5" style="max-width: 420px;">
    <h4 class="text-center mb-4">Forgot Password</h4>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <input type="email" name="email" placeholder="Enter your email" class="form-control mb-2" required>
        <button type="submit" class="btn btn-darkgreen w-100">Send Reset Link</button>
    </form>
</div>
@endsection
