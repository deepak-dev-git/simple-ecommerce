@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Add Product</h4>

    {{-- Show All Errors --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name"
                value="{{ old('name') }}"
                class="form-control @error('name') is-invalid @enderror">

            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label>Price</label>
            <input type="number" step="0.01" name="price"
                value="{{ old('price') }}"
                class="form-control @error('price') is-invalid @enderror">

            @error('price')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label>Stock Quantity</label>
            <input type="number" name="stock_quantity"
                value="{{ old('stock_quantity') }}"
                class="form-control @error('stock_quantity') is-invalid @enderror">

            @error('stock_quantity')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label>Discount (%)</label>
            <input type="number" name="discount"
                value="{{ old('discount') }}"
                class="form-control @error('discount') is-invalid @enderror">

            @error('discount')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label>Description</label>
            <textarea name="description"
                    class="form-control"
                    rows="4"
                    placeholder="Enter product description...">{{ old('description') }}</textarea>
        </div>
        <div class="mb-3">
            <label>Images</label>
            <input type="file" name="images[]" multiple
                class="form-control @error('images.*') is-invalid @enderror">

            @error('images.*')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div>
@endsection
