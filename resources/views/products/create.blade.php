@extends('layouts.main')

@section('content')
<div class="container-fluid">
    <h3 class="mb-4 fw-bolder">Add Product</h3>

    <div class="corner-3 bg-white shadow-sm p-3">
        <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
            @csrf

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
                        value="{{ old('name') }}"
                        class="form-control" required>
                </div>

                {{-- Price --}}
                <div class="col-sm-6">
                    <label class="form-label mt-2">Price</label>
                    <input type="number" step="0.01" name="price"
                        value="{{ old('price') }}"
                        class="form-control" required>
                </div>

                {{-- Stock Quantity --}}
                <div class="col-sm-6">
                    <label class="form-label mt-2">Stock Quantity</label>
                    <input type="number" name="stock_quantity"
                        value="{{ old('stock_quantity') }}"
                        class="form-control" required>
                </div>

                {{-- Discount --}}
                <div class="col-sm-6">
                    <label class="form-label mt-2">Discount (%)</label>
                    <input type="number" name="discount"
                        value="{{ old('discount') }}"
                        class="form-control">
                </div>

                {{-- Description --}}
                <div class="col-sm-12 mt-2">
                    <label class="form-label">Description</label>
                    <textarea name="description"
                        class="form-control"
                        rows="4"
                        placeholder="Enter product description...">{{ old('description') }}</textarea>
                </div>

                {{-- Images --}}
                <div class="col-sm-12 mt-2">
                    <label class="form-label">Product Images</label>
                    <input type="file" name="images[]" multiple class="form-control">
                </div>

                {{-- Submit Button --}}
                <div class="col-sm-6 mt-4">
                    <input type="submit" class="btn btn-primary float-end col-4" value="Save">
                </div>

            </div>
        </form>
    </div>
</div>
@endsection
