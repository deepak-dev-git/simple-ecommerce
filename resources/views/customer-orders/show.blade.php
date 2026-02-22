@extends('layouts.app')

@section('content')
    <div class="container-fluid">

        <h3 class="mb-4 fw-bolder">Order #{{ $order->order_no }}</h3>

        <div class="corner-3 bg-white shadow-sm p-4">

            {{-- Order Summary --}}
            <div class="row mb-4">

                <div class="col-md-6">
                    <div class="mb-2">
                        <label class="fw-semibold text-muted">Order Placed</label>
                        <p class="mb-0 fs-6">{{ $order->created_at->format('d-m-Y H:i') }}</p>
                    </div>

                    <div class="mb-2">
                        <label class="fw-semibold text-muted">Delivered At</label>
                        <p class="mb-0">
                            {{ $order->delivered_at ? $order->delivered_at->format('d-m-Y H:i') : 'Not delivered yet' }}</p>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-2">
                        <label class="fw-semibold text-muted">Total Amount</label>
                        <p class="mb-0 fs-5 fw-semibold text-primary">
                            ₹{{ number_format($order->total_amount, 2) }}
                        </p>
                    </div>

                    <div class="mb-2">
                        <label class="fw-semibold text-muted">Status</label>

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

                        <div>
                            <span class="badge {{ $statusClass }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                    </div>
                </div>

            </div>

            <hr>

            {{-- Products Table --}}
            <h5 class="fw-semibold mb-3">Ordered Products</h5>

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Product</th>
                            <th width="15%">Price</th>
                            <th width="10%">Qty</th>
                            <th width="15%">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->orderItems as $item)
                            <tr>
                                <td class="fw-semibold">
                                    {{ $item->product->name }}
                                </td>
                                <td>₹{{ number_format($item->price, 2) }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td class="fw-semibold">
                                    ₹{{ number_format($item->price * $item->quantity, 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <hr>
            {{-- Back Button --}}
            <div class="mt-4">
                <a href="{{ route('orders.index') }}" class="btn btn-secondary">
                    Back to Orders
                </a>
            </div>

        </div>
    </div>
@endsection
