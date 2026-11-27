
@extends('layouts.customer')

@section('title', 'My Orders')

@section('content')

<div class="orders-page">

    {{-- Heading --}}
    <div class="orders-header">
        <div>
            <h1>My Orders</h1>
            <div class="order-tabs">
                <a href="{{route('customer.orders.index',['status'=>'active'])}}" class="order-filter {{ $status === 'active' ? 'active' : '' }}">Active Orders:{{$activeCount}}</a>
                <a href="{{route('customer.orders.index',['status'=>'cancelled'])}}" class=" order-filter {{ $status === 'cancelled' ? 'active' : '' }}">Cancelled Orders:{{ $cancelCount}}</a>
            </div>
            <p>View and manage your previous purchases.</p>
        </div>

        <span class="orders-count">
            {{ $orders->total() }} Orders
        </span>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="success-alert">
            {{ session('success') }}
        </div>
    @endif
<div id="orders-container">
    @include('customer.orders.partials.list')
</div>

</div>

@endsection


@push('styles')
<style>
.cancel-section {
    display: flex;
    flex-direction: column;
    gap: 12px;
    width: 100%;
    max-width: 320px;
}

.cancel-order-btn,
.confirm-cancel-btn,
.keep-order-btn {
    padding: 10px 17px;
    border-radius: 7px;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    transition: 0.2s ease;
}

.cancel-order-btn {
    background: white;
    color: #dc2626;
    border: 1px solid #fecaca;
}

.cancel-order-btn:hover {
    background: #fef2f2;
}

.cancel-form textarea {
    width: 100%;
    padding: 12px;
    border: 1px solid #dbe5f3;
    border-radius: 8px;
    resize: vertical;
    font-family: inherit;
    font-size: 13px;
    box-sizing: border-box;
}

.cancel-form textarea:focus {
    outline: none;
    border-color: #2563eb;
}

.cancel-form-actions {
    display: flex;
    gap: 8px;
    margin-top: 8px;
}

.confirm-cancel-btn {
    background: #dc2626;
    color: white;
    border: 1px solid #dc2626;
}

.confirm-cancel-btn:hover {
    background: #b91c1c;
}

.keep-order-btn {
    background: white;
    color: #475569;
    border: 1px solid #cbd5e1;
}

@media(max-width: 650px) {
    .cancel-section {
        max-width: 100%;
    }
}
.orders-page {
    width: 92%;
    max-width: 1050px;
    margin: 45px auto 65px;
    color: #0f172a;
}


.orders-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    gap: 15px;
}
.ordered-product-image {
    width: 65px;
    height: 65px;
    flex-shrink: 0;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 9px;
    overflow: hidden;
}

.ordered-product-image img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    display: block;
}

.orders-header h1 {
    font-size: 32px;
    font-weight: 800;
    margin: 0 0 7px;
}

.orders-header p {
    color: #64748b;
    margin: 0;
}

.orders-count {
    background: #eff6ff;
    color: #2563eb;
    padding: 9px 16px;
    border-radius: 30px;
    font-size: 13px;
    font-weight: 700;
}

/* Order Card */
.payment-badge {
    display: inline-block;
    padding: 7px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
}

.payment-paid {
    background: #dcfce7;
    color: #166534;
}

.payment-pending {
    background: #fef3c7;
    color: #92400e;
}

.payment-failed {
    background: #fee2e2;
    color: #b91c1c;
}

.payment-cod {
    background: #e0f2fe;
    color: #0369a1;
}

.payment-unknown {
    background: #f1f5f9;
    color: #475569;
}

.orders-list {
    display: grid;
    gap: 20px;
}

.order-card {
    background: white;
    border: 1px solid #dbe5f3;
    border-radius: 14px;
    padding: 24px;
    box-shadow: 0 5px 18px rgba(15, 23, 42, 0.04);
}

.order-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
    padding-bottom: 18px;
    border-bottom: 1px solid #e2e8f0;
}

.order-label {
    color: #64748b;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.08em;
}

.order-top h3 {
    font-size: 17px;
    font-weight: 750;
    margin: 5px 0 0;
}

/* Status */

