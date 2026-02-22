@extends('layouts.main')

@section('content')
<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bolder mb-0">Products</h3>

        <a href="{{ route('admin.products.create') }}"
           class="btn btn-primary">
            + Add Product
        </a>
    </div>

    <div class="corner-3 bg-white shadow-sm p-3">

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
                                @if($product->discount)
                                    <span class="badge bg-success">
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
                                <form action="{{ route('admin.products.destroy', $product->id) }}"
                                      method="POST"
                                      class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="btn btn-sm btn-light border"
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
