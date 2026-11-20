
@extends('layouts.staff')

@section('title', 'Staff Dashboard')

@section('page-heading', 'Dashboard')

@push('styles')
<style>
    .welcome {
        background: linear-gradient(120deg, #1d4ed8, #3b82f6);
        color: white;
        padding: 28px;
        border-radius: 12px;
        margin-bottom: 28px;
    }

    .welcome h2 {
        margin-bottom: 10px;
        font-size: 23px;
    }

    .welcome p {
        font-size: 14px;
        color: #dbeafe;
    }

    .section-title {
        font-size: 19px;
        margin-bottom: 18px;
    }

    .cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
        gap: 18px;
        margin-bottom: 30px;
    }

    .card {
        background: white;
        padding: 22px;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }

    .card p {
        color: #64748b;
        font-size: 13px;
        margin-bottom: 13px;
    }

    .card h2 {
        font-size: 29px;
        color: #1e293b;
    }

    .card small {
        display: block;
        margin-top: 9px;
        color: #94a3b8;
        font-size: 11px;
    }

    .task-section {
        background: white;
        padding: 25px;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        margin-bottom: 25px;
    }

    .task-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 22px;
    }

    .task-header h2 {
        font-size: 18px;
    }

    .task-header span {
        font-size: 12px;
        color: #64748b;
    }

    .task-table {
        width: 100%;
        border-collapse: collapse;
    }

    .task-table th,
    .task-table td {
        padding: 15px 12px;
        text-align: left;
        border-bottom: 1px solid #e2e8f0;
        font-size: 13px;
    }

    .task-table th {
        color: #64748b;
        font-weight: 600;
        background: #f8fafc;
    }

    .empty {
        text-align: center;
        padding: 30px !important;
        color: #94a3b8;
    }

    .status {
        display: inline-block;
        padding: 6px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }

    .status-pending {
        background: #fef3c7;
        color: #92400e;
    }

    .status-submitted {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .status-completed {
        background: #dcfce7;
        color: #166534;
    }

    .status-needs_revision {
        background: #fee2e2;
        color: #991b1b;
    }

    @media(max-width: 650px) {
        .task-section {
            overflow-x: auto;
        }
    }
</style>
@endpush

@section('content')

    <!-- Welcome Section -->
    <div class="welcome">

        <h2>
            Welcome back, {{ auth()->user()->name }}!
        </h2>

        <p>
            Manage your assigned tasks and monitor your work progress here.
        </p>

    </div>

    <!-- Task Statistics -->
    <h2 class="section-title">Task Overview</h2>

    <div class="cards">

        <div class="card">
            <p>Total Assigned Tasks</p>
            <h2>{{ $totalTasks }}</h2>
            <small>Your assigned workload</small>
        </div>

        <div class="card">
            <p>Pending Tasks</p>
            <h2>{{ $pendingTasks }}</h2>
            <small>Tasks waiting to start</small>
        </div>

        <div class="card">
            <p>In Progress Tasks</p>
            <h2>{{$inprogressTasks}}</h2>
            <small>Tasks Currently Being Worked on</small>
        </div>

        <div class="card">
            <p>Submitted Tasks</p>
            <h2>{{ $submittedTasks }}</h2>
            <small>Waiting for Admin review</small>
        </div>

        <div class="card">
            <p>Completed Tasks</p>
            <h2>{{ $completedTasks }}</h2>
            <small>Tasks approved by Admin</small>
        </div>

        <div class="card">
            <p>Revision Required</p>
            <h2>{{ $revisionTasks }}</h2>
            <small>Tasks requiring changes</small>
        </div>

    </div>

    <!-- Recent Assigned Tasks -->
    <div class="task-section">

        <div class="task-header">
            <h2>Recent Assigned Tasks</h2>
            <span>{{ now()->format('d M Y') }}</span>
        </div>

        <table class="task-table">

            <thead>
                <tr>
                    <th>Task Name</th>
                    <th>Deadline</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>

                @forelse($recentTasks as $task)

                    <tr>
                        <td>{{ $task->title }}</td>

                        <td>
                            {{ $task->due_at
                                ? \Illuminate\Support\Carbon::parse($task->due_at)->format('d M Y,h:i A')
                                : 'Not specified' }}
                        </td>

                        <td>
                            <span class="status status-{{ $task->status }}">
                                {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                            </span>
                        </td>
                    </tr>

                @empty

                    <tr>
                        <td colspan="3" class="empty">
                            No tasks assigned yet.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    <!-- Upcoming Deadlines -->
    <div class="task-section">

        <div class="task-header">
            <h2>Upcoming Deadlines</h2>
            <span>Next scheduled tasks</span>
        </div>

        <table class="task-table">

            <thead>
                <tr>
                    <th>Task Name</th>
                    <th>Due Date</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>

                @forelse($upcomingTasks as $task)

                    <tr>
                        <td>{{ $task->title }}</td>

                        <td>
                            {{ \Illuminate\Support\Carbon::parse($task->due_at)->format('d M Y,h:i A') }}
                        </td>

                        <td>
                            <span class="status status-{{ $task->status }}">
                                {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                            </span>
                        </td>
                    </tr>

                @empty

                    <tr>
                        <td colspan="3" class="empty">
                            No upcoming deadlines.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

@endsection
