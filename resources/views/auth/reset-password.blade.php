@extends('layouts.app')
@section('title', 'Reset Password')

@section('content')
<div class="container mt-5" style="max-width: 420px;">
    <h4 class="text-center mb-4">Reset Password</h4>

    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <input type="email" name="email" placeholder="Email" class="form-control mb-2" required>
        <input type="password" name="password" placeholder="New Password" class="form-control mb-2" required>
        <input type="password" name="password_confirmation" placeholder="Confirm Password" class="form-control mb-2" required>
        <button type="submit" class="btn btn-darkgreen w-100">Reset Password</button>
    </form>
</div>
@endsection
