@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('page-heading', 'Dashboard')

@section('content')

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>
        body {
            background: #f1f4f9;
            font-family: Arial, sans-serif;
        }

        .main-container {
            max-width: 850px;
            margin: 55px auto;
        }

        .product-card {
            border: none;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
        }

        .card-header {
            background: linear-gradient(120deg, #172554, #2563eb);
            color: white;
            padding: 22px;
        }

        .card-body {
            padding: 30px;
        }

        .page-title {
            color: #172554;
            font-weight: 700;
        }

        .form-label {
            color: #172554;
            font-weight: 600;
        }

        .form-control {
            padding: 11px;
            border-radius: 8px;
            border: 1px solid #d1d9e6;
        }

        .form-control:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.15);
        }

        .btn-update {
            background: #2563eb;
            color: white;
            padding: 10px 22px;
            border-radius: 8px;
        }

        .btn-update:hover {
            background: #1d4ed8;
            color: white;
        }

        .btn-cancel {
            padding: 10px 22px;
            border-radius: 8px;
        }

        .current-image {
            width: 120px;
            height: 120px;
            object-fit: cover;
        }
    </style>
</head>

<body>

    <div class="container main-container">

        <h1 class="page-title text-center mb-4">
            PRODUCT MANAGEMENT
        </h1>

        <div class="card product-card">

            <div class="card-header text-center">
                <h3 class="fw-bold mb-1">Edit Product</h3>
                <small>Update the product details</small>
            </div>

            <div class="card-body">

                <form
                    action="/products/{{ $product->id }}"
                    method="POST"
                    enctype="multipart/form-data"
                >
                    @csrf
                    @method('PUT')

                    {{-- Current Product Images --}}
                    <div class="mb-4">
                        <label class="form-label">Current Product Images</label>

                        <div class="d-flex flex-wrap gap-3">

                            @forelse ($product->images->sortByDesc('is_primary') as $image)

                                <div class="text-center">

                                    <img
                                        src="{{ asset('storage/' . $image->image_path) }}"
                                        alt="{{ $product->name }}"
                                        class="img-thumbnail current-image"
                                    >

                                    <div class="small mt-1">
                                        {{ $image->is_primary ? 'Main Image' : 'Alternate Image' }}
                                    </div>

                                </div>

                            @empty

                                <p class="text-muted">
                                    No images uploaded for this product.
                                </p>

                            @endforelse

                        </div>
                    </div>

                    {{-- Product Name --}}
                    <div class="mb-3">
                        <label class="form-label">Product Name</label>

                        <input
                            type="text"
                            name="name"
                            class="form-control"
                            value="{{ old('name', $product->name) }}"
                        >
                    </div>

                    {{-- Description --}}
                    <div class="mb-3">
                        <label class="form-label">Description</label>

                        <textarea
                            name="description"
                            class="form-control"
                            rows="4"
                        >{{ old('description', $product->description) }}</textarea>
                    </div>

                    {{-- Replace Main Image --}}
                    <div class="mb-4">
                        <label class="form-label">
                            Replace Main Image (optional)
                        </label>

                        <input
                            type="file"
                            name="main_image"
                            class="form-control"
                            accept="image/jpeg,image/png,image/webp"
                        >

                        <small class="text-secondary">
                            Leave empty to keep the current main image.
                        </small>
                    </div>

                    {{-- Add Alternate Images --}}
                    <div class="mb-4">
                        <label class="form-label">
                            Add Alternate Images (optional)
                        </label>

                        <input
                            type="file"
                            name="alternate_images[]"
                            class="form-control"
                            accept="image/jpeg,image/png,image/webp"
                            multiple
                        >

                        <small class="text-secondary">
                            You can select multiple images, such as front view,
                            back view, and side view.
                        </small>
                    </div>

                    {{-- Product Category --}}
                    <div class="mb-3">
                        <label class="form-label">Product Category</label>

                        <select
                            name="category_id"
                            class="form-control"
                            required
                        >
                            <option value="">Select a category</option>

                            @foreach ($categories as $category)

                                <option
                                    value="{{ $category->id }}"
                                    {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}
                                >
                                    {{ $category->name }}
                                </option>

                            @endforeach

                        </select>
                    </div>

                    {{-- Price and Stock --}}
                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Price (₹)</label>

                            <input
                                type="number"
                                name="price"
                                step="0.01"
                                class="form-control"
                                value="{{ old('price', $product->price) }}"
                            >
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Stock</label>

                            <input
                                type="number"
                                name="stock"
                                class="form-control"
                                value="{{ old('stock', $product->stock) }}"
                            >
                        </div>

                    </div>

                    {{-- Buttons --}}
                    <div class="d-flex justify-content-between mt-4">

                        <a
                            href="/products"
                            class="btn btn-secondary btn-cancel"
                        >
                            Cancel
                        </a>

                        <button
                            type="submit"
                            class="btn btn-update"
                        >
                            Update Product
                        </button>

                    </div>

                </form>

            </div>
        </div>

        <p class="text-center text-secondary mt-3 small">
            E-Commerce Management System | Admin Panel
        </p>

    </div>

@endsection