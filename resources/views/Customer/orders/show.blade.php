@extends('layouts.customer')

@section('title', 'Order Placed')

@section('content')

<div class="os-page">

    {{-- Success message --}}
    <div class="os-hero">
        <div class="os-check">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
        </div>

        <h1>Order placed successfully!</h1>
        <p>Thank you for shopping with us.</p>
    </div>

    {{-- Order information --}}
    <section class="os-card">

{{-- Order tracking timeline --}}

{{-- Professional Order Tracking --}}
<section class="os-card os-tracking-card">

    <div class="os-tracking-header">
        <div>
            <span class="os-eyebrow">ORDER JOURNEY</span>
            <h2 class="os-title">Track your order</h2>
            <p class="os-tracking-subtitle">
                Follow the latest updates about your delivery.
            </p>
        </div>

        <span class="os-current-status os-status-{{ $order->status }}">
            {{ ucwords(str_replace('_', ' ', $order->status)) }}
        </span>
    </div>

    <div class="os-tracking-body">

        @forelse($order->statusHistories as $history)

            <div class="os-track-step {{ $loop->last ? 'os-track-current' : 'os-track-completed' }} {{ $history->status === 'cancelled' ? 'os-track-cancelled' : '' }}">

                <div class="os-track-visual">
                    <div class="os-track-dot">
                        @if($history->status === 'cancelled')
                            <span>×</span>
                        @elseif($loop->last)
                            <span>●</span>
                        @else
                            <span>✓</span>
                        @endif
                    </div>

                    @unless($loop->last)
                        <div class="os-track-line"></div>
                    @endunless
                </div>

                <div class="os-track-details">

                    <div class="os-track-title-row">
                        <h3>
                            {{ ucwords(str_replace('_', ' ', $history->status)) }}
                        </h3>

                        @if($loop->last)
                            <span class="os-latest-label">
                                Latest update
                            </span>
                        @endif
                    </div>

                    <p class="os-track-description">
                        {{ $history->description ?? 'Your order status has been updated.' }}
                    </p>

                    <div class="os-track-date">
                        <span> {{ $history->created_at->format('d M Y') }}</span>
                        <span class="os-date-separator">•</span>
                        <span>{{ $history->created_at->format('h:i A') }}</span>
                    </div>

                </div>

            </div>

        @empty

            <div class="os-track-empty">
                <p>No tracking updates are available yet.</p>
            </div>

        @endforelse

    </div>

    @if($order->status === 'cancelled')
        <div class="os-cancelled-notice">
            This order has been cancelled.
        </div>
    @elseif($order->status === 'delivered')
        <div class="os-delivered-notice">
            Your order has been successfully delivered.
        </div>
    @endif

</section>


        <h2 class="os-title">Order information</h2>

        <div class="os-info">
            <div class="os-item">
                <span>Order number</span>
                <b>{{ $order->order_number }}</b>
            </div>

            <div class="os-item">
                <span>Order status</span>
                <b><em class="os-status os-{{ strtolower($order->status) }}">{{ ucfirst($order->status) }}</em></b>
            </div>

            <div class="os-item">
                <span>Order date</span>
                <b>{{ $order->created_at->format('d M Y') }}</b>
            </div>

            <div class="os-item">
                <span>Total amount</span>
                <b class="os-amount">₹{{ number_format($order->total_amount, 2) }}</b>
            </div>
        </div>
    </section>

    {{-- Ordered products --}}
    <section class="os-card">
        <h2 class="os-title">Ordered products</h2>

        @forelse ($order->items as $item)

            <div class="os-product">
                @php
                $mainImage=$item->product?->images->first();
                @endphp
                <div class="os-product-image">
                    @if($mainImage)
                        <img src="{{asset('image_uploads/'.$mainImage->image_path)}}" alt="{{$item->product->name}}">
                    @else
                        <span>No Image</span>
                    @endif

                </div>
                <div class="os-product-info">
                    <h3>{{ $item->product->name }}</h3>
                    <p>
                        <span class="os-qty">Qty: {{ $item->quantity }}</span>
                        <span class="os-unit">₹{{ number_format($item->price, 2) }} each</span>
                    </p>
                </div>

                <div class="os-line">₹{{ number_format($item->price * $item->quantity, 2) }}</div>
            </div>

        @empty

            <p class="os-muted">No products found for this order.</p>

        @endforelse

        <div class="os-total">
            <span>Total</span>
            <b>₹{{ number_format($order->total_amount, 2) }}</b>
        </div>
    </section>

    {{-- Delivery address --}}
    <section class="os-card">
        <h2 class="os-title">Delivery address</h2>

        @if ($order->address)
            <div class="os-address">
                <div class="os-pin">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                </div>

                <div class="os-lines">
                    <p class="os-line1">{{ $order->address->address_line }}</p>
                    <p>{{ $order->address->city }}, {{ $order->address->state }}</p>
                    <p>{{ $order->address->postal_code }}</p>
                    <p>{{ $order->address->country }}</p>
                </div>
            </div>
        @else
            <p class="os-muted">No delivery address found for this order.</p>
        @endif
    </section>

    {{-- Actions --}}
    <div class="os-actions">
        <a href="{{ url('/shop') }}" class="os-btn">Continue shopping</a>
        <a href="{{ route('customer.account') }}" class="os-btn os-btn-ghost">My account</a>

            @if($order->canBeCancelled())

                <div class="os-cancel-section">

                    <button type="button"
                            class="os-btn os-btn-cancel"
                            onclick="toggleCancelForm({{ $order->id }})">
                        Cancel Order
                    </button>

                    <form id="cancel-form-{{ $order->id }}"
                        class="os-cancel-form"
                        action="{{ route('customer.orders.cancel', $order->id) }}"
                        method="POST"
                        style="display: none;">

                        @csrf
                        @method('PATCH')

                        <textarea
                            name="cancellation_reason"
                            placeholder="Please tell us why you want to cancel this order..."
                            maxlength="1000"
                            rows="3"></textarea>

                        <div class="os-cancel-actions">

                            <button type="submit" class="os-confirm-cancel">
                                Confirm Cancellation
                            </button>

                            <button type="button"
                                    class="os-keep-order"
                                    onclick="toggleCancelForm({{ $order->id }})">
                                Keep Order
                            </button>

                        </div>

                    </form>

                </div>

            @endif
    </div>

