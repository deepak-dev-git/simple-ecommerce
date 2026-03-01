@extends('layouts.app')

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            {{ $errors->first() }}
        </div>
    @endif
    <div class="container">
        <div class="container py-4">

            <h2 class="fw-bold mb-4">My Cart</h2>

            @if ($cartItems->count())
                @php $total = 0; @endphp

                <div class="row g-4">

                    <!-- CART ITEMS -->
                    <div class="col-lg-8">

                        @foreach ($cartItems as $item)
                            @php
                                $price = $item->product->discounted_price ?? $item->product->price;
                                $subtotal = $price * $item->quantity;
                                $total += $subtotal;
                                $img = $item->product->images[0] ?? null;
                            @endphp

                            <div class="card border-0 shadow-sm mb-3" id="row-{{ $item->id }}">
                                <div class="card-body">

                                    <div class="row align-items-center">

                                        <!-- IMAGE -->
                                        <div class="col-md-2 text-center">
                                            <img src="{{ $img ? asset('storage/' . $img) : 'https://via.placeholder.com/120' }}"
                                                class="cart-img">
                                        </div>

                                        <!-- INFO -->
                                        <div class="col-md-4">
                                            <h6 class="fw-semibold mb-1">
                                                {{ $item->product->name }}
                                            </h6>

                                            <div class="text-muted small">
                                                ₹{{ number_format($price, 2) }}
                                            </div>
                                        </div>

                                        <!-- QUANTITY -->
                                        <div class="col-md-3">

                                            <div class="d-flex align-items-center">

                                                <button class="btn btn-outline-secondary decrease"
                                                    data-id="{{ $item->id }}">−</button>

                                                <input type="number" class="form-control text-center mx-2 qty-input"
                                                    value="{{ $item->quantity }}" data-id="{{ $item->id }}"
                                                    min="1" max="{{ $item->product->stock_quantity }}">

                                                <button class="btn btn-outline-secondary increase"
                                                    data-id="{{ $item->id }}">+</button>

                                            </div>

                                        </div>

                                        <!-- SUBTOTAL -->
                                        <div class="col-md-2 fw-bold">
                                            ₹<span id="subtotal-{{ $item->id }}">
                                                {{ number_format($subtotal, 2) }}
                                            </span>
                                        </div>

                                        <!-- REMOVE -->
                                        <div class="col-md-1 text-end">
                                            <button class="btn btn-sm btn-danger removeItem" data-id="{{ $item->id }}">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </div>

                                    </div>

                                </div>
                            </div>
                        @endforeach

                    </div>


                    <!-- ORDER SUMMARY -->
                    <div class="col-lg-4">

                        <div class="card border-0 shadow-sm p-4 sticky-summary">

                            <h5 class="fw-bold mb-3">Order Summary</h5>

                            <div class="d-flex justify-content-between mb-2">
                                <span>Items Total</span>
                                <strong>₹<span id="cartTotal">{{ number_format($total, 2) }}</span></strong>
                            </div>

                            <div class="text-muted small mb-3">
                                Delivery within 3-5 business days
                            </div>

                            <button class="btn btn-success w-100 py-2" data-bs-toggle="modal"
                                data-bs-target="#checkoutModal">
                                Proceed to Checkout
                            </button>


                        </div>

                    </div>

                </div>
            @else
                <div class="text-center py-5">
                    <i class="fa fa-cart-shopping fa-3x text-muted mb-3"></i>
                    <h5>Your cart is empty</h5>
                    <a href="{{ route('shop.index') }}" class="btn btn-primary mt-3">
                        Continue Shopping
                    </a>
                </div>
            @endif

        </div>
    </div>

    <!-- Checkout Modal -->
    <div class="modal fade" id="checkoutModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">

                <form onsubmit="return false;">
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

                            <button type="button" id="rzp-button" class="btn btn-success w-20">
                                Pay with Razorpay
                            </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
{{-- Razorpay Hidden Form --}}
<form id="razorpay-form" action="{{ route('checkout.store') }}" method="POST">
    @csrf

    <input type="hidden" name="address_id" id="selected_address">

    <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
    <input type="hidden" name="razorpay_order_id" id="razorpay_order_id">
    <input type="hidden" name="razorpay_signature" id="razorpay_signature">
</form>
    <style>
        .cart-img {
            width: 90px;
            height: 90px;
            object-fit: cover;
            border-radius: 10px;
        }

        .quantity-input {
            width: 70px;
        }

        .sticky-summary {
            position: sticky;
            top: 90px;
            border-radius: 12px;
        }
    </style>
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
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script>
document.getElementById('rzp-button').addEventListener('click', function(e) {

    e.preventDefault();

    // ✅ get selected address
    let address = document.querySelector('input[name="address_id"]:checked');

    if (!address) {
        alert('Please select delivery address');
        return;
    }

    fetch("{{ route('checkout.razorpay') }}", {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({})
    })
    .then(res => res.json())
    .then(order => {

        console.log("Razorpay Order:", order);

        if (!order.amount) {
            alert('Payment initialization failed');
            return;
        }

        var options = {
            key: "{{ config('services.razorpay.key') }}",
            amount: order.amount,
            currency: order.currency,
            name: "{{ config('app.name') }}",
            order_id: order.id,

            handler: function (response) {

                // ✅ fill hidden form
                document.getElementById('selected_address').value =
                    address.value;

                document.getElementById('razorpay_payment_id').value =
                    response.razorpay_payment_id;

                document.getElementById('razorpay_order_id').value =
                    response.razorpay_order_id;

                document.getElementById('razorpay_signature').value =
                    response.razorpay_signature;

                // ✅ submit ONLY razorpay form
                document.getElementById('razorpay-form').submit();
            },

            theme: {
                color: "#28a745"
            }
        };

        var rzp = new Razorpay(options);
        rzp.open();
    })
    .catch(err => {
        console.error(err);
        alert("Unable to start payment");
    });

});
</script>
@endsection
