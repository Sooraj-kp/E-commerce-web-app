@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('page-heading', 'Dashboard')

@section('content')

    <style>
        :root {
            --bg: #f1f4f9;
            --surface: #ffffff;
            --border: #e4e9f0;
            --text: #1e293b;
            --text-muted: #64748b;
            --primary: #4f46e5;
            --shadow-sm: 0 1px 2px rgba(15, 23, 42, 0.06);
            --shadow-md: 0 10px 25px -5px rgba(15, 23, 42, 0.12);
            --radius: 12px;
        }

        * { box-sizing: border-box; }

        body {
            font-family: "Inter", "Segoe UI", -apple-system, BlinkMacSystemFont, Roboto, Arial, sans-serif;
            background: var(--bg);
            color: var(--text);
            margin: 0;
            -webkit-font-smoothing: antialiased;
        }

        /* Top navigation bar */
        .topbar {
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
        }

        .topbar-inner {
            max-width: 1100px;
            margin: 0 auto;
            padding: 14px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand-logo {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            display: grid;
            place-items: center;
            color: #fff;
        }

        .brand-logo svg { width: 20px; height: 20px; }

        .brand h1 {
            font-size: 18px;
            font-weight: 700;
            margin: 0;
            letter-spacing: -0.01em;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--primary);
            border: 1px solid #c7d2fe;
            padding: 9px 16px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: background 0.2s, color 0.2s, border-color 0.2s;
        }

        .back-link svg { width: 16px; height: 16px; }

        .back-link:hover {
            background: var(--primary);
            border-color: var(--primary);
            color: #fff;
        }

        .back-link:focus-visible {
            outline: 3px solid rgba(79, 70, 229, 0.4);
            outline-offset: 2px;
        }

        /* Layout */
        .page {
            max-width: 1100px;
            margin: 0 auto;
            padding: 32px 30px 48px;
        }

        .page-head {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 20px;
        }

        .page-head h2 {
            margin: 0 0 4px;
            font-size: 26px;
            font-weight: 700;
            letter-spacing: -0.02em;
        }

        .page-head p {
            margin: 0;
            font-size: 14px;
            color: var(--text-muted);
        }

        .count-pill {
            background: #eef2ff;
            color: var(--primary);
            font-size: 13px;
            font-weight: 600;
            padding: 6px 14px;
            border-radius: 999px;
        }

        /* Table card */
        .table-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
        }

        .table-wrap { overflow-x: auto; }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 760px;
        }

        thead th {
            background: #f8fafc;
            color: var(--text-muted);
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            text-align: left;
            padding: 14px 20px;
            border-bottom: 1px solid var(--border);
            white-space: nowrap;
        }

        tbody td {
            padding: 16px 20px;
            font-size: 14px;
            border-bottom: 1px solid var(--border);
            white-space: nowrap;
        }

        tbody tr:last-child td { border-bottom: none; }

        tbody tr { transition: background 0.15s; }
        tbody tr:hover { background: #f8fafc; }

        .th-right, .td-right { text-align: right; }

        .order-number {
            font-weight: 600;
            color: var(--primary);
        }

        .muted { color: var(--text-muted); }

        .amount {
            font-weight: 600;
            font-variant-numeric: tabular-nums;
        }

        /* Status badges */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
            background: #f1f5f9;
            color: #475569;
        }

        .badge::before {
            content: "";
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: currentColor;
        }

        .badge-pending    { background: #fffbeb; color: #b45309; }
        .badge-processing { background: #eff6ff; color: #1d4ed8; }
        .badge-shipped    { background: #f5f3ff; color: #6d28d9; }
        .badge-delivered,
        .badge-completed  { background: #ecfdf5; color: #047857; }
        .badge-cancelled,
        .badge-failed     { background: #fef2f2; color: #b91c1c; }

        /* Empty state */
        .empty {
            text-align: center;
            padding: 56px 20px !important;
            color: var(--text-muted);
        }

        .empty svg {
            width: 40px;
            height: 40px;
            color: #cbd5e1;
            display: block;
            margin: 0 auto 10px;
        }

        /* Pagination */
        .pagination-wrap {
            margin-top: 20px;
            font-size: 14px;
        }

        .pagination-wrap svg { width: 18px; height: 18px; }

        /* Mobile */
        @media (max-width: 600px) {
            .topbar-inner { padding: 12px 16px; }
            .page { padding: 20px 16px 36px; }

            .brand h1 { font-size: 16px; }
            .back-link span { display: none; }
            .back-link { padding: 9px 11px; }

            .page-head h2 { font-size: 22px; }
        }
    </style>
</head>

<body>

    <!-- Top navigation -->
    <header class="topbar">
        <div class="topbar-inner">

            <div class="brand">
                <div class="brand-logo">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="9" rx="1"/><rect x="14" y="3" width="7" height="5" rx="1"/><rect x="14" y="12" width="7" height="9" rx="1"/><rect x="3" y="16" width="7" height="5" rx="1"/></svg>
                </div>
                <h1>E-Commerce Admin</h1>
            </div>

            <a href="{{ url('/dashboard') }}" class="back-link">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
                <span>Back to Dashboard</span>
            </a>

        </div>
    </header>

    <main class="page">

        <div class="page-head">
            <div>
                <h2>Orders</h2>
                <p>Review all customer orders and their current status.</p>
            </div>
            <span class="count-pill">
                {{ method_exists($orders, 'total') ? $orders->total() : $orders->count() }} total
            </span>
        </div>

        <div class="table-card">
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Order Number</th>
                            <th>Customer ID</th>
                            <th class="th-right">Total Amount</th>
                            <th>Status</th>
                            <th>Order Date</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($orders as $order)
                            <tr>
                                <td class="muted">#{{ $order->id }}</td>
                                <td class="order-number">{{ $order->order_number }}</td>
                                <td>{{ $order->customer_id }}</td>
                                <td class="td-right amount">₹{{ number_format($order->total, 2) }}</td>
                                <td>
                                    <span class="badge badge-{{ strtolower($order->status) }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td class="muted">{{ $order->created_at?->format('d M Y, h:i A') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="empty">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                                    No orders found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if (method_exists($orders, 'links'))
            <div class="pagination-wrap">
                {{ $orders->links() }}
            </div>
        @endif

    </main>

@endsection