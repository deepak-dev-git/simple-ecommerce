<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Shop') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <!-- Vite -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <!-- Custom -->
    <link href="{{ url('css/custom.css') }}" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

</head>

<body>

    <div id="app">

        <nav class="navbar navbar-expand-lg main-navbar py-3">
            <div class="container d-flex flex-wrap flex-lg-nowrap align-items-center justify-content-between">

                <!-- BRAND -->
                <a class="navbar-brand brand-logo me-lg-3" href="{{ route('shop.index') }}">
                    {{ config('app.name', 'Shop') }}
                </a>

                <!-- SEARCH FORM (always visible) -->
                <form id="navbarSearchForm" method="GET" action="{{ route('shop.index') }}"
                    class="navbar-search flex-grow-1 my-2 my-lg-0 mx-lg-auto w-100 w-lg-auto position-relative"
                    style="max-width:500px;">
                    <div class="input-group w-100">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="fa fa-search text-muted"></i>
                        </span>
                        <input type="text" id="searchInput" name="search" class="form-control border-start-0"
                            placeholder="Search products..." autocomplete="off">
                        <button class="btn btn-primary px-3" disabled>Search</button>
                    </div>

                    <div id="searchSuggestions" class="search-suggestions d-none"></div>
                </form>

                <!-- TOGGLER FOR MOBILE -->
                <button class="navbar-toggler ms-2" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarContent">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- RIGHT LINKS -->
                <div class="collapse navbar-collapse justify-content-end mt-2 mt-lg-0" id="navbarContent">
                    <ul class="navbar-nav ms-auto align-items-center gap-lg-2">
                        {{-- Auth / Cart links as before --}}
                        @auth
                            {{-- CART --}}
                            @if (auth()->user()->is_admin == 0)
                                <li class="nav-item">
                                    <a href="{{ route('cart.index') }}" class="nav-link position-relative">
                                        <i class="fa fa-shopping-cart fa-lg"></i>
                                        @php
                                            $cartCount = \App\Models\CartItem::where('user_id', auth()->id())->sum(
                                                'quantity',
                                            );
                                        @endphp
                                        @if ($cartCount > 0)
                                            <span
                                                class="position-absolute top-0 start-100 translate-middle badge bg-danger rounded-pill cart-badge">
                                                {{ $cartCount }}
                                            </span>
                                        @endif
                                    </a>
                                </li>
                            @endif

                            <!-- USER DROPDOWN -->
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle d-flex align-items-center gap-2"
                                    data-bs-toggle="dropdown" href="#">
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                                        style="width:32px;height:32px;font-size:14px;">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </div>
                                    <span class="fw-semibold">{{ Auth::user()->name }}</span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                    @if (auth()->user()->is_admin == 0)
                                        <li>
                                            <a class="dropdown-item" href="{{ route('orders.index') }}">
                                                <i class="fa fa-box me-2"></i> My Orders
                                            </a>
                                        </li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                    @endif
                                    <li>
                                        <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                            <i class="fa fa-sign-out-alt me-2"></i> Logout
                                        </a>
                                    </li>
                                </ul>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf
                                </form>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">Login</a>
                            </li>
                            <li class="nav-item">
                                <a class="btn btn-primary rounded-pill px-4" href="{{ route('register') }}">Create
                                    Account</a>
                            </li>
                        @endauth
                    </ul>
                </div>
            </div>
        </nav>

        <!-- FLASH -->
        <div class="container mt-3">

            @if (session('success'))
                <div class="alert alert-success shadow-sm alert-dismissible fade show">
                    {{ session('success') }}
                    <button class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger shadow-sm alert-dismissible fade show">
                    {{ session('error') }}
                    <button class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

        </div>

        <!-- MAIN -->
        <main class="page-wrapper py-4">
            @yield('content')
        </main>

    </div>
    <script>
        const productShowUrl = "{{ route('shop.show', ':id') }}";
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const input = document.getElementById('searchInput');
            const box = document.getElementById('searchSuggestions');

            let debounceTimer;

            input.addEventListener('keyup', function() {

                let query = this.value.trim();

                clearTimeout(debounceTimer);

                if (query.length < 2) {
                    box.classList.add('d-none');
                    return;
                }

                debounceTimer = setTimeout(() => {

                    fetch("{{ route('shop.suggestions') }}?search=" + query)
                        .then(res => res.json())
                        .then(data => {

                            if (!data.length) {
                                box.classList.add('d-none');
                                return;
                            }

                            let html = '';

                            data.forEach(product => {

                                let url = productShowUrl.replace(':id', product.id);

                                html += `
                            <a href="${url}"
                               class="search-item text-decoration-none text-dark d-block">
                                ${product.name}
                            </a>
                        `;
                            });

                            box.innerHTML = html;
                            box.classList.remove('d-none');
                        });

                }, 300);
            });

            // Hide dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.navbar-search')) {
                    box.classList.add('d-none');
                }
            });

        });
    </script>
    <script>
        document.getElementById('navbarSearchForm')
            .addEventListener('submit', function(e) {

                const input = document.getElementById('searchInput');
                const value = input.value.trim();

                // prevent empty submission
                if (value === '') {
                    e.preventDefault();
                    input.focus();
                    return false;
                }

                // optional: reset suggestions
                document.getElementById('searchSuggestions')
                    .classList.add('d-none');
            });
    </script>

    <script>
        const input = document.getElementById('searchInput');
        const button = document.querySelector('#navbarSearchForm button');

        input.addEventListener('input', function() {
            button.disabled = input.value.trim() === '';
        });
    </script>
    @yield('scripts')
</body>

</html>
