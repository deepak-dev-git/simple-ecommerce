<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Shop') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Vite Assets -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <!-- Custom CSS -->
    <link href="{{ url('css/custom.css') }}" rel="stylesheet" type="text/css" />
</head>

<body>
<div id="app">

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm py-3">
        <div class="container">

            <!-- Logo (Fixed) -->
            <a class="navbar-brand fw-bold fs-4"
               href="{{ route('shop.index') }}">
                {{ config('app.name', 'Shop') }}
            </a>

            <button class="navbar-toggler"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarContent">

                <!-- LEFT SIDE -->
                {{-- <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link"
                           href="{{ route('shop.index') }}">
                            <i class="fa fa-store me-1"></i> Shop
                        </a>
                    </li>
                </ul> --}}

                <!-- RIGHT SIDE -->
                <ul class="navbar-nav ms-auto align-items-center">

                    @auth

                        {{-- Cart (Only for Customers) --}}
                        @if(auth()->user()->is_admin == 0)
                            <li class="nav-item me-3">
                                <a href="{{ route('cart.index') }}"
                                   class="nav-link position-relative">
                                    <i class="fa fa-shopping-cart fa-lg"></i>

                                    @php
                                        $cartCount = \App\Models\CartItem::where('user_id', auth()->id())
                                                        ->sum('quantity');
                                    @endphp

                                    @if($cartCount > 0)
                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                            {{ $cartCount }}
                                        </span>
                                    @endif
                                </a>
                            </li>
                        @endif

                        <!-- User Dropdown -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle fw-semibold"
                               href="#"
                               role="button"
                               data-bs-toggle="dropdown">
                                <i class="fa fa-user me-1"></i>
                                {{ Auth::user()->name }}
                            </a>

                            <ul class="dropdown-menu dropdown-menu-end shadow border-0">

                                {{-- Customer Orders --}}
                                @if(auth()->user()->is_admin == 0)
                                    <li>
                                        <a class="dropdown-item"
                                           href="{{ route('orders.index') }}">
                                            <i class="fa fa-box me-2"></i>
                                            My Orders
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                @endif

                                <li>
                                    <a class="dropdown-item"
                                       href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();">
                                        <i class="fa fa-sign-out-alt me-2"></i>
                                        Logout
                                    </a>
                                </li>
                            </ul>

                            <form id="logout-form"
                                  action="{{ route('logout') }}"
                                  method="POST"
                                  class="d-none">
                                @csrf
                            </form>
                        </li>

                    @else

                        <!-- Guest Links -->
                        <li class="nav-item">
                            <a class="nav-link"
                               href="{{ route('login') }}">
                                Login
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="btn btn-primary ms-2 px-4"
                               href="{{ route('register') }}">
                                Register
                            </a>
                        </li>

                    @endauth

                </ul>
            </div>
        </div>
    </nav>

    <!-- FLASH MESSAGES -->
    @if(session('success'))
        <div class="container mt-3">
            <div class="alert alert-success alert-dismissible fade show shadow-sm">
                {{ session('success') }}
                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="container mt-3">
            <div class="alert alert-danger alert-dismissible fade show shadow-sm">
                {{ session('error') }}
                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    <!-- MAIN CONTENT -->
    <main class="py-4">
        @yield('content')
    </main>

</div>

@yield('scripts')
</body>
</html>
