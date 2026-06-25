@extends('layouts.auth')

@section('title', 'Reset Password - Trackit')

@section('content')
    <div class="auth-card-header">
        <div class="auth-mini">
            <i class="bi bi-shield-lock"></i>
            New password
        </div>
    </div>

    <div class="auth-card-body">
        <h2>Set a new password</h2>
        <p class="auth-subtitle">Create a new password and keep your account secure.</p>

        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form action="{{ route('password.update') }}" method="POST" class="d-grid gap-3">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div>
                <label class="form-label" for="reset-email">Email address</label>
                <input
                    id="reset-email"
                    type="email"
                    name="email"
                    class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email') }}"
                    required
                    autocomplete="email"
                    placeholder="you@example.com"
                >
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label class="form-label" for="reset-password">Password</label>
                <input
                    id="reset-password"
                    type="password"
                    name="password"
                    class="form-control @error('password') is-invalid @enderror"
                    required
                    autocomplete="new-password"
                    placeholder="Enter new password"
                >
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label class="form-label" for="reset-password-confirmation">Confirm password</label>
                <input
                    id="reset-password-confirmation"
                    type="password"
                    name="password_confirmation"
                    class="form-control @error('password_confirmation') is-invalid @enderror"
                    required
                    autocomplete="new-password"
                    placeholder="Confirm password"
                >
                @error('password_confirmation')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="auth-actions d-grid">
                <button type="submit" class="btn btn-primary btn-auth-primary">
                    Reset password
                </button>
            </div>
        </form>
    </div>

    <div class="auth-footer">
        Remember your password?
        <a href="{{ route('login') }}" class="auth-link">Sign in</a>
    </div>
@endsection
