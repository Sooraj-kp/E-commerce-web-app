
@extends('layouts.staff')

@section('title', 'Task Details')

@section('page-heading', 'Task Details')

@push('styles')
<style>

    
    /* Main page container */
    .staff-task-page {
        max-width: 1100px;
        margin: 0 auto;
        padding: 25px;
    }

    /* Page heading */
    .task-page-header {
        margin-bottom: 25px;
    }

    .page-label {
        color: #64748b;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 1.5px;
    }

    .task-page-header h2 {
        font-size: 26px;
        color: #1e293b;
        margin: 8px 0;
    }

    .task-page-header p {
        color: #64748b;
        font-size: 14px;
        margin: 0;
    }

    /* Task information card */
    .task-info-card {
        background: #ffffff;
        padding: 25px;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        margin-bottom: 25px;
    }

    .task-details h3 {
        color: #64748b;
        font-size: 13px;
        margin-bottom: 8px;
    }

    .task-details h2 {
        color: #1e293b;
        font-size: 22px;
        margin: 0 0 20px;
    }

    .task-description {
        color: #475569;
        font-size: 14px;
        line-height: 1.7;
        margin-bottom: 20px;
    }

    .task-deadline,
    .estimated-time {
        color: #475569;
        font-size: 14px;
        margin-bottom: 12px;
    }

    .task-status h3 {
        color: #64748b;
        font-size: 13px;
        margin-bottom: 8px;
    }

    /* Status badges */
    .status-badge {
        display: inline-block;
        padding: 8px 15px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
    }

    .status-pending {
        background: #fef3c7;
        color: #92400e;
    }

    .status-in_progress {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .status-submitted {
        background: #ede9fe;
        color: #6d28d9;
    }

    .status-needs_revision {
        background: #fee2e2;
        color: #991b1b;
    }

    .status-completed {
        background: #dcfce7;
        color: #166534;
    }

    /* Submission card */
.task-submission-card {
    background: #ffffff;
    padding: 25px;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    margin-top: 25px;
}

.task-submission-card h3 {
    color: #1e293b;
    font-size: 20px;
    margin: 0 0 20px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    color: #334155;
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 8px;
}

.form-control {
    display: block;
    width: 100%;
    padding: 12px 14px;
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    font-size: 14px;
    color: #334155;
    background: #ffffff;
    box-sizing: border-box;
}

.form-control:focus {
    outline: none;
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

textarea.form-control {
    resize: vertical;
    min-height: 130px;
}

.file-input {
    padding: 12px;
    border: 2px dashed #cbd5e1;
    background: #f8fafc;
    border-radius: 8px;
    cursor: pointer;
}

.form-help {
    display: block;
    color: #64748b;
    font-size: 12px;
    margin-top: 7px;
}

.form-error {
    color: #dc2626;
    font-size: 13px;
    margin-top: 6px;
}

/* Submit button */
.submit-btn {
    background: #2563eb;
    color: #ffffff;
    border: none;
    padding: 12px 24px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.2s ease;
}

.submit-btn:hover {
    background: #1d4ed8;
}
</style>
@endpush

@section('content')

<div class="staff-task-page">

    {{-- Page heading --}}
    <div class="task-page-header">
        <span class="page-label">MY TASKS</span>
        <h2>Task Information</h2>
        <p>Here you can view the details of your assigned task.</p>
    </div>

    {{-- Task information card --}}
    <div class="task-info-card">

        <div class="task-details">
            <h3>Task Title</h3>
            <h2>{{ $tasks->title }}</h2>
        </div>

        <div class="task-description">
            <p>
                {{ $tasks->description ?? 'No description Provided' }}
            </p>
        </div>

        <div class="task-deadline">
            <p>
                @if($tasks->due_at)
                    {{ \Carbon\Carbon::parse($tasks->due_at)->format('d M Y, h:i A') }}
                @else
                    No deadline Specified
                @endif
            </p>
        </div>

        <div class="estimated-time">
            <p>
                @if($tasks->estimated_minutes)
                    {{ $tasks->estimated_minutes }} minutes
                @else
                    Not Specified
                @endif
            </p>
        </div>

        <div class="task-status">
            <h3>Current Status</h3>

            <p>
                <span class="status-badge status-{{ $tasks->status }}">
                    {{ ucfirst(str_replace('_', ' ', $tasks->status)) }}
                </span>
            </p>
        </div>

    </div>

    {{-- Start task section --}}
    @if($tasks->status === 'pending')

        <form action="{{ route('staff.task.start', $tasks->id) }}"
              method="POST">

            @csrf

            <button type="submit">
                Start Task
            </button>

        </form>

    @elseif($tasks->status === 'in_progress')

        <p>You have already started this task.</p>

    @endif

{{-- Task submission section --}}
@if(in_array($tasks->status, ['in_progress', 'needs_revision']))

    <div class="task-submission-card">

        <h3>Submit Task</h3>

        <form action="{{ route('staff.task.submit', $tasks->id) }}"
              method="POST"
              enctype="multipart/form-data">

            @csrf

            <div class="form-group">
                <label for="submission_note">
                    Completion Description
                </label>

                <textarea
                    name="submission_note"
                    id="submission_note"
                    class="form-control"
                    rows="5"
                    placeholder="Describe the work you have completed..."
                    required>{{ old('submission_note') }}</textarea>

                @error('submission_note')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="attachments">
                    Upload Proof Images
                </label>

                <input
                    type="file"
                    name="attachments[]"
                    id="attachments"
                    class="form-control file-input"
                    accept="image/jpeg,image/png,image/webp"
                    multiple
                    required>

                <small class="form-help">
                    Upload one or more JPG, PNG, or WebP images.
                </small>

                @error('attachments')
                    <p class="form-error">{{ $message }}</p>
                @enderror

                @error('attachments.*')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="submit-btn">
                Submit Task
            </button>

        </form>

    </div>

@endif

</div>

@endsection
