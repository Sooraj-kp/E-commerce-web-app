
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }} | My Store</title>

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

        header {
            background: #1f2937;
            color: white;
            padding: 18px 6%;
        }

        header a {
            color: white;
            text-decoration: none;
        }

        .container {
            max-width: 1100px;
            width: 90%;
            margin: 40px auto;
        }

        .back-link {
            display: inline-block;
            margin-bottom: 25px;
            color: #2563eb;
            text-decoration: none;
        }

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

        .stock {
            color: #15803d;
            font-weight: bold;
        }

        .description {
            line-height: 1.7;
            margin: 25px 0;
        }

        .thumbnail {
            width: 75px;
            height: 75px;
            object-fit: cover;
            margin: 10px 8px 0 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        @media(max-width: 700px) {
            .product-layout {
                grid-template-columns: 1fr;
            }

            .main-image {
                height: 280px;
            }
        }
    </style>
</head>

<body>

<header>
    <h2><a href="{{ url('/shop') }}">My Store</a></h2>
</header>

<div class="container">

    <a href="{{ url('/shop') }}" class="back-link">
        ← Back to Shop
    </a>

    <div class="product-layout">

        <div>
            @php
                $mainImage = $product->images->firstWhere('is_primary', true);
            @endphp

            @if($mainImage)
                <img
                    src="{{ asset('image_uploads/' . $mainImage->image_path) }}"
                    class="main-image"
                    alt="{{ $product->name }}"
                >
            @else
                <div class="main-image"></div>
            @endif

            <div>
                @foreach($product->images as $image)
                    <img
                        src="{{ asset('image_uploads/' . $image->image_path) }}"
                        class="thumbnail"
                        alt="{{ $product->name }}"
                    >
                @endforeach
            </div>
        </div>

        <div class="product-info">

            <h1>{{ $product->name }}</h1>

            <p class="category">
                Category: {{ $product->category->name ?? 'Uncategorized' }}
            </p>

            <p class="price">
                ₹{{ number_format($product->price, 2) }}
            </p>

            <p class="stock">
                Available stock: {{ $product->stock }}
            </p>

            <div class="description">
                <h3>Product Description</h3>
                {{ $product->description }}
            </div>

        </div>

    </div>

</div>

</body>
</html>
