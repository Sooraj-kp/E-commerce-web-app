@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('page-heading', 'Dashboard')

@section('content')

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Task Management</h2>

        <a href="{{ url('/admin/tasks/create') }}" class="btn btn-primary">
            + Create Task
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">

            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Task ID</th>
                        <th>Title</th>
                        <th>Assigned Staff</th>
                        <th>Due Date</th>
                        <th>Estimated Time</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($tasks as $task)
                        <tr>
                            <td>{{ $task->id }}</td>
                            <td>{{ $task->title }}</td>
                            <td>{{ $task->assignedStaff->name ?? 'Not assigned' }}</td>
                            <td>{{ $task->due_at ?? 'No deadline' }}</td>
                            <td>
                                {{ $task->estimated_minutes
                                    ? $task->estimated_minutes . ' minutes'
                                    : 'Not specified' }}
                            </td>
                            <td>{{ ucfirst(str_replace('_', ' ', $task->status)) }}</td>
                            <td>
                                <div class="d-flex  gap-2">
                                <a href="{{ url('/admin/tasks/' . $task->id) }}" class="btn btn-sm btn-info">
                                View Details
                                </a>

                                <a href="{{ url('/admin/tasks/' . $task->id . '/edit') }}" class="btn btn-sm btn-warning">
                                Edit
                                </a>

                                 <form action="{{route('admin.tasks.destroy',$task->id)}}" method="POST" onsubmit="return confirm('Are you  sure you wnat delete the task ,This action cannot be undone');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete</button>


                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">
                                No tasks have been created yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>

</div>

@endsection