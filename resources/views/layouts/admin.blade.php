<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin Panel') | {{ config('app.name', 'Laravel') }}</title>

    {{-- Kept so older pages that still use Bootstrap classes keep working. Remove once every page is restyled. --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Fraunces:ital,opsz,wght@0,9..144,600;1,9..144,500&display=swap" rel="stylesheet">

    <style>
        :root {
            /* ===== One blue only: cobalt (#2563eb) ===== */
            --brand: #2563eb;        /* buttons, links, logo, avatar, active bar */
            --brand-dark: #1d4ed8;   /* hover, pressed, active text */
            --brand-tint: #dbeafe;   /* stronger tint */
            --brand-soft: #eff6ff;   /* soft tint: chips, date pill */
            --brand-line: #bfd3f7;   /* blue borders */
            --brand-rgb: 37, 99, 235;

            --border: #dbe5f3;
            --ink: #0f172a;          /* main text, dark navy */
            --muted: #64748b;        /* secondary text */
            --danger: #e11d48;       /* delete, logout only */

            --bg: #f8fafc;
            --surface: #ffffff;

            --side-top: #f7faff;
            --side-bottom: #e9f1ff;
            --side-line: #d5e2f7;
            --side-text: #475569;
            --side-muted: #7f93b2;
            --side-w: 276px;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            background: var(--bg);
            color: var(--ink);
            font-family: "DM Sans", "Segoe UI", -apple-system, Roboto, Arial, sans-serif;
            font-size: 16px;
            line-height: 1.55;
            -webkit-font-smoothing: antialiased;
        }

        .admin-wrapper { display: flex; min-height: 100vh; }

        /* =============== Sidebar =============== */
        .admin-sidebar {
            position: sticky;
            top: 0;
            align-self: flex-start;
            width: var(--side-w);
            height: 100vh;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            color: var(--ink);
            background:
                radial-gradient(420px 280px at 0% 0%, rgba(var(--brand-rgb), 0.10), transparent 70%),
                linear-gradient(180deg, var(--side-top), var(--side-bottom));
            border-right: 1px solid var(--side-line);
            z-index: 60;
        }

        .sb-brand {
            display: flex;
            align-items: center;
            gap: 13px;
            padding: 26px 24px 22px;
        }

        .sb-logo {
            width: 44px;
            height: 44px;
            border-radius: 13px;
            background: var(--brand);
            display: grid;
            place-items: center;
            color: #fff;
            box-shadow: 0 10px 20px -8px rgba(var(--brand-rgb), 0.6), inset 0 1px 0 rgba(255, 255, 255, 0.25);
            flex-shrink: 0;
        }

        .sb-logo svg { width: 23px; height: 23px; }

        .sb-brand-text strong {
            display: block;
            font-family: "Fraunces", Georgia, serif;
            font-size: 21px;
            font-weight: 600;
            letter-spacing: -0.01em;
            line-height: 1.15;
            color: var(--ink);
        }

        .sb-brand-text span {
            font-size: 11.5px;
            font-weight: 700;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: var(--side-muted);
        }

        .sb-scroll {
            flex: 1;
            overflow-y: auto;
            padding: 6px 16px 16px;
            scrollbar-width: thin;
            scrollbar-color: rgba(var(--brand-rgb), 0.3) transparent;
        }

        .sb-label {
            font-size: 11.5px;
            font-weight: 700;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: var(--side-muted);
            padding: 20px 12px 8px;
        }

        .sb-link {
            position: relative;
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 12px 14px;
            margin-bottom: 3px;
            border-radius: 11px;
            border: 1px solid transparent;
            color: var(--side-text);
            font-size: 15.5px;
            font-weight: 500;
            text-decoration: none;
            transition: background 0.18s, color 0.18s, box-shadow 0.18s;
        }

        .sb-link svg {
            width: 20px;
            height: 20px;
            flex-shrink: 0;
            opacity: 0.8;
            transition: color 0.18s, opacity 0.18s;
        }

        .sb-link:hover {
            background: rgba(var(--brand-rgb), 0.08);
            color: var(--brand-dark);
        }

        .sb-link:hover svg { opacity: 1; }

        .sb-link.active {
            background: #fff;
            border-color: var(--side-line);
            color: var(--brand-dark);
            font-weight: 700;
            box-shadow: 0 8px 18px -10px rgba(var(--brand-rgb), 0.45);
        }

        .sb-link.active::before {
            content: "";
            position: absolute;
            left: -17px;
            top: 9px;
            bottom: 9px;
            width: 4px;
            border-radius: 0 4px 4px 0;
            background: var(--brand);
        }

        .sb-link.active svg { color: var(--brand); opacity: 1; }

        .sb-link:focus-visible,
        .sb-store:focus-visible,
        .sb-logout:focus-visible,
        .menu-btn:focus-visible {
            outline: 3px solid rgba(var(--brand-rgb), 0.4);
            outline-offset: 2px;
        }

        .sb-store {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 6px 16px 0;
            padding: 13px 16px;
            border-radius: 12px;
            border: 1px dashed var(--brand-line);
            background: rgba(255, 255, 255, 0.55);
            color: var(--brand-dark);
            font-size: 14.5px;
            font-weight: 600;
            text-decoration: none;
            transition: background 0.18s, border-color 0.18s;
        }

        .sb-store:hover { background: #fff; border-color: var(--brand); color: var(--brand-dark); }
        .sb-store svg { width: 17px; height: 17px; }

        /* user card */
        .sb-user {
            margin: 14px 16px 18px;
            padding: 14px;
            border-radius: 14px;
            background: #fff;
            border: 1px solid var(--side-line);
            box-shadow: 0 8px 20px -14px rgba(var(--brand-rgb), 0.5);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sb-avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: var(--brand);
            color: #fff;
            display: grid;
            place-items: center;
            font-weight: 700;
            font-size: 17px;
            flex-shrink: 0;
        }

        .sb-user-info { min-width: 0; flex: 1; }
        .sb-user-info b { display: block; font-size: 15px; line-height: 1.25; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .sb-user-info span { display: block; font-size: 12.5px; color: var(--muted); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

        .sb-logout {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            border: 1px solid var(--border);
            background: #fff;
            color: var(--danger);
            display: grid;
            place-items: center;
            cursor: pointer;
            flex-shrink: 0;
            transition: background 0.18s, color 0.18s, border-color 0.18s;
        }

        .sb-logout:hover { background: var(--danger); border-color: var(--danger); color: #fff; }
        .sb-logout svg { width: 18px; height: 18px; }

        /* =============== Main =============== */
        .admin-main { flex: 1; min-width: 0; display: flex; flex-direction: column; }

        .admin-topbar {
            position: sticky;
            top: 0;
            z-index: 40;
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border);
            padding: 0 34px;
            height: 78px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
        }

        .top-left { display: flex; align-items: center; gap: 14px; min-width: 0; }

        .menu-btn {
            display: none;
            width: 44px;
            height: 44px;
            border-radius: 12px;
            border: 1px solid var(--border);
            background: #fff;
            color: var(--ink);
            cursor: pointer;
            place-items: center;
            flex-shrink: 0;
        }

        .menu-btn svg { width: 22px; height: 22px; }

        .admin-topbar h5 {
            margin: 0;
            font-family: "Fraunces", Georgia, serif;
            font-size: 28px;
            font-weight: 600;
            letter-spacing: -0.02em;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .top-right { display: flex; align-items: center; gap: 18px; }

        .top-date {
            font-size: 14.5px;
            color: var(--brand-dark);
            font-weight: 500;
            padding: 8px 16px;
            border: 1px solid var(--brand-tint);
            border-radius: 999px;
            background: var(--brand-soft);
            white-space: nowrap;
        }

        .top-user { display: flex; align-items: center; gap: 11px; font-weight: 600; font-size: 15px; }

        .top-user .sb-avatar { width: 40px; height: 40px; font-size: 16px; }

        .admin-content { padding: 34px; flex: 1; }

        /* overlay for mobile */
        .sb-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.45);
            backdrop-filter: blur(2px);
            z-index: 55;
        }

        /* =============== Responsive =============== */
        @media (max-width: 991px) {
            .admin-sidebar {
                position: fixed;
                left: 0;
                top: 0;
                transform: translateX(-100%);
                transition: transform 0.3s cubic-bezier(0.2, 0.8, 0.2, 1);
            }

            body.nav-open .admin-sidebar { transform: none; box-shadow: 20px 0 60px rgba(15, 23, 42, 0.25); }
            body.nav-open .sb-overlay { display: block; }
            body.nav-open { overflow: hidden; }

            .sb-link.active::before { left: -17px; }

            .menu-btn { display: grid; }
            .admin-topbar { padding: 0 18px; height: 70px; }
            .admin-topbar h5 { font-size: 23px; }
            .top-date, .top-user span { display: none; }
            .admin-content { padding: 20px 16px; }
        }
    </style>

    @stack('styles')
</head>

<body>

    <div class="admin-wrapper">

        <!-- =============== Admin Sidebar =============== -->
        <aside class="admin-sidebar" id="sidebar" aria-label="Main navigation">

            <div class="sb-brand">
                <div class="sb-logo">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                </div>
                <div class="sb-brand-text">
                    <strong>E-Commerce </strong>
                    <span>Admin Panel</span>
                </div>
            </div>

            <nav class="sb-scroll">

                <div class="sb-label">Overview</div>
                <a href="{{ url('/admin') }}" class="sb-link {{ request()->is('admin') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="9" rx="1.5"/><rect x="14" y="3" width="7" height="5" rx="1.5"/><rect x="14" y="12" width="7" height="9" rx="1.5"/><rect x="3" y="16" width="7" height="5" rx="1.5"/></svg>
                    Dashboard
                </a>

                <div class="sb-label">Catalog</div>
                <a href="{{ url('/admin/categories') }}"
                        class="sb-link {{ request()->is('admin/categories*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M20.6 13.4 13.4 20.6a2 2 0 0 1-2.8 0L2 12V2h10l8.6 8.6a2 2 0 0 1 0 2.8z"/><circle cx="7" cy="7" r="1.2"/></svg>
                    Categories
                </a>
                <a href="{{ url('/products') }}" class="sb-link {{ request()->is('products*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
                    Products
                </a>

                <div class="sb-label">Sales</div>
                <a href="{{ route('admin.orders.index') }}"
   class="sb-link {{ request()->is('admin/orders*') ? 'active' : '' }}">

    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
         stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="9" cy="21" r="1"/>
        <circle cx="20" cy="21" r="1"/>
        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
    </svg>

    Orders
</a>
                <a href="{{ url('/customers') }}" class="sb-link {{ request()->is('customers*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    Customers
                </a>

                <div class="sb-label">Team</div>

{{-- Staff link --}}
<a href="{{ url('/staff') }}"
   class="sb-link {{ request()->is('staff*') ? 'active' : '' }}">

    <svg viewBox="0 0 24 24"
         fill="none"
         stroke="currentColor"
         stroke-width="1.9"
         stroke-linecap="round"
         stroke-linejoin="round">
        <path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
        <circle cx="10" cy="7" r="4"/>
        <path d="M20 8v6"/>
        <path d="M23 11h-6"/>
    </svg>

    <span>Staff</span>
</a>

{{-- Tasks link --}}
<a href="{{ url('/admin/tasks') }}"
   class="sb-link {{ request()->is('admin/tasks*') ? 'active' : '' }}">

    <svg viewBox="0 0 24 24"
         fill="none"
         stroke="currentColor"
         stroke-width="1.9"
         stroke-linecap="round"
         stroke-linejoin="round">
        <path d="M9 11l3 3L22 4"/>
        <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
    </svg>

    <span>Tasks</span>
</a>

            </nav>

            <a href="{{ url('/shop') }}" class="sb-store" target="_blank" rel="noopener">
                View store
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
            </a>

            @auth
                <div class="sb-user">
                    <div class="sb-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                    <div class="sb-user-info">
                        <b>{{ Auth::user()->name }}</b>
                        <span>{{ Auth::user()->email }}</span>
                    </div>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="sb-logout" aria-label="Log out" title="Log out">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                        </button>
                    </form>
                </div>
            @endauth

        </aside>

        <div class="sb-overlay" id="sbOverlay"></div>

        <!-- =============== Main Area =============== -->
        <div class="admin-main">

            <header class="admin-topbar">
                <div class="top-left">
                    <button type="button" class="menu-btn" id="menuBtn" aria-label="Open menu" aria-controls="sidebar">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
                    </button>
                    <h5>@yield('page-heading', 'Admin Panel')</h5>
                </div>

                <div class="top-right">
                    <span class="top-date">{{ now()->format('l, d M Y') }}</span>

                    @auth
                        <div class="top-user">
                            <div class="sb-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                            <span>{{ Auth::user()->name }}</span>
                        </div>
                    @endauth
                </div>
            </header>

            <main class="admin-content">
                @yield('content')
            </main>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Mobile sidebar: open, close on overlay, Esc, or link click
        const body = document.body;
        const closeNav = () => body.classList.remove('nav-open');

        document.getElementById('menuBtn').addEventListener('click', () => body.classList.add('nav-open'));
        document.getElementById('sbOverlay').addEventListener('click', closeNav);
        document.addEventListener('keydown', e => { if (e.key === 'Escape') closeNav(); });
        document.querySelectorAll('.sb-link').forEach(a => a.addEventListener('click', closeNav));
    </script>

    @stack('scripts')
</body>

</html>