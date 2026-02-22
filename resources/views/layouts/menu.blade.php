@auth
    <!-- Sidebar for desktop -->
    <nav id="sidebarDesktop" class="d-none d-lg-block bg-teal text-white vh-100 position-fixed top-0 start-0"
        style="width:250px;">
        <div class="d-flex flex-column h-100">
            <div class="text-center py-3 border-bottom border-light">
                <h3 class="fw-bold mb-0">E-Commerce</h3>
            </div>

            <!-- Menu Items -->
            <ul class="nav flex-column mt-3 px-0">
                <li class="nav-item">
                    <a href="{{ url('/admin/dashboard') }}"
                        class="nav-link px-4 py-2 {{ request()->is('admin/dashboard') ? 'bg-white text-dark fw-bold' : 'text-white' }}">
                        <i class="bi bi-speedometer2 me-2"></i> Dashboard
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ url('/admin/products') }}"
                        class="nav-link px-4 py-2 {{ request()->is('admin/products*') ? 'bg-white text-dark fw-bold' : 'text-white' }}">
                        <i class="bi bi-stack me-2"></i> Products
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/admin/orders') }}"
                        class="nav-link px-4 py-2 {{ request()->is('admin/orders*') ? 'bg-white text-dark fw-bold' : 'text-white' }}">
                        <i class="bi bi-calendar2-check me-2"></i> Orders
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/admin/customers') }}"
                        class="nav-link px-4 py-2 {{ request()->is('admin/customers*') ? 'bg-white text-dark fw-bold' : 'text-white' }}">
                        <i class="bi bi-people me-2"></i> Customers
                    </a>
                </li>
            </ul>

            <div class="mt-auto text-center border-top border-light py-3">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-sm btn-outline-light w-75" type="submit">
                        <i class="bi bi-box-arrow-right me-1"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Offcanvas Sidebar for mobile -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="sidebarMobile" aria-labelledby="sidebarMobileLabel">
        <div class="offcanvas-header bg-teal text-white">
            <h5 class="offcanvas-title" id="sidebarMobileLabel">EMS</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"
                aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-0">
            <ul class="nav flex-column mt-3 px-0">
                <li class="nav-item">
                    <a href="{{ url('/dashboard') }}"
                        class="nav-link px-4 py-2 {{ request()->is('dashboard') ? 'bg-teal text-white fw-bold' : 'text-dark' }}">
                        <i class="bi bi-speedometer2 me-2"></i> Dashboard
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ url('admin/products') }}"
                        class="nav-link px-4 py-2 {{ request()->is('products*') ? 'bg-teal text-white fw-bold' : 'text-dark' }}">
                        <i class="bi bi-people me-2"></i> Products
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/admin/orders') }}"
                        class="nav-link px-4 py-2 {{ request()->is('admin/orders*') ? 'bg-teal text-white fw-bold' : 'text-dark' }}">
                        <i class="bi bi-calendar2-check me-2"></i> Orders
                    </a>
                </li>
            </ul>

            <div class="mt-auto text-center border-top border-light py-3">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-sm btn-outline-dark w-75" type="submit">
                        <i class="bi bi-box-arrow-right me-1"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
@endauth
