@extends('layouts.main')

@section('content')
<div class="container-fluid">

    <h3 class="mb-4 fw-bolder">View Product</h3>

    <div class="corner-3 bg-white shadow-sm p-4">

        <div class="row">

            {{-- Product Details --}}
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="fw-semibold text-muted">Name</label>
                    <p class="fs-5 mb-0">{{ $product->name }}</p>
                </div>

                <div class="mb-3">
                    <label class="fw-semibold text-muted">Price</label>
                    <p class="mb-0">₹{{ number_format($product->price, 2) }}</p>
                </div>

                <div class="mb-3">
                    <label class="fw-semibold text-muted">Available Quantity</label>
                    <p class="mb-0">
                        @if($product->stock_quantity > 10)
                            <span class="badge bg-success">
                                {{ $product->stock_quantity }} In Stock
                            </span>
                        @elseif($product->stock_quantity > 0)
                            <span class="badge bg-warning text-dark">
                                {{ $product->stock_quantity }} Low Stock
                            </span>
                        @else
                            <span class="badge bg-danger">
                                Out of Stock
                            </span>
                        @endif
                    </p>
                </div>

                <div class="mb-3">
                    <label class="fw-semibold text-muted">Discount</label>
                    <p class="mb-0">
                        @if($product->discount)
                            <span class="badge bg-success">
                                {{ $product->discount }}%
                            </span>
                        @else
                            <span class="text-muted">No Discount</span>
                        @endif
                    </p>
                </div>

                <div class="mb-3">
                    <label class="fw-semibold text-muted">Discounted Price</label>
                    <p class="mb-0 fw-semibold text-primary">
                        ₹{{ number_format($product->discounted_price, 2) }}
                    </p>
                </div>
            </div>

            {{-- Images Section --}}
            <div class="col-md-6">
                <label class="fw-semibold text-muted d-block mb-2">Product Images</label>

                <div class="d-flex flex-wrap gap-3">
                    @if($product->images)
                        @foreach($product->images as $image)
                            <img src="{{ asset('storage/' . $image) }}"
                                 alt="Product Image"
                                 width="150"
                                 class="rounded shadow-sm border">
                        @endforeach
                    @else
                        <p class="text-muted">No images available.</p>
                    @endif
                </div>
            </div>

        </div>

        {{-- Back Button --}}
        <div class="mt-4">
            <a href="{{ route('admin.products.index') }}"
               class="btn btn-secondary">
                Back
            </a>
        </div>

    </div>
</div>
@endsection
