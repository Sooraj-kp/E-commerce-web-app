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
            max-width: 850px;
            margin: 55px auto;
        }

        .category-card {
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
    </style>
</head>

<body>

<div class="container main-container">

    <h1 class="page-title text-center mb-4">CATEGORY MANAGEMENT</h1>

    <div class="card category-card">

        <div class="card-header text-center">
            <h3 class="fw-bold mb-1">Edit Category</h3>
            <small>Update the category details</small>
        </div>

        <div class="card-body">

            <form action="/admin/categories/{{ $category->id }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Category Name</label>
                    <input
                        type="text"
                        name="name"
                        class="form-control"
                        value="{{ old('name', $category->name) }}"
                        required
                    >
                </div>

                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea
                        name="description"
                        class="form-control"
                        rows="4"
                    >{{ old('description', $category->description) }}</textarea>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="/admin/categories" class="btn btn-secondary btn-cancel">
                        Cancel
                    </a>

                    <button type="submit" class="btn btn-update">
                        Update Category
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