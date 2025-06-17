@extends('layouts.public')

@section('title', __('messages.edit_user'))

@section('content')
<div class="container" style="max-width: 480px; margin: 30px auto;">
    <h2 class="mb-4 text-center">{{ __('messages.edit_user') }}</h2>
    <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label fw-semibold">{{ __('messages.name') }}</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="role" class="form-label fw-semibold">{{ __('messages.role') }}</label>
            <select name="role" class="form-select" required>
                <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>{{ __('messages.user') }}</option>
                <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>{{ __('messages.admin') }}</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label fw-semibold">{{ __('messages.email') }}</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="contact" class="form-label fw-semibold">{{ __('messages.contact') }}</label>
            <input type="text" name="contact" value="{{ old('contact', $user->contact) }}" class="form-control">
        </div>

        <div class="mb-3 text-center">
            @if ($user->profile_picture)
                <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile Picture" width="60" height="60" class="rounded-circle mb-2">
            @endif
            <label for="profile_picture" class="form-label fw-semibold d-block">{{ __('messages.profile_picture') }}</label>
            <input type="file" name="profile_picture" class="form-control form-control-sm">
        </div>

        <button type="submit" class="btn btn-success w-100">{{ __('messages.update_user') }}</button>
    </form>
</div>
@endsection
