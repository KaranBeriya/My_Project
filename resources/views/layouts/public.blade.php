<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8" />
    <title>@yield('title', 'MyApp')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap + FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        body {
            min-height: 100vh;
            display: flex;
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        #sidebar {
            width: 230px;
            background-color: #1a1a1a;
            color: white;
            display: flex;
            flex-direction: column;
            padding-top: 1rem;
            position: fixed;
            height: 100vh;
            transition: all 0.3s ease;
        }

        #sidebar.collapsed {
            width: 70px;
        }

        #sidebar .sidebar-title {
            text-align: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        #sidebar .sidebar-title i {
            display: none;
            font-size: 2rem;
            color: #9df0aa;
        }

        #sidebar.collapsed .sidebar-title span {
            display: none;
        }

        #sidebar.collapsed .sidebar-title i {
            display: inline-block;
        }

        .user-profile {
            text-align: center;
            margin-bottom: 1.5rem;
            padding: 0 1rem;
            transition: all 0.3s ease;
        }

        .user-profile img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #28a745;
            margin-bottom: 0.5rem;
        }

        #sidebar.collapsed .user-profile img {
            width: 40px;
            height: 40px;
        }

        .user-profile h5,
        .user-profile small {
            transition: all 0.2s ease;
        }

        #sidebar.collapsed .user-profile h5,
        #sidebar.collapsed .user-profile small {
            display: none;
        }

        .nav-link {
            color: #ccc;
            padding: 10px 15px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .nav-link i {
            font-size: 1.2rem;
            color: rgb(127, 192, 137);
            width: 28px;
            text-align: center;
        }

        .nav-link:hover i {
            color: #72dd9c;
            transform: scale(1.1);
        }

        #sidebar.collapsed .nav-link span {
            display: none;
        }

        .nav-link.active,
        .nav-link:hover {
            color: #9df0aa;
            border-radius: 5px;
        }

        .logout-container {
            margin-top: auto;
            padding: 15px;
        }

        .logout-btn {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 10px;
            background-color: #2c2c2c;
            color: #9df0aa;
            border: none;
            padding: 10px;
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
        }

        .logout-btn:hover {
            background-color: #3a3a3a;
            color: rgba(65, 65, 65, 0.88);
        }

        .logout-btn i {
            font-size: 1.2rem;
            color: #9df0aa;
        }

        .logout-btn:hover i {
            color: #72dd9c;
            transform: scale(1.1);
        }

        #sidebar.collapsed .logout-btn span {
            display: none;
        }

        #sidebar.collapsed .nav-link,
        #sidebar.collapsed .logout-btn {
            justify-content: center;
        }

        #main-content {
            margin-left: 230px;
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            transition: all 0.3s ease;
        }

        #main-content.collapsed {
            margin-left: 70px;
        }

        nav.navbar {
            background-color: #e3f2fd !important;
        }

        .content {
            flex: 1;
            padding: 20px;
            background: #f8f9fa;
        }

        footer {
            background: linear-gradient(to right, #dee2e6, #e9ecef);
            color: #495057;
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .bell-icon::after {
            display: none !important;
        }

        .bell-icon:hover i {
            transform: scale(1.3);
            color: #28a745;
        }
    </style>
</head>
<body>

<div id="sidebar">
    <h3 class="sidebar-title">
        <i class="fas fa-fire"></i> <span>MyApp</span>
    </h3>

    <div class="user-profile">
        <img src="{{ Auth::user()->profile_picture 
                    ? asset('storage/' . Auth::user()->profile_picture) 
                    : asset('default-avatar.png') }}" 
             alt="Profile Picture" />
        <h5>{{ Auth::user()->name ?? 'User' }}</h5>
        <small>{{ ucfirst(Auth::user()->role ?? 'Role') }}</small>
    </div>

    <nav class="nav flex-column px-2">
        <a href="{{ url('/home') }}" class="nav-link {{ request()->is('home') ? 'active' : '' }}">
            <i class="fas fa-tachometer-alt"></i> <span>{{ __('messages.dashboard') }}</span>
        </a>
        <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.index') ? 'active' : '' }}">
            <i class="fas fa-users"></i> <span>{{ __('messages.users') }}</span>
        </a>
        <a href="{{ route('users.datatable') }}" class="nav-link {{ request()->routeIs('users.datatable') ? 'active' : '' }}">
            <i class="fas fa-list"></i> <span>User List</span>
        </a>
    </nav>

    <div class="logout-container">
        <form id="logout-form" method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn logout-btn">
                <i class="fas fa-sign-out-alt"></i> <span>{{ __('messages.logout') }}</span>
            </button>
        </form>
    </div>
</div>


<div id="main-content">
    <nav class="navbar navbar-expand-lg px-4">
        <span id="toggleSidebar" class="me-3" style="font-size: 1.5rem; cursor: pointer;">☰</span>
        <a class="navbar-brand me-auto" href="#">{{ __('messages.welcome') }}, {{ Auth::user()->name ?? 'User' }}</a>

        <!-- 🔔 Notifications -->
        @php $unreadNotifications = Auth::user()->unreadNotifications; @endphp
        <div class="position-relative dropdown me-3">
            <a class="nav-link position-relative bell-icon" href="#" role="button" id="notifDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-bell fa-lg" style="padding-left:20px"></i>
                @if($unreadNotifications->count())
                    <span class="badge rounded-pill bg-danger position-absolute top-0 start-100 translate-middle"
                          style="font-size: 0.7rem; padding: 0.3em 0.45em;">
                        {{ $unreadNotifications->count() }}
                    </span>
                @endif
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="notifDropdown" style="width: 300px; max-height: 400px; overflow-y: auto;">
                @forelse($unreadNotifications as $notification)
                    <li class="dropdown-item small text-dark">
                        {{ $notification->data['message'] ?? __('messages.new_notification') }}
                        <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button class="btn btn-sm btn-link text-primary p-0">{{ __('messages.mark_as_read') }}</button>
                        </form>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                @empty
                    <li class="dropdown-item text-muted">{{ __('messages.no_notifications') }}</li>
                @endforelse
            </ul>
        </div>

        <!-- 🌐 Language Switcher -->
        <div class="d-flex align-items-center">
            <form action="{{ route('language.switch') }}" method="POST" class="me-1">
                @csrf
                <input type="hidden" name="locale" value="en">
                <button type="submit" class="btn btn-sm btn-outline-secondary">EN</button>
            </form>
            <form action="{{ route('language.switch') }}" method="POST">
                @csrf
                <input type="hidden" name="locale" value="es">
                <button type="submit" class="btn btn-sm btn-outline-secondary">ES</button>
            </form>
        </div>
    </nav>

    <div class="content">
        @yield('content')
    </div>

    <footer>
        © {{ date('Y') }} MyApp — {{ __('messages.footer') }}
    </footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function () {
        $('#toggleSidebar').on('click', function () {
            $('#sidebar').toggleClass('collapsed');
            $('#main-content').toggleClass('collapsed');
        });
    });
</script>
@stack('script')
</body>
</html>
