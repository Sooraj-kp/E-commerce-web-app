@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('page-heading', 'Dashboard')

@section('content')

<style>
    :root {
        --dashboard-bg: #f1f4f9;
        --dashboard-surface: #ffffff;
        --dashboard-border: #e4e9f0;
        --dashboard-text: #1e293b;
        --dashboard-muted: #64748b;
        --dashboard-primary: #4f46e5;
        --dashboard-shadow-sm: 0 1px 2px rgba(15, 23, 42, 0.06);
        --dashboard-shadow-md: 0 10px 25px -5px rgba(15, 23, 42, 0.12);
        --dashboard-radius: 12px;
    }

    .admin-dashboard {
        max-width: 1100px;
        margin: 0 auto;
        padding: 10px 0 30px;
        color: var(--dashboard-text);
    }

    /* Welcome banner */
    .dashboard-welcome {
        background: linear-gradient(120deg, #4f46e5 0%, #6d28d9 100%);
        color: #fff;
        padding: 28px 32px;
        border-radius: var(--dashboard-radius);
        box-shadow: var(--dashboard-shadow-md);
        display: flex;
        align-items: center;
        gap: 18px;
    }

    .dashboard-avatar {
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

    .dashboard-welcome-text small {
        display: block;
        font-size: 13px;
        opacity: 0.8;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        margin-bottom: 4px;
    }

    .dashboard-welcome-text p {
        margin: 0;
        font-size: 22px;
        font-weight: 600;
    }

    /* Section heading */
    .dashboard-section-title {
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--dashboard-muted);
        margin: 36px 0 14px;
    }

    /* Summary cards */
    .dashboard-cards {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 20px;
    }

    .dashboard-stat-card {
        position: relative;
        display: flex;
        align-items: center;
        gap: 20px;
        background: var(--dashboard-surface);
        border: 1px solid var(--dashboard-border);
        border-radius: var(--dashboard-radius);
        padding: 26px;
        text-decoration: none;
        color: inherit;
        box-shadow: var(--dashboard-shadow-sm);
        transition:
            transform 0.2s ease,
            box-shadow 0.2s ease,
            border-color 0.2s ease;
    }

    .dashboard-stat-card:hover {
        transform: translateY(-3px);
        box-shadow: var(--dashboard-shadow-md);
        border-color: transparent;
        color: inherit;
    }

    .dashboard-stat-card:focus-visible {
        outline: 3px solid rgba(79, 70, 229, 0.4);
        outline-offset: 2px;
    }

    .dashboard-stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: grid;
        place-items: center;
        flex-shrink: 0;
    }

    .dashboard-stat-icon svg {
        width: 26px;
        height: 26px;
    }

    .dashboard-icon-products {
        background: #eef2ff;
        color: #4f46e5;
    }

    .dashboard-icon-orders {
        background: #ecfdf5;
        color: #059669;
    }

    .dashboard-icon-customers {
        background: #fff7ed;
        color: #ea580c;
    }

    .dashboard-icon-staff {
        background: #fdf2f8;
        color: #db2777;
    }

    .dashboard-stat-info {
        flex: 1;
        min-width: 0;
    }

    .dashboard-stat-info h3 {
        margin: 0 0 6px;
        font-size: 14px;
        font-weight: 600;
        color: var(--dashboard-muted);
    }

    .dashboard-stat-info p {
        margin: 0;
        font-size: 32px;
        font-weight: 700;
        letter-spacing: -0.02em;
        color: var(--dashboard-text);
        line-height: 1.1;
    }

    .dashboard-stat-arrow {
        color: #cbd5e1;
        transition: color 0.2s, transform 0.2s;
    }

    .dashboard-stat-arrow svg {
        width: 20px;
        height: 20px;
        display: block;
    }

    .dashboard-stat-card:hover .dashboard-stat-arrow {
        color: var(--dashboard-primary);
        transform: translateX(4px);
    }

    /* Responsive layout */
    @media (max-width: 768px) {
        .admin-dashboard {
            padding: 5px 0 20px;
        }

        .dashboard-cards {
            grid-template-columns: 1fr;
        }

        .dashboard-stat-card {
            padding: 20px;
        }

        .dashboard-stat-info p {
            font-size: 28px;
        }
    }

    @media (max-width: 600px) {
        .dashboard-welcome {
            padding: 20px;
        }

        .dashboard-welcome-text p {
            font-size: 18px;
        }
    }
</style>

<main class="admin-dashboard">

    {{-- Welcome section --}}
    <section class="dashboard-welcome">

        <div class="dashboard-avatar">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>

        <div class="dashboard-welcome-text">
            <small>Welcome back</small>
            <p>{{ auth()->user()->name }}</p>
        </div>

    </section>

    <h2 class="dashboard-section-title">Overview</h2>

    {{-- Dashboard summary cards --}}
    <section class="dashboard-cards">

        {{-- Products --}}
        <a href="{{ url('/products') }}" class="dashboard-stat-card">

            <div class="dashboard-stat-icon dashboard-icon-products">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                    <polyline points="3.27 6.96 12 12.01 20.73 6.96"/>
                    <line x1="12" y1="22.08" x2="12" y2="12"/>
                </svg>
            </div>

            <div class="dashboard-stat-info">
                <h3>Total Products</h3>
                <p>{{ $totalProducts }}</p>
            </div>

            <span class="dashboard-stat-arrow">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="9 18 15 12 9 6"/>
                </svg>
            </span>

        </a>

        {{-- Orders --}}
        <a href="{{ url('/orders') }}" class="dashboard-stat-card">

            <div class="dashboard-stat-icon dashboard-icon-orders">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="9" cy="21" r="1"/>
                    <circle cx="20" cy="21" r="1"/>
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                </svg>
            </div>

            <div class="dashboard-stat-info">
                <h3>Total Orders</h3>
                <p>{{ $totalOrders }}</p>
            </div>

            <span class="dashboard-stat-arrow">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="9 18 15 12 9 6"/>
                </svg>
            </span>

        </a>

        {{-- Customers --}}
        <a href="{{ url('/customers') }}" class="dashboard-stat-card">

            <div class="dashboard-stat-icon dashboard-icon-customers">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
            </div>

            <div class="dashboard-stat-info">
                <h3>Total Customers</h3>
                <p>{{ $totalCustomer }}</p>
            </div>

            <span class="dashboard-stat-arrow">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="9 18 15 12 9 6"/>
                </svg>
            </span>

        </a>

        {{-- Staff --}}
        <a href="{{ url('/staff') }}" class="dashboard-stat-card">

            <div class="dashboard-stat-icon dashboard-icon-staff">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="2" y="7" width="20" height="14" rx="2"/>
                    <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
                </svg>
            </div>

            <div class="dashboard-stat-info">
                <h3>Total Staff</h3>
                <p>{{ $totalStaff }}</p>
            </div>

            <span class="dashboard-stat-arrow">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="9 18 15 12 9 6"/>
                </svg>
            </span>

        </a>

    </section>

</main>

@endsection