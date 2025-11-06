<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css?family=Nunito:400,600,700" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        body {
            background-color: #f8fafc;
            font-family: 'Nunito', sans-serif;
        }

        /* Sidebar Styling */
        .app-sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, #ffffff, #f1f3f6);
            border-right: 1px solid #dee2e6;
            padding: 1.5rem 0;
            box-shadow: 2px 0 8px rgba(0, 0, 0, 0.05);
        }

        .app-sidebar .nav-link {
            color: #495057;
            font-weight: 500;
            padding: 0.75rem 1.25rem;
            border-radius: 8px;
            transition: all 0.2s ease-in-out;
            margin: 2px 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .app-sidebar .nav-link i {
            font-size: 1rem;
            opacity: 0.7;
        }

        .app-sidebar .nav-link:hover {
            background-color: #e9ecef;
            color: #212529;
        }

        .app-sidebar .nav-link.active {
            background-color: #0d6efd;
            color: #fff;
            font-weight: 600;
            box-shadow: 0 2px 6px rgba(13, 110, 253, 0.25);
        }

        .app-sidebar .nav-link.active i {
            opacity: 1;
        }

        /* Navbar Styling */
        .navbar {
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            background-color: #fff !important;
        }

        /* Main Content */
        main {
            background-color: #fff;
            border-radius: 12px;
            padding: 1.5rem;
            margin-top: 1rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        /* Mobile Sidebar Button */
        .mobile-menu {
            display: none;
        }

        @media (max-width: 767.98px) {
            .app-sidebar {
                display: none;
            }
            .mobile-menu {
                display: block;
                background: #fff;
                border-bottom: 1px solid #dee2e6;
                padding: 0.5rem 1rem;
            }
            .mobile-menu .btn {
                margin-right: 4px;
            }
        }
    </style>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>

<body>
<div id="app">
    {{-- Navbar --}}
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold text-primary" href="{{ url('/') }}">
                <i class="fa fa-boxes me-2"></i>{{ config('app.name', 'Laravel') }}
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                {{-- Right --}}
                <ul class="navbar-nav ms-auto">
                    @guest
                        @if (Route::has('login'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                        @endif

                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>
                        @endif
                    @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle fw-semibold" href="#" role="button"
                               data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                <i class="fa fa-user-circle me-1"></i>{{ Auth::user()->name }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fa fa-sign-out-alt me-2"></i>{{ __('Logout') }}
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

    {{-- Mobile Menu --}}
    <div class="mobile-menu">
        <a class="btn btn-outline-primary btn-sm {{ Request::is('master-items*') ? 'active' : '' }}" href="{{ url('master-items') }}">
            <i class="fa fa-cubes"></i> Items
        </a>
        <a class="btn btn-outline-primary btn-sm {{ Request::is('kategori-items*') ? 'active' : '' }}" href="{{ url('kategori-items') }}">
            <i class="fa fa-tags"></i> Kategori
        </a>
    </div>

    <div class="container-fluid">
        <div class="row">
            {{-- Sidebar --}}
            <aside class="col-md-2 app-sidebar d-none d-md-block">
                <nav class="nav flex-column px-3">
                    <a class="nav-link {{ Request::is('master-items*') ? 'active' : '' }}" href="{{ url('master-items') }}">
                        <i class="fa fa-cubes"></i> Master Items
                    </a>
                    <a class="nav-link {{ Request::is('kategori-items*') ? 'active' : '' }}" href="{{ url('kategori-items') }}">
                        <i class="fa fa-tags"></i> Kategori Items
                    </a>
                </nav>
            </aside>

            {{-- Main Content --}}
            <main class="col-md-10 col-12">
                @yield('content')
            </main>
        </div>
    </div>
</div>

@yield('js')
</body>
</html>
