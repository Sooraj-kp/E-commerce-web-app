@extends('layouts.customer')

@section('title', 'Update Profile')

@section('content')

<div class="pf-page">

    <a href="{{ route('customer.account') }}" class="pf-back">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
        Back to my account
    </a>

    <div class="pf-head">
        <h1>Account settings</h1>
        <p>Update your personal information and keep your account secure.</p>
    </div>

    {{-- Personal information --}}
    <section class="pf-card">

        <div class="pf-card-head">
            <div class="pf-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </div>
            <div>
                <h2>Personal information</h2>
                <p>Your name and the email you use to sign in.</p>
            </div>
        </div>

        @if (session('status') === 'profile-updated')
            <div class="pf-alert pf-success" role="status">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                Profile updated successfully!
            </div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PATCH')

            <div class="pf-field">
                <label for="name">Full name</label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name', $user->name) }}"
                    autocomplete="name"
                    class="{{ $errors->has('name') ? 'is-invalid' : '' }}"
                    required
                >

                @error('name')
                    <span class="pf-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="pf-field">
                <label for="email">Email address</label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email', $user->email) }}"
                    autocomplete="email"
                    class="{{ $errors->has('email') ? 'is-invalid' : '' }}"
                    required
                >

                @error('email')
                    <span class="pf-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="pf-actions">
                <button type="submit" class="pf-btn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    Save changes
                </button>
            </div>
        </form>

    </section>


    {{-- Change password --}}
    <section class="pf-card">

        <div class="pf-card-head">
            <div class="pf-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            </div>
            <div>
                <h2>Change password</h2>
                <p>Use a long, unique password to keep your account secure.</p>
            </div>
        </div>

        @if (session('status') === 'password-updated')
            <div class="pf-alert pf-success" role="status">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                Password updated successfully!
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            @method('PUT')

            <div class="pf-field">
                <label for="current_password">Current password</label>

                <div class="pf-control">
                    <input
                        type="password"
                        id="current_password"
                        name="current_password"
                        autocomplete="current-password"
                        class="has-toggle {{ $errors->updatePassword->has('current_password') ? 'is-invalid' : '' }}"
                        required
                    >
                    <button type="button" class="pf-toggle" data-target="current_password" aria-label="Show password">
                        <svg class="on" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        <svg class="off" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                    </button>
                </div>

                @error('current_password', 'updatePassword')
                    <span class="pf-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="pf-field">
                <label for="password">New password</label>

                <div class="pf-control">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        autocomplete="new-password"
                        class="has-toggle {{ $errors->updatePassword->has('password') ? 'is-invalid' : '' }}"
                        required
                    >
                    <button type="button" class="pf-toggle" data-target="password" aria-label="Show password">
                        <svg class="on" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        <svg class="off" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                    </button>
                </div>

                @error('password', 'updatePassword')
                    <span class="pf-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="pf-field">
                <label for="password_confirmation">Confirm new password</label>

                <div class="pf-control">
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        autocomplete="new-password"
                        class="has-toggle"
                        required
                    >
                    <button type="button" class="pf-toggle" data-target="password_confirmation" aria-label="Show password">
                        <svg class="on" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        <svg class="off" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                    </button>
                </div>
            </div>

            <div class="pf-actions">
                <button type="submit" class="pf-btn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    Update password
                </button>
            </div>
        </form>

    </section>

</div>

<script>
    // Show / hide password buttons
    document.querySelectorAll('.pf-toggle').forEach(btn => {
        btn.addEventListener('click', () => {
            const input = document.getElementById(btn.dataset.target);
            const show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            btn.classList.toggle('shown', show);
            btn.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
        });
    });
</script>

@endsection

