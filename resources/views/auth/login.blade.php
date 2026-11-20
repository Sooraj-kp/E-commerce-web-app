<x-guest-layout>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Fraunces:ital,opsz,wght@0,9..144,600;1,9..144,500&display=swap');

        :root {
            /* ===== Cobalt blue on white ===== */
            --brand: #2563eb;
            --brand-dark: #1d4ed8;
            --brand-tint: #dbeafe;
            --brand-soft: #eff6ff;
            --brand-rgb: 37, 99, 235;
            --border: #dbe5f3;
            --ink: #0f172a;
            --muted: #64748b;
            --danger: #e11d48;
        }

        /* Page background. Also neutralises the default Breeze guest wrapper
           (grey background, extra white card, Laravel logo) so only our card shows. */
        body { font-family: "DM Sans", "Segoe UI", -apple-system, Roboto, Arial, sans-serif; }

        .min-h-screen:has(.login-wrapper) {
            background:
                radial-gradient(900px 420px at 50% -8%, #e6efff, transparent 70%),
                #f8fafc !important;
        }

        .min-h-screen:has(.login-wrapper) > div:first-child { display: none; }

        div:has(> .login-wrapper) {
            background: transparent !important;
            box-shadow: none !important;
            padding: 0 !important;
            margin-top: 0 !important;
            max-width: none !important;
            overflow: visible !important;
        }

        .login-wrapper {
            width: 100%;
            max-width: 460px;
            margin: 0 auto;
            padding: 20px;
            font-family: "DM Sans", "Segoe UI", -apple-system, Roboto, Arial, sans-serif;
            color: var(--ink);
        }

        .login-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 22px;
            padding: 44px 40px 34px;
            box-shadow: 0 30px 60px -30px rgba(15, 23, 42, 0.22), 0 2px 6px rgba(15, 23, 42, 0.04);
        }

        /* ---------- Brand ---------- */
        .login-brand { text-align: center; margin-bottom: 34px; }

        .login-logo {
            width: 60px;
            height: 60px;
            margin: 0 auto 20px;
            border-radius: 17px;
            display: grid;
            place-items: center;
            color: #fff;
            background: var(--brand);
            box-shadow: 0 14px 26px -10px rgba(var(--brand-rgb), 0.65), inset 0 1px 0 rgba(255, 255, 255, 0.25);
        }

        .login-logo svg { width: 28px; height: 28px; }

        .login-brand h1 {
            margin: 0;
            font-family: "Fraunces", Georgia, serif;
            font-size: 34px;
            font-weight: 600;
            line-height: 1.1;
            letter-spacing: -0.025em;
            color: var(--ink);
        }

        .login-brand h1 em { font-style: italic; font-weight: 500; color: var(--brand); }

        .login-brand p { margin: 10px 0 0; color: var(--muted); font-size: 16px; }

        /* ---------- Fields ---------- */
        .login-field { margin-bottom: 22px; }

        .login-label {
            display: block;
            margin-bottom: 8px;
            color: var(--ink);
            font-size: 14.5px;
            font-weight: 600;
        }

        .login-control { position: relative; }

        .login-control > svg.lead {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            width: 19px;
            height: 19px;
            color: #94a3b8;
            pointer-events: none;
            transition: color 0.2s;
        }

        .login-control:focus-within > svg.lead { color: var(--brand); }

        .login-input {
            width: 100%;
            margin-top: 0 !important;
            border: 1px solid #cfd9e8 !important;
            border-radius: 12px !important;
            padding: 14px 16px 14px 46px !important;
            background: #f8fafc !important;
            color: var(--ink) !important;
            font-family: inherit !important;
            font-size: 15.5px !important;
            line-height: 1.4 !important;
            box-shadow: none !important;
            transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
        }

        .login-input::placeholder { color: #a0aec0; }

        .login-input:hover { border-color: #aebcd2 !important; }

        .login-input:focus {
            border-color: var(--brand) !important;
            background: #fff !important;
            box-shadow: 0 0 0 4px rgba(var(--brand-rgb), 0.14) !important;
            outline: none !important;
        }

        .login-input.has-toggle { padding-right: 50px !important; }

        .toggle-pass {
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
            width: 38px;
            height: 38px;
            border: none;
            border-radius: 9px;
            background: transparent;
            color: #94a3b8;
            display: grid;
            place-items: center;
            cursor: pointer;
            transition: background 0.15s, color 0.15s;
        }

        .toggle-pass:hover { background: var(--brand-soft); color: var(--brand); }
        .toggle-pass svg { width: 20px; height: 20px; }
        .toggle-pass .eye-off { display: none; }
        .toggle-pass.shown .eye { display: none; }
        .toggle-pass.shown .eye-off { display: block; }

        .login-field ul { margin: 8px 0 0; padding: 0; list-style: none; font-size: 14px; color: var(--danger); font-weight: 500; }

        /* ---------- Options ---------- */
        .login-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin: 2px 0 26px;
        }

        .login-remember {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: var(--muted);
            font-size: 14.5px;
            cursor: pointer;
        }

        .login-remember input {
            width: 17px;
            height: 17px;
            border-radius: 5px;
            accent-color: var(--brand);
            cursor: pointer;
        }

        .login-forgot {
            color: var(--brand);
            font-size: 14.5px;
            font-weight: 600;
            text-decoration: none;
        }

        .login-forgot:hover { color: var(--brand-dark); text-decoration: underline; text-underline-offset: 3px; }

        /* ---------- Button ---------- */
        .login-button {
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            border: none;
            border-radius: 12px;
            padding: 15px 16px;
            color: #fff;
            background: var(--brand);
            font-family: inherit;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 14px 26px -12px rgba(var(--brand-rgb), 0.85);
            transition: background 0.2s, transform 0.2s, box-shadow 0.2s;
        }

        .login-button svg { width: 18px; height: 18px; transition: transform 0.2s; }

        .login-button:hover { background: var(--brand-dark); transform: translateY(-1px); }
        .login-button:hover svg { transform: translateX(3px); }
        .login-button:active { transform: translateY(0); }
        .login-button:disabled { opacity: 0.75; cursor: wait; transform: none; }

        .login-button:focus-visible,
        .login-forgot:focus-visible,
        .toggle-pass:focus-visible {
            outline: 3px solid rgba(var(--brand-rgb), 0.4);
            outline-offset: 2px;
        }

        .login-secure {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 22px;
            color: var(--muted);
            font-size: 13.5px;
        }

        .login-secure svg { width: 16px; height: 16px; color: var(--brand); }

        .login-footer {
            margin-top: 26px;
            padding-top: 22px;
            border-top: 1px solid var(--border);
            text-align: center;
            color: #94a3b8;
            font-size: 13px;
        }

        @media (max-width: 480px) {
            .login-wrapper { padding: 12px; }
            .login-card { padding: 32px 22px 26px; border-radius: 18px; }
            .login-brand h1 { font-size: 29px; }
            .login-options { align-items: flex-start; }
        }
    </style>

    <div class="login-wrapper">
        <div class="login-card">

            {{-- Brand section --}}
            <div class="login-brand">
                <div class="login-logo">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                        <line x1="3" y1="6" x2="21" y2="6"/>
                        <path d="M16 10a4 4 0 0 1-8 0"/>
                    </svg>
                </div>

                <h1>Welcome <em>back</em></h1>
                <p>Sign in to your E-Commerce account</p>
            </div>

            {{-- Session Status --}}
            <x-auth-session-status class="mb-4" :status="session('status')" />

            {{-- Login form --}}
            <form method="POST" action="{{ route('login') }}" id="loginForm">
                @csrf

                {{-- Email --}}
                <div class="login-field">
                    <label for="email" class="login-label">Email address</label>

                    <div class="login-control">
                        <svg class="lead" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-10 6L2 7"/></svg>

                        <x-text-input
                            id="email"
                            class="login-input block mt-1"
                            type="email"
                            name="email"
                            :value="old('email')"
                            required
                            autofocus
                            autocomplete="username"
                            placeholder="you@example.com"
                        />
                    </div>

                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                {{-- Password --}}
                <div class="login-field">
                    <label for="password" class="login-label">Password</label>

                    <div class="login-control">
                        <svg class="lead" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>

                        <x-text-input
                            id="password"
                            class="login-input has-toggle block mt-1"
                            type="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            placeholder="Enter your password"
                        />

                        <button type="button" class="toggle-pass" id="togglePass" aria-label="Show password">
                            <svg class="eye" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            <svg class="eye-off" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                        </button>
                    </div>

                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                {{-- Remember me and forgot password --}}
                <div class="login-options">
                    <label for="remember_me" class="login-remember">
                        <input id="remember_me" type="checkbox" name="remember">
                        <span>Remember me</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="login-forgot" href="{{ route('password.request') }}">Forgot password?</a>
                    @endif
                </div>

                {{-- Submit --}}
                <button type="submit" class="login-button" id="loginBtn">
                    <span>Sign in</span>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </button>
            </form>

            <div class="login-secure">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/></svg>
                Secure, encrypted sign in
            </div>

            <div class="login-footer">
                &copy; {{ date('Y') }} E-Commerce Management System
            </div>

        </div>
    </div>

    <script>
        // Show / hide password
        const pass = document.getElementById('password');
        const toggle = document.getElementById('togglePass');
        toggle.addEventListener('click', () => {
            const show = pass.type === 'password';
            pass.type = show ? 'text' : 'password';
            toggle.classList.toggle('shown', show);
            toggle.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
        });

        // Loading state on submit
        document.getElementById('loginForm').addEventListener('submit', () => {
            const btn = document.getElementById('loginBtn');
            btn.disabled = true;
            btn.querySelector('span').textContent = 'Signing in…';
        });
    </script>
</x-guest-layout>