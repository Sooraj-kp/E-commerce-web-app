@extends('layouts.admin')

@section('title', 'Customers')
@section('page-heading', 'Customers')

@push('styles')
<style>
    /* Small changes only. Bootstrap classes stay as they were. */
    .customers-card {
        border: 1px solid var(--border);
        border-radius: 14px;
        overflow: hidden;
    }

    /* Light blue header instead of black */
    .customers-card thead th {
        background: var(--brand-soft);
        color: var(--brand-dark);
        font-weight: 700;
        padding: 14px 16px;
        border-bottom: 1px solid var(--brand-tint);
    }

    .customers-card tbody td {
        padding: 14px 16px;
        vertical-align: middle;
    }

    .customers-card tbody tr:hover {
        background: #f8fbff;
    }

    .customers-card .no-phone {
        color: #94a3b8;
    }
</style>
@endpush

@section('content')

<div class="container-fluid">

    <h2 class="mb-4">Customers</h2>

    <div class="card shadow-sm customers-card">
        <div class="card-body p-0">

            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Customer ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($customers as $customer)
                        <tr>
                            <td>{{ $customer->id }}</td>
                            <td class="fw-semibold">{{ $customer->name }}</td>
                            <td>{{ $customer->email }}</td>
                            <td>
                                @if ($customer->phone)
                                    {{ $customer->phone }}
                                @else
                                    <span class="no-phone">Not provided</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">
                                No customers found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>

</div>

@endsection