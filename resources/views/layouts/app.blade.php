<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Public Page')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .content {
            flex: 1;
            padding: 40px 20px;
            display: flex;
            justify-content: center;  /* horizontally center */
            align-items: center;      /* vertically center */
            flex-direction: column;
            text-align: center;
        }
        footer {
            background: linear-gradient(to right, #dee2e6, #e9ecef);
            color: #495057;
            height: 56px;
            font-size: 0.9rem;
            font-weight: 500;
        }
        .btn-animated {
            transition: all 0.3s ease-in-out;
        }
        .btn-animated:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        /* Custom button colors for light green */
        .btn-lightgreen {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
        }
        .btn-lightgreen:hover, 
        .btn-lightgreen:focus {
            background-color: #c3e6cb;
            border-color: #b1dfbb;
            color: #0b2e13;
            box-shadow: 0 4px 8px rgba(40, 167, 69, 0.4);
        }

        /* Dark color for brand name */
        .navbar-brand {
            color: hsl(131, 46.20%, 23.30%) !important;  /* Dark color */
            font-weight: 700;
            cursor: default; /* No pointer on hover */
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg" style="background-color: #e3f2fd;">
    <div class="container-fluid px-4">
        <a class="navbar-brand" href="#">Company</a>
        <div class="ms-auto d-flex">
            @if(Route::currentRouteName() === 'register')
                <!-- On Register page, show only Login button -->
                <a href="{{ route('login.page') }}" class="btn btn-lightgreen btn-animated">Login</a>
            @elseif(Route::currentRouteName() === 'login.page')
                <!-- On Login page, show only Register button -->
                <a href="{{ route('register') }}" class="btn btn-lightgreen btn-animated">Register</a>
            @else
                <!-- On other pages, show both buttons -->
                <a href="{{ route('register') }}" class="btn btn-lightgreen me-2 btn-animated">Register</a>
                <a href="{{ route('login.page') }}" class="btn btn-lightgreen btn-animated">Login</a>
            @endif
        </div>
    </div>
</nav>

    <!-- Page Content -->
    <div class="content text-center">
        @yield('content')
    </div>

    <!-- Footer -->
    <footer class="d-flex justify-content-center align-items-center">
        <span>© {{ date('Y') }} MyApp — Empowering Your Admin Experience.</span>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
</body>
</html>
