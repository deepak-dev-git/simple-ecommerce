@extends('layouts.app')

@section('content')
<div class="container py-4">

    {{-- Success Alert --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @php
        $price = $product->discounted_price ?? $product->price;
        $images = $product->images ?? [];
    @endphp

    <div class="card border-0 shadow-sm p-3 p-md-4">
        <div class="row g-4 align-items-start">

            {{-- Product Images --}}
            <div class="col-lg-6">

                @if(count($images))
                    <div class="mb-3 text-center">
                        <img id="mainImage"
                             src="{{ asset('storage/' . $images[0]) }}"
                             class="img-fluid rounded"
                             style="max-height:380px; object-fit:cover;">
                    </div>

                    {{-- Thumbnails --}}
                    <div class="d-flex flex-wrap justify-content-center gap-2">
                        @foreach($images as $image)
                            <img src="{{ asset('storage/' . $image) }}"
                                 class="img-thumbnail"
                                 style="width:70px; height:70px; object-fit:cover; cursor:pointer;"
                                 onclick="document.getElementById('mainImage').src=this.src;">
                        @endforeach
                    </div>
                @else
                    <img src="https://via.placeholder.com/600x400"
                         class="img-fluid rounded w-100">
                @endif

            </div>

            {{-- Product Info --}}
            <div class="col-lg-6">

                <h3 class="fw-bold mb-3">{{ $product->name }}</h3>

                {{-- Price --}}
                @if ($product->discount > 0)
                    <div class="mb-3">
                        <span class="text-muted text-decoration-line-through">
                            ₹{{ number_format($product->price, 2) }}
                        </span>
                        <span class="text-danger fs-4 fw-bold ms-2">
                            ₹{{ number_format($price, 2) }}
                        </span>
                        <span class="badge bg-danger ms-2">
                            -{{ $product->discount }}%
                        </span>
                    </div>
                @else
                    <div class="mb-3">
                        <span class="fs-4 fw-bold text-dark">
                            ₹{{ number_format($product->price, 2) }}
                        </span>
                    </div>
                @endif

                {{-- Stock --}}
                @if($product->stock_quantity > 0)
                    <p class="text-success small fw-semibold mb-2">
                        In Stock ({{ $product->stock_quantity }} available)
                    </p>
                    @if($alreadyAddedQty > 0)
                    <p class="text-danger small fw-semibold mb-2">
                        Already added ({{ $alreadyAddedQty }} item(s) in your cart )
                    </p>
                    @endif
                @else
                    <p class="text-danger small fw-semibold mb-2">
                        Out of Stock
                    </p>
                @endif

                {{-- Quantity + Add to Cart --}}
                @if($product->stock_quantity > 0)
                    <form action="{{ route('cart.add', $product->id) }}" method="POST" class="mt-3">
                        @csrf

                        <div class="d-flex align-items-center gap-2">

                            {{-- Quantity --}}
                            <input type="number"
                                   name="quantity"
                                   value="1"
                                   min="1"
                                   max="{{ $product->stock_quantity }}"
                                   class="form-control"
                                   style="width:90px;">

                            {{-- Add to Cart --}}
                            <button class="btn btn-success px-4">
                                <i class="fa-solid fa-cart-shopping me-1"></i>
                                Add
                            </button>

                        </div>
                    </form>
                @else
                    <button class="btn btn-secondary mt-3" disabled>
                        Out of Stock
                    </button>
                @endif

            </div>

        </div>
    </div>

    {{-- Description --}}
    <div class="card border-0 shadow-sm mt-4 p-3 p-md-4">
        <h5 class="fw-bold mb-3">Product Description</h5>
        <p class="text-muted mb-0" style="line-height:1.7;">
            {{ $product->description ?? 'No description available.' }}
        </p>
    </div>

</div>
@endsection
