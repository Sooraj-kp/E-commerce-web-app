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
            max-width: 1100px;
            margin: 55px auto;
        }

        .content-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 8px 30px rgba(0,0,0,0.08);
        }

        .card-header {
            background: linear-gradient(120deg, #172554, #2563eb);
            color: white;
            padding: 25px 30px;
        }

        .card-body {
            padding: 30px;
        }

        .table thead th {
            background: #f8fafc;
            color: #334155;
        }
    </style>
</head>

<body>

<div class="container main-container">

    <div class="content-card">

        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold mb-1">Category Management</h2>
                <p class="mb-0">View and manage product categories.</p>
            </div>

            <a href="/admin/categories/create" class="btn btn-light">
                + Add Category
            </a>
        </div>

        <div class="card-body">

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Category Name</th>
                            <th>Description</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($categories as $category)
                            <tr>
                                <td>{{ $category->id }}</td>
                                <td>{{ $category->name }}</td>
                                <td>{{ $category->description ?? 'No description' }}</td>
                                <td>{{ $category->created_at?->format('d-m-Y') }}</td>
                                <td>
    <a href="/admin/categories/{{ $category->id }}/edit" class="btn btn-warning btn-sm">
        Edit
    </a>

    <form
        action="/admin/categories/{{ $category->id }}"
        method="POST"
        style="display: inline;"
        onsubmit="return confirm('Are you sure you want to delete this category?');"
    >
        @csrf
        @method('DELETE')

        <button type="submit" class="btn btn-danger btn-sm">
            Delete
        </button>
    </form>
</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">
                                    No categories found.
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
