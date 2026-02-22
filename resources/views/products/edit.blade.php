@extends('layouts.main')

@section('content')
<div class="container-fluid">
    <h3 class="mb-4 fw-bolder">Edit Product</h3>

    <div class="corner-3 bg-white shadow-sm p-3">
        <form method="POST"
              action="{{ route('admin.products.update', $product->id) }}"
              enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">

                {{-- Error Section --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Product Name --}}
                <div class="col-sm-6">
                    <label class="form-label mt-2">Product Name</label>
                    <input type="text" name="name"
                        value="{{ old('name', $product->name) }}"
                        class="form-control" required>
                </div>

                {{-- Price --}}
                <div class="col-sm-6">
                    <label class="form-label mt-2">Price</label>
                    <input type="number" step="0.01" name="price"
                        value="{{ old('price', $product->price) }}"
                        class="form-control" required>
                </div>

                {{-- Stock Quantity --}}
                <div class="col-sm-6">
                    <label class="form-label mt-2">Stock Quantity</label>
                    <input type="number" name="stock_quantity"
                        value="{{ old('stock_quantity', $product->stock_quantity) }}"
                        class="form-control" required>
                </div>

                {{-- Discount --}}
                <div class="col-sm-6">
                    <label class="form-label mt-2">Discount (%)</label>
                    <input type="number" name="discount"
                        value="{{ old('discount', $product->discount) }}"
                        class="form-control">
                </div>

                {{-- Description --}}
                <div class="col-sm-12 mt-2">
                    <label class="form-label">Description</label>
                    <textarea name="description"
                        class="form-control"
                        rows="4">{{ old('description', $product->description) }}</textarea>
                </div>

                {{-- Existing Images --}}
                <div class="col-sm-12 mt-3">
                    <label class="form-label">Existing Images</label>
                    <div class="d-flex flex-wrap mt-2">
                        @if($product->images)
                            @foreach($product->images as $image)
                                <div class="me-3 mb-3 text-center">
                                    <img src="{{ asset('storage/'.$image) }}"
                                         width="100"
                                         class="rounded shadow-sm border">
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted">No images available.</p>
                        @endif
                    </div>
                </div>

                {{-- Add New Images --}}
                <div class="col-sm-12 mt-2">
                    <label class="form-label">Add New Images</label>
                    <input type="file" name="images[]" multiple class="form-control">
                </div>

                {{-- Buttons --}}
                <div class="col-sm-12 mt-4 d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.products.index') }}"
                       class="btn btn-secondary col-sm-2">
                        Cancel
                    </a>

                    <input type="submit"
                           class="btn btn-primary col-sm-2"
                           value="Update">
                </div>

            </div>
        </form>
    </div>
</div>
@endsection
