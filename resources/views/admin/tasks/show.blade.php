@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('page-heading', 'Dashboard')

@section('content')

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Task Details</h2>

    <div>
        <a href="{{ url('/admin/tasks/' . $task->id . '/edit') }}" class="btn btn-warning">
            Edit Task
        </a>

        <a href="{{ url('/admin/tasks') }}" class="btn btn-secondary">
            Back to Tasks
        </a>
    </div>
</div>

    <div class="card shadow-sm">
        <div class="card-body">
            @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

            <table class="table table-bordered">
                <tr>
                    <th style="width: 30%;">Task ID</th>
                    <td>{{ $task->id }}</td>
                </tr>

                <tr>
                    <th>Title</th>
                    <td>{{ $task->title }}</td>
                </tr>

                <tr>
                    <th>Description</th>
                    <td>{{ $task->description ?? 'No description provided' }}</td>
                </tr>

                <tr>
                    <th>Assigned Staff</th>
                    <td>{{ $task->assignedStaff->name ?? 'Not assigned' }}</td>
                </tr>

                <tr>
                    <th>Due Date</th>
                    <td>{{ $task->due_at ?? 'No deadline' }}</td>
                </tr>

                <tr>
                    <th>Estimated Duration</th>
                    <td>
                        {{ $task->estimated_minutes
                            ? $task->estimated_minutes . ' minutes'
                            : 'Not specified' }}
                    </td>
                </tr>

                <tr>
                    <th>Status</th>
                    <td>{{ ucfirst(str_replace('_', ' ', $task->status)) }}</td>
                </tr>

                <tr>
                    <th>Started At</th>
                    <td>{{ $task->started_at ?? 'Not started yet' }}</td>
                </tr>

                <tr>
                    <th>Submitted At</th>
                    <td>{{ $task->submitted_at ?? 'Not submitted yet' }}</td>
                </tr>

                <tr>
                    <th>Completed At</th>
                    <td>{{ $task->completed_at ?? 'Not completed yet' }}</td>
                </tr>

                <tr>
                    <th>Admin Feedback</th>
                    <td>{{ $task->admin_feedback ?? 'No feedback yet' }}</td>
                </tr>
            </table>




            {{-- Staff Submission Details --}}
<hr>

<div class="mt-4">

    <h4 class="mb-3">Staff Submission</h4>

    <div class="mb-4">
        <h6>Completion Description</h6>

        <p>
            {{ $task->submission_note ?? 'No submission description provided.' }}
        </p>
    </div>

    <h6 class="mb-3">Proof Images</h6>

    @if($task->attachments->count() > 0)

        <div class="row g-3">

            @foreach($task->attachments as $attachment)

                <div class="col-md-4 col-sm-6">

                    <div class="card shadow-sm h-100">

                        <img
                            src="{{ asset('image_uploads/' . $attachment->file_path) }}"
                            class="card-img-top"
                            alt="Task proof image"
                            style="height: 220px; object-fit: cover;">

                        <div class="card-body">
                            <p class="small mb-0">
                                {{ $attachment->file_name }}
                            </p>
                        </div>

                    </div>

                </div>

            @endforeach

        </div>

    @else

        <p class="text-muted">
            No proof images uploaded.
        </p>

    @endif

</div>

@if ($task->status === 'submitted')
    <hr>

    <div class="d-flex gap-3 align-items-start">

        {{-- Approve Task --}}
        <form action="{{ url('/admin/tasks/' . $task->id . '/approve') }}" method="POST">
            @csrf

            <button type="submit" class="btn btn-success">
                Approve Task
            </button>
        </form>

        {{-- Request Revision --}}
       <form action="{{ route('admin.tasks.requestRevision', $task->id) }}"
      method="POST"
      class="flex-grow-1">

    @csrf

    <label for="admin_feedback" class="form-label">
        Revision Feedback
    </label>

    <textarea
        name="admin_feedback"
        id="admin_feedback"
        class="form-control mb-2"
        rows="3"
        placeholder="Explain what the staff member needs to correct..."
        required>{{ old('admin_feedback') }}</textarea>

    <button type="submit" class="btn btn-warning">
        Request Revision
    </button>   

</form>
    </div>
@endif

        </div>
    </div>

</div>

@endsection