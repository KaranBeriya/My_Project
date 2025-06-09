@extends('layouts.app')

@section('title', 'Verify Email')

@section('content')
<div class="container mt-5 text-center">
    <h4>Verify Your Email Address</h4>
    <p>
        We have sent a verification link to your email.  
        Please check your inbox (or spam folder).
    </p>
    @if (session('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="btn btn-darkgreen mt-3">Resend Verification Email</button>
    </form>
</div>
@endsection
