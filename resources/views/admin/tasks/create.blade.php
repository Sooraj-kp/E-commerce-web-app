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
            max-width: 900px;
            margin: 55px auto;
        }

        .task-card {
            border: none;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 8px 30px rgba(0,0,0,0.08);
        }

        .card-header {
            background: linear-gradient(120deg, #172554, #2563eb);
            color: white;
            padding: 22px;
            font-size: 20px;
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

        .btn-create {
            background: #2563eb;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            border: none;
        }

        .btn-create:hover {
            background: #1d4ed8;
            color: white;
        }

        .btn-cancel {
            padding: 10px 20px;
            border-radius: 8px;
        }
    </style>
</head>

<body>

<div class="container main-container">

    <h1 class="page-title text-center mb-4">
        TASK MANAGEMENT
    </h1>

    <div class="card task-card">

        <div class="card-header text-center fw-bold">
            Create New Task
        </div>

        <div class="card-body">

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

            <form action="/admin/tasks" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="title" class="form-label">Task Title</label>
                    <input
                        type="text"
                        name="title"
                        id="title"
                        class="form-control"
                        value="{{ old('title') }}"
                        required
                    >
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea
                        name="description"
                        id="description"
                        class="form-control"
                        rows="4"
                    >{{ old('description') }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="assigned_to" class="form-label">Assign Staff</label>
                    <select name="assigned_to" id="assigned_to" class="form-select" required>
                        <option value="">Select Staff</option>

                        @foreach ($staff as $member)
                            <option
                                value="{{ $member->id }}"
                                {{ old('assigned_to') == $member->id ? 'selected' : '' }}
                            >
                                {{ $member->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="due_at" class="form-label">Due Date and Time</label>
                    <input
                        type="datetime-local"
                        name="due_at"
                        id="due_at"
                        class="form-control"
                        value="{{ old('due_at') }}"
                    >
                </div>

                <div class="mb-4">
                    <label for="estimated_minutes" class="form-label">
                        Estimated Duration (minutes)
                    </label>
                    <input
                        type="number"
                        name="estimated_minutes"
                        id="estimated_minutes"
                        class="form-control"
                        min="1"
                        value="{{ old('estimated_minutes') }}"
                    >
                </div>

                <button type="submit" class="btn btn-create">
                    Create Task
                </button>

                <a href="/admin/tasks" class="btn btn-secondary btn-cancel">
                    Cancel
                </a>
            </form>

        </div>
    </div>

    <p class="text-center text-secondary mt-3 small">
        E-Commerce Management System | Admin Panel
    </p>

</div>

@endsection