.status-badge {
    padding: 7px 14px;
    border-radius: 30px;
    font-size: 12px;
    font-weight: 700;
    text-transform: capitalize;
}

.status-pending {
    background: #fef3c7;
    color: #92400e;
}

.status-confirmed {
    background: #dbeafe;
    color: #1d4ed8;
}

.status-processing {
    background: #ede9fe;
    color: #6d28d9;
}

.status-shipped {
    background: #e0f2fe;
    color: #0369a1;
}

.status-delivered {
    background: #dcfce7;
    color: #166534;
}

.status-cancelled {
    background: #fee2e2;
    color: #b91c1c;
}

/* Order Information */

.order-info {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    padding: 22px 0;
}

.info-item span {
    display: block;
    color: #64748b;
    font-size: 13px;
    margin-bottom: 6px;
}

.info-item strong {
    font-size: 15px;
    font-weight: 700;
}

/* Bottom */

.order-bottom {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
    border-top: 1px solid #e2e8f0;
    padding-top: 18px;
}

.order-note {
    color: #64748b;
    font-size: 13px;
}

.view-order-btn {
    background: #2563eb;
    color: white;
    text-decoration: none;
    padding: 10px 17px;
    border-radius: 7px;
    font-size: 13px;
    font-weight: 700;
}

.view-order-btn:hover {
    background: #1d4ed8;
}

/* Empty State */

.empty-orders {
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

.empty-orders h2 {
    font-size: 21px;
    font-weight: 700;
}

.empty-orders p {
    color: #64748b;
    margin-bottom: 22px;
}

.shop-btn {
    display: inline-block;
    background: #2563eb;
    color: white;
    padding: 12px 20px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 700;
}

/* Pagination */

/* Pagination */

.orders-pagination {
    margin-top: 25px;
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 5px;
}

.orders-pagination svg {
    width: 16px !important;
    height: 16px !important;
    display: inline-block;
}

.orders-pagination a,
.orders-pagination span[aria-current="page"] {
    padding: 8px 13px;
    border-radius: 6px;
    text-decoration: none;
}

.orders-pagination a {
    color: #2563eb;
    background: white;
    border: 1px solid #dbe5f3;
}

.orders-pagination a:hover {
    background: #eff6ff;
}

.orders-pagination span[aria-current="page"] {
    background: #2563eb;
    color: white;
    border: 1px solid #2563eb;
}
.order-tabs {
    display: flex;
    gap: 12px;
    margin: 20px 0 25px;
    border-bottom: 1px solid #e2e8f0;
}

.order-tabs a {
    padding: 12px 18px;
    text-decoration: none;
    color: #64748b;
    font-weight: 600;
    border-bottom: 3px solid transparent;
    transition: 0.2s;
}

.order-tabs a:hover {
    color: #2563eb;
}

.order-tabs a.active {
    color: #2563eb;
    border-bottom-color: #2563eb;
}

@media(max-width: 650px) {

    .orders-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .orders-header h1 {
        font-size: 27px;
    }

    .order-info {
        grid-template-columns: 1fr 1fr;
    }

    .order-bottom {
        flex-direction: column;
        align-items: flex-start;
    }

    .view-order-btn {
        width: 100%;
        text-align: center;
    }

    .order-card {
        padding: 18px;
    }
}

</style>
@endpush
@push('scripts')
<script>
$(document).ready(function () {

    
    $(document).on('click', '.order-filter', function (e) {
        e.preventDefault();

        let url = $(this).attr('href');

        loadOrders(url);

        $('.order-filter').removeClass('active');
        $(this).addClass('active');
    });


    $(document).on('click', '.orders-pagination a', function (e) {
        e.preventDefault();

        let url = $(this).attr('href');

        loadOrders(url);
    });

    // AJAX function to load the orders list
    function loadOrders(url) {

        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'html',

            success: function (response) {
                $('#orders-container').html(response);
            },

            error: function (xhr) {
                console.error('Orders loading failed:', xhr.responseText);
            }
        });
    }
});

// Cancel order form toggle
function toggleCancelForm(orderId) {

    const form = document.getElementById('cancel-form-' + orderId);

    if (!form) return;

    form.style.display =
        form.style.display === 'none' ? 'block' : 'none';
}
</script>
@endpush

