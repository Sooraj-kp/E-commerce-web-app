@extends('layouts.admin')

@section('content')

<div class="container-fluid">

    <h2 class="mb-4">Order Details</h2>

    <div class="card mb-4">
        <div class="card-header">
            Order Information
        </div>

        <div class="card-body">
            <p><strong>Order Number:</strong> {{ $orders->order_number }}</p>
            <p><strong>Order Date:</strong> {{ $orders->created_at->format('d-m-Y') }}</p>
            <p><strong>Status:</strong> {{ ucfirst(str_replace('_', ' ', $orders->status)) }}</p>
            <p><strong>Total Amount:</strong> ₹{{ number_format($orders->total_amount, 2) }}</p>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">Customer Information</div>

        <div class="card-body">
            <p><strong>Name:</strong> {{ $orders->user?->name ?? 'Unknown' }}</p>
            <p><strong>Email:</strong> {{ $orders->user?->email ?? 'Unknown' }}</p>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">Delivery Address</div>

        <div class="card-body">
            @if($orders->address)
                <p>{{ $orders->address->address_line }}</p>
                <p>{{ $orders->address->city }}, {{ $orders->address->state }}</p>
                <p>{{ $orders->address->postal_code }}</p>
                <p>{{ $orders->address->country }}</p>
            @else
                <p>Delivery address not available.</p>
            @endif
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">Ordered Products</div>

        <div class="card-body">
            <table class="table table-bordered">

                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($orders->items as $item)
                        <tr>
                            <td>{{ $item->product?->name ?? 'Product unavailable' }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>₹{{ number_format($item->price, 2) }}</td>
                            <td>₹{{ number_format($item->price * $item->quantity, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
            </div>
            <div class="card mb-4">
            <div class="card-header">
                Update Order Status
            </div>

            <div class="card-body">

                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('admin.orders.updateStatus', $orders->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="status" class="form-label">
                            Select Order Status
                        </label>

                        <select name="status" id="status" class="form-control" required>
                            <option value="confirmed" {{ $orders->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="processing" {{ $orders->status == 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="shipped" {{ $orders->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="out_for_delivery" {{ $orders->status == 'out_for_delivery' ? 'selected' : '' }}>Out for Delivery</option>
                            <option value="delivered" {{ $orders->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="cancelled" {{ $orders->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>

                        @error('status')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Update Status
                    </button>
                </form>

            </div>
        </div>

            <div class="card mb-4">
        <div class="card-header">
            Assign Staff Member
        </div>

        <div class="card-body">

            <form action="{{ route('admin.orders.assignStaff', $orders->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label>Select Staff</label>

                    <select name="assigned_staff_id" class="form-control" required>
                        <option value="">Choose Staff Member</option>

                        @foreach($staffMembers as $staff)
                            <option value="{{ $staff->id }}"
                                {{ $orders->assigned_staff_id == $staff->id ? 'selected' : '' }}>
                                {{ $staff->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">
                    Assign Staff
                </button>
            </form>

        </div>
    </div>

    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
        Back to Orders
    </a>

</div>

@endsection