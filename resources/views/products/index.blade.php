@extends('layouts.admin')

@section('title', 'Product Management')
@section('page-heading', 'Product Management')

@section('content')

<style>
    .main-container {
        max-width: 1100px;
        margin: 20px auto;
    }

    .product-card {
        border: none;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
    }

    .product-card .card-header {
        background: linear-gradient(120deg, #172554, #2563eb);
        color: white;
        padding: 22px;
        font-size: 20px;
    }

    .product-card .card-body {
        padding: 30px;
    }

    .table thead th {
        background: #eaf0ff;
        color: #172554;
        padding: 15px;
        white-space: nowrap;
    }

    .table tbody td {
        padding: 14px;
        vertical-align: middle;
    }

    .table tbody tr:hover {
        background: #f1f5ff;
    }

    .btn-add {
        background: #2563eb;
        color: white;
        padding: 10px 20px;
        border-radius: 8px;
        text-decoration: none;
    }

    .btn-add:hover {
        background: #1d4ed8;
        color: white;
    }

    .page-title {
        color: #172554;
        font-weight: 700;
    }

    .product-image {
        width: 100%;
        height: 180px;
        object-fit: contain;
        background: #fff;
    }

    @media (max-width: 768px) {
        .product-card .card-body {
            padding: 15px;
        }
    }
</style>

<div class="container main-container">

    <h1 class="page-title text-center mb-4">
        PRODUCT MANAGEMENT
    </h1>

    <div class="card product-card">

        <div class="card-header text-center fw-bold">
            All Products
        </div>

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-4">

                <h5 class="fw-bold mb-0">Product List</h5>

                <a href="{{ url('/products/create') }}" class="btn btn-add">
                    + Add Product
                </a>

            </div>

            <div class="table-responsive">

                <table class="table table-bordered table-striped table-hover text-center">

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Images</th>
                            <th>Price</th>
                            <th>Category</th>
                            <th>Stock</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse ($products as $product)

                            <tr>
                                <td>{{ $product->id }}</td>

                                <td class="fw-semibold">
                                    {{ $product->name }}
                                </td>

                                <td>
                                    {{ $product->description }}
                                </td>

                                {{-- View Product Images --}}
                                <td>
                                    @if ($product->images->count() > 0)

                                        <button
                                            type="button"
                                            class="btn btn-primary btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#imagesModal{{ $product->id }}"
                                        >
                                            View Images ({{ $product->images->count() }})
                                        </button>

                                    @else

                                        <span class="text-muted">No image</span>

                                    @endif
                                </td>

                                <td>
                                    ₹{{ number_format($product->price, 2) }}
                                </td>

                                <td>
                                    {{ $product->category->name ?? 'Uncategorized' }}
                                </td>

                                <td>
                                    {{ $product->stock }}
                                </td>

                                <td>
                                    <a
                                        href="{{ url('/products/' . $product->id . '/edit') }}"
                                        class="btn btn-warning btn-sm"
                                    >
                                        Edit
                                    </a>

                                    <form
                                        action="{{ url('/products/' . $product->id) }}"
                                        method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Are you sure you want to delete this product?');"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn btn-danger btn-sm">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            {{-- Product Images Modal --}}
                            @if ($product->images->count() > 0)

                                <div
                                    class="modal fade"
                                    id="imagesModal{{ $product->id }}"
                                    tabindex="-1"
                                    aria-labelledby="imagesModalLabel{{ $product->id }}"
                                    aria-hidden="true"
                                >
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content">

                                            <div class="modal-header">
                                                <h5
                                                    class="modal-title"
                                                    id="imagesModalLabel{{ $product->id }}"
                                                >
                                                    {{ $product->name }} — Product Images
                                                </h5>

                                                <button
                                                    type="button"
                                                    class="btn-close"
                                                    data-bs-dismiss="modal"
                                                    aria-label="Close"
                                                ></button>
                                            </div>

                                            <div class="modal-body">

                                                <div class="row g-3">

                                                    @foreach ($product->images->sortByDesc('is_primary') as $image)

                                                        <div class="col-md-4 col-6 text-center">

                                                            <img
                                                                src="{{ asset('image_uploads/' . $image->image_path) }}"
                                                                alt="{{ $product->name }}"
                                                                class="img-thumbnail"
                                                                style="width: 100px; height: 100px; object-fit: cover;"
                                                                onerror="this.onerror=null; this.src=''; this.alt='Image failed to load';"
                                                            >

                                                            <small class="d-block mt-2">
                                                                {{ $image->is_primary ? 'Main Image' : 'Alternate Image' }}
                                                            </small>

                                                        </div>

                                                    @endforeach

                                                </div>

                                            </div>

                                        </div>
                                    </div>
                                </div>

                            @endif

                        @empty

                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    No products available.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>
    </div>

    <p class="text-center text-secondary mt-3 small">
        E-Commerce Management System | Admin Panel
    </p>

</div>

@endsection