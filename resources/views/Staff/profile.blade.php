
@extends('layouts.staff')

@section('title', 'My Profile')

@section('page-heading', 'My Profile')

@push('styles')
<style>
    .profile-heading {
        margin-bottom: 25px;
    }

    .profile-heading h2 {
        font-size: 23px;
        margin-bottom: 7px;
    }

    .profile-heading p {
        color: #64748b;
        font-size: 14px;
    }

    .profile-card {
        background: #fff;
        padding: 28px;
        border-radius: 12px;
        max-width: 850px;
        margin-bottom: 25px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }

    .profile-card h3 {
        font-size: 18px;
        margin-bottom: 8px;
    }

    .description {
        color: #64748b;
        font-size: 13px;
        margin-bottom: 25px;
    }

    .profile-summary {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 25px;
        padding-bottom: 22px;
        border-bottom: 1px solid #e2e8f0;
    }

    .profile-avatar {
        width: 60px;
        height: 60px;
        background: #2563eb;
        color: #fff;
        border-radius: 50%;
        display: grid;
        place-items: center;
        font-size: 24px;
        font-weight: 700;
    }

    .profile-summary h3 {
        margin-bottom: 5px;
    }

    .role-label {
        display: inline-block;
        background: #dbeafe;
        color: #1d4ed8;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 9px;
    }

    .form-group input {
        width: 100%;
        padding: 13px;
        border: 1px solid #cbd5e1;
        border-radius: 7px;
        font-size: 14px;
        outline: none;
    }

    .form-group input:focus {
        border-color: #2563eb;
    }

    .btn-primary {
        padding: 12px 22px;
        border: none;
        border-radius: 7px;
        background: #2563eb;
        color: white;
        cursor: pointer;
        font-size: 14px;
    }

    .btn-primary:hover {
        background: #1d4ed8;
    }

    .success-message {
        background: #dcfce7;
        color: #166534;
        padding: 12px;
        margin-bottom: 20px;
        border-radius: 7px;
        font-size: 14px;
    }

    .error-message {
        color: #dc2626;
        font-size: 12px;
        margin-top: 6px;
    }

    @media(max-width: 600px) {
        .profile-card {
            padding: 20px;
        }
    }
</style>
@endpush

@section('content')

    <div class="profile-heading">
        <h2>Account Settings</h2>
        <p>View and manage your personal information and account security.</p>
    </div>

    <!-- Profile Information -->

    <div class="profile-card">

        <div class="profile-summary">

            <div class="profile-avatar">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>

            <div>
                <h3>{{ $user->name }}</h3>
                <span class="role-label">Staff Member</span>
            </div>

        </div>

        <h3>Personal Information</h3>

        <p class="description">
            Update your name and email address.
        </p>

        @if(session('status') === 'profile-updated')
            <div class="success-message">
                Profile information updated successfully.
            </div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}">

            @csrf
            @method('PATCH')

            <div class="form-group">
                <label for="name">Full Name</label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name', $user->name) }}"
                    required
                    autofocus
                >

                @error('name')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email', $user->email) }}"
                    required
                >

                @error('email')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="btn-primary">
                Save Changes
            </button>

        </form>

    </div>

    <!-- Password Update -->

    <div class="profile-card">

        <h3>Change Password</h3>

        <p class="description">
            Choose a strong password to keep your account secure.
        </p>

        @if(session('status') === 'password-updated')
            <div class="success-message">
                Password changed successfully.
            </div>
        @endif

        @if($errors->updatePassword->any())
            <div class="success-message" style="background:#fee2e2;color:#991b1b;">
                Please correct the password information below.
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}">

            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="current_password">Current Password</label>

                <input
                    type="password"
                    id="current_password"
                    name="current_password"
                    required
                    autocomplete="current-password"
                >

                @error('current_password', 'updatePassword')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">New Password</label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    required
                    autocomplete="new-password"
                >

                @error('password', 'updatePassword')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm New Password</label>

                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    required
                    autocomplete="new-password"
                >
            </div>

            <button type="submit" class="btn-primary">
                Update Password
            </button>

        </form>

    </div>

@endsection
