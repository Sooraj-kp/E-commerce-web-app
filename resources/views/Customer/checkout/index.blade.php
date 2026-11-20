
@extends('layouts.customer')

@section('title', 'Checkout')

@section('content')

<div class="checkout-page">

    {{-- Page Heading --}}
    <div class="checkout-header">
        <div>
            <h1>Checkout</h1>
            <p>Select your delivery address to continue.</p>
        </div>

        <span class="checkout-step">Step 1 of 1</span>
    </div>

    @if(session('error'))
        <div class="checkout-alert">
            {{ session('error') }}
        </div>
    @endif

    @if($addresses->isEmpty())

        {{-- Empty Address Section --}}
        <div class="empty-address">

            <div class="empty-icon">
                <svg viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.7">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                    <circle cx="12" cy="10" r="3"/>
                </svg>
            </div>

            <h2>No delivery address found</h2>

            <p>Please add a delivery address before placing your order.</p>

            <a href="{{ route('customer.address.create') }}"
                class="add-address-btn">
                + Add New Address
            </a>

        </div>

    @else

        <form action="{{ route('checkout.store') }}" method="POST">
            @csrf

            <div class="checkout-section">

                <div class="section-heading">
                    <div>
                        <h2>Delivery Address</h2>
                        <p>Choose where you want your order delivered.</p>
                    </div>

                    <a href="{{ route('customer.address.create') }}"
                        class="add-another-btn">
                        + Add Address
                    </a>
                </div>

                <div class="address-grid">

                    @foreach($addresses as $address)

                        <label class="address-card">

                            <div class="address-top">

                                <div class="address-radio">
                                    <input
                                        type="radio"
                                        name="address_id"
                                        value="{{ $address->id }}"
                                        required
                                    >
                                    <span class="radio-custom"></span>
                                </div>

                                <span class="address-number">
                                    ADDRESS {{ $loop->iteration }}
                                </span>

                            </div>

                            <div class="address-details">

                                <div class="address-icon">
                                    <svg viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                        stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                        <circle cx="12" cy="10" r="3"/>
                                    </svg>
                                </div>

                                <div>
                                    <h3>{{ $address->address_line }}</h3>
                                    <p>{{ $address->city }}, {{ $address->state }}</p>
                                    <p>{{ $address->postal_code }}</p>
                                    <p>{{ $address->country }}</p>
                                </div>

                            </div>

                            <div class="address-bottom">
                                <span>Select this address</span>
                                <span class="selected-label">Selected</span>
                            </div>

                        </label>

                    @endforeach

                </div>

            </div>
            {{-- Order Summary --}}
            <div class="checkout-section order-summary">

                <div class="section-heading">
                    <div>
                        <h2>Order Summary</h2>
                        <p>Review your selected products.</p>
                    </div>
                </div>

                @if($buyNow)

                    {{-- Buy Now Product --}}
                    <div class="order-item">
                        <div>
                            <h3>{{ $product->name }}</h3>
                            <p>Quantity: {{ $quantity }}</p>
                            <p>Price: ₹{{ number_format($product->price, 2) }}</p>
                        </div>

                        <strong>
                            ₹{{ number_format($total, 2) }}
                        </strong>
                    </div>

                    <div class="order-total">
                        <span>Total</span>
                        <strong>₹{{ number_format($total, 2) }}</strong>
                    </div>

                @else

                    {{-- Normal Cart Products --}}
                    @php
                        $grandTotal = 0;
                    @endphp

                    @foreach($cart->items as $item)

                        @php
                            $subtotal = $item->product->price * $item->quantity;
                            $grandTotal += $subtotal;
                        @endphp

                        <div class="order-item">
                            <div>
                                <h3>{{ $item->product->name }}</h3>
                                <p>Quantity: {{ $item->quantity }}</p>
                                <p>Price: ₹{{ number_format($item->product->price, 2) }}</p>
                            </div>

                            <strong>
                                ₹{{ number_format($subtotal, 2) }}
                            </strong>
                        </div>

                    @endforeach

                    <div class="order-total">
                        <span>Total</span>
                        <strong>₹{{ number_format($grandTotal, 2) }}</strong>
                    </div>

                @endif

            </div>

            {{-- Place Order --}}
            <div class="checkout-footer">

                <div>
                    <h3>Ready to place your order?</h3>
                    <p>Review your selected delivery address before continuing.</p>
                </div>

                <button type="submit" class="place-order-btn">
                    Place Order
                    <span>→</span>
                </button>

            </div>

        </form>

    @endif

</div>

@endsection


