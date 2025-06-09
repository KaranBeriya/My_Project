@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Verify Your Email Address</h2>

    @if (session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    <p>
        Please check your email for a verification link.
        If you did not receive the email,
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn btn-link p-0 m-0 align-baseline">click here to request another</button>.
        </form>
    </p>
</div>
@endsection
