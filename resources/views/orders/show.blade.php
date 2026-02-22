@extends('layouts.main')

@section('content')
    <div class="container-fluid">

        <h3 class="mb-4 fw-bolder">Order #{{ $order->id }}</h3>

        <div class="corner-3 bg-white shadow-sm p-4">

            {{-- Order Summary --}}
            <div class="row mb-4">

                <div class="col-md-6">
                    <div class="mb-2">
                        <label class="fw-semibold text-muted">Customer</label>
                        <p class="mb-0 fs-6">{{ $order->user->name }}</p>
                    </div>

                    <div class="mb-2">
                        <label class="fw-semibold text-muted">Email</label>
                        <p class="mb-0">{{ $order->user->email }}</p>
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

            {{-- Update Status --}}
            <div class="mt-4">
                <h6 class="fw-semibold mb-3">Update Order Status</h6>

                <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST">
                    @csrf

                    <div class="row align-items-end">

                        <div class="col-md-4">
                            <label class="form-label">Select Status</label>

                            @php
                                $statuses = \App\Enums\OrderStatus::getAll();
                            @endphp

                            <select name="status" class="form-select">
                                @foreach ($statuses as $status)
                                    <option value="{{ $status }}" {{ $order->status == $status ? 'selected' : '' }}>
                                        {{ ucfirst($status) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2 mt-3 mt-md-0">
                            <button class="btn btn-primary w-100">
                                Update
                            </button>
                        </div>

                    </div>
                </form>
            </div>

            {{-- Back Button --}}
            <div class="mt-4">
                <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
                    Back to Orders
                </a>
            </div>

        </div>
    </div>
@endsection
