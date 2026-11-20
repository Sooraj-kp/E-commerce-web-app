@extends('layouts.staff')

@section('title', 'My Assigned Orders')
@section('page-heading', 'My Assigned Orders')

@push('styles')
<style>
    .so-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 14px;
        margin-bottom: 24px;
    }

    .so-head h2 { margin: 0 0 4px; font-size: 20px; font-weight: 700; }
    .so-head p { margin: 0; color: var(--muted); font-size: 16px; }

    .so-count {
        display: inline-block;
        margin-left: 6px;
        background: var(--brand-soft);
        color: var(--brand-dark);
        border: 1px solid var(--brand-tint);
        font-size: 14px;
        font-weight: 700;
        padding: 2px 12px;
        border-radius: 999px;
        vertical-align: middle;
    }

    /* Table card */
    .so-card {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 16px;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04), 0 18px 40px -28px rgba(15, 23, 42, 0.18);
        overflow: hidden;
    }

    .so-wrap { overflow-x: auto; }

    .so-table { width: 100%; border-collapse: collapse; min-width: 820px; }

    .so-table thead th {
        background: #f8fafd;
        color: var(--muted);
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.07em;
        text-align: left;
        padding: 17px 22px;
        border-bottom: 1px solid var(--border);
        white-space: nowrap;
    }

    .so-table tbody td {
        padding: 17px 22px;
        font-size: 15.5px;
        color: var(--ink);
        border-bottom: 1px solid #edf2f9;
        vertical-align: middle;
    }

    .so-table tbody tr:last-child td { border-bottom: none; }
    .so-table tbody tr { transition: background 0.15s; }
    .so-table tbody tr:hover { background: #f8fbff; }

    .so-num { font-weight: 700; color: var(--brand-dark); white-space: nowrap; }
    .so-muted { color: var(--muted); white-space: nowrap; }
    .so-amount { font-weight: 700; white-space: nowrap; font-variant-numeric: tabular-nums; }
    .so-na { color: #b6c0cf; }

    .th-right, .td-right { text-align: right; }

    /* Status badge */
    .so-badge {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 5px 13px;
        border-radius: 999px;
        font-size: 13.5px;
        font-weight: 600;
        background: #f1f5f9;
        color: #475569;
        white-space: nowrap;
    }

    .so-badge::before { content: ""; width: 7px; height: 7px; border-radius: 50%; background: currentColor; }

    .so-pending     { background: #fffbeb; color: #b45309; }
    .so-processing,
    .so-in_progress { background: var(--brand-soft); color: var(--brand-dark); }
    .so-shipped     { background: #f5f3ff; color: #6d28d9; }
    .so-delivered,
    .so-completed   { background: #f0fdf4; color: #15803d; }
    .so-cancelled,
    .so-failed      { background: #fff1f2; color: #be123c; }

    /* Action button */
    .so-view {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 8px 16px;
        border: 1px solid var(--border);
        border-radius: 999px;
        background: #fff;
        color: var(--ink);
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        white-space: nowrap;
        transition: border-color 0.15s, color 0.15s, background 0.15s;
    }

    .so-view svg { width: 17px; height: 17px; color: var(--brand); transition: color 0.15s; }
    .so-view:hover { border-color: var(--brand); background: var(--brand); color: #fff; }
    .so-view:hover svg { color: #fff; }
    .so-view:focus-visible { outline: 3px solid rgba(var(--brand-rgb), 0.4); outline-offset: 2px; }

    /* Empty */
    .so-empty { text-align: center; padding: 70px 20px; color: var(--muted); font-size: 17px; }

    .so-empty-icon {
        width: 70px;
        height: 70px;
        margin: 0 auto 16px;
        border-radius: 50%;
        background: var(--brand-soft);
        color: var(--brand);
        display: grid;
        place-items: center;
    }

    .so-empty-icon svg { width: 30px; height: 30px; }
    .so-empty p { margin: 0; }

    /* Pagination */
    .so-pager {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 16px;
        margin-top: 26px;
        font-size: 15px;
        color: var(--muted);
        font-weight: 600;
    }

    .so-pager a,
    .so-pager .off {
        padding: 10px 22px;
        border: 1px solid var(--border);
        border-radius: 999px;
        background: #fff;
        color: var(--ink);
        text-decoration: none;
        transition: border-color 0.15s, color 0.15s;
    }

    .so-pager a:hover { border-color: var(--brand); color: var(--brand); }
    .so-pager .off { opacity: 0.45; }
</style>
@endpush

@section('content')

    <div class="so-head">
        <div>
            <h2>Assigned to you</h2>
            <p>
                Orders you are responsible for.
                <span class="so-count">{{ method_exists($orders, 'total') ? $orders->total() : $orders->count() }}</span>
            </p>
        </div>
    </div>

    @if ($orders->count() > 0)

        <div class="so-card">
            <div class="so-wrap">
                <table class="so-table">
                    <thead>
                        <tr>
                            <th>Order number</th>
                            <th>Customer</th>
                            <th>Order date</th>
                            <th>Total amount</th>
                            <th>Status</th>
                            <th class="th-right">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($orders as $order)
                            <tr>
                                <td class="so-num">{{ $order->order_number }}</td>

                                <td>
                                    @if ($order->user?->name)
                                        {{ $order->user->name }}
                                    @else
                                        <span class="so-na">N/A</span>
                                    @endif
                                </td>

                                <td class="so-muted">{{ $order->created_at->format('d-m-Y') }}</td>

                                <td class="so-amount">₹{{ number_format($order->total_amount, 2) }}</td>

                                <td>
                                    <span class="so-badge so-{{ strtolower($order->status) }}">
                                        {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                    </span>
                                </td>

                                <td class="td-right">
                                    <a href="{{ route('staff.orders.show', $order->id) }}" class="so-view">
                                        <svg class="eye" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                        View details
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        @if (method_exists($orders, 'hasPages') && $orders->hasPages())
            <div class="so-pager">
                @if ($orders->onFirstPage()) <span class="off">Previous</span>
                @else <a href="{{ $orders->previousPageUrl() }}">Previous</a> @endif

                <span>Page {{ $orders->currentPage() }} of {{ $orders->lastPage() }}</span>

                @if ($orders->hasMorePages()) <a href="{{ $orders->nextPageUrl() }}">Next</a>
                @else <span class="off">Next</span> @endif
            </div>
        @endif

    @else

        <div class="so-card so-empty">
            <div class="so-empty-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="8" y1="13" x2="16" y2="13"/><line x1="8" y1="17" x2="13" y2="17"/></svg>
            </div>
            <p>No orders have been assigned to you yet.</p>
        </div>

    @endif

@endsection