@push('styles')
<style>

    .checkout-page {
        width: 92%;
        max-width: 1050px;
        margin: 45px auto 65px;
        color: #0f172a;
    }

    /* Heading */

    .checkout-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        margin-bottom: 30px;
    }

    .checkout-header h1 {
        font-size: 32px;
        font-weight: 800;
        margin: 0 0 7px;
    }

    .checkout-header p {
        color: #64748b;
        margin: 0;
        font-size: 15px;
    }

    .checkout-step {
        background: #eff6ff;
        color: #2563eb;
        padding: 9px 16px;
        border-radius: 30px;
        font-size: 13px;
        font-weight: 700;
    }

    /* Section */

    .checkout-section {
        background: white;
        border: 1px solid #dbe5f3;
        border-radius: 16px;
        padding: 28px;
        box-shadow: 0 8px 25px rgba(15, 23, 42, 0.04);
    }

    .section-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        margin-bottom: 25px;
    }

    .section-heading h2 {
        font-size: 20px;
        font-weight: 750;
        margin: 0 0 5px;
    }

    .section-heading p {
        color: #64748b;
        font-size: 14px;
        margin: 0;
    }

    .add-another-btn {
        text-decoration: none;
        color: #2563eb;
        border: 1px solid #bfdbfe;
        padding: 9px 14px;
        border-radius: 7px;
        font-size: 13px;
        font-weight: 700;
        white-space: nowrap;
    }

    .add-another-btn:hover {
        background: #eff6ff;
    }

    /* Address Grid */

    .address-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 18px;
    }

    .address-card {
        display: block;
        padding: 20px;
        border: 1.5px solid #dbe5f3;
        border-radius: 13px;
        cursor: pointer;
        background: #fbfdff;
        transition: 0.2s ease;
    }

    .address-card:hover {
        border-color: #93c5fd;
        box-shadow: 0 8px 20px rgba(37, 99, 235, 0.08);
    }

    .address-card:has(input:checked) {
        border-color: #2563eb;
        background: #eff6ff;
        box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.08);
    }

    .address-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .address-number {
        font-size: 11px;
        font-weight: 800;
        color: #64748b;
        letter-spacing: 0.08em;
    }

    .address-radio {
        display: flex;
        align-items: center;
        position: relative;
    }

    .address-radio input {
        position: absolute;
        opacity: 0;
    }

    .radio-custom {
        width: 19px;
        height: 19px;
        border: 2px solid #94a3b8;
        border-radius: 50%;
        display: block;
        position: relative;
    }

    .address-radio input:checked + .radio-custom {
        border-color: #2563eb;
    }

    .address-radio input:checked + .radio-custom::after {
        content: "";
        position: absolute;
        width: 9px;
        height: 9px;
        background: #2563eb;
        border-radius: 50%;
        top: 3px;
        left: 3px;
    }

    .address-details {
        display: flex;
        align-items: flex-start;
        gap: 13px;
    }

    .address-icon {
        width: 40px;
        height: 40px;
        flex-shrink: 0;
        display: grid;
        place-items: center;
        border-radius: 10px;
        background: #eff6ff;
        color: #2563eb;
    }

    .address-icon svg {
        width: 21px;
        height: 21px;
    }

    .address-details h3 {
        font-size: 16px;
        font-weight: 700;
        margin: 0 0 6px;
    }

    .address-details p {
        color: #64748b;
        font-size: 14px;
        margin: 3px 0;
        line-height: 1.5;
    }

    .address-bottom {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-top: 1px solid #e2e8f0;
        margin-top: 18px;
        padding-top: 13px;
        font-size: 12px;
        color: #64748b;
    }

    .selected-label {
        display: none;
        color: #2563eb;
        font-weight: 700;
    }

    .address-card:has(input:checked) .selected-label {
        display: inline;
    }

    /* Footer */

    .checkout-footer {
        margin-top: 24px;
        padding: 23px 26px;
        border-radius: 14px;
        background: white;
        border: 1px solid #dbe5f3;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
    }

    .checkout-footer h3 {
        font-size: 16px;
        font-weight: 700;
        margin: 0 0 5px;
    }

    .checkout-footer p {
        color: #64748b;
        font-size: 13px;
        margin: 0;
    }

    .place-order-btn {
        background: #2563eb;
        color: white;
        border: none;
        padding: 13px 24px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        transition: 0.2s;
        white-space: nowrap;
    }

    .place-order-btn:hover {
        background: #1d4ed8;
        transform: translateY(-1px);
    }

    .place-order-btn span {
        margin-left: 8px;
        font-size: 17px;
    }

    /* Empty Address */

    .empty-address {
        background: white;
        border: 1px solid #dbe5f3;
        border-radius: 16px;
        padding: 55px 20px;
        text-align: center;
    }

    .empty-icon {
        width: 65px;
        height: 65px;
        margin: 0 auto 18px;
        background: #eff6ff;
        color: #2563eb;
        border-radius: 50%;
        display: grid;
        place-items: center;
    }

    .empty-icon svg {
        width: 30px;
        height: 30px;
    }

    .empty-address h2 {
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .empty-address p {
        color: #64748b;
        margin-bottom: 22px;
    }

    .add-address-btn {
        display: inline-block;
        background: #2563eb;
        color: white;
        text-decoration: none;
        padding: 12px 20px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 14px;
    }

    .add-address-btn:hover {
        background: #1d4ed8;
    }

    .checkout-alert {
        background: #fee2e2;
        color: #991b1b;
        padding: 13px 16px;
        border-radius: 8px;
        margin-bottom: 20px;
    }
    .order-summary {
    margin-top: 24px;
    }

    .order-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        padding: 18px 0;
        border-bottom: 1px solid #e2e8f0;
    }

    .order-item h3 {
        font-size: 15px;
        margin: 0 0 8px;
    }

    .order-item p {
        color: #64748b;
        font-size: 13px;
        margin: 4px 0;
    }

    .order-item strong {
        white-space: nowrap;
    }

    .order-total {
        display: flex;
        justify-content: space-between;
        padding-top: 20px;
        font-size: 17px;
        font-weight: 700;
    }

    /* Responsive */

    @media(max-width: 650px) {
        .checkout-header,
        .section-heading,
        .checkout-footer {
            flex-direction: column;
            align-items: flex-start;
        }

        .checkout-header h1 {
            font-size: 27px;
        }

        .checkout-section {
            padding: 20px;
        }

        .checkout-footer {
            padding: 20px;
        }

        .place-order-btn {
            width: 100%;
        }
    }



</style>
@endpush