@push('styles')
<style>
    .pf-page {
        --brand: #2563eb;
        --brand-dark: #1d4ed8;
        --brand-soft: #eff6ff;
        --brand-tint: #dbeafe;
        --border: #dbe5f3;
        --ink: #0f172a;
        --muted: #64748b;
        --danger: #e11d48;

        width: 92%;
        max-width: 760px;
        margin: 36px auto 60px;
        color: var(--ink);
    }

    .pf-page * { box-sizing: border-box; }

    .pf-back {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 18px;
        color: var(--muted);
        font-weight: 600;
        font-size: 15px;
        text-decoration: none;
        transition: color 0.15s;
    }

    .pf-back svg { width: 18px; height: 18px; transition: transform 0.2s; }
    .pf-back:hover { color: var(--brand); }
    .pf-back:hover svg { transform: translateX(-3px); }

    .pf-head { margin-bottom: 26px; }

    .pf-head h1 {
        margin: 0 0 6px;
        font-size: 34px;
        font-weight: 800;
        letter-spacing: -0.03em;
        line-height: 1.15;
    }

    .pf-head p { margin: 0; color: var(--muted); font-size: 17px; }

    /* Cards */
    .pf-card {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 20px;
        padding: 32px;
        margin-bottom: 24px;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04), 0 26px 50px -36px rgba(15, 23, 42, 0.25);
    }

    .pf-card-head {
        display: flex;
        align-items: center;
        gap: 16px;
        padding-bottom: 22px;
        margin-bottom: 26px;
        border-bottom: 1px solid var(--border);
    }

    .pf-icon {
        width: 50px;
        height: 50px;
        border-radius: 14px;
        background: var(--brand);
        color: #fff;
        display: grid;
        place-items: center;
        flex-shrink: 0;
        box-shadow: 0 12px 22px -10px rgba(37, 99, 235, 0.65);
    }

    .pf-icon svg { width: 24px; height: 24px; }

    .pf-card-head h2 { margin: 0 0 3px; font-size: 21px; font-weight: 800; letter-spacing: -0.02em; line-height: 1.2; }
    .pf-card-head p { margin: 0; color: var(--muted); font-size: 15.5px; }

    /* Fields */
    .pf-field { margin-bottom: 20px; }

    .pf-field label { display: block; margin-bottom: 8px; font-size: 14.5px; font-weight: 600; }

    .pf-control { position: relative; }

    .pf-field input {
        width: 100%;
        padding: 14px 16px;
        border: 1px solid #cfd9e8;
        border-radius: 12px;
        background: #f8fafc;
        color: var(--ink);
        font: inherit;
        font-size: 15.5px;
        transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
    }

    .pf-field input.has-toggle { padding-right: 52px; }

    .pf-field input:hover { border-color: #aebcd2; }

    .pf-field input:focus {
        outline: none;
        background: #fff;
        border-color: var(--brand);
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.14);
    }

    .pf-field input.is-invalid { border-color: var(--danger); background: #fff8f9; }
    .pf-field input.is-invalid:focus { box-shadow: 0 0 0 4px rgba(225, 29, 72, 0.12); }

    .pf-toggle {
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

    .pf-toggle:hover { background: var(--brand-soft); color: var(--brand); }
    .pf-toggle svg { width: 20px; height: 20px; }
    .pf-toggle .off { display: none; }
    .pf-toggle.shown .on { display: none; }
    .pf-toggle.shown .off { display: block; }

    .pf-error { display: block; margin-top: 7px; color: var(--danger); font-size: 14px; font-weight: 500; }

    /* Messages */
    .pf-alert {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 13px 16px;
        margin-bottom: 24px;
        border-radius: 12px;
        font-size: 15px;
        font-weight: 500;
    }

    .pf-alert svg { width: 20px; height: 20px; flex-shrink: 0; }

    .pf-success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #15803d; }

    /* Buttons */
    .pf-actions { display: flex; justify-content: flex-end; padding-top: 6px; }

    .pf-btn {
        display: inline-flex;
        align-items: center;
        gap: 9px;
        padding: 14px 30px;
        border: none;
        border-radius: 999px;
        background: var(--brand);
        color: #fff;
        font: inherit;
        font-size: 15.5px;
        font-weight: 700;
        cursor: pointer;
        box-shadow: 0 14px 26px -14px rgba(37, 99, 235, 0.9);
        transition: background 0.2s, transform 0.2s;
    }

    .pf-btn svg { width: 18px; height: 18px; }
    .pf-btn:hover { background: var(--brand-dark); transform: translateY(-2px); }

    .pf-btn:focus-visible, .pf-back:focus-visible, .pf-toggle:focus-visible {
        outline: 3px solid rgba(37, 99, 235, 0.4);
        outline-offset: 3px;
    }

    @media (max-width: 600px) {
        .pf-page { margin-top: 24px; }
        .pf-head h1 { font-size: 28px; }
        .pf-card { padding: 24px 18px; border-radius: 16px; }
        .pf-card-head h2 { font-size: 19px; }
        .pf-actions { justify-content: stretch; }
        .pf-btn { width: 100%; justify-content: center; }
    }
</style>
@endpush