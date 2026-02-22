@extends('layouts.main')

@section('content')
    <div class="container-fluid">

        <h3 class="mb-4 fw-bolder">Customer #{{ $customer->id }}</h3>

        <div class="corner-3 bg-white shadow-sm p-4">

            {{-- Order Summary --}}
            <div class="row mb-4">

                <div class="col-md-6">
                    <div class="mb-2">
                        <label class="fw-semibold text-muted">Customer Name</label>
                        <p class="mb-0 fs-6">{{ $customer->name }}</p>
                    </div>

                    <div class="mb-2">
                        <label class="fw-semibold text-muted">Email</label>
                        <p class="mb-0">{{ $customer->email }}</p>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-2">
                        <label class="fw-semibold text-muted">Total Orders</label>
                        <p class="mb-0 fs-5 fw-semibold text-primary">
                            {{ $customer->orders->count() }}
                        </p>
                    </div>

                    <div class="mb-2">
                        <label class="fw-semibold text-muted">Status</label>
                        @php
                            $statusClass = $customer->status == true ? 'bg-success' : 'bg-danger';
                            $status = $customer->status == true ? 'Active' : 'Inactive';
                        @endphp

                        <div>
                            <span class="badge {{ $statusClass }}">
                                {{ $status }}
                            </span>
                        </div>
                    </div>
                </div>

            </div>
            <hr>

            {{-- Update Status --}}
            <div class="mt-4">
                <h6 class="fw-semibold mb-3">Update Customer Status</h6>

                <form action="{{ route('admin.customers.update-status', $customer->id) }}" method="POST">
                    @csrf

                    <div class="row align-items-end">

                        <div class="col-md-4">
                            <label class="form-label">Select Status</label>
                            <select name="status" class="form-select">
                                <option value="1" {{ old('status', $customer->status) == 1 ? 'selected' : '' }}>
                                    Active
                                </option>
                                <option value="0" {{ old('status', $customer->status) == 0 ? 'selected' : '' }}>
                                    Inactive
                                </option>
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
                <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary">
                    Back to Customers
                </a>
            </div>
            <hr>

            {{-- Products Table --}}
            <h5 class="fw-semibold mb-3">Orders</h5>

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Order ID</th>
                            <th width="15%">Order Value</th>
                            <th width="10%">Status</th>
                            <th width="15%">Placed At</th>
                            <th width="15%">Delivered At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($customer->orders as $order)
                            <tr>
                                <td class="fw-semibold">
                                    {{ $order->order_no }}
                                </td>
                                <td>₹{{ number_format($order->total_amount, 2) }}</td>
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
                                <td>
                                    <span class="badge {{ $statusClass }}">
                                        {{ $order->status }}
                                    </span>
                                </td>

                                <td>{{ $order->created_at->format('d-m-Y H:i') }}</td>
                                <td class="fw-semibold">
                                    {{ $order->delivered_at ? $order->delivered_at->format('d-m-Y H:i') : 'Not delivered yet' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
@endsection
