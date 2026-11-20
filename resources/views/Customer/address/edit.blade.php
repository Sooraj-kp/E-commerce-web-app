@extends('layouts.customer')

@section('title', 'Edit Delivery Address')

@section('content')

<div class="ad-page">

    <a href="{{ route('customer.account') }}" class="ad-back">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
        Back to my account
    </a>

    <div class="ad-card">

        <div class="ad-head">
            <div class="ad-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4z"/></svg>
            </div>
            <div>
                <h2>Edit delivery address</h2>
                <p>Change the details below and save.</p>
            </div>
        </div>

        @if ($errors->any())
            <div class="ad-alert" role="alert">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                Please check the highlighted fields.
            </div>
        @endif

        <form action="{{ route('customer.address.update', $address->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="ad-field">
                <label for="address_line">Address</label>
                <input
                    type="text"
                    id="address_line"
                    name="address_line"
                    value="{{ old('address_line', $address->address_line) }}"
                    placeholder="House no, street, area"
                    autocomplete="street-address"
                    class="{{ $errors->has('address_line') ? 'is-invalid' : '' }}"
                >
                @error('address_line') <span class="ad-error">{{ $message }}</span> @enderror
            </div>

            <div class="ad-row">
                <div class="ad-field">
                    <label for="city">City</label>
                    <input
                        type="text"
                        id="city"
                        name="city"
                        value="{{ old('city', $address->city) }}"
                        autocomplete="address-level2"
                        class="{{ $errors->has('city') ? 'is-invalid' : '' }}"
                    >
                    @error('city') <span class="ad-error">{{ $message }}</span> @enderror
                </div>

                <div class="ad-field">
                    <label for="state">State</label>
                    <input
                        type="text"
                        id="state"
                        name="state"
                        value="{{ old('state', $address->state) }}"
                        autocomplete="address-level1"
                        class="{{ $errors->has('state') ? 'is-invalid' : '' }}"
                    >
                    @error('state') <span class="ad-error">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="ad-row">
                <div class="ad-field">
                    <label for="postal_code">Postal code</label>
                    <input
                        type="text"
                        id="postal_code"
                        name="postal_code"
                        value="{{ old('postal_code', $address->postal_code) }}"
                        inputmode="numeric"
                        autocomplete="postal-code"
                        class="{{ $errors->has('postal_code') ? 'is-invalid' : '' }}"
                    >
                    @error('postal_code') <span class="ad-error">{{ $message }}</span> @enderror
                </div>

                <div class="ad-field">
                    <label for="country">Country</label>
                    <input
                        type="text"
                        id="country"
                        name="country"
                        value="{{ old('country', $address->country) }}"
                        autocomplete="country-name"
                        class="{{ $errors->has('country') ? 'is-invalid' : '' }}"
                    >
                    @error('country') <span class="ad-error">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="ad-actions">
                <a href="{{ route('customer.account') }}" class="ad-cancel">Cancel</a>

                <button type="submit" class="ad-save">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    Update address
                </button>
            </div>

        </form>

    </div>

</div>

@endsection

@push('styles')
<style>
    .ad-page {
        --brand: #2563eb;
        --brand-dark: #1d4ed8;
        --brand-soft: #eff6ff;
        --brand-tint: #dbeafe;
        --border: #dbe5f3;
        --ink: #0f172a;
        --muted: #64748b;
        --danger: #e11d48;

        width: 92%;
        max-width: 700px;
        margin: 36px auto 60px;
        color: var(--ink);
    }

    .ad-page * { box-sizing: border-box; }

    .ad-back {
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

    .ad-back svg { width: 18px; height: 18px; transition: transform 0.2s; }
    .ad-back:hover { color: var(--brand); }
    .ad-back:hover svg { transform: translateX(-3px); }

    .ad-card {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 20px;
        padding: 34px;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04), 0 26px 50px -36px rgba(15, 23, 42, 0.25);
    }

    .ad-head {
        display: flex;
        align-items: center;
        gap: 16px;
        padding-bottom: 24px;
        margin-bottom: 26px;
        border-bottom: 1px solid var(--border);
    }

    .ad-icon {
        width: 54px;
        height: 54px;
        border-radius: 15px;
        background: var(--brand);
        color: #fff;
        display: grid;
        place-items: center;
        flex-shrink: 0;
        box-shadow: 0 12px 22px -10px rgba(37, 99, 235, 0.65);
    }

    .ad-icon svg { width: 26px; height: 26px; }

    .ad-head h2 { margin: 0 0 3px; font-size: 25px; font-weight: 800; letter-spacing: -0.025em; line-height: 1.2; }
    .ad-head p { margin: 0; color: var(--muted); font-size: 15.5px; }

    /* Fields */
    .ad-field { margin-bottom: 20px; min-width: 0; }

    .ad-row { display: grid; grid-template-columns: 1fr 1fr; gap: 0 18px; }

    .ad-field label { display: block; margin-bottom: 8px; font-size: 14.5px; font-weight: 600; }

    .ad-field input {
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

    .ad-field input::placeholder { color: #a0aec0; }
    .ad-field input:hover { border-color: #aebcd2; }

    .ad-field input:focus {
        outline: none;
        background: #fff;
        border-color: var(--brand);
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.14);
    }

    .ad-field input.is-invalid { border-color: var(--danger); background: #fff8f9; }
    .ad-field input.is-invalid:focus { box-shadow: 0 0 0 4px rgba(225, 29, 72, 0.12); }

    .ad-error { display: block; margin-top: 7px; color: var(--danger); font-size: 14px; font-weight: 500; }

    .ad-alert {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 13px 16px;
        margin-bottom: 22px;
        background: #fff1f2;
        border: 1px solid #fecdd3;
        color: #be123c;
        border-radius: 12px;
        font-size: 15px;
        font-weight: 500;
    }

    .ad-alert svg { width: 20px; height: 20px; flex-shrink: 0; }

    /* Actions */
    .ad-actions {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 12px;
        margin-top: 8px;
        padding-top: 24px;
        border-top: 1px solid var(--border);
    }

    .ad-cancel {
        padding: 13px 24px;
        border-radius: 999px;
        color: var(--muted);
        font-weight: 600;
        font-size: 15.5px;
        text-decoration: none;
        transition: background 0.15s, color 0.15s;
    }

    .ad-cancel:hover { background: #f1f5f9; color: var(--ink); }

    .ad-save {
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

    .ad-save svg { width: 18px; height: 18px; }
    .ad-save:hover { background: var(--brand-dark); transform: translateY(-2px); }

    .ad-save:focus-visible, .ad-cancel:focus-visible, .ad-back:focus-visible {
        outline: 3px solid rgba(37, 99, 235, 0.4);
        outline-offset: 3px;
    }

    @media (max-width: 600px) {
        .ad-page { margin-top: 24px; }
        .ad-card { padding: 24px 18px; border-radius: 16px; }
        .ad-head h2 { font-size: 22px; }
        .ad-row { grid-template-columns: 1fr; }
        .ad-actions { flex-direction: column-reverse; align-items: stretch; }
        .ad-save, .ad-cancel { justify-content: center; text-align: center; }
    }
</style>
@endpush