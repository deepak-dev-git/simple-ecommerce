@extends('layouts.app')

@section('content')
<div class="container">
        {{-- @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif --}}
    <h2 class="mb-4">Products</h2>

    <div class="row">
        @forelse($products as $product)
            @php
                $price = $product->discounted_price ?? $product->price;
                // $images = $product->images ? json_decode($product->images, true) : [];
                $firstImage = $product->images[0] ?? null;
            @endphp

            <div class="col-md-3 mb-4">
                <div class="card h-100">

                    @if($firstImage)
                        <img src="{{ asset('storage/'.$firstImage) }}"
                             class="card-img-top"
                             style="height:200px; object-fit:cover;">
                    @else
                        <img src="https://via.placeholder.com/300x200"
                             class="card-img-top">
                    @endif

                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $product->name }}</h5>

                        @if($product->discount > 0)
                            <p>
                                <span class="text-muted text-decoration-line-through">
                                    ₹{{ $product->price }}
                                </span>
                                <span class="text-danger fw-bold">
                                    ₹{{ $price }}
                                </span>
                            </p>
                        @else
                            <p class="fw-bold">₹{{ $product->price }}</p>
                        @endif

                        <a href="{{ route('shop.show', $product->id) }}"
                           class="btn btn-primary mt-auto">
                           View Details
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <p>No products available.</p>
        @endforelse
    </div>

    <div class="d-flex justify-content-center">
        {{ $products->links() }}
    </div>
</div>
@endsection
