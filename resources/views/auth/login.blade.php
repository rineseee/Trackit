@extends('layouts.auth')

@section('title', 'Login - Trackit')

@section('content')
    <div class="auth-card-header">
        <div class="auth-mini">
            <i class="bi bi-shield-lock"></i>
            Secure access
        </div>
    </div>

    <div class="auth-card-body">
        <h2>Welcome back</h2>
        <p class="auth-subtitle">Sign in to continue managing issues and projects.</p>

        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <form action="{{ route('login.store') }}" method="POST" class="d-grid gap-3">
            @csrf

            <div>
                <label class="form-label" for="login-email">Email address</label>
                <input
                    id="login-email"
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
                <label class="form-label" for="login-password">Password</label>
                <input
                    id="login-password"
                    type="password"
                    name="password"
                    class="form-control @error('password') is-invalid @enderror"
                    required
                    autocomplete="current-password"
                    placeholder="Enter your password"
                >
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap">
                <div class="form-check">
                    <input
                        type="checkbox"
                        class="form-check-input"
                        name="remember"
                        id="remember"
                    >
                    <label class="form-check-label" for="remember">
                        Remember me
                    </label>
                </div>
            </div>

            <div class="auth-actions d-grid gap-2">
                <button type="submit" class="btn btn-primary btn-auth-primary">
                    Sign in
                </button>
            </div>
        </form>
    </div>

    <div class="auth-footer">
        Don't have an account?
        <a href="{{ route('register') }}" class="auth-link">Create one</a>
    </div>
@endsection
