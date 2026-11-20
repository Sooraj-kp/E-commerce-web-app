@extends('layouts.customer')

@section('title', 'My Cart')

@section('content')

@php
    $items = $cart ? $cart->items->filter(fn ($i) => $i->product) : collect();
    $itemCount = $items->sum('quantity');
    $total = $items->sum(fn ($i) => $i->product->price * $i->quantity);
@endphp

<div class="cart-container">

    @if(session('success'))
        <div class="alert-success">
            {{session('success')}}
        </div>
        @endif
        @if(session('error'))
            <div class="alert-error">
                {{session('error')}}
            </div>
        @endif

    <div class="cart-head">
        <h2>My shopping cart</h2>
        @if ($items->isNotEmpty())
            <span class="cart-count">{{ $itemCount }} {{ \Illuminate\Support\Str::plural('item', $itemCount) }}</span>
        @endif
    </div>

    @if ($items->isEmpty())

        <div class="empty-cart">
            <div class="empty-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
            </div>
            <h3>Your cart is empty</h3>
            <p>Explore our products and add something to your cart.</p>
            <a href="{{ url('/shop') }}" class="btn-main">Continue shopping</a>
        </div>

    @else

        <div class="cart-layout">

            {{-- Items --}}
            <div class="cart-list">

                @foreach ($items as $item)

                    @php
                        $product   = $item->product;
                        $mainImage = $product->images->firstWhere('is_primary', true) ?? $product->images->first();
                        $subtotal  = $product->price * $item->quantity;
                    @endphp

                    <div class="cart-card">

                        <div class="cart-image">
                            @if ($mainImage)
                                <img
                                    src="{{ asset('image_uploads/' . $mainImage->image_path) }}"
                                    alt="{{ $product->name }}"
                                >
                            @else
                                <div class="no-image">No image</div>
                            @endif
                        </div>

                        <div class="cart-details">
                            <h3>{{ $product->name }}</h3>

                            <p class="cart-price">₹{{ number_format($product->price, 2) }} <span>each</span></p>

                            <div class="quantity-control"
                                data-cart-item-id="{{$item->id}}"
                                data-stock="{{$product->stock}}"
                                data-price="{{$product->price}}"
                                data-update-url="{{route('cart.update',$item->id)}}">  
                                
                                <button type="button" class="quantity-minus">-</button>

                                <input type="number" class="quantity-input" value="{{$item->quantity}}" min="1" data-old-quantity="{{$item->quantity}}" max="{{$product->stock}}">
                                <button type="button" class="quantity-plus">+</button>
                            </div>
                        </div>

                        <div class="cart-subtotal">
                            <span>Subtotal</span>
                            <b class="item-subtotal">₹{{ number_format($subtotal, 2) }}</b>
                        </div>
                        <form action="{{route('cart.remove',$item->id)}}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="color:red" class='btn-remove'>Remove</button>
                        </form>

                    </div>

                @endforeach

            </div>

            {{-- Summary --}}
            <aside class="cart-summary">
                <h3>Order summary</h3>

                <div class="sum-row">
                    <span id="cart-item-count">Items ({{ $itemCount }})</span>
                    <span id="cart-subtotal">₹{{ number_format($total, 2) }}</span>
                </div>

                <div class="sum-row">
                    <span>Shipping</span>
                    <span class="muted">At checkout</span>
                </div>

                <div class="sum-total">
                    <span>Total</span>
                    <b id="cart-grand-total">₹{{ number_format($total, 2) }}</b>
                    
                </div>

                {{-- Point this to your own checkout route --}}
                <a href="{{ url('/checkout') }}" class="btn-main btn-block">Proceed to checkout</a>
                <a href="{{ url('/shop') }}" class="btn-link">Continue shopping</a>
            </aside>

        </div>

    @endif

</div>

@endsection

