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
            --danger: #dc2626;
            --danger-dark: #b91c1c;
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

        .logout-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: transparent;
            color: var(--danger);
            border: 1px solid #fecaca;
            padding: 9px 16px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            transition: background 0.2s, color 0.2s, border-color 0.2s;
        }

        .logout-btn svg { width: 16px; height: 16px; }

        .logout-btn:hover {
            background: var(--danger);
            border-color: var(--danger);
            color: #fff;
        }

        .logout-btn:focus-visible,
        .stat-card:focus-visible {
            outline: 3px solid rgba(79, 70, 229, 0.4);
            outline-offset: 2px;
        }

        /* Layout */
        .dashboard {
            max-width: 1100px;
            margin: 0 auto;
            padding: 32px 30px 48px;
        }

        /* Welcome banner */
        .welcome {
            background: linear-gradient(120deg, #4f46e5 0%, #6d28d9 100%);
            color: #fff;
            padding: 28px 32px;
            border-radius: var(--radius);
            box-shadow: var(--shadow-md);
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .avatar {
            width: 54px;
            height: 54px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid rgba(255, 255, 255, 0.4);
            display: grid;
            place-items: center;
            font-size: 22px;
            font-weight: 700;
            flex-shrink: 0;
        }

        .welcome-text small {
            display: block;
            font-size: 13px;
            opacity: 0.8;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 4px;
        }

        .welcome-text p {
            margin: 0;
            font-size: 22px;
            font-weight: 600;
        }

        /* Section title */
        .section-title {
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--text-muted);
            margin: 36px 0 14px;
        }

        /* Stat cards */
        .cards {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .stat-card {
            position: relative;
            display: flex;
            align-items: center;
            gap: 20px;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 26px;
            text-decoration: none;
            color: inherit;
            box-shadow: var(--shadow-sm);
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-md);
            border-color: transparent;
        }

        .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            display: grid;
            place-items: center;
            flex-shrink: 0;
        }

        .stat-icon svg { width: 26px; height: 26px; }

        .icon-products  { background: #eef2ff; color: #4f46e5; }
        .icon-orders    { background: #ecfdf5; color: #059669; }
        .icon-customers { background: #fff7ed; color: #ea580c; }
        .icon-staff     { background: #fdf2f8; color: #db2777; }

        .stat-info { flex: 1; min-width: 0; }

        .stat-info h3 {
            margin: 0 0 6px;
            font-size: 14px;
            font-weight: 600;
            color: var(--text-muted);
        }

        .stat-info p {
            margin: 0;
            font-size: 32px;
            font-weight: 700;
            letter-spacing: -0.02em;
            color: var(--text);
            line-height: 1.1;
        }

        .stat-arrow {
            color: #cbd5e1;
            transition: color 0.2s, transform 0.2s;
        }

        .stat-arrow svg { width: 20px; height: 20px; display: block; }

        .stat-card:hover .stat-arrow {
            color: var(--primary);
            transform: translateX(4px);
        }

        /* Tablet / mobile */
        @media (max-width: 600px) {
            .topbar-inner { padding: 12px 16px; }
            .dashboard { padding: 20px 16px 36px; }

            .brand h1 { font-size: 16px; }
            .logout-btn span { display: none; }
            .logout-btn { padding: 9px 11px; }

            .welcome { padding: 20px; }
            .welcome-text p { font-size: 18px; }

            .cards { grid-template-columns: 1fr; }
            .stat-card { padding: 20px; }
            .stat-info p { font-size: 28px; }
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
                <h1>Admin Dashboard</h1>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                    <span>Log Out</span>
                </button>
            </form>

        </div>
    </header>

    <main class="dashboard">

        <!-- Welcome section -->
        <section class="welcome">
            <div class="avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            <div class="welcome-text">
                <small>Welcome back</small>
                <p>{{ auth()->user()->name }}</p>
            </div>
        </section>

        <h2 class="section-title">Overview</h2>

        <!-- Dashboard summary cards -->
        <section class="cards">

            <a href="{{ url('/products') }}" class="stat-card">
                <div class="stat-icon icon-products">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
                </div>
                <div class="stat-info">
                    <h3>Total Products</h3>
                    <p>{{ $totalProducts }}</p>
                </div>
                <span class="stat-arrow"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg></span>
            </a>

            <a href="{{ route('admin.orders.index') }}" class="stat-card">
                <div class="stat-icon icon-orders">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                </div>
                <div class="stat-info">
                    <h3>Total Orders</h3>
                    <p>{{ $totalOrders }}</p>
                </div>
                <span class="stat-arrow"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg></span>
            </a>

            <a href="{{ url('/customers') }}" class="stat-card">
                <div class="stat-icon icon-customers">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                </div>
                <div class="stat-info">
                    <h3>Total Customers</h3>
                    <p>{{ $totalCustomer }}</p>
                </div>
                <span class="stat-arrow"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg></span>
            </a>

            <a href="{{ url('/staff') }}" class="stat-card">
                <div class="stat-icon icon-staff">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                </div>
                <div class="stat-info">
                    <h3>Total Staff</h3>
                    <p>{{ $totalStaff }}</p>
                </div>
                <span class="stat-arrow"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg></span>
            </a>

        </section>

    </main>

@endsection