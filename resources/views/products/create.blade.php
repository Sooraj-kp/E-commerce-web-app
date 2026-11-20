@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('page-heading', 'Dashboard')

@section('content')

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

        <style>
            body {
                background: #f1f4f9;
                font-family: Arial, sans-serif;
            }

            .main-container {
                max-width: 950px;
                margin: 55px auto;
            }

            .form-card {
                background: white;
                border-radius: 16px;
                overflow: hidden;
                box-shadow: 0 8px 30px rgba(0,0,0,0.08);
            }

            .form-header {
                background: linear-gradient(120deg, #172554, #2563eb);
                color: white;
                padding: 30px 35px;
            }

            .form-body {
                padding: 35px;
            }

            .form-label {
                font-weight: 600;
                color: #334155;
                margin-bottom: 8px;
            }

            .form-control {
                padding: 12px 14px;
                border: 1px solid #dbe2ea;
                border-radius: 8px;
            }

            .form-control:focus {
                border-color: #2563eb;
                box-shadow: 0 0 0 3px rgba(37,99,235,0.12);
            }

            textarea {
                resize: none;
            }

            .btn-add {
                background: #2563eb;
                color: white;
                padding: 11px 25px;
                border-radius: 8px;
                border: none;
            }

            .btn-add:hover {
                background: #1d4ed8;
                color: white;
            }

            .btn-cancel {
                padding: 11px 25px;
                border-radius: 8px;
            }

            .section-title {
                color: #172554;
                font-weight: 700;
                margin-bottom: 25px;
            }
        </style>
    </head>

    <body>

    <div class="container main-container">

        <div class="form-card">

            <!-- Header -->
            <div class="form-header">
                <h2 class="fw-bold mb-2">Add New Product</h2>
                <p class="mb-0">Enter the details below to add a product to your store.</p>
            </div>

            <!-- Form Body -->
            <div class="form-body">

                <h5 class="section-title">Product Information</h5>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Please correct the following errors:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="/products" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-4">
                        <label class="form-label">Product Name</label>
                        <input
                            type="text"
                            name="name"
                            class="form-control"
                            placeholder="e.g. Wireless Mouse"
                            value="{{ old('name') }}"
                            required
                        >
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Product Description</label>
                        <textarea
                            name="description"
                            class="form-control"
                            rows="4"
                            placeholder="Enter product description"
                            required>{{ old('description') }}</textarea>
                    </div>
                    <div class="mb-4">
        <label class="form-label">Product Category</label>

        <select name="category_id" class="form-control" required>
            <option value="">Select a category</option>

            @foreach ($categories as $category)
                <option
                    value="{{ $category->id }}"
                    {{ old('category_id') == $category->id ? 'selected' : '' }}
                >
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="mb-4">
        <label class="form-label">Main Product Image</label>
        <input type="file" name="main_image" class="form-control" accept="image/* required">

    </div>
    <div class="mb-4">
        <label class="form-label">Alternate Images</label>
        <input type="file" name="alternate_images[]" class="form-control" accept="image/*" multiple>
        <small class="text-secondary">Select multiple images , such as front View,back view of the  Product</small>
    </div>

                    <div class="row">

                        <div class="col-md-6 mb-4">
                            <label class="form-label">Price (₹)</label>
                            <input
                                type="number"
                                name="price"
                                class="form-control"
                                placeholder="Enter product price"
                                value="{{ old('price') }}"
                                min="0"
                                step="0.01"
                                required
                            >
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="form-label">Stock Quantity</label>
                            <input
                                type="number"
                                name="stock"
                                class="form-control"
                                placeholder="Enter available quantity"
                                value="{{ old('stock') }}"
                                min="0"
                                required
                            >
                        </div>

                    </div>

                    <hr class="my-3">

                    <div class="d-flex justify-content-end gap-2 mt-4">

                        <a href="/products" class="btn btn-outline-secondary btn-cancel">
                            Cancel
                        </a>

                        <button type="submit" class="btn btn-add">
                            + Add Product
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