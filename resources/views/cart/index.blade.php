@extends('layouts.app')

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            {{ $errors->first() }}
        </div>
    @endif
    <div class="container">
        <h2 class="mb-4">My Cart</h2>

        @if ($cartItems->count())
            @php $total = 0; @endphp

            <div class="table-responsive">

                <table class="table table-bordered align-middle">
                    <thead class="table-light d-none d-md-table-header-group">
                        <tr>
                            <th>Product</th>
                            <th width="120">Price</th>
                            <th width="170">Quantity</th>
                            <th width="120">Subtotal</th>
                            <th width="100">Action</th>
                        </tr>
                    </thead>

                    <tbody id="cartTableBody">

                        @foreach ($cartItems as $item)
                            @php
                                $price = $item->product->discounted_price ?? $item->product->price;
                                $subtotal = $price * $item->quantity;
                                $total += $subtotal;
                            @endphp

                            <!-- DESKTOP ROW -->
                            <tr id="row-{{ $item->id }}" class="d-none d-md-table-row">
                                <td>{{ $item->product->name }}</td>

                                <td>₹{{ $price }}</td>

                                <td>
                                    <div class="d-flex justify-content-center align-items-center">
                                        <button type="button" class="btn btn-sm btn-outline-secondary decrease"
                                            data-id="{{ $item->id }}">−</button>

                                        <input type="number" class="form-control text-center mx-2 quantity-input"
                                            value="{{ $item->quantity }}" min="1"
                                            max="{{ $item->product->stock_quantity }}" data-id="{{ $item->id }}"
                                            style="width:60px;">

                                        <button type="button" class="btn btn-sm btn-outline-secondary increase"
                                            data-id="{{ $item->id }}">+</button>
                                    </div>
                                </td>

                                <td>
                                    ₹<span id="subtotal-{{ $item->id }}">{{ $subtotal }}</span>
                                </td>

                                <td>
                                    <button type="button" class="btn btn-sm btn-danger removeItem"
                                        data-id="{{ $item->id }}">
                                        Remove
                                    </button>
                                </td>
                            </tr>

                            <!-- MOBILE CARD -->
                            <tr class="d-md-none border rounded mb-3">
                                <td colspan="5" class="p-3">

                                    <div class="fw-bold mb-2">
                                        {{ $item->product->name }}
                                    </div>

                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Price:</span>
                                        <span>₹{{ $price }}</span>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span>Quantity:</span>

                                        <div class="d-flex align-items-center">
                                            <button type="button" class="btn btn-sm btn-outline-secondary decrease"
                                                data-id="{{ $item->id }}">−</button>

                                            <input type="number" class="form-control text-center mx-2 quantity-input"
                                                value="{{ $item->quantity }}" min="1"
                                                max="{{ $item->product->stock_quantity }}" data-id="{{ $item->id }}"
                                                style="width:60px;">

                                            <button type="button" class="btn btn-sm btn-outline-secondary increase"
                                                data-id="{{ $item->id }}">+</button>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between mb-3">
                                        <span>Subtotal:</span>
                                        <span>
                                            ₹<span id="subtotal-{{ $item->id }}">{{ $subtotal }}</span>
                                        </span>
                                    </div>

                                    <button type="button" class="btn btn-sm btn-danger w-100 removeItem"
                                        data-id="{{ $item->id }}">
                                        Remove
                                    </button>

                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>

            </div>

            <div class="text-end mt-3">
                <h4>Total: ₹<span id="cartTotal">{{ $total }}</span></h4>

                <button class="btn btn-success mt-2" data-bs-toggle="modal" data-bs-target="#checkoutModal">
                    Checkout
                </button>
            </div>
        @else
            <p>Your cart is empty.</p>
        @endif
    </div>

    <!-- Checkout Modal -->
    <div class="modal fade" id="checkoutModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">

                <form action="{{ route('checkout.store') }}" method="POST">
                    @csrf

                    <div class="modal-header">
                        <h5 class="modal-title">Select Delivery Address</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        @if ($errors->has('address_id'))
                            <div class="alert alert-danger">
                                {{ $errors->first('address_id') }}
                            </div>
                        @endif
                        <!-- Address List -->
                        <div class="mb-4">
                            <h6 class="fw-bold mb-3">Your Addresses</h6>

                            @forelse(auth()->user()->addresses as $address)
                                <div class="card mb-3 shadow-sm border-0">
                                    <div class="card-body">

                                        <div class="form-check">

                                            <input class="form-check-input mt-2" type="radio" name="address_id"
                                                value="{{ $address->id }}" {{ $address->is_default ? 'checked' : '' }}
                                                required>

                                            <label class="form-check-label w-100 ps-2">

                                                <div class="fw-semibold">
                                                    {{ $address->building_no }}, {{ $address->street }}
                                                </div>

                                                <div>
                                                    {{ $address->city }}, {{ $address->district }}
                                                </div>

                                                <div>
                                                    {{ $address->state }} - {{ $address->postal_code }}
                                                </div>

                                                <div class="text-muted">
                                                    {{ $address->country }}
                                                </div>

                                                @if ($address->is_default)
                                                    <span class="badge bg-success mt-2">
                                                        Default Address
                                                    </span>
                                                @endif

                                            </label>

                                        </div>

                                    </div>
                                </div>

                            @empty
                                <div class="alert alert-warning">
                                    No saved addresses found.
                                    {{-- <a href="{{ route('addresses.create') }}">
                                    Add Address
                                </a> --}}
                                </div>
                            @endforelse
                        </div>

                        <!-- Order Summary -->
                        <div class="border-top pt-3">
                            <h6 class="fw-bold mb-3">Order Summary</h6>

                            <div class="d-flex justify-content-between">
                                <span>Total Amount:</span>
                                <strong>₹<span id="modalTotal">{{ number_format($total, 2) }}</span></strong>
                            </div>

                            <div class="text-muted small mt-2">
                                Delivery within 3-5 business days.
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Cancel
                        </button>

                        <button type="submit" class="btn btn-success">
                            Place Order
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>