@push('styles')
<style>
    .cart-container {
        --c-brand: #2563eb;
        --c-brand-dark: #1d4ed8;
        --c-soft: #eff6ff;
        --c-tint: #dbeafe;
        --c-border: #dbe5f3;
        --c-ink: #0f172a;
        --c-muted: #64748b;

        width: 90%;
        max-width: 1100px;
        margin: 38px auto 60px;
        color: var(--c-ink);
    }

    .cart-head {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-bottom: 26px;
    }

    .cart-head h2 {
        margin: 0;
        font-size: 30px;
        font-weight: 800;
        letter-spacing: -0.03em;
    }

    .cart-count {
        background: var(--c-soft);
        color: var(--c-brand-dark);
        border: 1px solid var(--c-tint);
        padding: 4px 14px;
        border-radius: 999px;
        font-size: 14px;
        font-weight: 700;
    }

    /* Layout */
    .cart-layout {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 340px;
        gap: 28px;
        align-items: start;
    }

    .cart-list { display: flex; flex-direction: column; gap: 16px; }

    /* Item card */
    .cart-card {
        display: flex;
        align-items: center;
        gap: 22px;
        background: #fff;
        border: 1px solid var(--c-border);
        padding: 18px;
        border-radius: 18px;
        transition: box-shadow 0.2s, border-color 0.2s;
    }

    .cart-card:hover {
        box-shadow: 0 18px 36px -24px rgba(15, 23, 42, 0.3);
        border-color: #c8d7ee;
    }

    .cart-image {
        width: 130px;
        height: 130px;
        flex-shrink: 0;
        border-radius: 14px;
        background: #f5f7fb;
        overflow: hidden;
    }

    .cart-image img { width: 100%; height: 100%; object-fit: cover; display: block; }

    .no-image {
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #94a3b8;
        font-size: 14px;
    }

    .cart-details { flex: 1; min-width: 0; }

    .cart-details h3 {
        margin: 0 0 6px;
        font-size: 18px;
        font-weight: 700;
        line-height: 1.35;
    }

    .cart-price {
        margin: 0 0 12px;
        color: var(--c-brand);
        font-size: 17px;
        font-weight: 700;
    }

    .cart-price span { color: var(--c-muted); font-size: 14px; font-weight: 500; }

    .cart-qty {
        display: inline-block;
        background: #f1f5f9;
        color: #334155;
        padding: 5px 14px;
        border-radius: 999px;
        font-size: 14px;
        font-weight: 600;
    }

    .cart-subtotal { text-align: right; flex-shrink: 0; }
    .cart-subtotal span { display: block; color: var(--c-muted); font-size: 13.5px; margin-bottom: 2px; }
    .cart-subtotal b { font-size: 20px; font-weight: 800; letter-spacing: -0.02em; }

    /* Summary */
    .cart-summary {
        position: sticky;
        top: 24px;
        background: #fff;
        border: 1px solid var(--c-border);
        border-radius: 18px;
        padding: 26px;
        box-shadow: 0 18px 40px -30px rgba(15, 23, 42, 0.3);
    }

    .cart-summary h3 { margin: 0 0 18px; font-size: 19px; font-weight: 800; }

    .sum-row {
        display: flex;
        justify-content: space-between;
        padding: 9px 0;
        color: #334155;
        font-size: 15.5px;
    }

    .sum-row .muted { color: var(--c-muted); }

    .sum-total {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
        margin: 12px 0 22px;
        padding-top: 18px;
        border-top: 1px solid var(--c-border);
        font-size: 16px;
        font-weight: 700;
    }

    .sum-total b { font-size: 26px; font-weight: 800; letter-spacing: -0.03em; }

    /* Buttons */
    .btn-main {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: var(--c-brand);
        color: #fff;
        padding: 14px 28px;
        border-radius: 12px;
        font-size: 15.5px;
        font-weight: 700;
        text-decoration: none;
        box-shadow: 0 12px 22px -12px rgba(37, 99, 235, 0.85);
        transition: background 0.2s, transform 0.2s;
    }

    .btn-main:hover { background: var(--c-brand-dark); color: #fff; transform: translateY(-1px); }
    .btn-block { display: flex; width: 100%; }

    .btn-link {
        display: block;
        text-align: center;
        margin-top: 14px;
        color: var(--c-brand);
        font-size: 15px;
        font-weight: 600;
        text-decoration: none;
    }
    .alert-success {
        background: #dcfce7;
        color: #166534;
        padding: 12px 18px;
        border-radius: 8px;
        margin-bottom: 18px;
    }

    .alert-error {
        background: #fee2e2;
        color: #991b1b;
        padding: 12px 18px;
        border-radius: 8px;
        margin-bottom: 18px;
    }

    .btn-link:hover { color: var(--c-brand-dark); text-decoration: underline; text-underline-offset: 3px; }

    .btn-main:focus-visible, .btn-link:focus-visible { outline: 3px solid rgba(37, 99, 235, 0.4); outline-offset: 2px; }

    /* Empty */
    .empty-cart {
        text-align: center;
        background: #fff;
        border: 1px dashed #c3d0e4;
        padding: 64px 24px;
        border-radius: 20px;
    }
    .btn-remove {
        margin-top: 12px;
        padding: 9px 16px;
        background: #fee2e2;
        color: #dc2626;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
    }

.btn-remove:hover {
    background: #fecaca;
}

    .empty-icon {
        width: 76px;
        height: 76px;
        margin: 0 auto 18px;
        border-radius: 50%;
        background: var(--c-soft);
        color: var(--c-brand);
        display: grid;
        place-items: center;
    }

    .empty-icon svg { width: 34px; height: 34px; }
    .empty-cart h3 { margin: 0 0 8px; font-size: 22px; font-weight: 800; }
    .empty-cart p { margin: 0 0 24px; color: var(--c-muted); font-size: 16px; }

    /* Responsive */
    @media (max-width: 900px) {
        .cart-layout { grid-template-columns: 1fr; }
        .cart-summary { position: static; }
    }

    @media (max-width: 600px) {
        .cart-head h2 { font-size: 25px; }

        .cart-card { flex-wrap: wrap; align-items: flex-start; gap: 14px; padding: 14px; }
        .cart-image { width: 96px; height: 96px; }
        .cart-details { flex: 1 1 calc(100% - 120px); }
        .cart-details h3 { font-size: 16px; }
        .cart-subtotal {
            flex: 1 1 100%;
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            padding-top: 12px;
            border-top: 1px solid var(--c-border);
            text-align: left;
        }
        .cart-subtotal span { margin: 0; }
    }
</style>
@endpush
@push('scripts')
<script>
    $(document).ready(function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

            function updateCartItem(control,quantity,oldQuantity){
            let url=control.data('update-url');

            $.ajax({
                url:url,
                type:'PATCH',
                data:{
                    quantity:quantity
                },
                dataType:'json',

                success:function(response){
                    console.log(response);
                    control.find('.quantity-input').val(response.quantity);

                    let price=parseFloat(control.data('price'));
                    let subtotal=price * response.quantity;

                    control.closest('.cart-card').find('.item-subtotal').text('₹'+subtotal.toFixed(2));

                    let total=0;
                    let itemCount=0;

                    $('.cart-card').each(function(){
                        let card=$(this);
                        let itemQuantity=parseInt(card.find('.quantity-input').val());
                        let itemPrice=parseInt(card.find('.quantity-control').data('price'));

                        total += quantity * itemPrice;
                        itemCount += quantity;

                    });

                        $('#cart-subtotal').text('₹' + total.toFixed(2));
                        $('#cart-grand-total').text('₹' + total.toFixed(2));
                        $('#cart-item-count').text('Items (' + itemCount + ')');

                        control.find('.quantity-input')
                            .data('old-quantity', response.quantity);
                },
                error:function(xhr){
                    control.find('.quantity-input').val(oldQuantity);
                    let message=xhr.responseJSON?.errors?.quantity?.[0] || xhr.responseJSON?.message|| 'Unable to update cart';

                    alert(message); 

                }

            });
        }
        $('.quantity-plus').click(function(){
            let control=$(this).closest('.quantity-control');
            let input=control.find('.quantity-input');

            let quantity=parseInt(input.val());

            let stock=parseInt(control.data('stock'));


            if(quantity < stock){
                input.val(quantity + 1);
                updateCartItem(control,quantity+1,quantity);

            }

        });
        $('.quantity-minus').click(function(){
            let control=$(this).closest('.quantity-control');
            let input = control.find('.quantity-input');

            let quantity=parseInt(input.val());

            if(quantity > 1){
                input.val(quantity-1);
                updateCartItem(control,quantity-1,quantity);
            }

        });

        $('.quantity-input').on('change',function(){
            let input=$(this);
            let control=input.closest('.quantity-control');

            let newQuantity=parseInt(input.val());
            let oldQuantity=parseInt(input.data('old-quantity'));

            let stock=parseInt(control.data('stock'));

            if(isNaN(newQuantity) || newQuantity < 1 || newQuantity > stock){
                alert('Please enter the quantity between 1 and '+stock);
                input.val(oldQuantity);
                return;
            }
            if(newQuantity !== oldQuantity){
                updateCartItem(control,newQuantity,oldQuantity);
            }

        });



    });
</script>
@endpush