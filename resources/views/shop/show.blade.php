@extends('layouts.app')

@section('content')
    <div class="container py-4">

        @php
            $price = $product->discounted_price ?? $product->price;
            $images = $product->images ?? [];
        @endphp

        <div class="row g-5">

            <!-- PRODUCT IMAGES -->
            <div class="col-lg-6">

                <div class="product-gallery shadow-sm rounded p-3 bg-white">

                    <div class="text-center mb-3">
                        <img id="mainImage"
                            src="{{ count($images) ? asset('storage/' . $images[0]) : 'https://via.placeholder.com/600x400' }}"
                            class="img-fluid rounded main-product-image">
                    </div>

                    <!-- THUMBNAILS -->
                    <div class="d-flex justify-content-center gap-2 flex-wrap">
                        @foreach ($images as $image)
                            <img src="{{ asset('storage/' . $image) }}" class="thumb-img" onclick="changeImage(this)">
                        @endforeach
                    </div>

                </div>

            </div>

            <!-- PRODUCT INFO -->
            <div class="col-lg-6">

                <div class="bg-white shadow-sm rounded p-4 h-100">

                    <h3 class="fw-bold mb-3">{{ $product->name }}</h3>

                    <!-- PRICE -->
                    @if ($product->discount > 0)
                        <div class="mb-3">
                            <span class="text-muted text-decoration-line-through">
                                ₹{{ number_format($product->price, 2) }}
                            </span>

                            <span class="price-highlight ms-2">
                                ₹{{ number_format($price, 2) }}
                            </span>

                            <span class="badge bg-danger ms-2">
                                -{{ $product->discount }}%
                            </span>
                        </div>
                    @else
                        <div class="price-highlight mb-3">
                            ₹{{ number_format($product->price, 2) }}
                        </div>
                    @endif

                    <!-- STOCK -->
                    @if ($product->stock_quantity > 0)
                        <p class="text-success fw-semibold">
                            ✔ In Stock ({{ $product->stock_quantity }} available)
                        </p>
                    @else
                        <p class="text-danger fw-semibold">
                            Out of Stock
                        </p>
                    @endif

                    @if ($alreadyAddedQty > 0)
                        <p class="text-muted small">
                            Already {{ $alreadyAddedQty }} item(s) in cart
                        </p>
                    @endif

                    <!-- ADD TO CART -->
                    @if ($product->stock_quantity > 0)
                        <form action="{{ route('cart.add', $product->id) }}" method="POST" class="mt-4">
                            @csrf

                            <div class="d-flex gap-3 align-items-center">

                                {{-- Quantity Selector --}}
                                <div class="input-group qty-wrapper" style="width:140px;">

                                    <button type="button" class="btn btn-outline-secondary qty-minus">
                                        <i class="fa fa-minus"></i>
                                    </button>

                                    <input type="number" name="quantity" value="1" min="1"
                                        max="{{ $product->stock_quantity }}" class="form-control text-center qty-input">

                                    <button type="button" class="btn btn-outline-secondary qty-plus">
                                        <i class="fa fa-plus"></i>
                                    </button>

                                </div>

                                {{-- Add to cart --}}
                                <button class="btn btn-success px-4 add-cart-btn">
                                    <i class="fa fa-cart-shopping me-2"></i>
                                    Add to Cart
                                </button>

                            </div>
                        </form>
                    @endif
                    {{-- DESCRIPTION --}}
                    @if ($product->description)
                        <hr class="my-4 opacity-25">

                        <h6 class="fw-bold mb-2">Description</h6>

                        <p class="text-muted description-text mb-0">
                            {{ $product->description }}
                        </p>
                    @endif
                </div>

            </div>

        </div>

        <!-- EXPLORE MORE -->
        @if ($exploreProducts->count())
            <div class="mt-5">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="fw-bold mb-0">Explore More Products</h4>
                    <a href="{{ route('shop.index') }}" class="text-decoration-none">
                        View All →
                    </a>
                </div>

                <div class="row g-4">

                    @foreach ($exploreProducts as $item)
                        @php
                            $img = $item->images[0] ?? null;
                            $p = $item->discounted_price ?? $item->price;
                        @endphp

                        <div class="col-6 col-md-3">

                            <a href="{{ route('shop.show', $item->id) }}" class="text-decoration-none text-dark">

                                <div class="card explore-card border-0 shadow-sm h-100">

                                    <img src="{{ $img ? asset('storage/' . $img) : 'https://via.placeholder.com/300x200' }}"
                                        class="card-img-top explore-img">

                                    <div class="card-body">
                                        <h6 class="fw-semibold small">
                                            {{ $item->name }}
                                        </h6>

                                        <div class="fw-bold">
                                            ₹{{ $p }}
                                        </div>
                                    </div>

                                </div>

                            </a>
                        </div>
                    @endforeach

                </div>

            </div>
        @endif

    </div>


    <script>
        function changeImage(el) {
            document.getElementById('mainImage').src = el.src;
        }
    </script>

    <script>
        $(function() {
            $('.qty-plus').click(function() {
                let input = $(this).siblings('.qty-input');
                let max = parseInt(input.attr('max'));
                let value = parseInt(input.val()) || 1;

                if (value < max) {
                    input.val(value + 1);
                }
            });

            $('.qty-minus').click(function() {
                let input = $(this).siblings('.qty-input');
                let min = parseInt(input.attr('min'));
                let value = parseInt(input.val()) || 1;

                if (value > min) {
                    input.val(value - 1);
                }
            });
        });
    </script>
@endsection
