<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Admin</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito:400,600,700|Quicksand:500,700|Comfortaa:400,700" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Quicksand', 'Nunito', sans-serif;
            background-color: #fff9fb;
        }
        
        .navbar {
            background: linear-gradient(to right, #ffd6e7, #d9f2ff);
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

        .sidebar {
            width: 250px;
            position: fixed;
            top: 56px;
            left: 0;
            height: calc(100% - 56px);
            background: linear-gradient(to bottom, #fff0f5, #f0f9ff);
            padding-top: 20px;
            overflow-y: auto;
            border-right: 2px dashed #ffc0cb;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.05);
        }

        .sidebar a {
            color: #ff6b9d;
            padding: 12px 20px;
            display: block;
            text-decoration: none;
            font-weight: 600;
            border-radius: 0 25px 25px 0;
            margin: 5px 0;
            transition: all 0.3s;
        }

        .sidebar a i {
            width: 25px;
            text-align: center;
            margin-right: 10px;
            color: #a0d2ff;
        }

        .sidebar a:hover {
            background: linear-gradient(to right, #ffe6f0, #e6f7ff);
            color: #ff4f8b;
            transform: translateX(5px);
            box-shadow: 2px 2px 10px rgba(255, 107, 157, 0.1);
        }

        .sidebar a.active {
            background: linear-gradient(to right, #ffcce6, #d9f2ff);
            color: #ff4f8b;
            border-left: 4px solid #ff6b9d;
        }

        main {
            margin-left: 250px;
            padding: 80px 30px 30px 30px;
            min-height: 100vh;
            background-color: #fff9fb;
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

        .nav-link {
            color: #5e72e4;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .nav-link:hover {
            color: #ff6b9d;
        }

        .user-avatar {
            width: 35px;
            height: 35px;
            background-color: #ffe6f0;
            color: #ff6b9d;
            font-weight: bold;
            text-align: center;
            border-radius: 50%;
            line-height: 35px;
        }

        /* Admin specific styles */
        .admin-card {
            background-color: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(255, 182, 193, 0.2);
            border: 1px solid #ffebf1;
        }

        .admin-card-header {
            color: #ff6b9d;
            font-weight: 700;
            border-bottom: 2px dashed #ffd6e7;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        .btn-admin {
            background-color: #ff9ec5;
            color: white;
            border: none;
            border-radius: 20px;
            padding: 8px 20px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-admin:hover {
            background-color: #ff6b9d;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(255, 107, 157, 0.3);
        }

        .btn-admin-outline {
            background-color: transparent;
            color: #ff6b9d;
            border: 2px solid #ff9ec5;
            border-radius: 20px;
            padding: 8px 20px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-admin-outline:hover {
            background-color: #fff0f5;
            color: #ff4f8b;
            transform: translateY(-2px);
        }

        .table-admin {
            background-color: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(255, 182, 193, 0.2);
        }

        .table-admin thead {
            background: linear-gradient(to right, #ffd6e7, #d9f2ff);
            color: #5e72e4;
        }

        .table-admin th {
            border-bottom: 2px dashed #ffc0cb;
        }

        .table-admin tr:hover {
            background-color: #fff9fb;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand navbar-dark fixed-top px-3">
        <div class="container-fluid d-flex justify-content-between">
            <a class="navbar-brand fw-bold">
                <i class="bi bi-bag-heart-fill"></i> SquishIT Admin
            </a>

            <div class="d-flex align-items-center">
                <div class="dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle d-flex align-items-center" href="#"
                        role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="{{ asset('storage/' . Auth::user()->profile_image) }}" alt="Profile Image"
                            class="rounded-circle profile-image" width="40" height="40">
                        <span class="ms-2">{{ Auth::user()->name }}</span>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li>
                            <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                <i class="bi bi-person-circle"></i> Profile
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="sidebar">
        <a href="{{ route('dashboard.index') }}" class="{{ request()->routeIs('dashboard.index') ? 'active' : '' }}">
            <i class="bi bi-house"></i> Dashboard
        </a>
        <a href="{{ route('admin.orders') }}" class="{{ request()->routeIs('admin.orders') ? 'active' : '' }}">
            <i class="bi bi-card-list"></i> Orders
        </a>
        <a href="{{ route('admin.users') }}" class="{{ request()->routeIs('admin.users') ? 'active' : '' }}">
            <i class="bi bi-people"></i> Users
        </a>
        <a href="{{ route('admin.categories') }}" class="{{ request()->routeIs('admin.categories') ? 'active' : '' }}">
            <i class="bi bi-tags"></i> Categories
        </a> 
        <a href="{{ route('admin.items') }}" class="{{ request()->routeIs('admin.items') ? 'active' : '' }}">
            <i class="bi bi-box"></i> Items
        </a>
        <a href="{{ route('admin.reviews') }}" class="{{ request()->routeIs('admin.reviews') ? 'active' : '' }}">
            <i class="bi bi-chat-left-text"></i> Reviews
        </a>
    </div>

    <main>
        @yield('content')
        @yield('body')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>

</html>