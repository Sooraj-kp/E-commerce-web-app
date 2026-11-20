@extends('layouts.admin')

@section('content')

<div class="container-fluid">

    <h2 class="mb-4">Manage Orders</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            All Customer Orders
        </div>

        <div class="card-body">

            <table class="table table-bordered table-hover">

                <thead>
                    <tr>
                        <th>Order Number</th>
                        <th>Customer</th>
                        <th>Email</th>
                        <th>Order Date</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td>{{ $order->order_number }}</td>
                            <td>{{ $order->user?->name ?? 'Unknown' }}</td>
                            <td>{{ $order->user?->email ?? 'Unknown' }}</td>
                            <td>{{ $order->created_at->format('d-m-Y') }}</td>
                            <td>₹{{ number_format($order->total_amount, 2) }}</td>
                            <td>{{ ucfirst(str_replace('_', ' ', $order->status)) }}</td>
                            <td>
                            <a href="{{ route('admin.orders.show', $order->id) }}"
                            class="btn btn-sm btn-primary">
                                View
                            </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">
                                No orders found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>

            {{ $orders->links() }}

        </div>
    </div>

</div>

@endsection