@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('page-heading', 'Dashboard')

@section('content')


    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f6fa;
            color: #222;
        }

        .navbar {
            background: #1f2937;
            color: white;
            padding: 18px 6%;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar h2 {
            margin: 0;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
        }

        .container {
            width: 88%;
            max-width: 1200px;
            margin: 35px auto;
        }

        .page-heading {
            margin-bottom: 25px;
        }

        .page-heading h1 {
            margin-bottom: 8px;
        }

    .product-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 25px;
    }

        .product-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.08);
        }

        .product-image {
            width: 100%;
            height: 220px;
            object-fit: cover;
            background: #e5e7eb;
        }

        .product-details {
            padding: 18px;
        }

        .product-details h3 {
            margin: 0 0 8px;
        }

        .category {
            color: #6b7280;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .price {
            font-size: 20px;
            font-weight: bold;
            color: #2563eb;
            margin: 12px 0;
        }

        .stock {
            font-size: 13px;
            color: #15803d;
        }

        .empty-message {
            background: white;
            padding: 25px;
            border-radius: 8px;
            text-align: center;
        }

        @media (max-width: 900px) {
    .product-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 550px) {
    .product-grid {
        grid-template-columns: 1fr;
    }
}
    </style>
</head>

<body>

    <header class="navbar">
        <h2>My Store</h2>

        <nav>
            <a href="{{ url('/shop') }}">Shop</a>
            <a href="{{ url('/login') }}">Login</a>
        </nav>
    </header>

    <main class="container">

        <div class="page-heading">
            <h1>Our Products</h1>
            <p>Browse our available products.</p>
        </div>

        @if($products->count() > 0)

            <div class="product-grid">

                @foreach($products as $product)

                    <a href="{{ url('/shop/' . $product->id) }}"
                        class="product-card"
                        style="text-decoration:none; color:inherit; display:block;">

                        @php
                            $mainImage = $product->images->firstWhere('is_primary', true);
                        @endphp

                        @if($mainImage)
                            <img
                                src="{{ asset('image_uploads/' . $mainImage->image_path) }}"
                                alt="{{ $product->name }}"
                                class="product-image"
                            >
                        @else
                            <div class="product-image"></div>
                        @endif

                        <div class="product-details">

                            <h3>{{ $product->name }}</h3>

                            <div class="category">
                                {{ $product->category->name ?? 'Uncategorized' }}
                            </div>

                            <p>{{ Str::limit($product->description, 90) }}</p>

                            <div class="price">
                                ₹{{ number_format($product->price, 2) }}
                            </div>

                            <div class="stock">
                                In stock: {{ $product->stock }}
                            </div>

                        </div>
                    </a>

                @endforeach

            </div>

        @else

            <div class="empty-message">
                <h3>No products available</h3>
                <p>Please check back later.</p>
            </div>

        @endif

    </main>

@endsection
