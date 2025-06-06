@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Email Verification Required</h2>
    <p>Please check your email to verify your address.</p>

    @if (session('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="btn btn-darkgreen">Resend Verification Email</button>
    </form>
</div>
@endsection
