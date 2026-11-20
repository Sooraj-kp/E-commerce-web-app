
@extends('layouts.staff')

@section('title', 'My Tasks')

@section('page-heading', 'My Assigned Tasks')

@push('styles')
<style>
    /* Main container */
    .staff-tasks-page {
        max-width: 1200px;
        margin: 0 auto;
        padding: 25px;
    }

    /* Page header */
    .tasks-header {
        margin-bottom: 25px;
    }

    .tasks-header h2 {
        font-size: 26px;
        color: #1e293b;
        margin: 0 0 8px;
    }

    .tasks-header p {
        color: #64748b;
        font-size: 14px;
        margin: 0;
    }

    /* Task table card */
    .tasks-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }

    .tasks-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        margin-bottom: 20px;
    }

    .tasks-card-header h3 {
        color: #1e293b;
        font-size: 18px;
        margin: 0;
    }

    .task-count {
        background: #eff6ff;
        color: #1d4ed8;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    /* Responsive table wrapper */
    .table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .tasks-table {
        width: 100%;
        min-width: 760px;
        border-collapse: collapse;
        text-align: left;
    }

    .tasks-table thead {
        background: #f8fafc;
    }

    .tasks-table th {
        color: #64748b;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 15px 14px;
        border-bottom: 1px solid #e2e8f0;
        white-space: nowrap;
    }

    .tasks-table td {
        padding: 17px 14px;
        color: #475569;
        font-size: 14px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    .tasks-table tbody tr:hover {
        background: #f8fafc;
    }

    .tasks-table tbody tr:last-child td {
        border-bottom: none;
    }

    .task-number {
        color: #94a3b8;
        font-weight: 600;
    }

    .task-name {
        color: #1e293b;
        font-weight: 600;
        min-width: 150px;
    }

    .task-description {
        max-width: 260px;
        line-height: 1.6;
    }

    .task-deadline {
        white-space: nowrap;
    }

    /* Status badges */
    .status-badge {
        display: inline-block;
        padding: 7px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        white-space: nowrap;
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

    /* View details button */
    .view-details-btn {
        display: inline-block;
        background: #2563eb;
        color: #ffffff;
        padding: 9px 15px;
        border-radius: 7px;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        white-space: nowrap;
        transition: background 0.2s ease;
    }

    .view-details-btn:hover {
        background: #1d4ed8;
        color: #ffffff;
    }

    /* Empty state */
    .empty-tasks {
        text-align: center;
        padding: 40px 20px;
        color: #64748b;
    }

    .empty-tasks h3 {
        color: #334155;
        font-size: 17px;
        margin-bottom: 8px;
    }

    .empty-tasks p {
        font-size: 14px;
        margin: 0;
    }
    .btn-delete {
    background: #fee2e2;
    color: #991b1b;
}

.btn-delete:hover {
    background: #fecaca;
    color: #7f1d1d;
}

.action-buttons {
    display: flex;
    align-items: center;
    gap: 8px;
}

    /* Small screen adjustments */
    @media (max-width: 768px) {
        .staff-tasks-page {
            padding: 15px;
        }

        .tasks-card {
            padding: 16px;
        }

        .tasks-header h2 {
            font-size: 22px;
        }
    }
</style>
@endpush

@section('content')

<div class="staff-tasks-page">

    {{-- Page heading --}}
    <div class="tasks-header">
        <h2>My Assigned Tasks</h2>
        <p>View and manage the tasks assigned to you.</p>
    </div>

    {{-- Tasks table card --}}
    <div class="tasks-card">

        <div class="tasks-card-header">
            <h3>All Assigned Tasks</h3>

            <span class="task-count">
                {{ $tasks->count() }} Tasks
            </span>
        </div>

        <div class="table-wrapper">

            <table class="tasks-table">

                <thead>
                    <tr>
                        <th>#</th>
                        <th>Task Name</th>
                        <th>Description</th>
                        <th>Deadline</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($tasks as $task)

                        <tr>

                            <td class="task-number">
                                {{ $loop->iteration }}
                            </td>

                            <td class="task-name">
                                {{ $task->title }}
                            </td>

                            <td class="task-description">
                                {{ \Illuminate\Support\Str::limit($task->description ?? 'No description provided', 70) }}
                            </td>

                            <td class="task-deadline">

                                @if($task->due_at)
                                    {{ \Carbon\Carbon::parse($task->due_at)->format('d M Y, h:i A') }}
                                @else
                                    Not specified
                                @endif

                            </td>

                            <td>
                                <span class="status-badge status-{{ $task->status }}">
                                    {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                </span>
                            </td>

                            <td>
                                <a href="{{ route('staff.task.show', $task->id) }}"
                                   class="view-details-btn">
                                    View Details
                                </a>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="6">
                                <div class="empty-tasks">
                                    <h3>No Tasks Assigned</h3>
                                    <p>You currently do not have any assigned tasks.</p>
                                </div>
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection
