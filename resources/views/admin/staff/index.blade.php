
@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('page-heading', 'Dashboard')

@section('content')

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background: #f4f6fa;
            padding: 35px;
            color: #1e293b;
        }

        .container {
            max-width: 1100px;
            margin: auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .header h1 {
            font-size: 26px;
        }

        .header p {
            color: #64748b;
            margin-top: 8px;
            font-size: 14px;
        }

        .btn {
            display: inline-block;
            padding: 11px 18px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
            border: none;
            cursor: pointer;
        }

        .btn-primary {
            background: #2563eb;
            color: white;
        }

        .btn-primary:hover {
            background: #1d4ed8;
        }

        .btn-edit {
            background: #e0e7ff;
            color: #1d4ed8;
        }

        .table-container {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            padding: 16px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
            font-size: 14px;
        }

        th {
            background: #f8fafc;
            color: #64748b;
        }

        .role {
            background: #dcfce7;
            color: #166534;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
        }

        .success {
            background: #dcfce7;
            color: #166534;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .empty {
            text-align: center;
            padding: 30px;
            color: #64748b;
        }

        .back-link {
            display: inline-block;
            margin-top: 22px;
            color: #2563eb;
            text-decoration: none;
        }

        @media(max-width: 600px) {
            body {
                padding: 15px;
            }

            .header {
                align-items: flex-start;
                gap: 15px;
                flex-direction: column;
            }
        }
    </style>
</head>

<body>

<div class="container">

    <div class="header">
        <div>
            <h1>Manage Staff</h1>
            <p>View and manage your staff members.</p>
        </div>

        <a href="/staff/create" class="btn btn-primary">
            + Add New Staff
        </a>
    </div>

    @if(session('success'))
        <div class="success">
            {{ session('success') }}
        </div>
    @endif

    <div class="table-container">

        <h2>Staff Members</h2>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>

                @forelse($staff as $member)

                    <tr>
                        <td>{{ $member->id }}</td>

                        <td>{{ $member->name }}</td>

                        <td>{{ $member->email }}</td>

                        <td>
                            <span class="role">
                                {{ ucfirst($member->role) }}
                            </span>
                        </td>

                        <td>
                            <a href="/staff/{{ $member->id }}/edit"
                               class="btn btn-edit">
                                Edit
                            </a>
                        </td>
                    </tr>

                @empty

                    <tr>
                        <td colspan="5" class="empty">
                            No staff members found.
                        </td>
                    </tr>

                @endforelse

            </tbody>
        </table>

    </div>

    <a href="/admin" class="back-link">
        ← Back to Admin Dashboard
    </a>

</div>

@endsection
