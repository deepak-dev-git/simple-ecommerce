@extends('layouts.app')

@section('content')
<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bolder mb-0">My Orders</h3>
    </div>
        <form method="GET" class="row g-2 mb-3">
            {{-- Search --}}
            <div class="col-md-4">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                    placeholder="Search Order No / Total">
            </div>

            {{-- Status Filter --}}
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">All Status</option>

                    @foreach ($statuses as $status)
                        <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                            {{ $status }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Buttons --}}
            <div class="col-md-3 d-flex gap-2">
                <button class="btn btn-primary w-100">
                    Search
                </button>

                <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
                    Reset
                </a>
            </div>

        </form>
    <div class="corner-3 bg-white shadow-sm p-3">

        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#Order No</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th width="160" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td>{{ $order->order_no }}</td>

                            <td>
                                ₹{{ number_format($order->total_amount, 2) }}
                            </td>

                            <td>
                                @php
                                    $statusClass = match(strtolower($order->status)) {
                                        'pending' => 'bg-warning text-dark',
                                        'inprogress' => 'bg-info',
                                        'completed' => 'bg-success',
                                        'cancelled' => 'bg-danger',
                                        'dispatched' => 'bg-primary',
                                        'refunded' => 'bg-dark',
                                        default => 'bg-secondary',
                                    };
                                @endphp

                                <span class="badge {{ $statusClass }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>

                            <td>
                                {{ $order->created_at->format('d-m-Y') }}
                            </td>

                            <td class="text-center">

                                {{-- View --}}
                                <a href="{{ route('orders.show', $order->id) }}"
                                   class="btn btn-sm btn-light border me-1">
                                    <i class="fa-solid fa-eye text-primary"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                No orders found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="d-flex justify-content-center mt-4">
            {{ $orders->links() }}
        </div>

    </div>
</div>
@endsection
