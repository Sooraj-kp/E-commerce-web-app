
@extends('layouts.customer')

@section('title', $product->name . ' | My Store')

@section('page-heading', 'Product Details')

@section('content')

<div class="product-container">


    <div class="product-layout">

        <!-- Product Images -->
        <div class="product-gallery">

            @php
                $mainImage = $product->images->firstWhere('is_primary', true)
                    ?? $product->images->first();
            @endphp

            @if($mainImage)
                <img
                    src="{{ asset('image_uploads/' . $mainImage->image_path) }}"
                    class="main-image"
                    id="main-product-image"
                    alt="{{ $product->name }}"
                >
            @else
                <div class="no-image">No image available</div>
            @endif

            <div class="thumbnail-list">

                @foreach($product->images as $image)

                    <img
                        src="{{ asset('image_uploads/' . $image->image_path) }}"
                        class="thumbnail"
                        alt="{{ $product->name }}"
                    >

                @endforeach

            </div>

        </div>

        <!-- Product Information -->
        <div class="product-info">

            <h1>{{ $product->name }}</h1>

            <p class="category">
                Category:
                {{ $product->category->name ?? 'Uncategorized' }}
            </p>

            <p class="price">
                ₹{{ number_format($product->price, 2) }}
            </p>

           
            @if($product->stock > 10)
                <p class="stock available">
                    In Stock: {{ $product->stock }} available
                </p>
            @elseif($product->stock > 0)
                <p class="stock low-stock">
                    Only {{ $product->stock }} left in stock!
                </p>
            @else
                <p class="out-stock">Out of Stock</p>
            @endif

            @if($product->stock > 0)

                @auth
                    <form id="cartForm"
                        action="{{ route('cart.add', $product->id) }}"
                        method="POST">
                        @csrf

                        <div class="quantity-box">
                            <label for="quantity">Quantity:</label>

                            <button type="button" id="decrease">-</button>

                            <input
                                type="number"
                                id="quantity"
                                name="quantity"
                                value="1"
                                min="1"
                                max="{{ $product->stock }}"
                                required
                            >

                            <button type="button" id="increase">+</button>
                        </div>

                        <div class="purchase-buttons">
                            <button type="submit" class="add-cart-btn">
                                Add To Cart
                            </button>

                            <button
                                type="submit"
                                form="buyNowForm"
                                class="buy-now-btn">
                                Buy Now
                            </button>
                        </div>

                        <p id="cartMessage"></p>
                    </form>

                    <form
                        action="{{ route('checkout.buyNow', $product->id) }}"
                        method="POST"
                        id="buyNowForm">
                        @csrf
                        <input
                            type="hidden"
                            name="quantity"
                            id="buyNowQuantity"
                            value="1"
                        >
                    </form>

                @else
                    <a href="{{ route('login') }}" class="add-cart-btn">
                        Login To Add Cart
                    </a>
                @endauth

            @endif


        

            <div class="description">
                <h3>Product Description</h3>

                <p>{{ $product->description }}</p>

            </div>



            <!-- Add to Cart will be implemented next -->

        </div>

    </div>

</div>

@endsection


@push('styles')

