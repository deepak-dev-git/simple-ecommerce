@extends('layouts.main')

@section('content')
    <div class="container-fluid">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bolder mb-0">Orders</h3>
        </div>

        <div class="corner-3 bg-white shadow-sm p-3">

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#Order No</th>
                            <th>Customer</th>
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
                                <td class="fw-semibold">
                                    {{ $order->user->name }}
                                </td>

                                <td>
                                    ₹{{ number_format($order->total_amount, 2) }}
                                </td>

                                <td>
                                    @php
                                        $statusClass = match (strtolower($order->status)) {
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
                                    <a href="{{ route('admin.orders.show', $order->id) }}"
                                        class="btn btn-sm btn-light border me-1">
                                        <i class="fa-solid fa-eye text-primary"></i>
                                    </a>

                                    {{-- Delete --}}
                                    <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn btn-sm btn-light border"
                                            onclick="return confirm('Delete this order?')">
                                            <i class="fa-solid fa-trash text-danger"></i>
                                        </button>
                                    </form>

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
