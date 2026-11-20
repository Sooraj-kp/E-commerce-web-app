@extends('layouts.customer')

@section('title', 'My Account')

@section('content')

    <div class="ac-page">

        <div class="ac-head">
            <h1>My account</h1>
            <p>Your details and saved delivery addresses.</p>
        </div>

        {{-- Customer information --}}
        <section class="ac-card">
            <h2 class="ac-title">Customer information</h2>

            <div class="ac-profile">
                <div class="ac-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>

                <div class="ac-info">
                    <div class="ac-item">
                        <span>Name</span>
                        <b>{{ $user->name }}</b>
                    </div>

                    <div class="ac-item">
                        <span>Email</span>
                        <b>{{ $user->email }}</b>
                    </div>
                </div>
            </div>
        </section>

        {{-- Account Settings --}}
        <section class="ac-card">
            <h2 class="ac-title">Account Settings</h2>

            <div class="ac-settings">
                <div>
                    <h3>Update Email &amp; Password</h3>
                    <p>Manage your login credentials and account security.</p>
                </div>

                <a href="{{ route('profile.edit') }}" class="ac-add-btn">
                    Manage Account
                </a>
            </div>
        </section>

        {{-- Delivery addresses --}}
        <section class="ac-card">
            <div class="ac-title-row">
                <h2 class="ac-title">My delivery addresses</h2>

                @if ($addresses->count())
                    <span class="ac-count">{{ $addresses->count() }}</span>
                @endif

                <a href="{{ route('customer.address.create') }}" class="ac-add-btn">
                    + Add Address
                </a>
            </div>

            @if ($addresses->isEmpty())

                <div class="ac-empty">
                    <div class="ac-empty-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    </div>
                    <p>No delivery addresses saved yet.</p>
                </div>

            @else

                <div class="ac-grid">
                    @foreach ($addresses as $address)
                        <div class="ac-address">

                            {{-- Address details --}}
                            <div class="ac-body">
                                <div class="ac-pin">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                </div>

                                <div class="ac-lines">
                                    <span class="ac-tag">Address {{ $loop->iteration }}</span>
                                    <p class="ac-line1">{{ $address->address_line }}</p>
                                    <p>{{ $address->city }}, {{ $address->state }}</p>
                                    <p>{{ $address->postal_code }}</p>
                                    <p>{{ $address->country }}</p>
                                </div>
                            </div>

                            

                            {{-- Remove button, beneath the address --}}
                            <div class="ac-foot">

                                <a href="{{ route('customer.address.edit', $address->id)  }}" class="edit">
                                Edit
                            </a>
                                <form
                                    action="{{ route('customer.address.destroy', $address->id) }}"
                                    method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this address?');"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="ac-remove">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                                        Remove address
                                    </button>
                                </form>
                            </div>

                        </div>
                    @endforeach
                </div>

            @endif
        </section>

    </div>

@endsection

