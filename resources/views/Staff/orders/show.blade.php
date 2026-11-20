@extends('layouts.staff')

@section('title', 'Order Details')
@section('page-heading', 'Order Details')

@push('styles')
<style>
    .sd-page { max-width: 820px; }

    .sd-back {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 18px;
        color: var(--muted);
        font-weight: 600;
        font-size: 15px;
        text-decoration: none;
        transition: color 0.15s;
    }

    .sd-back svg { width: 18px; height: 18px; transition: transform 0.2s; }
    .sd-back:hover { color: var(--brand); }
    .sd-back:hover svg { transform: translateX(-3px); }
    .sd-back:focus-visible { outline: 3px solid rgba(var(--brand-rgb), 0.4); outline-offset: 3px; border-radius: 6px; }

    .sd-card {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 18px;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04), 0 22px 44px -34px rgba(15, 23, 42, 0.22);
        overflow: hidden;
    }

    /* Top strip: order number + status */
    .sd-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 14px;
        padding: 26px 30px;
        border-bottom: 1px solid var(--border);
        background: #f8fafd;
    }

    .sd-top span {
        display: block;
        margin-bottom: 2px;
        font-size: 13px;
        font-weight: 700;
        letter-spacing: 0.07em;
        text-transform: uppercase;
        color: var(--muted);
    }

    .sd-top h2 {
        margin: 0;
        font-size: 26px;
        font-weight: 700;
        letter-spacing: -0.02em;
        color: var(--brand-dark);
    }

    /* Status badge */
    .sd-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 7px 16px;
        border-radius: 999px;
        font-size: 14.5px;
        font-weight: 600;
        background: #f1f5f9;
        color: #475569;
        white-space: nowrap;
    }

    .sd-badge::before { content: ""; width: 8px; height: 8px; border-radius: 50%; background: currentColor; }

    .sd-pending     { background: #fffbeb; color: #b45309; }
    .sd-processing,
    .sd-in_progress { background: var(--brand-soft); color: var(--brand-dark); }
    .sd-shipped     { background: #f5f3ff; color: #6d28d9; }
    .sd-delivered,
    .sd-completed   { background: #f0fdf4; color: #15803d; }
    .sd-cancelled,
    .sd-failed      { background: #fff1f2; color: #be123c; }

    /* Info */
    .sd-body { padding: 28px 30px 32px; }

    .sd-title { margin: 0 0 22px; font-size: 19px; font-weight: 700; letter-spacing: -0.01em; }

    .sd-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0; }

    .sd-item {
        padding: 18px 0;
        border-top: 1px solid #edf2f9;
    }

    .sd-item:nth-child(-n+2) { border-top: none; padding-top: 0; }
    .sd-item:nth-child(odd) { padding-right: 24px; }
    .sd-item:nth-child(even) { padding-left: 24px; border-left: 1px solid #edf2f9; }

    .sd-item span {
        display: block;
        margin-bottom: 4px;
        font-size: 13px;
        font-weight: 600;
        color: var(--muted);
    }

    .sd-item b { display: block; font-size: 18px; font-weight: 600; }
    .sd-item b.sd-amount { font-size: 26px; font-weight: 800; letter-spacing: -0.02em; color: var(--brand); }

    @media (max-width: 600px) {
        .sd-top, .sd-body { padding-left: 18px; padding-right: 18px; }
        .sd-top h2 { font-size: 22px; }

        .sd-grid { grid-template-columns: 1fr; }
        .sd-item:nth-child(n) { padding: 16px 0; border-left: none; border-top: 1px solid #edf2f9; }
        .sd-item:first-child { border-top: none; padding-top: 0; }
    }
</style>
@endpush

@section('content')

<div class="sd-page">

    <a href="{{ route('staff.orders.index') }}" class="sd-back">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
        Back to my assigned orders
    </a>

    <div class="sd-card">

        <div class="sd-top">
            <div>
                <span>Order number</span>
                <h2>{{ $orders->order_number }}</h2>
            </div>

            <span class="sd-badge sd-{{ strtolower($orders->status) }}">
                {{ ucfirst(str_replace('_', ' ', $orders->status)) }}
            </span>
        </div>

        <div class="sd-body">

        <hr style="margin: 25px 0; border: 0; border-top: 1px solid var(--border);">

<h3 class="sd-title">Update Order Status</h3>

@if(session('success'))
    <div style="padding: 12px; background: #dcfce7; color: #166534; border-radius: 8px; margin-bottom: 15px;">
        {{ session('success') }}
    </div>
@endif

<form action="{{ route('staff.orders.updateStatus', $orders->id) }}" method="POST">

    @csrf
    @method('PUT')

    <label for="status">Select New Status</label>

    <select name="status" id="status" required>
        <option value="">-- Select Status --</option>
        <option value="processing">Processing</option>
        <option value="shipped">Shipped</option>
        <option value="out_for_delivery">Out for Delivery</option>
        <option value="delivered">Delivered</option>
    </select>

    @error('status')
        <p style="color: red;">{{ $message }}</p>
    @enderror

    <button type="submit" style="margin-top: 15px; padding: 10px 20px; background: var(--brand); color: white; border: none; border-radius: 8px; cursor: pointer;">
        Update Status
    </button>

</form>
            <h3 class="sd-title">Order information</h3>

            <hr style="margin: 25px 0; border: 0; border-top: 1px solid var(--border);">

<h3 class="sd-title">Customer Information</h3>

<div class="sd-grid">

    <div class="sd-item">
        <span>Customer Name</span>
        <b>{{ $orders->user?->name ?? 'N/A' }}</b>
    </div>

    <div class="sd-item">
        <span>Email Address</span>
        <b>{{ $orders->user?->email ?? 'N/A' }}</b>
    </div>

</div>

            <div class="sd-grid">

                <div class="sd-item">
                    <span>Order number</span>
                    <b>{{ $orders->order_number }}</b>
                </div>

                <div class="sd-item">
                    <span>Order date</span>
                    <b>{{ $orders->created_at->format('d-m-Y') }}</b>
                </div>

                <div class="sd-item">
                    <span>Status</span>
                    <b>{{ ucfirst(str_replace('_', ' ', $orders->status)) }}</b>
                </div>

                <div class="sd-item">
                    <span>Total amount</span>
                    <b class="sd-amount">₹{{ number_format($orders->total_amount, 2) }}</b>
                </div>

            </div>
        </div>

    </div>

</div>

@endsection