</div>

@endsection

@push('styles')
<style>
    /* Cancellation Form */

.os-cancel-section {
    width: 100%;
    max-width: 360px;
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.os-cancel-form {
    width: 100%;
}

.os-cancel-form textarea {
    width: 100%;
    padding: 13px;
    border: 1px solid var(--border);
    border-radius: 12px;
    font-family: inherit;
    font-size: 14px;
    resize: vertical;
    box-sizing: border-box;
}

.os-cancel-form textarea:focus {
    outline: none;
    border-color: var(--brand);
}

.os-cancel-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 10px;
}

.os-confirm-cancel,
.os-keep-order {
    padding: 11px 16px;
    border-radius: 999px;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    font-family: inherit;
}

.os-confirm-cancel {
    background: #dc2626;
    color: white;
    border: 1px solid #dc2626;
}

.os-confirm-cancel:hover {
    background: #b91c1c;
}

.os-keep-order {
    background: white;
    color: var(--ink);
    border: 1px solid var(--border);
}

.os-keep-order:hover {
    background: var(--brand-soft);
}

@media (max-width: 600px) {
    .os-cancel-section {
        max-width: 100%;
    }

    .os-cancel-actions button {
        flex: 1;
    }
}
    .os-page {
        --brand: rgb(41, 110, 194);
        --brand-dark: #831111;
        --brand-soft: #eff6ff;
        --brand-tint: #dbeafe;
        --border: #dbe5f3;
        --ink: #0f172a;
        --muted: #64748b;
        --success: #16a34a;

        width: 92%;
        max-width: 820px;
        margin: 40px auto 60px;
        color: var(--ink);
    }
    .os-btn-cancel {
        background: #fff;
        color: #dc2626;
        border: 1px solid #fecaca;
        box-shadow: none;
        cursor: pointer;
        font-family: inherit;
    }
    .os-product-image {
        width: 70px;
        height: 70px;
        flex-shrink: 0;
        border: 1px solid var(--border);
        border-radius: 10px;
        overflow: hidden;
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        color: var(--muted);
    }

    .os-product-image img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        display: block;
    }

    .os-product-info {
        flex: 1;
        min-width: 0;
    }

    .os-btn-cancel:hover {
        background: #fef2f2;
        color: #b91c1c;
        border-color: #fca5a5;
        transform: translateY(-2px);
    }

    .os-btn-cancel:focus-visible {
        outline: 3px solid rgba(220, 38, 38, 0.3);
        outline-offset: 3px;
}
    .os-page * { box-sizing: border-box; }

    /* Hero */
    .os-hero { text-align: center; margin-bottom: 34px; }

    .os-check {
        width: 84px;
        height: 84px;
        margin: 0 auto 20px;
        border-radius: 50%;
        background: #f0fdf4;
        color: var(--success);
        border: 1px solid #bbf7d0;
        display: grid;
        place-items: center;
        box-shadow: 0 0 0 10px rgba(22, 163, 74, 0.07);
    }

    .os-check svg { width: 40px; height: 40px; }

    .os-hero h1 { margin: 0 0 8px; font-size: 34px; font-weight: 800; letter-spacing: -0.03em; line-height: 1.15; }
    .os-hero p { margin: 0; color: var(--muted); font-size: 17px; }

    /* Cards */
    .os-card {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 18px;
        padding: 28px;
        margin-bottom: 22px;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04), 0 22px 44px -34px rgba(15, 23, 42, 0.22);
    }

    .os-title { margin: 0 0 22px; font-size: 19px; font-weight: 700; letter-spacing: -0.01em; }

    /* Info */
    .os-info { display: grid; grid-template-columns: 1fr 1fr; gap: 22px 40px; }

    .os-item span {
        display: block;
        margin-bottom: 4px;
        font-size: 12.5px;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: var(--muted);
    }

    .os-item b { display: block; font-size: 17px; font-weight: 600; overflow-wrap: anywhere; }
    .os-item b.os-amount { font-size: 24px; font-weight: 800; letter-spacing: -0.02em; color: var(--brand); }

    .os-status {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 4px 13px;
        border-radius: 999px;
        font-style: normal;
        font-size: 14px;
        font-weight: 700;
        background: #f1f5f9;
        color: #475569;
    }

    .os-status::before { content: ""; width: 7px; height: 7px; border-radius: 50%; background: currentColor; }

    .os-pending    { background: #fffbeb; color: #b45309; }
    .os-processing { background: var(--brand-soft); color: var(--brand-dark); }
    .os-shipped    { background: #f5f3ff; color: #6d28d9; }
    .os-delivered,
    .os-completed  { background: #f0fdf4; color: #15803d; }
    .os-cancelled,
    .os-failed     { background: #fff1f2; color: #be123c; }

    /* Products */
    .os-product {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        padding: 16px 0;
        border-bottom: 1px solid #edf2f9;
    }

    .os-product:first-of-type { padding-top: 0; }

    .os-product h3 { margin: 0 0 8px; font-size: 17px; font-weight: 700; line-height: 1.3; }
    .os-product p { margin: 0; display: flex; flex-wrap: wrap; align-items: center; gap: 10px; }

    .os-qty {
        background: #f1f5f9;
        color: #334155;
        font-size: 13.5px;
        font-weight: 600;
        padding: 3px 12px;
        border-radius: 999px;
    }

    .os-unit { color: var(--muted); font-size: 15px; }

    .os-line { font-size: 18px; font-weight: 700; letter-spacing: -0.01em; white-space: nowrap; }

    .os-total {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
        margin-top: 20px;
        font-size: 17px;
        font-weight: 600;
    }

    .os-total b { font-size: 28px; font-weight: 800; letter-spacing: -0.03em; color: var(--brand); }

    .os-muted { margin: 0; color: var(--muted); font-size: 16px; }

    /* Address */
    .os-address { display: flex; gap: 16px; align-items: flex-start; }

    .os-pin {
        width: 46px;
        height: 46px;
        border-radius: 13px;
        background: var(--brand-soft);
        color: var(--brand);
        display: grid;
        place-items: center;
        flex-shrink: 0;
    }

    .os-pin svg { width: 22px; height: 22px; }

    .os-lines p { margin: 0; color: #475569; font-size: 16px; line-height: 1.6; }
    .os-lines .os-line1 { color: var(--ink); font-weight: 600; font-size: 17px; }

    /* Buttons */
    .os-actions { display: flex; justify-content: center; gap: 12px; flex-wrap: wrap; margin-top: 30px; }

    .os-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 14px 32px;
        border-radius: 999px;
        background: var(--brand);
        color: #fff;
        font-size: 15.5px;
        font-weight: 700;
        text-decoration: none;
        box-shadow: 0 14px 26px -14px rgba(37, 99, 235, 0.9);
        transition: background 0.2s, transform 0.2s;
    }

    .os-btn:hover { background: var(--brand-dark); color: #fff; transform: translateY(-2px); }

    .os-btn-ghost {
        background: #fff;
        color: var(--ink);
        border: 1px solid var(--border);
        box-shadow: none;
    }

    .os-btn-ghost:hover { background: var(--brand-soft); color: var(--brand-dark); border-color: var(--brand-tint); }

    .os-btn:focus-visible { outline: 3px solid rgba(37, 99, 235, 0.4); outline-offset: 3px; }

    @media (max-width: 600px) {
        .os-page { margin-top: 26px; }
        .os-hero h1 { font-size: 27px; }
        .os-card { padding: 22px 18px; border-radius: 16px; }
        .os-info { grid-template-columns: 1fr; gap: 18px; }
        .os-actions { flex-direction: column; }
        .os-btn { width: 100%; }
    }


/* =================================
   PROFESSIONAL ORDER TRACKING
================================= */

.os-tracking-card {
    overflow: hidden;
}

.os-tracking-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 16px;
    margin-bottom: 30px;
    flex-wrap: wrap;
}

.os-eyebrow {
    display: block;
    color: #2563eb;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 1.8px;
    margin-bottom: 8px;
}

.os-tracking-header .os-title {
    margin-bottom: 7px;
    font-size: 22px;
}

.os-tracking-subtitle {
    margin: 0;
    color: #64748b;
    font-size: 14px;
    line-height: 1.5;
}

.os-current-status {
    display: inline-flex;
    align-items: center;
    padding: 9px 15px;
    border-radius: 30px;
    background: #eff6ff;
    color: #1d4ed8;
    font-size: 12px;
    font-weight: 750;
    border: 1px solid #bfdbfe;
}

.os-status-pending {
    background: #fffbeb;
    color: #b45309;
    border-color: #fde68a;
}

.os-status-confirmed,
.os-status-processing {
    background: #eff6ff;
    color: #1d4ed8;
    border-color: #bfdbfe;
}

.os-status-shipped,
.os-status-out_for_delivery {
    background: #f5f3ff;
    color: #6d28d9;
    border-color: #ddd6fe;
}

.os-status-delivered {
    background: #f0fdf4;
    color: #15803d;
    border-color: #bbf7d0;
}

.os-status-cancelled {
    background: #fff1f2;
    color: #be123c;
    border-color: #fecdd3;
}

/* Timeline */

.os-tracking-body {
    padding: 8px 0 0;
}

.os-track-step {
    display: flex;
    gap: 17px;
    min-height: 105px;
}

.os-track-visual {
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 25px;
    flex-shrink: 0;
}

.os-track-dot {
    width: 27px;
    height: 27px;
    border-radius: 50%;
    display: grid;
    place-items: center;
    font-size: 13px;
    font-weight: 800;
    z-index: 1;
    background: #16a34a;
    color: white;
    border: 3px solid #dcfce7;
}

.os-track-line {
    flex: 1;
    width: 2px;
    min-height: 65px;
    background: #bbf7d0;
}

.os-track-details {
    flex: 1;
    padding: 2px 0 27px;
    min-width: 0;
}

.os-track-title-row {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 7px;
}

.os-track-title-row h3 {
    margin: 0;
    font-size: 15px;
    font-weight: 750;
    color: #334155;
}

.os-track-current .os-track-dot {
    width: 29px;
    height: 29px;
    background: #2563eb;
    border: 4px solid #dbeafe;
    box-shadow: 0 0 0 3px #eff6ff;
}

.os-track-current .os-track-title-row h3 {
    color: #1d4ed8;
    font-size: 16px;
}

.os-latest-label {
    font-size: 10px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    color: #1d4ed8;
    background: #eff6ff;
    border: 1px solid #bfdbfe;
    padding: 5px 9px;
    border-radius: 20px;
}

.os-track-description {
    margin: 0 0 9px;
    color: #64748b;
    font-size: 13px;
    line-height: 1.6;
}

.os-track-date {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
    color: #94a3b8;
    font-size: 12px;
    font-weight: 600;
}

.os-date-separator {
    color: #cbd5e1;
}

.os-track-cancelled .os-track-dot {
    background: #dc2626;
    border-color: #fee2e2;
}

.os-track-cancelled .os-track-title-row h3 {
    color: #dc2626;
}

.os-track-cancelled .os-track-line {
    background: #fecaca;
}

.os-track-empty {
    padding: 20px;
    text-align: center;
    background: #f8fafc;
    border-radius: 12px;
    color: #64748b;
}

.os-track-empty p {
    margin: 0;
    font-size: 14px;
}

.os-cancelled-notice,
.os-delivered-notice {
    margin-top: 15px;
    padding: 14px 16px;
    border-radius: 12px;
    font-size: 13px;
    font-weight: 650;
}

.os-cancelled-notice {
    background: #fff1f2;
    color: #be123c;
    border: 1px solid #fecdd3;
}

.os-delivered-notice {
    background: #f0fdf4;
    color: #15803d;
    border: 1px solid #bbf7d0;
}

@media (max-width: 600px) {
    .os-tracking-header {
        flex-direction: column;
    }

    .os-current-status {
        align-self: flex-start;
    }

    .os-track-step {
        gap: 13px;
    }

    .os-track-title-row h3 {
        font-size: 14px;
    }

    .os-track-details {
        padding-bottom: 25px;
    }
}

</style>
@endpush
@push('scripts')
<script>
    function toggleCancelForm(orderId) {
        const form = document.getElementById('cancel-form-' + orderId);

        if (form.style.display === 'none') {
            form.style.display = 'block';
        } else {
            form.style.display = 'none';
        }
    }
</script>
@endpush