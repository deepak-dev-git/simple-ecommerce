@extends('layouts.main')

@section('content')
    <div class="container-fluid">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bolder mb-0">Customers</h3>
        </div>

        <div class="corner-3 bg-white shadow-sm p-3">

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#Customer ID</th>
                            <th>Customer Name</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Orders Count</th>
                            <th width="160" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customers as $customer)
                            <tr>
                                <td>{{ $customer->id }}</td>
                                <td class="fw-semibold">
                                    {{ $customer->name }}
                                </td>

                                <td>
                                    {{ $customer->email }}
                                </td>

                                <td>
                                    @php
                                        $statusClass = $customer->status == true ? 'bg-success' : 'bg-danger';
                                        $status = $customer->status == true ? 'Active' : 'Inactive';
                                    @endphp

                                    <span class="badge {{ $statusClass }}">
                                        {{ $status }}
                                    </span>
                                </td>

                                <td>
                                    {{ $customer->orders->count() }}
                                </td>

                                <td class="text-center">

                                    {{-- View --}}
                                    <a href="{{ route('admin.customers.show', $customer->id) }}"
                                        class="btn btn-sm btn-light border me-1">
                                        <i class="fa-solid fa-eye text-primary"></i>
                                    </a>

                                    {{-- Delete --}}
                                    <form action="{{ route('admin.customers.destroy', $customer->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn btn-sm btn-light border"
                                            onclick="return confirm('Delete this customer?')">
                                            <i class="fa-solid fa-trash text-danger"></i>
                                        </button>
                                    </form>

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    No customers found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="d-flex justify-content-center mt-4">
                {{ $customers->links() }}
            </div>

        </div>
    </div>
@endsection
