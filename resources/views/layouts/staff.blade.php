<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Staff Panel') | E-Commerce</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Fraunces:ital,opsz,wght@0,9..144,600;1,9..144,500&display=swap" rel="stylesheet">

    <style>
        :root {
            /* ===== One blue only: cobalt (#2563eb). Same palette as the admin layout ===== */
            --brand: #2563eb;
            --brand-dark: #1d4ed8;
            --brand-tint: #dbeafe;
            --brand-soft: #eff6ff;
            --brand-line: #bfd3f7;
            --brand-rgb: 37, 99, 235;

            --border: #dbe5f3;
            --ink: #0f172a;
            --muted: #64748b;
            --danger: #e11d48;

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

        .staff-wrapper { display: flex; min-height: 100vh; }

        /* =============== Sidebar =============== */
        .staff-sidebar {
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
            z-index: 100;
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
        .sb-logout:focus-visible,
        .menu-btn:focus-visible {
            outline: 3px solid rgba(var(--brand-rgb), 0.4);
            outline-offset: 2px;
        }

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

        .sb-user form { margin: 0; }

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
        .staff-main { flex: 1; min-width: 0; display: flex; flex-direction: column; }

        .staff-topbar {
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

        .staff-topbar h1 {
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

        .staff-content { padding: 34px; flex: 1; min-width: 0; }

        /* overlay for mobile */
        .staff-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.45);
            backdrop-filter: blur(2px);
            z-index: 90;
        }

        /* =============== Responsive =============== */
        @media (max-width: 991px) {
            .staff-sidebar {
                position: fixed;
                left: 0;
                top: 0;
                transform: translateX(-100%);
                transition: transform 0.3s cubic-bezier(0.2, 0.8, 0.2, 1);
            }

            body.staff-nav-open .staff-sidebar { transform: none; box-shadow: 20px 0 60px rgba(15, 23, 42, 0.25); }
            body.staff-nav-open .staff-overlay { display: block; }
            body.staff-nav-open { overflow: hidden; }

            .menu-btn { display: grid; }
            .staff-topbar { padding: 0 18px; height: 70px; }
            .staff-topbar h1 { font-size: 23px; }
            .top-date, .top-user span { display: none; }
            .staff-content { padding: 20px 16px; }
        }
    </style>

    @stack('styles')
</head>

<body>

    <div class="staff-wrapper">

        <!-- =============== Sidebar =============== -->
        <aside class="staff-sidebar" id="staffSidebar" aria-label="Main navigation">

            <div class="sb-brand">
                <div class="sb-logo">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                </div>
                <div class="sb-brand-text">
                    <strong>E-Shop</strong>
                    <span>Staff Panel</span>
                </div>
            </div>

            <nav class="sb-scroll">

                <div class="sb-label">Overview</div>
                <a href="{{ url('/Staff/dashboard') }}"
                   class="sb-link {{ request()->is('Staff/dashboard', 'staff/dashboard') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="9" rx="1.5"/><rect x="14" y="3" width="7" height="5" rx="1.5"/><rect x="14" y="12" width="7" height="9" rx="1.5"/><rect x="3" y="16" width="7" height="5" rx="1.5"/></svg>
                    Dashboard
                </a>

                <div class="sb-label">Work</div>
                <a href="{{ route('staff.task.index') }}"
                   class="sb-link {{ request()->routeIs('staff.task.*') || request()->is('Staff/tasks*', 'staff/tasks*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                    My Tasks
                </a>

                <a href="{{ route('staff.orders.index') }}"
                   class="sb-link {{ request()->routeIs('staff.orders.*') || request()->is('Staff/orders*', 'staff/orders*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="8" y1="13" x2="16" y2="13"/><line x1="8" y1="17" x2="13" y2="17"/></svg>
                    My Assigned Orders
                </a>

                <div class="sb-label">Account</div>
                <a href="{{ route('profile.edit') }}"
                   class="sb-link {{ request()->routeIs('profile.*') || request()->is('profile*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    My Profile
                </a>

            </nav>

            @auth
                <div class="sb-user">
                    <div class="sb-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>

                    <div class="sb-user-info">
                        <b>{{ Auth::user()->name }}</b>
                        <span>Staff member</span>
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="sb-logout" aria-label="Log out" title="Log out">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                        </button>
                    </form>
                </div>
            @endauth

        </aside>

        <!-- Mobile overlay -->
        <div class="staff-overlay" id="staffOverlay"></div>

        <!-- =============== Main Area =============== -->
        <div class="staff-main">

            <header class="staff-topbar">
                <div class="top-left">
                    <button type="button" class="menu-btn" id="staffMenuBtn" aria-label="Open navigation" aria-controls="staffSidebar">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
                    </button>
                    <h1>@yield('page-heading', 'Staff Panel')</h1>
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

            <main class="staff-content">
                @yield('content')
            </main>

        </div>

    </div>

    <script>
        // Mobile sidebar: open, close on overlay, Esc, or link click
        const staffMenuBtn = document.getElementById('staffMenuBtn');
        const staffOverlay = document.getElementById('staffOverlay');
        const closeStaffMenu = () => document.body.classList.remove('staff-nav-open');

        staffMenuBtn.addEventListener('click', () => document.body.classList.add('staff-nav-open'));
        staffOverlay.addEventListener('click', closeStaffMenu);
        document.addEventListener('keydown', e => { if (e.key === 'Escape') closeStaffMenu(); });
        document.querySelectorAll('.sb-link').forEach(a => a.addEventListener('click', closeStaffMenu));
    </script>

    @stack('scripts')

</body>
</html>