<style>
    .product-container {
        width: 90%;
        max-width: 1100px;
        margin: 40px auto;
    }

    .back-link {
        display: inline-block;
        margin-bottom: 25px;
        color: #2563eb;
        text-decoration: none;
    }

    /* Product Layout */
    .product-layout {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 40px;
        background: white;
        padding: 30px;
        border-radius: 12px;
    }

    .main-image {
        width: 100%;
        height: 400px;
        object-fit: contain;
        background: #f3f4f6;
        border-radius: 8px;
    }

    .no-image {
        height: 300px;
        display: grid;
        place-items: center;
        background: #f3f4f6;
        color: #64748b;
    }

    /* Product Thumbnails */
    .thumbnail-list {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 10px;
    }

    .thumbnail {
        width: 75px;
        height: 75px;
        object-fit: cover;
        border: 1px solid #ddd;
        border-radius: 5px;
        cursor: pointer;
        transition: 0.3s;
    }

    .thumbnail:hover,
    .thumbnail.active {
        border: 2px solid #2563eb;
    }

    /* Product Information */
    .product-info h1 {
        margin-top: 0;
    }

    .price {
        font-size: 28px;
        font-weight: bold;
        color: #2563eb;
    }

    .category {
        color: #666;
    }

    /* Stock Status */
    .available {
        color: #15803d;
    }

    .low-stock {
        color: #d97706;
    }

    .stock {
        color: #15803d;
        font-weight: bold;
    }

    .out-stock {
        color: #dc2626;
        font-weight: bold;
    }

    /* Quantity Controls */
    .quantity-box {
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: flex-start;
        gap: 10px;
        margin: 20px 0;
    }

    .quantity-box label {
        white-space: nowrap;
    }

    .quantity-box button {
        width: 38px;
        height: 38px;
        padding: 0;
        flex-shrink: 0;
        border: 1px solid #ddd;
        border-radius: 5px;
        background: #f3f4f6;
        cursor: pointer;
        font-size: 18px;
    }

    .quantity-box input {
        width: 60px;
        height: 38px;
        box-sizing: border-box;
        padding: 5px;
        text-align: center;
        border: 1px solid #ddd;
        border-radius: 5px;
    }

    /* Purchase Buttons */
    .purchase-buttons {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-top: 15px;
        flex-wrap: wrap;
    }

    .add-cart-btn,
    .buy-now-btn {
        display: inline-block;
        width: auto;
        min-width: 120px;
        padding: 12px 20px;
        border: none;
        border-radius: 6px;
        color: white;
        font-size: 15px;
        font-weight: bold;
        cursor: pointer;
        text-decoration: none;
        text-align: center;
    }

    .add-cart-btn {
        background: #2874f0;
    }

    .add-cart-btn:hover {
        background: #1557c0;
    }

    .buy-now-btn {
        background: #15803d;
    }

    .buy-now-btn:hover {
        background: #166534;
    }

    /* Description */
    .description {
        line-height: 1.7;
        margin: 25px 0;
    }

    /* Tablet */
    @media (max-width: 900px) {
        .product-layout {
            grid-template-columns: 1fr;
            gap: 20px;
            padding: 20px;
        }

        .main-image {
            height: 320px;
        }
    }

    /* Mobile */
    @media (max-width: 600px) {
        .product-container {
            width: 95%;
            margin: 20px auto;
        }

        .product-layout {
            grid-template-columns: 1fr;
            padding: 15px;
        }

        .main-image {
            height: 260px;
        }

        .product-info h1 {
            font-size: 22px;
        }

        .price {
            font-size: 24px;
        }

        .purchase-buttons {
            gap: 8px;
        }

        .add-cart-btn,
        .buy-now-btn {
            min-width: 110px;
            padding: 11px 15px;
            font-size: 14px;
        }
    }
</style>

@endpush

@push('scripts')
<script>
            (function(){

        const quantityInput = document.getElementById('quantity');
        const increaseBtn = document.getElementById('increase');
        const decreaseBtn = document.getElementById('decrease');
        const buyNowQuantity = document.getElementById('buyNowQuantity');

        if (!quantityInput || !increaseBtn || !decreaseBtn) return;

        const maxStock = Number(quantityInput.max);

        function updateQuantity(){
            let quantity = Number(quantityInput.value);

            if(!Number.isInteger(quantity) || quantity < 1){
                quantity = 1;
            }

            if(quantity > maxStock){
                quantity = maxStock;
            }

            quantityInput.value = quantity;

            if(buyNowQuantity){
                buyNowQuantity.value = quantity;
            }
        }

        increaseBtn.addEventListener('click', function(){
            quantityInput.value = Number(quantityInput.value) + 1;
            updateQuantity();
        });

        decreaseBtn.addEventListener('click', function(){
            quantityInput.value = Number(quantityInput.value) - 1;
            updateQuantity();
        });

        quantityInput.addEventListener('input', updateQuantity);
        quantityInput.addEventListener('change', updateQuantity);

    })();
        (function(){

            const mainImage = document.getElementById('main-product-image');
            const thumbnails = document.querySelectorAll('.thumbnail');

            if (!mainImage) return;

            thumbnails.forEach(function(thumbnail){
                thumbnail.addEventListener('click', function(){
                    mainImage.src = this.src;

                    thumbnails.forEach(function(image){
                        image.classList.remove('active');
                    });

                    this.classList.add('active');
                });
            });

        })();

        $(document)
            .off('submit.productCart', '#cartForm')
            .on('submit.productCart', '#cartForm', function(event){

                event.preventDefault();

                $.ajax({
                    url: $(this).attr("action"),
                    type: "POST",
                    data: $(this).serialize(),
                    dataType: "json",
                    headers: {
                        "Accept": "application/json"
                    },
                    success: function(response){
                        console.log("Success", response);
                        $("#cartMessage").text(response.message).css("color", "green");
                    },
                    error: function(xhr){
                        console.log("Error", xhr.responseJSON);

                        let message = xhr.responseJSON?.message || 'Something went wrong';

                        $("#cartMessage").text(message).css("color", "red");
                    }
                });

            });



       
</script>
@endpush