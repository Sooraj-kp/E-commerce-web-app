
    @if($orders->isEmpty())

        {{-- Empty Orders --}}
        <div class="empty-orders">
            <div class="empty-icon">
                <svg viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="1.7">
                    <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                    <path d="M3 6h18M16 10a4 4 0 0 1-8 0"/>
                </svg>
            </div>

            <h2>No orders yet</h2>
            <p>You haven't placed any orders. Start shopping today!</p>

            <a href="{{ url('/shop') }}" class="shop-btn">
                Continue Shopping
            </a>
        </div>

    @else

        {{-- Order List --}}
        <div class="orders-list">
            @foreach($orders as $order)

                <div class="order-card">

                    <div class="order-top">

                        <div>
                            <span class="order-label">ORDER NUMBER</span>
                            <h3>{{ $order->order_number }}</h3>
                        </div>

                        <span class="status-badge status-{{ strtolower($order->status) }}">
                            {{ ucfirst($order->status) }}
                        </span>

                    </div>
                    {{-- Ordered Products --}}
<div class="ordered-products">

    @foreach($order->items->take(2) as $item)

        @if($item->product)

            @php
                 $mainImage = $item->product->images->first();
            @endphp

            <div class="ordered-product">

                <div class="ordered-product-image">
                    @if($mainImage)
                        <img
                            src="{{asset('image_uploads/'.$mainImage->image_path)}}" alt="{{$item->product->name}}"
                        >
                    @else
                        <span>No Image</span>
                    @endif
                </div>

                <div class="ordered-product-details">
                    <h4>{{ $item->product->name }}</h4>

                    <p>Quantity: {{ $item->quantity }}</p>

                    <strong>
                        ₹{{ number_format($item->price * $item->quantity, 2) }}
                    </strong>
                

            </div>

        @endif

    @endforeach
</div>
    @if($order->items->count() > 2)
        <p class="more-products">
            +{{ $order->items->count() - 2 }} more items
        </p>
    @endif

</div>

                    <div class="order-info">

                        <div class="info-item">
                            <span>Order Date</span>
                            <strong>{{ $order->created_at->format('d M Y') }}</strong>
                        </div>

                        <div class="info-item">
                            <span>Total Amount</span>
                            <strong>₹{{ number_format($order->total_amount, 2) }}</strong>
                        </div>

                        <div class="info-item">
                            <span>Order Items</span>
                            <strong>{{ $order->items->count() }} Products</strong>
                        </div>

                    </div>

                    <div class="order-bottom">

                        <span class="order-note">
                            Thank you for shopping with us!
                        </span>

                        <a href="{{ route('customer.orders.show', $order->id) }}"
                           class="view-order-btn">
                            View Details →
                        </a>

                        @if($order->canBeCancelled())

    <div class="cancel-section">

        <button type="button"
                class="cancel-order-btn"
                onclick="toggleCancelForm({{ $order->id }})">
            Cancel Order
        </button>

        <form id="cancel-form-{{ $order->id }}"
              class="cancel-form"
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

            <div class="cancel-form-actions">
                <button type="submit" class="confirm-cancel-btn">
                    Confirm Cancellation
                </button>

                <button type="button"
                        class="keep-order-btn"
                        onclick="toggleCancelForm({{ $order->id }})">
                    Keep Order
                </button>
            </div>

        </form>

    </div>

@endif

                    </div>

                </div>

            @endforeach

        </div>

        {{-- Pagination --}}
        <div class="orders-pagination">
            {{ $orders->links() }}
        </div>

    @endif