@push('styles')
<style>
    .edit {
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
    .ac-page {
        --brand: #2563eb;
        --brand-dark: #1d4ed8;
        --brand-soft: #eff6ff;
        --brand-tint: #dbeafe;
        --border: #dbe5f3;
        --ink: #0f172a;
        --muted: #64748b;
        --danger: #e11d48;

        width: 92%;
        max-width: 960px;
        margin: 40px auto 60px;
        color: var(--ink);
    }

    .ac-page * { box-sizing: border-box; }

    .ac-head { margin-bottom: 28px; }

    .ac-head h1 {
        margin: 0 0 6px;
        font-size: 34px;
        font-weight: 800;
        letter-spacing: -0.03em;
        line-height: 1.15;
    }

    .ac-head p { margin: 0; color: var(--muted); font-size: 17px; }

    .ac-card {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 18px;
        padding: 28px;
        margin-bottom: 24px;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04), 0 22px 44px -34px rgba(15, 23, 42, 0.22);
    }

    .ac-title { margin: 0 0 22px; font-size: 19px; font-weight: 700; letter-spacing: -0.01em; }

    .ac-title-row { display: flex; align-items: center; gap: 12px; margin-bottom: 22px; }
    .ac-title-row .ac-title { margin: 0; }

    .ac-count {
        background: var(--brand-soft);
        color: var(--brand-dark);
        border: 1px solid var(--brand-tint);
        font-size: 13.5px;
        font-weight: 700;
        padding: 2px 12px;
        border-radius: 999px;
    }

    .ac-add-btn {
        display: inline-block;
        background: var(--brand);
        color: #fff;
        padding: 10px 18px;
        border-radius: 8px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        transition: background 0.2s;
        margin-left: auto;
    }

    .ac-add-btn:hover { background: var(--brand-dark); color: #fff; }

    .ac-settings {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
    }

    .ac-settings h3 { margin: 0 0 6px; font-size: 16px; }
    .ac-settings p { margin: 0; color: var(--muted); font-size: 14px; }

    /* Profile */
    .ac-profile { display: flex; align-items: center; gap: 22px; }

    .ac-avatar {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        background: var(--brand);
        color: #fff;
        display: grid;
        place-items: center;
        font-size: 29px;
        font-weight: 700;
        flex-shrink: 0;
        box-shadow: 0 12px 22px -10px rgba(37, 99, 235, 0.65);
    }

    .ac-info { display: grid; grid-template-columns: 1fr 1fr; gap: 14px 40px; flex: 1; min-width: 0; }

    .ac-item { min-width: 0; }

    .ac-item span {
        display: block;
        font-size: 12.5px;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: var(--muted);
        margin-bottom: 3px;
    }

    .ac-item b { display: block; font-size: 17px; font-weight: 600; overflow-wrap: anywhere; }

    /* Addresses */
    .ac-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(270px, 1fr)); gap: 16px; }

    .ac-address {
        display: flex;
        flex-direction: column;
        border: 1px solid var(--border);
        border-radius: 14px;
        background: #fbfcff;
        overflow: hidden;
        transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
    }

    .ac-address:hover {
        border-color: var(--brand-tint);
        background: #fff;
        box-shadow: 0 16px 32px -24px rgba(37, 99, 235, 0.45);
    }

    .ac-body { display: flex; gap: 14px; padding: 20px; flex: 1; }

    .ac-pin {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: var(--brand-soft);
        color: var(--brand);
        display: grid;
        place-items: center;
        flex-shrink: 0;
    }

    .ac-pin svg { width: 21px; height: 21px; }

    .ac-lines { min-width: 0; }
    .ac-lines p { margin: 0; color: #475569; font-size: 15.5px; line-height: 1.55; }
    .ac-lines .ac-line1 { color: var(--ink); font-weight: 600; font-size: 16px; }

    .ac-tag {
        display: inline-block;
        margin-bottom: 8px;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: var(--brand-dark);
    }

    /* Remove button, beneath the address */
    .ac-foot {
        display: flex;
        justify-content: flex-end;
        padding: 10px 14px;
        border-top: 1px solid var(--border);
        background: #f8fafd;
    }

    .ac-foot form { margin: 0; }

    .ac-remove {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 8px 14px;
        border: 1px solid transparent;
        border-radius: 999px;
        background: transparent;
        color: var(--danger);
        font: inherit;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.18s, color 0.18s, border-color 0.18s;
    }

    .ac-remove svg { width: 16px; height: 16px; }

    .ac-remove:hover { background: var(--danger); border-color: var(--danger); color: #fff; }

    .ac-remove:focus-visible { outline: 3px solid rgba(225, 29, 72, 0.35); outline-offset: 2px; }

    /* Empty */
    .ac-empty { text-align: center; padding: 30px 10px 14px; }

    .ac-empty-icon {
        width: 64px;
        height: 64px;
        margin: 0 auto 14px;
        border-radius: 50%;
        background: var(--brand-soft);
        color: var(--brand);
        display: grid;
        place-items: center;
    }

    .ac-empty-icon svg { width: 28px; height: 28px; }
    .ac-empty p { margin: 0; color: var(--muted); font-size: 16.5px; }

    @media (max-width: 640px) {
        .ac-page { margin-top: 26px; }
        .ac-head h1 { font-size: 28px; }
        .ac-card { padding: 22px 18px; border-radius: 16px; }
        .ac-profile { flex-direction: column; align-items: flex-start; gap: 16px; }
        .ac-info { grid-template-columns: 1fr; gap: 12px; }

        .ac-settings { flex-direction: column; align-items: flex-start; }
        .ac-title-row { flex-wrap: wrap; }
        .ac-add-btn { margin-left: 0; }

        .ac-foot { padding: 10px 14px; }
        .ac-remove { width: 100%; justify-content: center; }
    }
</style>
@endpush