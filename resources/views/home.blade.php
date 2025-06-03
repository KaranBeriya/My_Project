@extends('layouts.public')

@section('title', 'Dashboard')

@section('content')
<div class="container mt-5 text-center">
    <h1 class="mb-4" style="color: black;">Welcome to Dashboard</h1>
    <p class="mb-4" style="color: black; font-size: 1.2rem;">
        Manage your app efficiently.
    </p>
    <p style="color: black; font-size: 1rem; font-style: italic;">
        Empower your workflow with real-time insights and seamless control.
    </p>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    body {
        background: linear-gradient(135deg, #283e51, #485563);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        min-height: 100vh;
        color: #eee;
        padding-bottom: 40px;
    }
</style>
@endpush
