@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('page-heading', 'Dashboard')

@section('content')

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Edit Task</h2>

        <a href="{{ url('/admin/tasks') }}" class="btn btn-secondary">
            Back to Tasks
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">

            <form action="{{ url('/admin/tasks/' . $task->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="title" class="form-label">Task Title</label>
                    <input
                        type="text"
                        name="title"
                        id="title"
                        class="form-control"
                        value="{{ old('title', $task->title) }}"
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
                    >{{ old('description', $task->description) }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="assigned_to" class="form-label">Assign Staff</label>
                    <select name="assigned_to" id="assigned_to" class="form-control" required>
                        <option value="">Select Staff</option>

                        @foreach($staff as $member)
                            <option
                                value="{{ $member->id }}"
                                {{ old('assigned_to', $task->assigned_to) == $member->id ? 'selected' : '' }}
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
                        value="{{ old('due_at', $task->due_at ? date('Y-m-d\TH:i', strtotime($task->due_at)) : '') }}"
                    >
                </div>

                <div class="mb-3">
                    <label for="estimated_minutes" class="form-label">
                        Estimated Duration (minutes)
                    </label>
                    <input
                        type="number"
                        name="estimated_minutes"
                        id="estimated_minutes"
                        class="form-control"
                        min="1"
                        value="{{ old('estimated_minutes', $task->estimated_minutes) }}"
                    >
                </div>

                <button type="submit" class="btn btn-primary">
                    Update Task
                </button>

                <a href="{{ url('/admin/tasks') }}" class="btn btn-secondary">
                    Cancel
                </a>
            </form>

        </div>
    </div>

</div>

@endsection