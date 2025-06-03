@extends('layouts.public')

@section('title', 'Users List')

@section('content')
<div style="display: flex; gap: 20px; flex-wrap: wrap;">
    <!-- Yellow Box -->
    <div
        style="flex: 0 0 220px; background-color: #fff176; padding: 15px 20px; border-radius: 8px; box-sizing: border-box;">
        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px;">
            <i class="fas fa-user-plus"></i>
            <span style="font-weight: bold; font-size: 18px;">{{ count($users) }}</span>
        </div>
        <h4 style="font-size: 20px; margin: 0 0 10px 0;">Register User</h4>
        <p id="toggleUserList"
            style="font-weight: 600; color: #b28900; cursor: pointer; display: inline-flex; align-items: center; gap: 10px; font-size: 16px;">
            More Information
            <span
                style="background-color: #b28900; border-radius: 50%; width: 26px; height: 26px; display: flex; justify-content: center; align-items: center; color: white;">
                <i class="fas fa-arrow-down" style="font-size: 14px;"></i>
            </span>
        </p>
    </div>

    <!-- Blue Box -->
    <div id="createUserBox"
        style="flex: 0 0 220px; background-color: #bbdefb; padding: 15px 20px; border-radius: 8px; box-sizing: border-box; cursor: pointer;">
        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px;">
            <i class="fas fa-user-edit"></i>
            <span style="font-weight: bold; font-size: 18px;">Create</span>
        </div>
        <h4 style="font-size: 20px; margin: 0 0 10px 0;">Create User</h4>
        <p
            style="font-weight: 600; color: #0d47a1; display: inline-flex; align-items: center; gap: 10px; font-size: 16px;">
            Click to open form
            <span
                style="background-color: #0d47a1; border-radius: 50%; width: 26px; height: 26px; display: flex; justify-content: center; align-items: center; color: white;">
                <i class="fas fa-arrow-down" style="font-size: 14px;"></i>
            </span>
        </p>
    </div>
</div>

<!-- Create User Form -->
<div id="createUserFormContainer"
    style="margin: 20px auto; background: #e3f2fd; padding: 15px; border-radius: 8px; display: none; max-width: 400px;">
    
    <!-- Session Alerts -->
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <h3 style="text-align: center;">Create New User</h3>
    <form id="createUserForm" method="POST" action="{{ route('users.store') }}" enctype="multipart/form-data"
        style="text-align: left;">
        @csrf
        <!-- Name -->
        <div class="form-group" style="margin-bottom: 10px;">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" class="form-control" required>
            <small id="error-name" class="text-danger"></small>
        </div>
        <!-- Role -->
        <div class="form-group" style="margin-bottom: 10px;">
            <label for="role">Role</label>
            <select name="role" id="role" class="form-control" required>
                <option value="">-- Select Role --</option>
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>
            <small id="error-role" class="text-danger"></small>
        </div>
        <!-- Email -->
        <div class="form-group" style="margin-bottom: 10px;">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" class="form-control" required>
            <small id="error-email" class="text-danger"></small>
        </div>
        <!-- Password -->
        <div class="form-group" style="margin-bottom: 10px; position: relative;">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" class="form-control" required>
            <i class="fas fa-eye toggle-password"
                style="position: absolute; top: 35px; right: 10px; cursor: pointer;"></i>
            <small id="error-password" class="text-danger"></small>
        </div>
        <!-- Contact -->
        <div class="form-group" style="margin-bottom: 10px;">
            <label for="contact">Contact</label>
            <input type="text" name="contact" id="contact" class="form-control">
            <small id="error-contact" class="text-danger"></small>
        </div>
        <!-- Profile -->
        <div class="form-group" style="margin-bottom: 10px;">
            <label for="profile_picture">Profile Picture</label>
            <input type="file" name="profile_picture" id="profile_picture" class="form-control">
            <small id="error-profile_picture" class="text-danger"></small>
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%;">Create User</button>
        <div id="formMessage" style="margin-top: 10px;"></div>
    </form>
</div>

<!-- Users Table -->
<div id="usersList" style="margin-top: 30px; display: none;">
    <h3>Registered Users</h3>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Email</th>
                <th>Contact</th>
                @if(auth()->user() && auth()->user()->role == 'Admin')
                <th>Action</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr data-user-id="{{ $user->id }}">
                <td><img src="{{ asset('storage/' . $user->profile_picture) }}" width="50" height="50"
                        class="rounded-circle"></td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->contact }}</td>
                @if(auth()->user() && auth()->user()->role == 'Admin')
                <td>
                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-warning"><i
                            class="fas fa-edit"></i></a>

                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display: inline;"
                        onsubmit="return confirm('Are you sure you want to delete this user?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>

                </td>
                @endif
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@push('script')
<script>
    const loggedInUserRole = "{{ auth()->user()->role }}";
    const usersList = document.getElementById('usersList');
    const createUserFormContainer = document.getElementById('createUserFormContainer');
    const toggleUserListBtn = document.getElementById('toggleUserList');
    const createUserBox = document.getElementById('createUserBox');

    window.addEventListener('load', () => {
        usersList.style.display = 'none';
        createUserFormContainer.style.display = 'none';
    });

    toggleUserListBtn.addEventListener('click', () => {
        usersList.style.display = (usersList.style.display === 'none') ? 'block' : 'none';
        createUserFormContainer.style.display = 'none';
    });

    createUserBox.addEventListener('click', () => {
        createUserFormContainer.style.display = (createUserFormContainer.style.display === 'none') ? 'block' : 'none';
        usersList.style.display = 'none';
        createUserFormContainer.scrollIntoView({ behavior: 'smooth' });
    });

    document.querySelectorAll('.toggle-password').forEach(icon => {
        icon.addEventListener('click', () => {
            const input = icon.previousElementSibling;
            input.type = (input.type === 'password') ? 'text' : 'password';
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
        });
    });

    const createUserForm = document.getElementById('createUserForm');
    createUserForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(createUserForm);
        const response = await fetch(createUserForm.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
        const data = await response.json();
        if (data.success) {
            document.getElementById('formMessage').innerText = 'User created successfully!';
            createUserForm.reset();
            clearErrors();
            location.reload();
        } else if (data.errors) {
            showErrors(data.errors);
        } else {
            document.getElementById('formMessage').innerText = 'An error occurred.';
        }
    });

    function clearErrors() {
        document.querySelectorAll('small.text-danger').forEach(el => el.innerText = '');
    }

    function showErrors(errors) {
        clearErrors();
        for (const [key, messages] of Object.entries(errors)) {
            const el = document.getElementById(`error-${key}`);
            if (el) el.innerText = messages.join(', ');
        }
    }
</script>
@endpush
