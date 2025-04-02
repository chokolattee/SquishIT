<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito:400,600,700|Quicksand:500,700|Comfortaa:400,700" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <!-- Bootstrap JS (Include before closing </body>) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        body {
            font-family: 'Quicksand', 'Nunito', sans-serif;
            background-color: #fcf9ff;
        }
        
        .navbar {
            background: linear-gradient(to right, #ffcce6, #e6f7ff);
            border-bottom: 3px dashed #ffc0cb;
            padding: 0.6rem 0;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        }

        .navbar-brand {
            font-family: 'Comfortaa', cursive;
            color: #ff6b9d;
            font-weight: 700;
            font-size: 1.8rem;
            letter-spacing: 0.5px;
            text-shadow: 1px 1px 2px rgba(255, 255, 255, 0.8);
            transition: transform 0.3s;
        }
        
        .navbar-brand:hover {
            transform: scale(1.05);
            color: #ff4f8b;
        }
        
        .navbar-brand i {
            color: #ff4f8b;
            filter: drop-shadow(0 0 2px rgba(255, 255, 255, 0.8));
        }

        .container {
            max-width: 96%;
            padding-left: 1rem;
            padding-right: 1rem;
        }
        
        .navbar-nav .nav-link {
            color: #5e72e4;
            font-weight: 600;
            margin: 0 0.4rem;
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            transition: all 0.3s;
        }
        
        .navbar-nav .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.5);
            color: #ff6b9d;
            transform: translateY(-2px);
        }
        
        .search-form {
            position: relative;
        }
        
        .search-form .form-control {
            border-radius: 20px;
            border: 2px solid #d9ecff;
            padding-left: 1rem;
            background-color: rgba(255, 255, 255, 0.8);
            transition: all 0.3s;
        }
        
        .search-form .form-control:focus {
            box-shadow: 0 0 10px rgba(255, 107, 157, 0.3);
            border-color: #ff6b9d;
            background-color: white;
        }
        
        .search-btn {
            border-radius: 20px;
            background-color: #a0d2ff;
            color: #5e72e4;
            border: none;
            padding: 0.375rem 1rem;
            transition: all 0.3s;
        }
        
        .search-btn:hover {
            background-color: #ff6b9d;
            color: white;
            transform: scale(1.05);
        }
        
        .badge {
            position: relative;
            top: -8px;
            left: -4px;
            font-size: 0.65rem;
            border-radius: 50%;
            padding: 0.25em 0.5em;
            background-color: #ff6b9d;
            color: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .auth-btn {
            border-radius: 20px;
            padding: 0.4rem 1.2rem;
            font-weight: bold;
            transition: all 0.3s;
            margin: 0 0.3rem;
        }
        
        .login-btn {
            background-color: #a0d2ff;
            color: #5e72e4;
            border: none;
        }
        
        .login-btn:hover {
            background-color: #5e72e4;
            color: white;
            transform: translateY(-2px);
        }
        
        .register-btn {
            background-color: #ff9ec5;
            color: #ff4f8b;
            border: none;
        }
        
        .register-btn:hover {
            background-color: #ff4f8b;
            color: white;
            transform: translateY(-2px);
        }
        
        .dropdown-menu {
            border-radius: 15px;
            border: 2px dashed #ffc0cb;
            background-color: rgba(255, 255, 255, 0.95);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }
        
        .dropdown-item {
            color: #5e72e4;
            font-weight: 600;
            padding: 0.5rem 1.5rem;
            transition: all 0.2s;
        }
        
        .dropdown-item:hover {
            background-color: #f0f8ff;
            color: #ff6b9d;
            transform: translateX(5px);
        }
        
        .profile-image {
            border: 3px solid white;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: all 0.3s;
        }
        
        .navbar-toggler {
            border: 2px solid #ff6b9d;
        }
        
        .navbar-toggler:focus {
            box-shadow: 0 0 0 0.25rem rgba(255, 107, 157, 0.25);
        }
    </style>
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                    <i class="bi bi-bag-heart-fill me-2"></i> SquishIT
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/') }}">
                                <i class="bi bi-house-heart"></i> Home
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('getCart') }}">
                                <i class="bi bi-cart3"></i> Cart
                                <span class="badge">
                                    {{ session()->has('cart') ? count(session()->get('cart')->items) : 0 }}
                                </span>
                            </a>
                        </li>
                        @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('orders.my') }}">
                                <i class="bi bi-box-seam"></i> My Orders
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('reviews.index') }}">
                                <i class="bi bi-star"></i> My Reviews
                            </a>
                        </li>
                        @endauth
                    </ul>
                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Search Form -->
                        <form class="d-flex search-form me-3" action="{{ route('search') }}" method="GET">
                            <input class="form-control me-2" type="search" name="term" placeholder="Find a squishie..." aria-label="Search">
                            <button class="btn search-btn" type="submit">
                                <i class="bi bi-search"></i>
                            </button>
                        </form>

                        @guest
                        @if (Route::has('login'))
                        <li class="nav-item">
                            <a class="nav-link auth-btn login-btn" href="{{ route('login') }}">
                                <i class="bi bi-person-check"></i> {{ __('Login') }}
                            </a>
                        </li>
                        @endif

                        @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link auth-btn register-btn" href="{{ route('register') }}">
                                <i class="bi bi-person-plus"></i> {{ __('Register') }}
                            </a>
                        </li>
                        @endif
                        @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img src="{{ asset('storage/' . Auth::user()->profile_image) }}" alt="Profile Image" class="rounded-circle profile-image" width="40" height="40">
                                <span class="ms-2">{{ Auth::user()->name }}</span>
                            </a>

                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    <i class="bi bi-person-circle"></i> Profile
                                </a>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="bi bi-box-arrow-right"></i> Logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
            @yield('body')
        </main>
    </div>
</body>

</html>