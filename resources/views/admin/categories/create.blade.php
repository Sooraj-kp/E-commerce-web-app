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
    </style>
</head>

<body>

<div class="container main-container">

    <div class="form-card">

        <div class="form-header">
            <h2 class="fw-bold mb-2">Add New Category</h2>
            <p class="mb-0">Enter the details to create a product category.</p>
        </div>

        <div class="form-body">

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

            <form action="/admin/categories" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="form-label">Category Name</label>
                    <input
                        type="text"
                        name="name"
                        class="form-control"
                        placeholder="e.g. Electronics"
                        value="{{ old('name') }}"
                        required
                    >
                </div>

                <div class="mb-4">
                    <label class="form-label">Description</label>
                    <textarea
                        name="description"
                        class="form-control"
                        rows="4"
                        placeholder="Enter a short category description"
                    >{{ old('description') }}</textarea>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">

                    <a href="/admin/categories" class="btn btn-outline-secondary btn-cancel">
                        Cancel
                    </a>

                    <button type="submit" class="btn btn-add">
                        + Add Category
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
