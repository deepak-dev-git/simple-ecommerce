@extends('layouts.main')

@section('content')
    <div class="container-fluid">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bolder mb-0">Products</h3>

            <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                + Add Product
            </a>
        </div>

        <div class="corner-3 bg-white shadow-sm p-3">
            <form method="GET" action="{{ route('admin.products.index') }}" class="row g-2 mb-3">

                <div class="col-md-4">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                        placeholder="Search product...">
                </div>

                <div class="col-md-3">
                    <select name="active" class="form-select">
                        <option value="">All</option>

                        <option value="1" {{ request('active') === '1' ? 'selected' : '' }}>
                            Active
                        </option>

                        <option value="0" {{ request('active') === '0' ? 'selected' : '' }}>
                            Inactive
                        </option>
                    </select>
                </div>

                <div class="col-md-3 d-flex gap-2">
                    <button class="btn btn-primary">Filter</button>

                    <a href="{{ route('admin.products.index') }}" class="btn btn-light border">Reset</a>
                </div>

            </form>
            {{-- Total Info --}}
            <p class="text-muted mb-3">
                Showing {{ $products->firstItem() }} to {{ $products->lastItem() }}
                of {{ $products->total() }} products
            </p>

            {{-- Table --}}
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">#</th>
                            <th>Name</th>
                            <th width="15%">Price</th>
                            <th width="15%">Quantity</th>
                            <th width="15%">Status</th>
                            <th width="15%">Discount</th>
                            <th width="15%" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $key => $product)
                            <tr>
                                <td>{{ $products->firstItem() + $key }}</td>
                                <td class="fw-semibold">{{ $product->name }}</td>
                                <td>₹{{ number_format($product->price, 2) }}</td>
                                <td>{{ $product->stock_quantity }}</td>
                                <td>
                                    @if ($product->status)
                                        <span class="badge bg-success">
                                            Active
                                        </span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($product->discount)
                                        <span class="badge bg-warning text-dark">
                                            {{ $product->discount }}%
                                        </span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="text-center">

                                    {{-- View --}}
                                    <a href="{{ route('admin.products.show', $product->id) }}"
                                        class="btn btn-sm btn-light border me-1">
                                        <i class="fa-solid fa-eye text-info"></i>
                                    </a>

                                    {{-- Edit --}}
                                    <a href="{{ route('admin.products.edit', $product->id) }}"
                                        class="btn btn-sm btn-light border me-1">
                                        <i class="fa-solid fa-pen-to-square text-warning"></i>
                                    </a>

                                    {{-- Delete --}}
                                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light border"
                                            onclick="return confirm('Are you sure you want to delete this product?')">
                                            <i class="fa-solid fa-trash text-danger"></i>
                                        </button>
                                    </form>

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    No products found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="d-flex justify-content-center mt-4">
                {{ $products->links() }}
            </div>

        </div>
    </div>
@endsection
