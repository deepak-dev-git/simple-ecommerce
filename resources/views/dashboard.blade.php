@extends('layouts.main')

@section('content')
    <div class="container py-4">
        <h3 class="mb-4 fw-bold">Dashboard</h3>

        <div class="row g-4">
            <div class="col-md-3">
                <div class="card shadow-sm border-0 text-center p-3">
                    <h6 class="text-muted">Total Customers</h6>
                    <h2 class="fw-bold text-info">{{ $totalCustomers }}</h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm border-0 text-center p-3">
                    <h6 class="text-muted">Total Products</h6>
                    <h2 class="fw-bold text-dark">{{ $totalProducts }}</h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm border-0 text-center p-3">
                    <h6 class="text-muted">Total Orders Placed</h6>
                    <h2 class="fw-bold text-primary">{{ $totalOrders }}</h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm border-0 text-center p-3">
                    <h6 class="text-muted">Pending Orders</h6>
                    <h2 class="fw-bold text-warning">{{ $totalOrdersPending }}</h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm border-0 text-center p-3">
                    <h6 class="text-muted">Total Orders Completed</h6>
                    <h2 class="fw-bold text-success">{{ $totalOrdersCompleted }}</h2>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm border-0 text-center p-3">
                    <h6 class="text-muted">Total Orders Cancelled</h6>
                    <h2 class="fw-bold text-danger">{{ $totalOrdersCancelled }}</h2>
                </div>
            </div>




            <div class="col-md-3">
                <div class="card shadow-sm border-0 text-center p-3">
                    <h6 class="text-muted">Total Income</h6>
                    <h2 class="fw-bold text-success">{{ $totalIncome }}</h2>
                </div>
            </div>
        </div>
    </div>
@endsection
