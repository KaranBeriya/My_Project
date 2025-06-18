<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
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
            justify-content: center;
            align-items: center;
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
        .navbar-brand {
            color: hsl(131, 46.20%, 23.30%) !important;
            font-weight: 700;
            cursor: default;
        }
        footer {
            background: linear-gradient(to right, #dee2e6, #e9ecef);
            color: #495057;
            font-size: 0.9rem;
            font-weight: 500;
            height: 56px;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg" style="background-color: #e3f2fd;">
        <div class="container-fluid px-4">
            <a class="navbar-brand" href="#">Company</a>

            <div class="ms-auto d-flex align-items-center">
                <!-- 🌐 Language Switch -->
                <div class="dropdown me-3">
                    <button class="btn btn-lightgreen dropdown-toggle" type="button" id="languageDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        @if(app()->getLocale() === 'es')
                            🇪🇸 Español
                        @else
                            🇬🇧 English
                        @endif
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="languageDropdown">
                        <li><a class="dropdown-item" href="{{ route('lang.switch', 'en') }}">🇬🇧 English</a></li>
                        <li><a class="dropdown-item" href="{{ route('lang.switch', 'es') }}">🇪🇸 Español</a></li>
                    </ul>
                </div>

                <!-- Auth Links -->
                @if(Route::currentRouteName() === 'register')
                    <a href="{{ route('login.page') }}" class="btn btn-lightgreen btn-animated">{{ __('messages.login') }}</a>
                @elseif(Route::currentRouteName() === 'login.page')
                    <a href="{{ route('register') }}" class="btn btn-lightgreen btn-animated">{{ __('messages.register') }}</a>
                @else
                    <a href="{{ route('register') }}" class="btn btn-lightgreen me-2 btn-animated">{{ __('messages.register') }}</a>
                    <a href="{{ route('login.page') }}" class="btn btn-lightgreen btn-animated">{{ __('messages.login') }}</a>
                @endif
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <div class="content text-center">
        @yield('content')
    </div>

    <!-- Footer -->
    <footer class="text-center">
        <span>© {{ date('Y') }} MyApp — {{ __('messages.footer') }}</span>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
