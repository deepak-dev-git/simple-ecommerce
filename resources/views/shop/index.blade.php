@extends('layouts.app')

@section('content')
    <div class="container">
        {{-- OFFER MARQUEE --}}
        <div class="offer-marquee mb-3">
            <marquee behavior="scroll" direction="left">
                 50% offer for prepaid orders • Free Delivery above ₹999 • Limited Time Sale • Save ₹199 for the First Order • Assured Cashbacks
            </marquee>
        </div>


        {{-- HERO BANNER CAROUSEL --}}
        <div id="shopBannerCarousel" class="carousel slide mb-4 shadow-sm rounded overflow-hidden" data-bs-ride="carousel">

            {{-- Indicators --}}
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#shopBannerCarousel" data-bs-slide-to="0" class="active"></button>
                <button type="button" data-bs-target="#shopBannerCarousel" data-bs-slide-to="1"></button>
                <button type="button" data-bs-target="#shopBannerCarousel" data-bs-slide-to="2"></button>
            </div>

            <div class="carousel-inner">

                {{-- Banner 1 --}}
                <div class="carousel-item active">
                    <img src="{{ asset('/banners/bg3.jpeg') }}" class="d-block w-100 banner-img">
                </div>

                {{-- Banner 2 --}}
                <div class="carousel-item">
                    <img src="{{ asset('/banners/bg2.jpeg') }}" class="d-block w-100 banner-img">
                </div>

                {{-- Banner 3 --}}
                <div class="carousel-item">
                    <img src="{{ asset('/banners/bg1.png') }}" class="d-block w-100 banner-img">
                </div>

            </div>

            {{-- Controls --}}
            {{-- <button class="carousel-control-prev" type="button"
            data-bs-target="#shopBannerCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
    </button>

    <button class="carousel-control-next" type="button"
            data-bs-target="#shopBannerCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
    </button> --}}

        </div>
        <!-- HEADER + SEARCH -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">

            <div>
                <h3 class="fw-bold mb-0">Explore Products</h3>
                <small class="text-muted">
                    Discover amazing items
                </small>
            </div>

            {{-- <!-- SEARCH -->
            <form method="GET" action="{{ route('shop.index') }}" class="d-flex">
                <div class="input-group shadow-sm">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="fa fa-search text-muted"></i>
                    </span>

                    <input type="text" name="search" value="{{ request('search') }}" class="form-control border-start-0"
                        placeholder="Search products...">

                    <button class="btn btn-primary px-4">
                        Search
                    </button>
                </div>
            </form> --}}

        </div>

        <!-- PRODUCT GRID -->
        <div class="row g-4">

            @forelse($products as $product)
                @php
                    $price = $product->discounted_price ?? $product->price;
                    $firstImage = $product->images[0] ?? null;
                @endphp

                <div class="col-6 col-md-4 col-lg-3">

                    <div class="card product-card h-100 border-0 shadow-sm product-clickable"
                        data-url="{{ route('shop.show', $product->id) }}">

                        <!-- IMAGE -->
                        <div class="product-image-wrapper">

                            @if ($product->discount > 0)
                                <span class="badge bg-danger product-badge">
                                    -{{ $product->discount }}%
                                </span>
                            @endif

                            <img src="{{ $firstImage ? asset('storage/' . $firstImage) : 'https://via.placeholder.com/400x300' }}"
                                class="card-img-top product-image">

                            <a href="{{ route('shop.show', $product->id) }}" class="product-view-btn">
                                <i class="fa fa-eye"></i>
                            </a>

                        </div>

                        <!-- BODY -->
                        <div class="card-body d-flex flex-column">

                            <h6 class="fw-semibold mb-2 product-title">
                                {{ $product->name }}
                            </h6>

                            <!-- PRICE -->
                            <div class="mb-3">

                                @if ($product->discount > 0)
                                    <span class="text-muted text-decoration-line-through small">
                                        ₹{{ $product->price }}
                                    </span>

                                    <div class="fw-bold text-danger fs-5">
                                        ₹{{ $price }}
                                    </div>
                                @else
                                    <div class="fw-bold fs-5">
                                        ₹{{ $product->price }}
                                    </div>
                                @endif

                            </div>

                            <!-- ACTION AREA -->
                            <div class="mt-auto cart-action" onclick="event.stopPropagation()">

                                <form action="{{ route('cart.add', $product->id) }}" method="POST"
                                    class="add-cart-form d-flex flex-wrap flex-sm-nowrap align-items-center gap-2 w-100">
                                    @csrf

                                    {{-- Quantity Controls --}}
                                    <div class="qty-wrapper d-none">
                                        <button type="button" class="qty-btn minus">−</button>

                                        <input type="number" name="quantity" value="1" min="1"
                                            class="qty-input">

                                        <button type="button" class="qty-btn plus">+</button>
                                    </div>

                                    {{-- Main Button --}}
                                    <button type="button" class="btn btn-primary w-100 add-cart-btn">
                                        <i class="fa fa-shopping-cart me-1"></i>
                                        Add to Cart
                                    </button>

                                    {{-- Final Submit --}}
                                    <button type="submit" class="btn btn-success confirm-cart d-none">
                                        Add
                                    </button>

                                </form>

                            </div>

                        </div>
                    </div>

                </div>

            @empty

                <!-- EMPTY STATE -->
                <div class="text-center py-5">
                    <i class="fa fa-box-open fa-3x text-muted mb-3"></i>
                    <h5>No products found</h5>
                    <p class="text-muted">
                        Try searching something else.
                    </p>
                </div>
            @endforelse

        </div>

        <!-- PAGINATION -->
        <div class="d-flex justify-content-center mt-5">
            {{ $products->appends(request()->query())->links() }}
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // CARD CLICK REDIRECT
            // document.querySelectorAll('.product-clickable').forEach(card => {
            //     card.addEventListener('click', function() {
            //         window.location.href = this.dataset.url;
            //     });
            // });

            // SHOW QUANTITY UI
            document.querySelectorAll('.add-cart-btn').forEach(btn => {

                btn.addEventListener('click', function(e) {
                    e.stopPropagation();

                    const form = this.closest('.add-cart-form');

                    form.querySelector('.qty-wrapper').classList.remove('d-none');
                    form.querySelector('.confirm-cart').classList.remove('d-none');

                    this.classList.add('d-none');
                });
            });

            // PLUS / MINUS
            document.querySelectorAll('.plus').forEach(btn => {
                btn.onclick = () => {
                    const input = btn.parentElement.querySelector('.qty-input');
                    input.value = parseInt(input.value) + 1;
                };
            });

            document.querySelectorAll('.minus').forEach(btn => {
                btn.onclick = () => {
                    const input = btn.parentElement.querySelector('.qty-input');
                    if (input.value > 1)
                        input.value = parseInt(input.value) - 1;
                };
            });

        });
    </script>
@endsection