@endsection



@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            function updateCart(id, quantity) {

                if (quantity < 1) quantity = 1;

                fetch("{{ url('/cart/update') }}/" + id, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            quantity: quantity
                        })
                    })
                    .then(response => response.json())
                    .then(data => {

                        if (!data) return;

                        // Update subtotal
                        document.querySelectorAll('#subtotal-' + id).forEach(el => {
                            el.innerText = parseFloat(data.subtotal).toFixed(2);
                        });

                        // Update cart total
                        document.getElementById('cartTotal').innerText =
                            parseFloat(data.total).toFixed(2);

                        // Update modal total
                        let modalTotal = document.getElementById('modalTotal');
                        if (modalTotal) {
                            modalTotal.innerText =
                                parseFloat(data.total).toFixed(2);
                        }

                        // Update quantity inputs
                        document.querySelectorAll('input[data-id="' + id + '"]').forEach(input => {
                            input.value = data.quantity;
                        });

                    });
            }

            // Increase
            document.querySelectorAll('.increase').forEach(btn => {
                btn.addEventListener('click', function() {
                    let id = this.dataset.id;
                    let input = document.querySelector('input[data-id="' + id + '"]');
                    updateCart(id, parseInt(input.value) + 1);
                });
            });

            // Decrease
            document.querySelectorAll('.decrease').forEach(btn => {
                btn.addEventListener('click', function() {
                    let id = this.dataset.id;
                    let input = document.querySelector('input[data-id="' + id + '"]');
                    updateCart(id, parseInt(input.value) - 1);
                });
            });

            // Manual change
            document.querySelectorAll('.quantity-input').forEach(input => {
                input.addEventListener('change', function() {
                    updateCart(this.dataset.id, parseInt(this.value));
                });
            });

            // Remove
            document.querySelectorAll('.removeItem').forEach(btn => {
                btn.addEventListener('click', function() {

                    let id = this.dataset.id;

                    fetch("{{ url('/cart/remove') }}/" + id, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.empty) {
                                location.reload();
                                return;
                            }
                            document.querySelectorAll('#row-' + id).forEach(row => {
                                row.remove();
                            });
                            document.getElementById('cartTotal').innerText =
                                parseFloat(data.total).toFixed(2);

                        });
                });
            });

        });
    </script>
@endsection
