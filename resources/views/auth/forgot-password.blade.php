@extends('layouts.auth')

@section('title', 'Forgot Password - Trackit')

@section('content')
    <div class="auth-card-header">
        <div class="auth-mini">
            <i class="bi bi-key"></i>
            Password reset
        </div>
    </div>

    <div class="auth-card-body">
        <h2>Reset password</h2>
        <p class="auth-subtitle">Enter your email and we will send a reset link.</p>

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

        <form action="{{ route('password.email') }}" method="POST" class="d-grid gap-3">
            @csrf

            <div>
                <label class="form-label" for="forgot-email">Email address</label>
                <input
                    id="forgot-email"
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

            <div class="auth-actions d-grid">
                <button type="submit" class="btn btn-primary btn-auth-primary">
                    Send reset link
                </button>
            </div>
        </form>
    </div>

    <div class="auth-footer">
        Remember your password?
        <a href="{{ route('login') }}" class="auth-link">Sign in</a>
    </div>
@endsection
